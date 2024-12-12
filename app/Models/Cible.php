<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cible extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
        'nom', 'adresse', 'id_pays', 'contact', 'updated_at', 'created_by' 
    ];
}
