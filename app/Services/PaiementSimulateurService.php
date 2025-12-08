<?php

namespace App\Services;

use App\Models\AutoEcoleUser;
use App\Models\AutoEcolePaiement;
use App\Models\CodeCaisse;
use App\Models\ConfigPaiement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service de simulation de paiement
 */
class PaiementSimulateurService
{
    private $parrainageService;

    public function __construct(ParrainageService $parrainageService)
    {
        $this->parrainageService = $parrainageService;
    }

    /**
     * Initialise un paiement en ligne
     * 
     * @param int $userId
     * @param string $tranche 'x', 'y' ou 'complet'
     * @param string $methodePaiement 'orange_money', 'mtn_money', 'card'
     * @param string $telephone (optionnel pour mobile money)
     * @return array
     */
    public function initierPaiementEnLigne(int $userId, string $tranche, string $methodePaiement, string $telephone): array
    {
        $user = AutoEcoleUser::findOrFail($userId);
        $config = ConfigPaiement::getConfig();

        // Vérifications selon le type de tranche
        if ($tranche === 'x') {
            if ($user->paiement_x_effectue) {
                return [
                    'success' => false,
                    'message' => 'La première tranche a déjà été payée'
                ];
            }
            $montant = $config->montant_x;
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
            // Montant complet = X + Y
            $montant = $config->montant_x + $config->montant_y;
        }
        else {
            return [
                'success' => false,
                'message' => 'Type de tranche invalide'
            ];
        }

        try {
            DB::beginTransaction();

            // Créer l'enregistrement de paiement en attente
            $paiement = AutoEcolePaiement::create([
                'user_id' => $userId,
                'montant' => $montant,
                'type_paiement' => 'en_ligne',
                'tranche' => $tranche,
                'transaction_id' => AutoEcolePaiement::genererTransactionId(),
                'statut' => 'en_attente',
                'methode_paiement' => $methodePaiement,
                'date_paiement' => now()
            ]);

            // Simuler l'appel à l'API de paiement
            $resultatAPI = $this->appelAPIExternePaiement([
                'montant' => $montant,
                'methode' => $methodePaiement,
                'telephone' => $telephone,
                'transaction_id' => $paiement->transaction_id,
                'user_id' => $userId
            ]);

            if ($resultatAPI['success']) {
                // Traiter le paiement réussi
                $this->traiterPaiementReussi($paiement, $user, $tranche);
                
                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Paiement effectué avec succès',
                    'transaction_id' => $paiement->transaction_id,
                    'montant' => $montant,
                    'tranche' => $tranche
                ];
            } else {
                $paiement->update(['statut' => 'echoue', 'notes' => $resultatAPI['message']]);
                DB::commit();

                return [
                    'success' => false,
                    'message' => $resultatAPI['message'] ?? 'Le paiement a échoué'
                ];
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Erreur lors du traitement du paiement: ' . $e->getMessage()
            ];
        }
    }

    /**
     * SIMULATEUR D'API DE PAIEMENT
     */
    private function appelAPIExternePaiement(array $data): array
    {
        // Simulation - retourne toujours un succès
        sleep(1); // Simule le délai de l'API
        
        return [
            'success' => true,
            'message' => 'Paiement simulé avec succès',
            'reference' => 'SIM-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10))
        ];
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

        // Code attribué à un utilisateur spécifique ?
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

        // Traiter le paiement (mise à jour compte utilisateur)
        $this->traiterPaiementReussi($paiement, $user, $tranche);

        DB::commit();

        // ⚠ IMPORTANT : Réponse complète pour l’app mobile
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

            // Vérifier si dispensé de Y (niveau parrainage)
            if ($user->verifierDispenseY()) {
                $user->update(['dispense_y' => true]);
            }

            // Débloquer les cours si validé
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
            // Paiement complet = X + Y en une seule fois
            $user->update([
                'paiement_x_effectue' => true,
                'paiement_x_date' => now(),
                'paiement_y_effectue' => true,
                'paiement_y_date' => now(),
                'cours_verrouilles' => false
            ]);

            // Pas de dispense Y car déjà payé
            // Débloquer les cours si validé
            if ($user->validated) {
                $user->debloquerCours();
            }
        }

        // Mettre à jour le niveau de parrainage
        $this->parrainageService->mettreAJourNiveauParrainage($user->id);
    }

    /**
     * Vérifie le statut d'un paiement
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