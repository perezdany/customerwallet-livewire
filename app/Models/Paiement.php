<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'paiement', 'id_facture', 'date_paiement', 'updated_at', 'created_by'
    ];

    public function factures()
    {
        return $this->belongsTo(Facture::class, 'id_facture', 'id');
    }

    
}
