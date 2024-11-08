<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospection_service extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
         'service_id', 'prospection_id', 
    ];
}
