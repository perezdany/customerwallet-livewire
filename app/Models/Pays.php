<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    use HasFactory;

    
    public $timestamps = true;
    
    protected $fillable = [
        'nom_pays', 'updated_at', 
    ];
}
