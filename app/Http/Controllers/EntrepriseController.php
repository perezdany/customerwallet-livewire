<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entreprise;

use DB;

class EntrepriseController extends Controller
{
    //Hendle Entreporise

    public function GetAll()
    {
        $get = DB::table('entreprises')
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->orderBy('nom_entreprise', 'asc')
        ->get(['entreprises.*', 'statutentreprises.libele_statut']);

        return $get;
    }

    public function GetById($id)
    {
        $get = DB::table('entreprises')
        ->where('entreprises.id', $id)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->get(['entreprises.*', 'statutentreprises.libele_statut']);

        return $get;
    }

    public function AddEntreprise(Request $request)
    {
        $Insert = Entreprise::create([
           
            'nom_entreprise'=> $request->entreprise_name,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'id_statutentreprise' => 1,
             'created_by' => auth()->user()->id, 
        ]);

        //dd($request->chiffre);

        //Recuperer l'enregistrement
       $get = Entreprise::where('nom_entreprise', '=', $request->entreprise_name)->get();

       return $get;
    }

    public function DetectNewCustomer()
    {
        //RECUPER LES CLIENT OU LE STATUT EST DONC CLIENT DONC 2
        $get = Entreprise::where('id_statutentreprise', 2)
        ->orderBy('client_depuis', 'desc')
        ->get();
        return $get;
    }

    public function SaveEntreprise(Request $request)
    {
       //dd($request->statut );
        if(isset($request->entreprise) AND  $request->entreprise == 0)
        {
            return back()->with('error', 'Vous n\'avez pas choisi l\'entreprise');
        }

        //SI ON CHOISI LE STATUT CLIENT IL FAUT METTRE LA DATE
        if($request->statut == 2)
        { 
            $Insert = Entreprise::create([
           
                'nom_entreprise'=> $request->nom,
                'id_statutentreprise' => $request->statut,
                'chiffre_affaire' => $request->chiffre, 
                'nb_employes' => $request->nb_emp,
                 'created_by' => auth()->user()->id,
                 'client_depuis' => $request->depuis,

            ]);

    
        }
        else
        {
            //dd('ici');
            $Insert = Entreprise::create([
           
                'nom_entreprise'=> $request->nom,
                'id_statutentreprise' => $request->statut,
                'chiffre_affaire' => $request->chiffre, 
                'nb_employes' => $request->nb_emp,
                 'created_by' => auth()->user()->id,
            ]);

            return redirect('entreprises')->with('success', 'Enregistrement effectué');
        }
      

        
    }

    public function EditEntrForm(Request $request)
    {
        //dd($request->id_entreprise);
        return view('admin/entreprises',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function EditEntreprise(Request $request)
    {
       

        $affected= DB::table('entreprises')
        ->where('id', $request->id_entreprise)
        ->update([
           
            'nom_entreprise'=> $request->nom,
            'id_statutentreprise' => $request->statut,
            'client_depuis' => $request->depuis,
            'adresse' => $request->addresse,
             
        ]);

      
        return redirect('entreprises')->with('success', 'Modificaiton effectuée');
    }

    public function DisplayCustomers()
    {
        $get = DB::table('entreprises')
        ->where('entreprises.id_statutentreprise', 2)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->get(['entreprises.*', 'statutentreprises.libele_statut']);

        return $get;
    }

    public function DisplayProspects()
    {
        $get = DB::table('entreprises')
        ->where('entreprises.id_statutentreprise', 1)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->join('utilisateurs', 'entreprises.created_by', '=', 'utilisateurs.id')
        ->get(['entreprises.*', 'statutentreprises.libele_statut', 'utilisateurs.nom_prenoms']);

        return $get;
    }

    Public function DeleteEntreprise(Request $request)
    {
        $deleted = DB::table('entreprises')->where('id', '=', $request->id_entreprise)->delete();

        return redirect('entreprises')->with('success', 'Elément supprimé');
    }

    public function GetAboutThisTable(Request $request)
    {
        
        return view('dash/about_this',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GetProspAboutThisTable(Request $request)
    {
        
       /* return view('dash/prosp_about_this',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );*/

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GetFicheCustomer(Request $request)
    {
        
        return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GetAboutThis($id)
    {
        $get = DB::table('contrats')
        ->where('entreprises.id', $id)
    
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->join('prospections', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('services', 'prospections.service_propose', '=', 'services.id')
        ->get(['entreprises.*', 'statutentreprises.libele_statut', ]);

        return $get;
    }

}
