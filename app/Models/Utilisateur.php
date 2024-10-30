<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
        'login', 'password', 'nom_prenoms', 'id_departement', 'poste', 
        'update_at', 'created_by', 'active', 'id_role', 'count_login',
    ];
}
