<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'type',
        'ordre',
        'description'
    ];

    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class)->orderBy('ordre');
    }

    // Utiliser hasManyThrough car lecons passe par chapitres
    public function lecons(): HasManyThrough
    {
        return $this->hasManyThrough(Lecon::class, Chapitre::class);
    }

    public function scopeTheorique($query)
    {
        return $query->where('type', 'theorique')->orderBy('ordre');
    }

    public function scopePratique($query)
    {
        return $query->where('type', 'pratique')->orderBy('ordre');
    }
}