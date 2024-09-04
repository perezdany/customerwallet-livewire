<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'titre_contrat', 'montant', 'reste_a_payer', 'date_solde', 'debut_contrat', 
        'fin_contrat', 'id_entreprise', 'created_by', 'statut_solde', 'update_at', 'path'
    ];
}
