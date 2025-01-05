<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
     
    public $timestamps = true;

    protected $fillable = [
        'intitule', 'specifite'
    ];

    public function utilisateurs()
    {
        $this->hasMany(Utilisateur::class);
    }
}
