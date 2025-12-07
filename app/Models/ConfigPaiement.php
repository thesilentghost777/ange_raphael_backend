<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigPaiement extends Model
{
    use HasFactory;

    protected $table = 'config_paiement';

    protected $fillable = [
        'montant_x',
        'montant_y',
        'montant_z',
        'delai_paiement_y'
    ];

    protected $casts = [
        'montant_x' => 'decimal:2',
        'montant_y' => 'decimal:2',
        'montant_z' => 'decimal:2',
        'delai_paiement_y' => 'integer'
    ];

    public static function getConfig()
    {
        return self::firstOrCreate([]);
    }
}
