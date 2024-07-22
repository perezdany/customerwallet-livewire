<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
        'libele_departement', 'update_at',

    ];
}
