<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Paiement;

use App\Models\Contrat;

use DB;

class PaiementController extends Controller
{
    //Handle Paiement

    public function GoForm(Request $request)
    {
        return view('admin/paiements_form',
            [
                'id' => $request->id_facture,
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

        $total_montant_facture = 0;

        $Insert = Paiement::create([
            'paiement' => $request->paiement, 
            'id_facture' => $request->id_facture, 
            'date_paiement' => $request->date_paiement, 
            'created_by' => auth()->user()->id, 
        ]);

        //Récuper tous les anciens montants
        $get_montants = DB::table('paiements')
        ->where('paiements.id_facture', $request->id_facture)
        ->join('factures', 'paiements.id_facture', '=', 'factures.id')
        ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
        ->join('services', 'prestations.id_service', '=', 'services.id')
        ->get(['paiements.paiement']);

        foreach($get_montants as $get_montants)
        {
            $total_paiement = $total_paiement + $get_montants->paiement;
        }

        //Calcul du nouveau reste 
        $rest = $request->montant_facture - $total_paiement;
        
        //MISE A JOUR DE LA TABLE FACTURE
        if($rest == 0)
        {
            $affected = DB::table('factures')
            ->where('id', $request->id_facture)
            ->update([ 'reglee' => 1, ]); //LA FACTURE DEVIENT REGLEE DEFINITIVEMENT
        }

        //MISE A JOUR DE LA TABLE CONTRAT EN MODIFIANT LE reste_a_payer DE LA TABLE
        //Récuper toutes les factures réglée

        $get_montant_facture = DB::table('factures')
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')    
            ->where('factures.reglee', 1)
            ->where('prestations.id_contrat', $request->id_contrat)
            ->get(['factures.*']);

            //dd($get_montant_facture);
        foreach($get_montant_facture as  $get_montant_facture)
        {
            //echo $total_montant_facture."---";
            $total_montant_facture = $total_montant_facture + $get_montant_facture->montant_facture;
        }
        
        //Prendre le montant du contrat en question
        $le_contrat = Contrat::where('id', $request->id_contrat)->get();

        foreach($le_contrat as $le_contrat)
        {
            //dd( $total_montant_facture);
            $rest_a_payer =  $le_contrat->montant -  $total_montant_facture ;
            
            $affected = DB::table('contrats')
            ->where('id', $le_contrat->id)
            ->update(['reste_a_payer' => $rest_a_payer, ]);

            //Vérifier que le reste est zéro et mettre a jour pour dire que le contrat est soldé ou clos

            if($rest_a_payer == 0)
            {
                $affected = DB::table('contrats')
                ->where('id', $le_contrat->id)
                ->update(['statut_solde' => 1, ]);
            }
        }

        return redirect('facture')->with('success', 'Paiement enregistré');

    }

    public function EditPaiement(Request $request)
    {
        $ch = strval($request->paiement);
        if(strlen($ch) > 13)
        {
            //rediriger pour lui dire que c'est trop long
            return redirect('prestation')->with('error', 'données montant saisies trop long');
        }
        
        //FAIRE LES MISES A JOURS DES TABLES CONCERNEES
        $total_paiement = 0;

        $total_montant_facture = 0;

        $Insert =DB::table('paiements')
        ->where('id', $request->id_paiement)
        ->update([
            'paiement' => $request->paiement, 
            'id_facture' => $request->id_facture, 
            'date_paiement' => $request->date_paiement, 
            'created_by' => auth()->user()->id, 
        ]);

        //Récuper tous les anciens montants
        $get_montants = DB::table('paiements')
        ->where('paiements.id_facture', $request->id_facture)
        ->join('factures', 'paiements.id_facture', '=', 'factures.id')
        ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
        ->join('services', 'prestations.id_service', '=', 'services.id')
        ->get(['paiements.paiement']);

        foreach($get_montants as $get_montants)
        {
            $total_paiement = $total_paiement + $get_montants->paiement;
        }

        //Calcul du nouveau reste 
        $rest = $request->montant_facture - $total_paiement;
        
        //MISE A JOUR DE LA TABLE FACTURE
        if($rest == 0)
        {
            $affected = DB::table('factures')
            ->where('id', $request->id_facture)
            ->update([ 'reglee' => 1, ]); //LA FACTURE DEVIENT REGLEE DEFINITIVEMENT
        }

        //MISE A JOUR DE LA TABLE CONTRAT EN MODIFIANT LE reste_a_payer DE LA TABLE
        //Récuper toutes les factures réglée

        $get_montant_facture = DB::table('factures')
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')    
            ->where('factures.reglee', 1)
            ->where('prestations.id_contrat', $request->id_contrat)
            ->get(['factures.*']);

        foreach($get_montant_facture as  $get_montant_facture)
        {
            $total_montant_facture = $total_montant_facture + $get_montant_facture->montant_facture;
        }

        //Prendre le montant du contrat en question
        $le_contrat = Contrat::where('id', $request->id_contrat)->get();

        foreach($le_contrat as $le_contrat)
        {
            //dd( $total_montant_facture);
            $rest_a_payer =  $le_contrat->montant -  $total_montant_facture ;
            
            $affected = DB::table('contrats')
            ->where('id', $le_contrat->id)
            ->update(['reste_a_payer' => $rest_a_payer, ]);

            //Vérifier que le reste est zéro et mettre a jour pour dire que le contrat est soldé ou clos

            if($rest_a_payer == 0)
            {
                $affected = DB::table('contrats')
                ->where('id', $le_contrat->id)
                ->update(['statut_solde' => 1, ]);
            }
        }

        return redirect('facture')->with('success', 'Paiement modifié');

    } 

    public function GetPaimentByIdFacture($id)
    {
        $get = DB::table('paiements')
            ->join('factures', 'paiements.id_facture', '=', 'factures.id')
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('services', 'prestations.id_service', '=', 'services.id')
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')       
            ->where('factures.id', $id)
           
            ->get(['paiements.*', 'factures.numero_facture', 'prestations.*', 'contrats.titre_contrat', 'contrats.date_solde',
            'contrats.montant', 'contrats.reste_a_payer',  'services.libele_service', 'services.description',
             'typeprestations.libele',  'entreprises.nom_entreprise']);

        return $get;
    }

    public function EditPaiementForm(Request $request)
    {
        return view('admin/edit_paiements_form',
            [
                'id_edit' => $request->id_paiement,
            ]
        );
    }

    public function PaiementByFacture(Request $request)
    {
        return view('admin/paiements_by_facture',
            [
                'id' => $request->id_facture,
            ]
        );
    }

    public function GetById($id)
    {
        $get = DB::table('paiements')
            ->join('factures', 'paiements.id_facture', '=', 'factures.id')
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id') 
            ->join('services', 'prestations.id_service', '=', 'services.id')
            ->where('paiements.id', $id)
           
            ->get(['paiements.*', 'contrats.montant', 'prestations.id_contrat', 
            'prestations.date_prestation', 'factures.numero_facture', 'factures.id_prestation',
             'services.libele_service', 'typeprestations.libele', 'contrats.reste_a_payer', 'contrats.date_solde']);

        return $get;
    }
}
