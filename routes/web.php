<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContratController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\ProspectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuiviController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\InterlocuteurController;
use App\Http\Controllers\Calculator;
use App\Http\Controllers\TypePrestationController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\FactureController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//ROUTE AVEC MIDDLEWARE

Route::middleware(['guest:web'])->group(function(){

    //PAGE DE CONNEXION
    Route::get('/', function () {
        return view('login');
    })->name('login');

    Route::post('go_login', [AuthController::class, 'AdminLogin']);

    //DECONNEXION
    //Route::get('logout', [AuthController::class, 'logoutUser']);
});



//SI IL EST DEJA CONNECTE 
Route::middleware(['auth:web'])->group(function(){
    
    //TABLEAU DE BORD
    Route::get('welcome', function () {
        return view('welcome');
    })->name('home');
    
    Route::get('welcome', function () {
        return view('welcome');
    })->name('home');
    
    //PAGES TABLEAU DE BORD

    //CONTRATS
    Route::get('contrat', function () {
        return view('dash/contrats');
    });

    //AJOUTER UN CONTRAT
    Route::post('add_contrat', [ContratController::class, 'AddContrat']);

    //AJOUTER UNE PRESTATION
    Route::post('add_prestation', [PrestationController::class, 'AddPrestation']);

    //AJOUTER UNE PROSECTION
    Route::post('add_prospection', [ProspectionController::class, 'AddProspection']);


    //DECONNEXION
    Route::post('logout', [AuthController::class, 'logoutUser']);

    //PROFIL UTILISATEUR
    Route::post('profile', [UserController::class, 'GoProfil']);

    //PROFIL UTILISATEUR
    Route::post('edit_user_form', [UserController::class, 'GoProfil']);


    //MODIFIER L'UTILISATEUR
    Route::post('edit_user', [UserController::class, 'EditUser']);

    //MODIFIER MOT DE PASSE
    Route::post('edit_password', [UserController::class, 'EditPassword']);

    //DESACTIVER L'UTILISATEUR
    Route::post('disable_user', [UserController::class, 'DisableUser']);

    //ACTIVER L'UTILISATEUR
    Route::post('enable_user', [UserController::class, 'EnableUser']);

    //AJOUTER UN UTILISATEUR
    Route::post('add_user', [UserController::class, 'AddUser']);

    //TABLEAU DES PRESTATIONS
    Route::get('prestation', function () {
        return view('admin/prestations');
    });

    // MODIFIER UNE PRESTATION
    Route::post('edit_prestation_form', [PrestationController::class, 'EditPrestForm']);
    Route::post('edit_prestation', [PrestationController::class, 'EditPrestation']);

    //TABLEAU DES PROSPECTIONS
    Route::get('prospection', function () {
        return view('admin/prospections');
    });

    //MODIFIER UNE PROSPECTION
    Route::post('edit_prospect_form', [ProspectionController::class, 'EditProspForm']);

    Route::post('edit_prospection', [ProspectionController::class, 'EditProspection']);

    //MODIFIER UN CONTRAT
    Route::post('edit_contrat_form', [ContratController::class, 'EditContratForm']);

    Route::post('edit_contrat', [ContratController::class, 'EditContrat']);

    //LES SUIVIS D'ENTREPRISE
    Route::get('suivi', function(){
        return view('admin/suivis');
    });

    //AJOUTER UN SUIVI
    Route::post('add_suivi', [SuiviController::class, 'AddSuivi']);

    //M%ODIIFIR LE SUIVI
    Route::post('edit_suivi_form', [SuiviController::class, 'EditSuiviForm']);

    Route::post('edit_suivi', [SuiviController::class, 'EditSuivi']);

    //UTILISATEURS
    Route::get('utilisateurs', function(){
        return view('admin/users');
    });


    //DEPARTEMENT AJOUTER MODIFIER 
    Route::get('departements', function(){
        return view('admin/departements');
    });

    Route::post('edit_depart_form', [DepartementController::class, 'EditDepForm']);

    Route::post('add_departement', [DepartementController::class, 'AddDepartement']);
    
    Route::post('edit_departement', [DepartementController::class, 'EditDepartement']);


    //SERVICES
    Route::get('services', function(){
        return view('admin/services');
    });
    
    //MODIFIER LE SERVICE
    Route::post('edit_service_form', [ServiceController::class, 'EditServiceForm']);
    Route::post('edit_service', [ServiceController::class, 'EditService']);

    //AJOUTER UN SERVICE
    Route::post('add_service', [ServiceController::class, 'AddService']);

    //SUPPRIMER SERVICE 
    Route::post('delete_service', [ServiceController::class, 'DeleteService']);

    //ENTREPRISES
    Route::get('entreprises', function(){
        return view('admin/entreprises');
    });

    //MODIFIER
    Route::post('edit_entreprise_form', [EntrepriseController::class, 'EditEntrForm']);
    Route::post('edit_entreprise', [EntrepriseController::class, 'EditEntreprise']);

    //AFFICHER LES INFOS DE L'ENTREPRISE.. SI IL Y A DES PRESTATIONS QUI LUI SONT PROPOSE ETC
    Route::post('display_about_customer', [EntrepriseController::class, 'GetAboutThisTable']);

    //AFFICHER LES INFORMATIONS SUR LES PROSPECTS
    Route::post('display_about_prospect', [EntrepriseController::class, 'GetProspAboutThisTable']);

    //AJOUTER 
    Route::post('add_entreprise', [EntrepriseController::class, 'SaveEntreprise']);

    //AFFICHER LA LISTE DE TOUS LES CLIENTS
    Route::get('customers', function(){
        return view('dash/customers');
    });

     //AFFICHER LA LISTE DE TOUS LES PROSPECTS
     Route::get('prospects', function(){
        return view('dash/prospects');
    });

    //SUPPRIMER UNE ENTREPRISE
    Route::post('delete_entreprise', [EntrepriseController::class, 'DeleteEntreprise']);

    
     //AFFICHER LA LISTE DE TOUTES LES PRESTATIONS
     Route::get('prestations_all', function(){
        return view('dash/prestations_display');
    });

    //AFFICHER LA LISTE DE TOUTES LES PROSPECTIONS
    Route::get('all_prospections', function(){
        return view('dash/all_prospections');
    });

     //AFFICHER LA LISTE DE TOUS LES CONTRATS EN COURS
     Route::get('contrats_en_cours', function(){
        return view('dash/contrats_en_cours');
    });

    Route::get('all_contrats', function(){
        return view('dash/all_contrats');
    });

    //AFFICHER LES SUIVIS D'UNE PROSPECTION
    Route::post('display_suivi', [SuiviController::class, 'GosuiviPage']);

    //LES PAIEMENTS
    //ALLER AUX PAIEMENTS
    Route::post('paiement_form', [PaiementController::class, 'GoForm']);

    //PAYER
    Route::post('do_paiement', [PaiementController::class, 'DoPaiement']);

    //EDITER UN PAIEMENT
    Route::post('edit_paiement_form', [PaiementController::class, 'EditPaiementForm']);

    Route::post('edit_paiement', [PaiementController::class, 'EditPaiement']);

    //AFFICHER LES PAIEMENTS DE LA FACTURES
    Route::post('paiement_by_facture', [PaiementController::class, 'PaiementByFacture']);

    //INTERLOCUTEURS
    Route::get('interlocuteurs', function(){
        return view('admin/interlocuteurs');
    });
    
    //MODIFIER INTERLOCUTEUR
    Route::post('edit_interlocuteur_form', [InterlocuteurController::class, 'EditInterlocForm']);
    Route::post('edit_interlocuteur', [InterlocuteurController::class, 'EditInterlocuteur']);

    //AFFICHER LES INTERLOCUTEURS D'UNE ENTREPRISE 
    Route::post('display_by_id_entreprise', [InterlocuteurController::class, 'DisplayByIdEntreprise']);

    //LES GRAPHES 

    //MENSUEL
    Route::get('monthly', [Calculator::class, 'MonthlyChart']);

    //RECHERCHER UN MOIS 
    Route::post('search_monthly_chart', [Calculator::class, 'SearchMonth']);

    //ANNUEL
    Route::get('yearly', [Calculator::class, 'YearlyChart']);

    //RECHERCHER UNE ANNEE
    Route::post('search_yearly_chart', [Calculator::class, 'SearchYear']);

    //LES TYPES DE PRESTATIONS
    Route::get('type_prestation', function(){
        return view('admin/type_prestation');
    });

    //AJOUTER UN TYPE DE PRESTATION
    Route::post('add_type_prestation', [TypePrestationController::class, 'AddTypePrestation']);

    //MODIFIER UN TYPE DE PRESTAION 
    Route::post('edit_typeprest_form', [TypePrestationController::class, 'EditTypePrestationForm']);

    Route::post('edit_typeprest', [TypePrestationController::class, 'EditTypePrestation']);

    //FACTURE
   
    //AFFICHER TOUTES LES FACTURES
    Route::get('facture', function(){
        return view('admin/factures');
    });

    //AFFICHER LES FACTURES DE LA PRESTATION
    Route::post('display_facture', [FactureController::class, 'FactureByPrestation']);

    //AJOUTER UNE FACTURE
    Route::post('add_facture', [FactureController::class, 'AddFacture']);

    //MODIFIER UNE FACTURE
    Route::post('edit_facture_form', [FactureController::class, 'EditFactureForm']);
    Route::post('edit_facture', [FactureController::class, 'EditFacture']);

    //LES ROLES D'UTILISATEURS
    Route::get('roles', function(){
        return view('admin/roles');
    });
    
    //AJOUTER ROLE
    Route::post('add_role', [RoleController::class, 'AddRole']);

    //MODIFIER ROLE
    Route::post('edit_role_form', [RoleController::class, 'EditRoleForm']);
    Route::post('edit_role', [RoleController::class, 'EditRole']);

});



