<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrat extends Model
{
    use HasFactory;
    
    public $timestamps = true;

    protected $fillable = [
        'titre_contrat', 'montant', 'reste_a_payer', 'date_solde', 'debut_contrat', 
        'fin_contrat', 'id_entreprise', 'created_by', 'statut_solde',  'path', 'proforma_file',
        'bon_commande', 'reconduction', 'avenant', 'id_contrat_parent', 'id_type_prestation'
    ];
}
