<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResultatQuiz extends Model
{
    use HasFactory;
    protected $table = 'resultats_quiz';
    protected $fillable = [
        'user_id',
        'chapitre_id',
        'score',
        'reussi',
        'date_tentative',
        'peut_retenter',
        'date_prochaine_tentative'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'reussi' => 'boolean',
        'peut_retenter' => 'boolean',
        'date_tentative' => 'datetime',
        'date_prochaine_tentative' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'user_id');
    }

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class);
    }
}
