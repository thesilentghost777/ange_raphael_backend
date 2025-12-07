<?php

namespace App\Services;

use App\Models\AutoEcoleUser;
use App\Models\Filleul;
use Illuminate\Support\Facades\DB;

class ParrainageService
{
    /**
     * Attribue un parrain à un nouvel utilisateur
     */
    public function attribuerParrain(AutoEcoleUser $user, string $codeParrainage): void
    {
        // Code par défaut du centre
        if ($codeParrainage === 'AR-cfpam-2025') {
            $user->update([
                'parrain_id' => null,
                'niveau_parrainage' => 0
            ]);
            return;
        }

        $parrain = AutoEcoleUser::where('code_parrainage', $codeParrainage)->first();

        if (!$parrain) {
            // Code invalide, utiliser code par défaut
            $user->update([
                'parrain_id' => null,
                'niveau_parrainage' => 0
            ]);
            return;
        }

        // Trouver l'emplacement optimal dans l'arbre
        $parrainEffectifId = $this->trouverEmplacementOptimal($parrain->id);

        if ($parrainEffectifId) {
            $user->update([
                'parrain_id' => $parrainEffectifId,
                'niveau_parrainage' => 0
            ]);

            // Créer l'enregistrement de parrainage
            Filleul::create([
                'parrain_id' => $parrainEffectifId,
                'filleul_id' => $user->id,
                'date_parrainage' => now()
            ]);

            // Mettre à jour le niveau du parrain
            $this->mettreAJourNiveauParrainage($parrainEffectifId);
        }
    }

    /**
     * Trouve l'emplacement optimal dans l'arbre de parrainage
     * Recherche en profondeur (DFS)
     */
    private function trouverEmplacementOptimal(int $parrainId): ?int
    {
        $parrain = AutoEcoleUser::find($parrainId);

        if (!$parrain) {
            return null;
        }

        // Si le parrain a moins de 3 filleuls directs
        if ($parrain->filleuls()->count() < 3) {
            return $parrain->id;
        }

        // Sinon, rechercher parmi ses descendants (DFS)
        foreach ($parrain->filleuls as $filleul) {
            $emplacement = $this->trouverEmplacementOptimal($filleul->id);
            if ($emplacement) {
                return $emplacement;
            }
        }

        return null;
    }

    /**
     * Met à jour le niveau de parrainage d'un utilisateur
     */
    public function mettreAJourNiveauParrainage(int $userId): void
    {
        $user = AutoEcoleUser::find($userId);

        if (!$user) {
            return;
        }

        $nouveauNiveau = $this->calculerNiveauParrainage($user);

        if ($user->niveau_parrainage !== $nouveauNiveau) {
            $user->update(['niveau_parrainage' => $nouveauNiveau]);

            // Vérifier si le niveau permet de dispenser Y
            if ($nouveauNiveau >= 1 && $user->paiement_x_effectue && !$user->paiement_y_effectue) {
                if ($user->verifierDelaiPaiementY()) {
                    $user->update(['dispense_y' => true]);
                }
            }

            // Mettre à jour le parrain également
            if ($user->parrain_id) {
                $this->mettreAJourNiveauParrainage($user->parrain_id);
            }
        }
    }

    /**
     * Calcule le niveau de parrainage d'un utilisateur
     */
    private function calculerNiveauParrainage(AutoEcoleUser $user): int
    {
        $filleuls = $user->filleuls;

        // N0 : Aucun filleul
        if ($filleuls->count() === 0) {
            return 0;
        }

        // N1 : Au moins 1 filleul
        if ($filleuls->count() < 3) {
            return 1;
        }

        // Prendre les 3 premiers filleuls
        $troispremiers = $filleuls->take(3);
        $niveaux = $troispremiers->pluck('niveau_parrainage')->toArray();
        $niveauMin = min($niveaux);

        // N2 : Les 3 filleuls sont au moins N1
        if ($niveauMin >= 2) {
            return 3; // N3
        } elseif ($niveauMin >= 1) {
            return 2; // N2
        } else {
            return 1; // N1
        }
    }

    /**
     * Récupère l'arbre de parrainage d'un utilisateur
     */
    public function getArbreParrainage(int $userId): array
    {
        $user = AutoEcoleUser::with('filleuls')->find($userId);

        if (!$user) {
            return [];
        }

        return [
            'code_parrainage' => $user->code_parrainage,
            'niveau' => $user->niveau_parrainage,
            'bonus' => $this->getBonusParNiveau($user->niveau_parrainage),
            'filleuls' => $this->construireArbreFilleuls($user)
        ];
    }

    /**
     * Construit récursivement l'arbre des filleuls
     */
    private function construireArbreFilleuls(AutoEcoleUser $user, int $profondeur = 0): array
    {
        if ($profondeur >= 3) { // Limiter la profondeur
            return [];
        }

        return $user->filleuls->map(function($filleul) use ($profondeur) {
            return [
                'id' => $filleul->id,
                'nom_complet' => $filleul->nom . ' ' . $filleul->prenom,
                'niveau' => $filleul->niveau_parrainage,
                'date_inscription' => $filleul->date_inscription,
                'filleuls' => $this->construireArbreFilleuls($filleul, $profondeur + 1)
            ];
        })->toArray();
    }

    /**
     * Retourne les bonus selon le niveau
     */
    private function getBonusParNiveau(int $niveau): array
    {
        $config = \App\Models\ConfigPaiement::getConfig();

        return [
            0 => [
                'description' => 'Payer X + Y',
                'montant_total' => $config->montant_x + $config->montant_y
            ],
            1 => [
                'description' => 'Payer seulement X (dispensé de Y)',
                'montant_total' => $config->montant_x,
                'economie' => $config->montant_y
            ],
            2 => [
                'description' => 'Remboursement de X (géré en présentiel)',
                'montant_total' => 0,
                'economie' => $config->montant_x + $config->montant_y,
                'remboursement' => $config->montant_x
            ],
            3 => [
                'description' => 'Remboursement de X + Bonus Z (géré en présentiel)',
                'montant_total' => 0,
                'economie' => $config->montant_x + $config->montant_y,
                'remboursement' => $config->montant_x,
                'bonus' => $config->montant_z
            ]
        ][$niveau] ?? [];
    }

    /**
     * Statistiques de parrainage
     */
    public function getStatistiquesParrainage(int $userId): array
    {
        $user = AutoEcoleUser::find($userId);

        if (!$user) {
            return [];
        }

        return [
            'niveau_actuel' => $user->niveau_parrainage,
            'nombre_filleuls_directs' => $user->filleuls()->count(),
            'nombre_total_filleuls' => $this->compterTousLesFilleuls($user),
            'bonus' => $this->getBonusParNiveau($user->niveau_parrainage),
            'prochaine_etape' => $this->getProchainObjectif($user)
        ];
    }

    /**
     * Récupère la liste des filleuls d'un utilisateur
     */
    public function getFilleuls(int $userId): array
    {
        $user = AutoEcoleUser::with(['filleuls' => function($query) {
            $query->orderBy('date_inscription', 'desc');
        }])->find($userId);

        if (!$user) {
            return [];
        }

        return $user->filleuls->map(function($filleul) {
            return [
                'id' => $filleul->id,
                'nom' => $filleul->nom,
                'prenom' => $filleul->prenom,
                'email' => $filleul->email,
                'telephone' => $filleul->telephone,
                'niveau_parrainage' => $filleul->niveau_parrainage,
                'date_inscription' => $filleul->date_inscription,
                'paiement_x_effectue' => $filleul->paiement_x_effectue,
                'paiement_y_effectue' => $filleul->paiement_y_effectue,
                'dispense_y' => $filleul->dispense_y,
                'nombre_filleuls' => $filleul->filleuls()->count()
            ];
        })->toArray();
    }

    /**
     * Compte tous les filleuls récursivement
     */
    private function compterTousLesFilleuls(AutoEcoleUser $user): int
    {
        $total = $user->filleuls()->count();

        foreach ($user->filleuls as $filleul) {
            $total += $this->compterTousLesFilleuls($filleul);
        }

        return $total;
    }

    /**
     * Retourne le prochain objectif
     */
    private function getProchainObjectif(AutoEcoleUser $user): string
    {
        $filleulsCount = $user->filleuls()->count();

        if ($filleulsCount < 3) {
            return "Parrainer " . (3 - $filleulsCount) . " personne(s) supplémentaire(s) pour atteindre N1";
        }

        $niveauMin = $user->filleuls->take(3)->min('niveau_parrainage');

        if ($user->niveau_parrainage === 1) {
            return "Tous vos filleuls doivent atteindre N1 pour passer à N2";
        } elseif ($user->niveau_parrainage === 2) {
            return "Tous vos filleuls doivent atteindre N2 pour passer à N3";
        } else {
            return "Niveau maximum atteint !";
        }
    }
}