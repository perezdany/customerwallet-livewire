<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proposition extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
        'libele', 'path_doc', 'id_prospection', 
         'id_utilisateur', 'updated_at', 
    ];
}
