<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = [
        'libele_service',
        'suspendu',
        'description',
        'updated_at',
    ];


   
}
