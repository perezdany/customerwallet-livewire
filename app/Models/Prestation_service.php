<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation_service extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
         'service_id', 'contrat_id', 
    ];
}
