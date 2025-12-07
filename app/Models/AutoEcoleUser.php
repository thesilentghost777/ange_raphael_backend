<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class AutoEcoleUser extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'type_permis',
        'code_parrainage',
        'parrain_id',
        'niveau_parrainage',
        'session_id',
        'centre_examen_id',
        'validated',
        'paiement_x_date',
        'paiement_y_date',
        'paiement_x_effectue',
        'paiement_y_effectue',
        'dispense_y',
        'code_caisse',
        'cours_verrouilles',
        'date_inscription'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'validated' => 'boolean',
        'paiement_x_effectue' => 'boolean',
        'paiement_y_effectue' => 'boolean',
        'dispense_y' => 'boolean',
        'cours_verrouilles' => 'boolean',
        'niveau_parrainage' => 'integer',
        'paiement_x_date' => 'datetime',
        'paiement_y_date' => 'datetime',
        'date_inscription' => 'datetime'
    ];

    // Relations
    public function parrain(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'parrain_id');
    }

    public function filleuls(): HasMany
    {
        return $this->hasMany(AutoEcoleUser::class, 'parrain_id');
    }

    public function filleulesRecords(): HasMany
    {
        return $this->hasMany(Filleul::class, 'parrain_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function centreExamen(): BelongsTo
    {
        return $this->belongsTo(CentreExamen::class);
    }

    public function joursPratique(): HasMany
    {
        return $this->hasMany(UserJourPratique::class, 'user_id');
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(AutoEcolePaiement::class, 'user_id');
    }

    public function progressionLecons(): HasMany
    {
        return $this->hasMany(ProgressionLecon::class, 'user_id');
    }

    public function resultatsQuiz(): HasMany
    {
        return $this->hasMany(ResultatQuiz::class, 'user_id');
    }

    // Helpers
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function genererCodeParrainage(): string
    {
        $prefix = $this->type_permis === 'permis_a' ? 'ARA' : 'ARB';
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        return "{$prefix}-{$random}";
    }

    public function verifierDelaiPaiementY(): bool
    {
        if (!$this->paiement_x_effectue || $this->dispense_y) {
            return true;
        }

        $config = ConfigPaiement::first();
        $delai = $config->delai_paiement_y ?? 60;
        
        $dateLimit = $this->paiement_x_date->addDays($delai);
        
        return now()->lessThanOrEqualTo($dateLimit);
    }

    public function calculerNiveauParrainage(): int
    {
        $filleuls = $this->filleuls;
        
        if ($filleuls->count() === 0) {
            return 0;
        }

        if ($filleuls->count() < 3) {
            return 1;
        }

        // Vérifier si tous les 3 filleuls sont au moins N1
        $niveauxFilleuls = $filleuls->pluck('niveau_parrainage')->toArray();
        
        if (count($niveauxFilleuls) >= 3) {
            $min = min(array_slice($niveauxFilleuls, 0, 3));
            
            if ($min >= 2) {
                return 3;
            } elseif ($min >= 1) {
                return 2;
            } else {
                return 1;
            }
        }

        return 1;
    }

    public function trouverEmplacementFilleul($parrainId = null)
    {
        $parrain = $parrainId ? self::find($parrainId) : $this;
        
        if (!$parrain) {
            return null;
        }

        // Si le parrain a moins de 3 filleuls directs
        if ($parrain->filleuls()->count() < 3) {
            return $parrain->id;
        }

        // Recherche en profondeur (DFS)
        foreach ($parrain->filleuls as $filleul) {
            $emplacement = $filleul->trouverEmplacementFilleul($filleul->id);
            if ($emplacement) {
                return $emplacement;
            }
        }

        return null;
    }

    public function verifierDispenseY(): bool
    {
        if (!$this->paiement_x_effectue) {
            return false;
        }

        // Calculer le niveau actuel
        $niveauActuel = $this->calculerNiveauParrainage();
        
        // Si niveau >= 1 avant le délai de 2 mois
        if ($niveauActuel >= 1 && $this->verifierDelaiPaiementY()) {
            return true;
        }

        return false;
    }

    public function debloquerCours(): void
    {
        if ($this->validated && $this->paiement_x_effectue) {
            $this->update(['cours_verrouilles' => false]);
        }
    }

    public function verrouillerCours(): void
    {
        $this->update(['cours_verrouilles' => true]);
    }

    // Scopes
    public function scopeValide($query)
    {
        return $query->where('validated', true);
    }

    public function scopePaiementComplet($query)
    {
        return $query->where('paiement_x_effectue', true)
                    ->where(function($q) {
                        $q->where('paiement_y_effectue', true)
                          ->orWhere('dispense_y', true);
                    });
    }
}
