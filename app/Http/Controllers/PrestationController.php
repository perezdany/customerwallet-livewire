<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prestation;
use App\Models\Prestation_service;

use  App\Http\Controllers\ServiceController;

use  App\Http\Controllers\ContratController;

use  App\Http\Controllers\EntrepriseController;

use DB;

class PrestationController extends Controller
{
    //Handle Prestation

    public function AddPrestation(Request $request)
    {
       
        if($request->type == 0)
        {
            return back()->with('error', 'Choisissez impérativement le type de prestation');
        }

        if($request->contrat == 0)
        {
            return back()->with('error', 'Choisissez impérativement le contrat');
        }
       
        //VERIFIER SI IL N'EST PAS CLIENT CHANGE SONT STATUT A MEME TEMPS
        //RECUPER L'(ENTREPRISE)
        $recup_entreprise = (new ContratController())->GetById($request->contrat);
        foreach($recup_entreprise as $recup_entreprise)
        {
          
            $entreprise = (new EntrepriseController())->GetById($recup_entreprise->id_entreprise);
            foreach($entreprise as $entreprise)
            {
              
                if($entreprise->id_statutentreprise == 1)
                {
                    //dd($entreprise);
                    $affected = DB::table('entreprises')
                    ->where('id', $entreprise->id)
                    ->update([ 'id_statutentreprise' => 2, 
                        'client_depuis' => date('Y-m-d') 
                ]);
                }
            }
            
        }
        

        $insert = Prestation::create([
             'date_prestation' => $request->date_execute, 
             'id_type_prestation' => $request->type,
              'localisation' => $request->localisation, 
              'id_contrat' => $request->contrat, 
              
               'created_by' => auth()->user()->id,
        ]);

        //IMPLEMENTATION DE LA RELATION PLUSIEURS A PLUSIEURS
        //Etant donné qu'on peut sélectionner plusieurs services lors de l'enregistrement de la prospection
        //$insert->services()->attach($request->service);//Il sera lié au IDS des différents ser1vices services selectionnés

        if($request->service == false)//L'utilisateur peut ne pas remplir
        {
           
        }
        else
        {
            for($a = 0; $a < count($request->service); $a++)
            {
              
                $Insert = Prestation_service::create([
        
                    'service_id' =>  $request->service[$a],
                    'prestation_id' => $request->id_prestation,

                ]);
            }
        }

        return back()->with('success', 'Enregistrement effectué');
    }

    public function MyOwnPrestation($id)
    {
         
          $get = DB::table('prestations')
          ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
          ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
          ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
          ->where('prestations.created_by', '=', $id)
          ->get(['prestations.*', 'contrats.fin_contrat',  'contrats.reste_a_payer', 'contrats.titre_contrat',  
           'typeprestations.libele', 'entreprises.nom_entreprise']);
   
         return $get;
    }

    public function GetAll()
    {
        $get = DB::table('prestations')
          ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
          ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
          
          ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
          ->join('utilisateurs', 'prestations.created_by', '=', 'utilisateurs.id')
          ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.reste_a_payer', 'contrats.titre_contrat',
        
          'typeprestations.libele', 'entreprises.nom_entreprise', 'utilisateurs.nom_prenoms',]);
        
           return $get;
    }

    public function getAllNoReglee()
    {
        $get = DB::table('prestations')
          ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
          ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
         
          ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
          ->join('utilisateurs', 'prestations.created_by', '=', 'utilisateurs.id')
          ->where('contrats.statut_solde', '=', 0)
          ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.reste_a_payer', 'contrats.titre_contrat',
          
          'typeprestations.libele', 'entreprises.nom_entreprise', 'utilisateurs.nom_prenoms',]);
        
           return $get;
    }

    public function EditPrestForm(Request $request)
    {
        //dd($request->id_prestation);
        return view('admin/edit_prestation',
            [
                'id' => $request->id_prestation,
            ]
        );
    }

    public function EditPrestation(Request $request)
    {
        $affected = DB::table('prestations')
              ->where('id', $request->id_prestation)
              ->update([ 'date_prestation' => $request->date_execute, 
              'id_type_prestation' => $request->type,
               'localisation' => $request->localisation, 
               'id_contrat' => $request->contrat, 
               
                'created_by' => auth()->user()->id,]);

        
        //IMPLEMENTATION DE LA RELATION PLUSIEURS A PLUSIEURS
        //Etant donné qu'on peut sélectionner plusieurs services lors de l'enregistrement de la prospection

        //INSERER DANS LA TABLE MANY TO MANY SI IL A CHOISI UN OU PLUSIEURS SERVICE
      
        if($request->service == false)//L'utilisateur peut ne pas remplir
        {
           
        }
        else
        {
            for($a = 0; $a < count($request->service); $a++)
            {
              
                $Insert = Prestation_service::create([
        
                    'service_id' =>  $request->service[$a],
                    'prestation_id' => $request->id_prestation,

                ]);
            }
        }
        

    
        return redirect('prestation')->with('success' ,'Modification effectuée');
    }

    public function GetById($id)
    {
        $get = DB::table('prestations')
        ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
        
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->where('prestations.id', '=', $id)
        ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.titre_contrat', 'contrats.date_solde',
        'contrats.montant', 'contrats.reste_a_payer',  
         'typeprestations.libele',  'entreprises.nom_entreprise']);
 
        return $get;
    }

    public function GetPrestationByIdEntr($id)
    {
        $get = DB::table('prestations')
        ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
    
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->where('entreprises.id', '=', $id)
        ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.titre_contrat', 'contrats.date_solde',
        'contrats.montant', 'contrats.reste_a_payer', 
         'typeprestations.libele',  'entreprises.nom_entreprise', ]);
 
        return $get;
    }

}
