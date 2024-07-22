<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'paiement', 'id_prestation', 'date_paiement', 'numero_facture',  'updated_at', 'created_by'
    ];
}
