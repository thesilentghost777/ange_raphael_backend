<?php

namespace App\Services;

use App\Models\AutoEcoleUser;
use App\Models\AutoEcolePaiement;
use App\Models\CodeCaisse;
use App\Models\ConfigPaiement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

/**
 * Service de paiement avec MoneyFusion
 */
class PaiementService
{
    public function __construct(ParrainageService $parrainageService)
{
    $this->parrainageService = $parrainageService;
    $this->moneyFusionApiUrl = env('MONEYFUSION_API_URL');
    $this->returnUrl = env('MONEYFUSION_RETURN_URL');
    $this->webhookUrl = env('MONEYFUSION_WEBHOOK_URL');
}

    /**
     * Initialise un paiement en ligne avec MoneyFusion
     * 
     * @param int $userId
     * @param string $tranche 'x', 'y' ou 'complet'
     * @param string $methodePaiement 'orange_money', 'mtn_money', 'card'
     * @param string $telephone
     * @return array
     */
    public function initierPaiementEnLigne(int $userId, string $tranche, string $methodePaiement, string $telephone): array
{
    $user = AutoEcoleUser::findOrFail($userId);
    $config = ConfigPaiement::getConfig();

    // Supprimer les anciens paiements en attente pour cette tranche
    AutoEcolePaiement::where('user_id', $userId)
        ->where('statut', 'en_attente')
        ->where('tranche', $tranche)
        ->delete();

    Log::info('Anciens paiements en attente supprimés', [
        'user_id' => $userId,
        'tranche' => $tranche
    ]);

    // Vérifications selon le type de tranche
    if ($tranche === 'x') {
        if ($user->paiement_x_effectue) {
            return [
                'success' => false,
                'message' => 'La première tranche a déjà été payée'
            ];
        }
        $montant = $config->montant_x;
        $description = 'Première tranche (X)';
    } 
    elseif ($tranche === 'y') {
        if (!$user->paiement_x_effectue) {
            return [
                'success' => false,
                'message' => 'La première tranche doit être payée d\'abord'
            ];
        }
        if ($user->dispense_y) {
            return [
                'success' => false,
                'message' => 'Vous êtes dispensé de payer la deuxième tranche'
            ];
        }
        if ($user->paiement_y_effectue) {
            return [
                'success' => false,
                'message' => 'La deuxième tranche a déjà été payée'
            ];
        }
        $montant = $config->montant_y;
        $description = 'Deuxième tranche (Y)';
    }
    elseif ($tranche === 'complet') {
        if ($user->paiement_x_effectue && $user->paiement_y_effectue) {
            return [
                'success' => false,
                'message' => 'Le paiement complet a déjà été effectué'
            ];
        }
        if ($user->paiement_x_effectue) {
            return [
                'success' => false,
                'message' => 'La première tranche a déjà été payée. Veuillez payer uniquement la tranche Y'
            ];
        }
        $montant = $config->montant_x + $config->montant_y;
        $description = 'Paiement complet (X+Y)';
    }
    else {
        return [
            'success' => false,
            'message' => 'Type de tranche invalide'
        ];
    }

    try {
        DB::beginTransaction();

        // Générer un transaction_id unique
        $transactionId = 'AE-' . time() . '-' . $userId . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));

        // Créer l'enregistrement de paiement en attente
        $paiement = AutoEcolePaiement::create([
            'user_id' => $userId,
            'montant' => $montant,
            'type_paiement' => 'en_ligne',
            'tranche' => $tranche,
            'transaction_id' => $transactionId,
            'statut' => 'en_attente',
            'methode_paiement' => $methodePaiement,
            'date_paiement' => now()
        ]);

        // Appeler l'API MoneyFusion
        $resultatAPI = $this->appelAPIMoneyFusion([
            'montant' => $montant,
            'telephone' => $telephone,
            'transaction_id' => $transactionId,
            'user_id' => $userId,
            'description' => $description,
            'tranche' => $tranche
        ]);

        if ($resultatAPI['success']) {
            // Enregistrer le token MoneyFusion
            $paiement->update([
                'notes' => 'Token MoneyFusion: ' . $resultatAPI['token']
            ]);
            
            DB::commit();

            Log::info('Paiement MoneyFusion initié avec succès', [
                'transaction_id' => $transactionId,
                'token' => $resultatAPI['token'],
                'payment_url' => $resultatAPI['url']
            ]);

            return [
                'success' => true,
                'message' => 'Paiement initié avec succès',
                'transaction_id' => $transactionId,
                'token' => $resultatAPI['token'],
                'payment_url' => $resultatAPI['url'],
                'montant' => $montant,
                'tranche' => $tranche
            ];
        } else {
            $paiement->update(['statut' => 'echoue', 'notes' => $resultatAPI['message']]);
            DB::commit();

            return [
                'success' => false,
                'message' => $resultatAPI['message'] ?? 'Échec de l\'initialisation du paiement'
            ];
        }

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur lors de l\'initialisation du paiement: ' . $e->getMessage(), [
            'user_id' => $userId,
            'tranche' => $tranche,
            'trace' => $e->getTraceAsString()
        ]);
        
        return [
            'success' => false,
            'message' => 'Erreur lors du traitement du paiement: ' . $e->getMessage()
        ];
    }
}

    /**
     * Appel à l'API MoneyFusion pour initialiser un paiement
     */
    private function appelAPIMoneyFusion(array $data): array
    {
        $phone_number_to_use = 657929578;
        try {
            Log::info('Appel API MoneyFusion', [
                'url' => $this->moneyFusionApiUrl,
                'data' => $data
            ]);

            // Préparer les données selon le format MoneyFusion
            $paymentData = [
                'totalPrice' => (int) $data['montant'],
                'article' => [
                    [
                        'description' => $data['description'],
                        'prix' => (int) $data['montant']
                    ]
                ],
                'personal_Info' => [
                    [
                        'userId' => $data['user_id'],
                        'transactionId' => $data['transaction_id'],
                        'tranche' => $data['tranche']
                    ]
                ],
                'numeroSend' => $phone_number_to_use,
                'nomclient' => 'ange raphael',
                'return_url' => $this->returnUrl,
                'webhook_url' => $this->webhookUrl
            ];

            // Appel HTTP vers MoneyFusion
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])
                ->post($this->moneyFusionApiUrl, $paymentData);

            Log::info('Réponse API MoneyFusion', [
                'status' => $response->status(),
                'body' => $response->json()
            ]);

            if ($response->successful()) {
                $result = $response->json();
                
                if (isset($result['statut']) && $result['statut'] === true) {
                    return [
                        'success' => true,
                        'token' => $result['token'],
                        'url' => $result['url'],
                        'message' => $result['message'] ?? 'Paiement initié'
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Erreur lors de l\'initialisation du paiement avec MoneyFusion'
            ];

        } catch (\Exception $e) {
            Log::error('Erreur API MoneyFusion: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'message' => 'Erreur de connexion à MoneyFusion: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Traite un paiement par code caisse
     */
    public function traiterPaiementCodeCaisse(string $code, int $userId): array
    {
        try {
            DB::beginTransaction();

            $codeCaisse = CodeCaisse::where('code', $code)->first();

            if (!$codeCaisse) {
                return [
                    'success' => false,
                    'message' => 'Code caisse invalide'
                ];
            }

            if (!$codeCaisse->estValide()) {
                return [
                    'success' => false,
                    'message' => 'Ce code a déjà été utilisé ou est expiré'
                ];
            }

            if ($codeCaisse->user_id && $codeCaisse->user_id !== $userId) {
                return [
                    'success' => false,
                    'message' => 'Ce code n\'est pas attribué à votre compte'
                ];
            }

            $user = AutoEcoleUser::findOrFail($userId);
            $tranche = $codeCaisse->tranche;

            // Vérifications des tranches
            if ($tranche === 'x') {
                if ($user->paiement_x_effectue) {
                    return [
                        'success' => false,
                        'message' => 'La première tranche a déjà été payée'
                    ];
                }
            } elseif ($tranche === 'y') {
                if (!$user->paiement_x_effectue) {
                    return [
                        'success' => false,
                        'message' => 'La première tranche doit être payée d\'abord'
                    ];
                }
                if ($user->paiement_y_effectue) {
                    return [
                        'success' => false,
                        'message' => 'La deuxième tranche a déjà été payée'
                    ];
                }
            } elseif ($tranche === 'complet') {
                if ($user->paiement_x_effectue && $user->paiement_y_effectue) {
                    return [
                        'success' => false,
                        'message' => 'Le paiement complet a déjà été effectué'
                    ];
                }
                if ($user->paiement_x_effectue) {
                    return [
                        'success' => false,
                        'message' => 'La première tranche a déjà été payée. Ce code ne peut pas être utilisé'
                    ];
                }
            }

            // Création du paiement
            $paiement = AutoEcolePaiement::create([
                'user_id' => $userId,
                'montant' => $codeCaisse->montant,
                'type_paiement' => 'code_caisse',
                'tranche' => $tranche,
                'transaction_id' => 'CC-' . $code,
                'statut' => 'valide',
                'date_paiement' => now()
            ]);

            // Marquer le code comme utilisé
            $codeCaisse->update([
                'utilise' => true,
                'user_id' => $userId,
                'date_utilisation' => now()
            ]);

            // Traiter le paiement
            $this->traiterPaiementReussi($paiement, $user, $tranche);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Paiement validé avec succès',
                'montant' => $codeCaisse->montant,
                'tranche' => $tranche,
                'transaction_id' => $paiement->transaction_id,
                'statut' => 'valide',
                'date_paiement' => $paiement->date_paiement
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur traitement code caisse: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Traite un paiement réussi et met à jour le compte utilisateur
     */
    private function traiterPaiementReussi(AutoEcolePaiement $paiement, AutoEcoleUser $user, string $tranche): void
    {
        $paiement->update(['statut' => 'valide']);

        if ($tranche === 'x') {
            $user->update([
                'paiement_x_effectue' => true,
                'paiement_x_date' => now()
            ]);

            if ($user->verifierDispenseY()) {
                $user->update(['dispense_y' => true]);
            }

            if ($user->validated) {
                $user->debloquerCours();
            }

        } elseif ($tranche === 'y') {
            $user->update([
                'paiement_y_effectue' => true,
                'paiement_y_date' => now(),
                'cours_verrouilles' => false
            ]);
            
        } elseif ($tranche === 'complet') {
            $user->update([
                'paiement_x_effectue' => true,
                'paiement_x_date' => now(),
                'paiement_y_effectue' => true,
                'paiement_y_date' => now(),
                'cours_verrouilles' => false
            ]);

            if ($user->validated) {
                $user->debloquerCours();
            }
        }

        $this->parrainageService->mettreAJourNiveauParrainage($user->id);
    }

    /**
     * Vérifie le statut d'un paiement via MoneyFusion
     */
    public function verifierStatutPaiement(string $transactionId): array
    {
        $paiement = AutoEcolePaiement::where('transaction_id', $transactionId)->first();

        if (!$paiement) {
            return [
                'success' => false,
                'message' => 'Transaction introuvable'
            ];
        }

        // Extraire le token MoneyFusion des notes
        $token = null;
        if ($paiement->notes && strpos($paiement->notes, 'Token MoneyFusion: ') !== false) {
            $token = str_replace('Token MoneyFusion: ', '', $paiement->notes);
        }

        // Si le paiement est déjà validé, retourner directement
        if ($paiement->statut === 'valide') {
            return [
                'success' => true,
                'statut' => 'paid',
                'montant' => $paiement->montant,
                'tranche' => $paiement->tranche,
                'date' => $paiement->date_paiement
            ];
        }

        // Vérifier le statut via MoneyFusion si on a un token
        if ($token) {
            try {
                $response = Http::get("https://www.pay.moneyfusion.net/paiementNotif/{$token}");
                
                if ($response->successful()) {
                    $result = $response->json();
                    
                    if (isset($result['statut']) && $result['statut'] === true) {
                        $statutMF = $result['data']['statut'] ?? 'pending';
                        
                        return [
                            'success' => true,
                            'statut' => $statutMF,
                            'montant' => $paiement->montant,
                            'tranche' => $paiement->tranche,
                            'date' => $paiement->date_paiement,
                            'moneyfusion_data' => $result['data']
                        ];
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erreur vérification statut MoneyFusion: ' . $e->getMessage());
            }
        }

        return [
            'success' => true,
            'statut' => $paiement->statut,
            'montant' => $paiement->montant,
            'tranche' => $paiement->tranche,
            'date' => $paiement->date_paiement
        ];
    }

    /**
     * Vérifie et verrouille les cours si délai Y dépassé
     */
    public function verifierDelaiPaiementY(int $userId): void
    {
        $user = AutoEcoleUser::find($userId);

        if (!$user || !$user->paiement_x_effectue || $user->paiement_y_effectue || $user->dispense_y) {
            return;
        }

        if (!$user->verifierDelaiPaiementY()) {
            $user->verrouillerCours();
        }
    }
}