<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Paiement;

use DB;

class PaiementController extends Controller
{
    //Handle Paiement

    public function GoForm(Request $request)
    {
        return view('admin/paiements_form',
            [
                'id' => $request->id_prestation,
            ]
        );
    }

    public function DoPaiement(Request $request)
    {
        $ch = strval($request->paiement);
        if(strlen($ch) > 13)
        {
            //rediriger pour lui dire que c'est trop long
            return redirect('prestation')->with('error', 'données montant saisies trop long');
        }
        
        //FAIRE LES MISES A JOURS DES TABLES CONCERNEES
        $total_paiement = 0;


        $Insert = Paiement::create([
            'paiement' => $request->paiement, 
            'id_prestation' => $request->id_prestation,
            'date_paiement' => $request->date_paiement, 
            'numero_facture' => $request->numero_facture, 
            'created_by' => auth()->user()->id, 
        ]);

        //Récuper tous les anciens montants
        $get_montants = DB::table('paiements')
        ->where('prestations.id', $request->id_prestation)
        ->where('contrats.id', $request->id_contrat)
        ->join('prestations', 'paiements.id_prestation', '=', 'prestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
        ->get(['paiements.paiement']);

        foreach($get_montants as $get_montants)
        {
            $total_paiement = $total_paiement + $get_montants->paiement;
        }

        //Calcul du nouveau reste 
        $rest = $request->montant - $total_paiement;

        //MISE A JOUR DE LA TABLE CONTRAT
        $affected = DB::table('contrats')
              ->where('id', $request->id_contrat)
              ->update([ 'reste_a_payer' => $rest, ]);


        return redirect('prestation')->with('success', 'Paiement enregistré');

    }

    public function GetPaimentByIdPrestation($id)
    {
        $get = DB::table('paiements')
            ->join('prestations', 'paiements.id_prestation', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->where('prestations.id', $id)
           
            ->get(['paiements.*']);

        return $get;
    }
}
