<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    use HasFactory;
    protected $table = 'sessions1';
    protected $fillable = [
        'nom',
        'date_debut',
        'date_fin',
        'date_examen',
        'statut',
        'description'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_examen' => 'date'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(AutoEcoleUser::class);
    }

    public function scopeOuvertes($query)
    {
        return $query->where('statut', 'ouvert');
    }

    public function scopeRecentes($query, $limit = 4)
    {
        return $query->orderBy('date_debut', 'desc')->limit($limit);
    }
}
