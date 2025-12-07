<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Filleul extends Model
{
    use HasFactory;

    protected $fillable = [
        'parrain_id',
        'filleul_id',
        'date_parrainage'
    ];

    protected $casts = [
        'date_parrainage' => 'datetime'
    ];

    public function parrain(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'parrain_id');
    }

    public function filleul(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'filleul_id');
    }
}
