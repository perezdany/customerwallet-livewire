<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/*use App\Models\Role;
use App\Models\Permission;*/

class Utilisateur extends Authenticatable
{
    use HasFactory;

    public $timestamps = true;
    
    protected $fillable = [
        'login', 'password', 'nom_prenoms', 'departements_id', 'poste', 
        'update_at', 'created_by', 'active', 'roles_id', 'count_login',
    ];

    public function roles()
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);

    }

    public function hasAnyPermission($permissions)
    {
        //SI IL A SOIT UNE PERMISSION OU UNE AUTRE 
        return $this->permissions()->whereIn('libele', $permissions)->first() !== null;
    }

    public function departements()
    {
        return $this->belongsTo(Departement::class);
    }

    //DES FONCTIONS QUI VONT VERIFIER SI L'UTILISATEUR A TEL OU TELLE ROLE OU PERMISSION 

     //POUR SON DEPARTEMENT
     public function hasDepartement($departement)
     {
         
        return $this->roles()->where('libele_departement', $departement)->first() !== null;
 
     }
 
    public function hasRole($role)
    {
        return $this->roles()->where('intitule', $role)->first() !== null;

    }

    public function hasPermission($permission)
    {
        return $this->permissions()->where('libele', $permission)->first() !== null;

    }

    public function getDepartemantLibeleAttribute()
    {
        return $this->departements->libele_departement;
    }

   
   
     
}
