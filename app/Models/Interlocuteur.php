<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interlocuteur extends Model
{
    use HasFactory;
    

    public $timestamps = true;
    
    protected  $fillable = [
         'titre', 'nom', 'tel', 'email', 'fonction', 'id_entreprise', 'created_by', 'updated_at',
    ];
}
