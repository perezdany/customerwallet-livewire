<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivi extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'date_activite', 'heure_action', 'action', 'name_interl',
        'tel_interl', 'comment', 'id_utilisateur', 'id_entreprise'
    ];
}
