<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    public $timestamps =  true;

    protected $fillable = [
         'numero_facture', 'date_reglement', 'date_emission', 
         'montant_facture', 'id_prestation', 'reglee', 'created_at', 'updated_at', 'created_by', 'file_path',
    ];
}
