<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivi extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'titre', 'activite', 'id_prospection', 'created_by', 'updated_at', 
    ];
}
