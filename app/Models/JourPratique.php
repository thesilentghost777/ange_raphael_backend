<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JourPratique extends Model
{
    use HasFactory;

    protected $table = 'jours_pratique';

    protected $fillable = [
        'jour',
        'heure',
        'zone',
        'actif'
    ];

    protected $casts = [
        'heure' => 'datetime:H:i',
        'actif' => 'boolean'
    ];

    public function userJours(): HasMany
    {
        return $this->hasMany(UserJourPratique::class);
    }

    public function scopeActifs($query)
    {
        return $query->where('actif', true);
    }
}
