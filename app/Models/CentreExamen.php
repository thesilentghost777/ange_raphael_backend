<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentreExamen extends Model
{
    use HasFactory;

    protected $table = 'centres_examen';

    protected $fillable = [
        'nom',
        'adresse',
        'ville',
        'telephone',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(AutoEcoleUser::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}
