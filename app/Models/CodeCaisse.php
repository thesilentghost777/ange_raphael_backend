<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodeCaisse extends Model
{
    use HasFactory;

    protected $table = 'codes_caisse';

    protected $fillable = [
        'code',
        'user_id',
        'montant',
        'tranche',
        'utilise',
        'date_utilisation',
        'date_expiration',
        'genere_par'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'utilise' => 'boolean',
        'date_utilisation' => 'datetime',
        'date_expiration' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(AutoEcoleUser::class, 'user_id');
    }

    public function generateurUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'genere_par');
    }

    public static function genererCode(): string
    {
        return 'CC-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 10));
    }

    public function estValide(): bool
    {
        if ($this->utilise) {
            return false;
        }

        if ($this->date_expiration && now()->greaterThan($this->date_expiration)) {
            return false;
        }

        return true;
    }
}
