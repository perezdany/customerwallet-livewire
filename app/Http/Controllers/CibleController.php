<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entreprise;

use DB;

class CibleController extends Controller
{
    //Les entreprise qu'on prévoit prospecter

    public function GetAll()
    {
      
       $get = DB::table('entreprises')
       ->where('entreprises.id_statutentreprise', 3)
       ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
       ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->orderBy('nom_entreprise', 'asc')
       ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

       return $get;
    }

    public function GetById($id)
    {
        $get = DB::table('entreprises')
        ->where('entreprises.id', $id)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
        ->orderBy('nom_entreprise', 'asc')
        ->get(['entreprises.*', 'pays.nom_pays']);
        
        return $get;
    }

    public function AddCible(Request $request)
    { 
        //dd($request->all());
        $Insert = Entreprise::create([
           
        'nom_entreprise'=> $request->nom,
        'chiffre_affaire' => $request->chiffre, 
        'nb_employes' => $request->nb_emp,
        'adresse' => $request->adresse,
        'activite' => $request->activite,
        'telephone' => $request->tel,
        'mobile' => $request->mobile,
        'date_creation' => $request->date_creation,
        'id_pays' => $request->pays,
        'adresse_email' => $request->email,
        'site_web' => $request->site_web,
        'id_statutentreprise' => 3,
        'particulier' => $request->particulier,
         'created_by' => auth()->user()->id, 
        ]);

        //dd($request->chiffre);

      
        return redirect('cibles')->with('success', 'Enregistrement effectué');
    }

    public function EditCibleForm(Request $request)
    {
      
        //dd($request->id_entreprise);
        return view('dash/cibles',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );

        
    }

    public function DisplayCibleInfo(Request $request)
    {
      
        //dd($request->id_entreprise);
        return view('dash/cibles',
            [
                'display_entreprise' => $request->id_entreprise,
            ]
        );

        
    }


    public function EditCible(Request $request)
    {
        $affected= DB::table('entreprises')
        ->where('id', $request->id_entreprise)
        ->update([
            'nom_entreprise'=> $request->nom,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'date_creation' => $request->date_creation,
            'adresse' => $request->adresse,
            'activite' => $request->activite,
            'telephone' => $request->tel,
            'mobile' => $request->mobile,
            'adresse_email' => $request->email,
            'site_web' => $request->site_web,
            'id_pays' => $request->pays,
            'particulier' => $request->particulier,
            'created_by' => auth()->user()->id, 
        ]);
        return redirect('cibles')->with('success', 'Modification effectuée');
    }

    public function DeleteCible(Request $request)
    {
        $deleted = DB::table('entreprises')->where('id', '=', $request->id_entreprise)->delete();

        return redirect('cibles')->with('success', 'Elément supprimé');
    }
}
