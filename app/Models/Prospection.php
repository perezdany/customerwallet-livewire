<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospection extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable=[
         'service_propose', 'date_prospection', 'duree_jours', 'date_fin', 'id_entreprise', 'id_utilisateur', 'interlocuteur', 'update_at',
    ];
}
