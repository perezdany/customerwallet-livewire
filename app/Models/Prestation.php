<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
        'date_prestation', 'id_type_prestation', 'localisation', 'id_contrat',  'created_by'
    ];

    public function services()
    {
        //return $this->belongsTomany('App\Models\Service');
        return $this->belongsTomany('App\Models\Service');
    }
}
