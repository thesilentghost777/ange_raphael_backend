<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserJourPratique extends Model
{
    use HasFactory;

    protected $table = 'user_jours_pratique';

    protected $fillable = [
        'user_id',
        'jour_pratique_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'user_id');
    }

    public function jourPratique(): BelongsTo
    {
        return $this->belongsTo(JourPratique::class);
    }
}
