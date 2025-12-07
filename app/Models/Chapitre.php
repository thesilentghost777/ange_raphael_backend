<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapitre extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'nom',
        'ordre',
        'description'
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function lecons(): HasMany
    {
        return $this->hasMany(Lecon::class)->orderBy('ordre');
    }

    public function quiz(): HasMany
    {
        return $this->hasMany(Quiz::class)->orderBy('ordre');
    }
}
