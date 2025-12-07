<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecon extends Model
{
    use HasFactory;

    protected $fillable = [
        'chapitre_id',
        'titre',
        'contenu_texte',
        'image_url',
        'video_url',
        'ordre',
        'duree_minutes'
    ];

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class);
    }

    public function progressions(): HasMany
    {
        return $this->hasMany(ProgressionLecon::class);
    }

    public function estCompletePar($userId): bool
    {
        return $this->progressions()
            ->where('user_id', $userId)
            ->where('completee', true)
            ->exists();
    }
}
