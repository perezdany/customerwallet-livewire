<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Prospection extends Model
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable=[
        'date_prospection', 'duree_jours', 'date_fin', 
         'id_entreprise', 'id_utilisateur', 'interlocuteur', 'update_at',
         'path_cr', 'facture_path'
    ];

    public function services()
    {
        //return $this->belongsTomany('App\Models\Service');
        return $this->belongsTomany('App\Models\Service');
    }
}
