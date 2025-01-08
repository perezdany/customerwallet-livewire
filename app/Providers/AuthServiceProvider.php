<?php

namespace App\Providers;

use App\Models\Utilisateur;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //OU ON CREE LES GATES
        Gate::define("admin", function(Utilisateur $user){
           
            return $user->hasRole("admin");
            
        });

        Gate::define("standar", function(Utilisateur $user){
            return $user->hasRole("standar");
        });

        Gate::define("employe", function(Utilisateur $user){
            return $user->hasRole("employe");
        });

        Gate::define("manager", function(Utilisateur $user){
            return $user->hasRole("manager");
        });

        Gate::define("commercial", function(Utilisateur $user){
            return $user->hasRole("commercial");
        });

        Gate::define("manager-commercial", function(Utilisateur $user){
            return $user->hasRole("manager-commercial");
        });

        Gate::define("comptable", function(Utilisateur $user){
            return $user->hasRole("comptable");
        });

        //GATE POUR SUPPRIMER
        Gate::define("delete", function(Utilisateur $user){
            return $user->hasPermission("Suppression");
        });

        Gate::define("edit", function(Utilisateur $user){
            return $user->hasPermission("Ecriture");
        });

       Gate::after(function (Utilisateur $user) {
    
            return $user->hasRole("super-admin");
        });

       /* //LES GATES POUR LES DEPARTEMENTS
        Gate::define("COMMERCIAL", function (Utilisateur $user) {
            return $user->hasDepartement("COMMERCIAL");
        });

        Gate::define("FINANCE & CONTROL DE GESTION", function (Utilisateur $user) {
            return $user->hasDepartement("FINANCE & CONTROL DE GESTION");
        });

        Gate::define("DIRECTION GENERALE", function (Utilisateur $user) {
            return $user->hasDepartement("DIRECTION GENERALE");
        });*/
    }
}
