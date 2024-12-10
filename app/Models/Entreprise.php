<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
       'nom_entreprise', 'client_depuis', 'id_statutentreprise', 
       'chiffre_affaire', 'nb_employes',
       'created_by', 'updated_at'
    ];
}
