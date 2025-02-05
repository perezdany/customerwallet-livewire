<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
       'nom_entreprise', 'client_depuis', 'adresse', 'id_statutentreprise', 'particulier',
       'date_creation', 'chiffre_affaire', 'nb_employes', 'telephone', 'mobile', 'etat', 'id_pays',
        'activite', 'adresse_email', 'site_web', 'dirigeant', 'created_by',
    ];


    public function contrats()
    {
        return $this->hasMany(Contrat::class);
    }

}
