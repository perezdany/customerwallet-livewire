<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prestation;

use  App\Http\Controllers\ServiceController;

use  App\Http\Controllers\ContratController;

use  App\Http\Controllers\EntrepriseController;

use DB;

class PrestationController extends Controller
{
    //Handle Prestation

    public function AddPrestation(Request $request)
    {
       
        if($request->service == 0)
        {
            return back()->with('error', 'Choisissez impérativement le service');
        }

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
        

        $Insert = Prestation::create([
             'date_prestation' => $request->date_execute, 
             'id_type_prestation' => $request->type,
              'localisation' => $request->localisation, 
              'id_contrat' => $request->contrat, 
              'id_service' => $request->service,
               'created_by' => auth()->user()->id,
        ]);

        return redirect('welcome')->with('success', 'Enregistrement effectué');
    }

    public function MyOwnPrestation($id)
    {
         
          $get = DB::table('prestations')
          ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
          ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
          ->join('services', 'prestations.id_service', '=', 'services.id')
          ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
          ->where('prestations.created_by', '=', $id)
          ->get(['prestations.*', 'contrats.fin_contrat',  'contrats.reste_a_payer', 'contrats.titre_contrat', 
           'services.libele_service', 'services.description', 
           'typeprestations.libele', 'entreprises.nom_entreprise']);
   
         return $get;
    }

    public function GetAll()
    {
        $get = DB::table('prestations')
          ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
          ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
          ->join('services', 'prestations.id_service', '=', 'services.id')
          ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
          ->join('utilisateurs', 'prestations.created_by', '=', 'utilisateurs.id')
          ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.reste_a_payer', 'contrats.titre_contrat',
          'services.libele_service', 'services.description', 
          'typeprestations.libele', 'entreprises.nom_entreprise', 'utilisateurs.nom_prenoms',]);
        
           return $get;
    }

    public function getAllNoReglee()
    {
        $get = DB::table('prestations')
          ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
          ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
          ->join('services', 'prestations.id_service', '=', 'services.id')
          ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
          ->join('utilisateurs', 'prestations.created_by', '=', 'utilisateurs.id')
          ->where('contrats.statut_solde', '=', 0)
          ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.reste_a_payer', 'contrats.titre_contrat',
          'services.libele_service', 'services.description', 
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
               'id_service' => $request->service,
                'created_by' => auth()->user()->id,]);

    
        return redirect('prestation')->with('success' ,'Modification effectuée');
    }

    public function GetById($id)
    {
        $get = DB::table('prestations')
        ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
        ->join('services', 'prestations.id_service', '=', 'services.id')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->where('prestations.id', '=', $id)
        ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.titre_contrat', 'contrats.date_solde',
        'contrats.montant', 'contrats.reste_a_payer',  'services.libele_service', 'services.description',
         'typeprestations.libele',  'entreprises.nom_entreprise']);
 
        return $get;
    }

    public function GetPrestationByIdEntr($id)
    {
        $get = DB::table('prestations')
        ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
        ->join('services', 'prestations.id_service', '=', 'services.id')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->where('entreprises.id', '=', $id)
        ->get(['prestations.*', 'contrats.fin_contrat', 'contrats.titre_contrat', 'contrats.date_solde',
        'contrats.montant', 'contrats.reste_a_payer',  'services.libele_service', 'services.description',
         'typeprestations.libele',  'entreprises.nom_entreprise']);
 
        return $get;
    }

}
