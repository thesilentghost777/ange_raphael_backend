<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressionLecon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lecon_id',
        'completee',
        'date_completion'
    ];

    protected $casts = [
        'completee' => 'boolean',
        'date_completion' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'user_id');
    }

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class);
    }
}
