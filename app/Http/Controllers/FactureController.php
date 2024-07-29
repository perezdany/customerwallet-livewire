<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB; 

use App\Models\Facture;

class FactureController extends Controller
{
    //Handle Factures

    public function DisplayByIdPrestation($id)
    {
        $get =  DB::table('factures')
        ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
        ->where('prestations.id', $id)
        ->get(['factures.*', 'contrats.titre_contrat']);

        return $get;

    }


    public function FactureByPrestation(Request $request)
    {
        return view('admin/prestations',
        [
            'id_prestation' => $request->id_prestation,
        ]
        );
    }

    public function GetById($id)
    {
        $get = DB::table('factures')
            
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('services', 'prestations.id_service', '=', 'services.id')
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')       
            ->where('factures.id', $id)
            ->get(['factures.*', 'prestations.localisation', 'prestations.date_prestation', 'prestations.id_contrat',
            'contrats.titre_contrat', 'contrats.date_solde', 
            'contrats.montant', 'contrats.reste_a_payer',  'services.libele_service', 'services.description',
             'typeprestations.libele',  'entreprises.nom_entreprise']);
       
        return $get;
    }
    public function GetAll()
    {
        $get = DB::table('factures')
            
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('services', 'prestations.id_service', '=', 'services.id')
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')   
            ->get(['factures.*', 'prestations.localisation', 'prestations.date_prestation', 'contrats.titre_contrat', 'contrats.date_solde',
            'contrats.montant', 'contrats.reste_a_payer',  'services.libele_service', 'services.description',
             'typeprestations.libele',  'entreprises.nom_entreprise']);

        return $get;
    }

    public function AddFacture(Request $request)
    {
        if($request->id_prestation == 0)
        {
            return back()->with('error', 'Choisissez impérativement la prestation');
        }

        $Insert = Facture::create([
            'numero_facture' => $request->numero_facture, 
            'date_reglement' => $request->date_reglement,
             'date_emission' => $request->date_emission, 
             'montant_facture' => $request->montant_facture, 
             'id_prestation' => $request->id_prestation,
              'reglee' => 0,
              'created_by' => auth()->user()->id,
       ]);


       return redirect('facture')->with('success', 'Facture enregistrée');

    }


    public function EditFactureForm(Request $request)
    {
        //dd($request->id_prestation);
        return view('admin/factures',
            [
                'id_edit' => $request->id_facture,
            ]
        );
    }

    public function EditFacture(Request $request)
    {
        /*if($request->id_prestatoin == null)
        {
            return redirect('facture')->with('success', 'Vous n\'avez pas choisi de prestation.');
        }
        else
        {*/
            $affected = DB::table('factures')
            ->where('id', $request->id_facture)
            ->update([ 'numero_facture' => $request->numero_facture, 
                'date_reglement' => $request->date_reglement,
                'date_emission' => $request->date_emission, 
                'montant_facture' => $request->montant_facture, 
                'id_prestation' => $request->id_prestation,]);

            return redirect('facture')->with('success', 'Facture modifiée');
       //}
        
    }

    //FACTURE DATE DEPASSEE
    public function FactureDateDepassee()
    {
        $today = date('Y-m-d');
        
        $get = DB::table('factures')
            
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('services', 'prestations.id_service', '=', 'services.id')
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')   
            ->where('date_reglement', '<', $today)
            ->where('reglee', 0)
            ->take(3)
            ->get(['factures.*', 'prestations.localisation', 'prestations.date_prestation', 'contrats.titre_contrat', 'contrats.date_solde',
            'contrats.montant', 'contrats.reste_a_payer',  'services.libele_service', 'services.description',
             'typeprestations.libele',  'entreprises.nom_entreprise']);

        return $get;
    }
}
