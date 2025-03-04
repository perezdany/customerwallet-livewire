<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doc extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'libele',
        'path_doc',
        'id_prospection',
        'id_contrat',
        'id_facture',
        'id_utilisateur',
        'id_entreprise'
    ];

}
