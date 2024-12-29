<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
       'nom_entreprise', 'client_depuis', 'adresse', 'id_statutentreprise', 
       'chiffre_affaire', 'nb_employes', 'telephone',  'etat', 'id_pays',
        'activite', 'adresse_email', 'created_by',
    ];
}
