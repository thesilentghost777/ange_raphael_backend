<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoEcolePaiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'montant',
        'type_paiement',
        'tranche',
        'transaction_id',
        'statut',
        'methode_paiement',
        'notes',
        'date_paiement'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime'
    ];

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'user_id');
    }

    // Helpers
    public static function genererTransactionId(): string
    {
        return 'TXN-' . now()->format('YmdHis') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
    }
}
