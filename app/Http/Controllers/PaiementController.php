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
            return redirect('facture')->with('error', 'données montant saisies trop long');
        }
        
        //FAIRE LES MISES A JOURS DES TABLES CONCERNEES
        $total_paiement = 0;

        $total_montant_facture = 0;

        $Insert = Paiement::create([
            'paiement' => $request->paiement, //c'est le montant
            'id_facture' => $request->id_facture, 
            'date_paiement' => $request->date_paiement, 
            'created_by' => auth()->user()->id, 
        ]);

        //Récuper tous les anciens montants POUR DEFINIR SI LA FACTURE EST REGLEE TOTALEMENT
        $get_montants = DB::table('paiements')
        ->where('paiements.id_facture', $request->id_facture)
        ->join('factures', 'paiements.id_facture', '=', 'factures.id')
        ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
        
        ->get(['paiements.paiement']);

        foreach($get_montants as $get_montants)
        {
            $total_paiement = $total_paiement + $get_montants->paiement;
        }
    
        //Calcul du nouveau reste 
        $rest = $request->montant_facture - $total_paiement;
        //dd($rest);
        //MISE A JOUR DE LA TABLE FACTURE
        if($rest == 0)
        {
            $affected = DB::table('factures')
            ->where('id', $request->id_facture)
            ->update([ 'reglee' => 1, 
            'date_reglement' => date('Y-m-d')]); //LA FACTURE DEVIENT REGLEE DEFINITIVEMENT aprs je mets ce code 'date_reglement' => date('Y-m-d')
        }
        

        //MISE A JOUR DE LA TABLE CONTRAT EN MODIFIANT LE reste_a_payer DE LA TABLE
        //Récuper toutes les factures réglée
        /*
        $get_montant_facture = DB::table('factures')
            
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')    
            ->where('factures.reglee', 1)
            ->where('factures.id_contrat', $request->id_contrat)
            ->get(['factures.*']);

            //dd($get_montant_facture);
        foreach($get_montant_facture as  $get_montant_facture)
        {
            //echo $total_montant_facture."---";
            $total_montant_facture = $total_montant_facture + $get_montant_facture->montant_facture;
        }

        //Vérifier que le reste est zéro et mettre a jour pour dire que le contrat est soldé ou clos
        //Prendre le montant du contrat en question
        $le_contrat = Contrat::where('id', $request->id_contrat)->get();

        foreach($le_contrat as $le_contrat)
        {
            //SI LE CONTRAT EST RECONDUIT, ET QUE LE RESTE A POAYER DANS LA TABLE EST ZERO ON REMET LE MONTANT DU CONTRAT DANS LE CHAMP
            //dd( $total_montant_facture);
            $rest_a_payer =  $le_contrat->montant -  $total_montant_facture ;

            if($le_contrat->reconduction == 1)
            {
                //ALORS ON NE METS PAS LE RESTE A PAYER 0 ON REMET LE MONTANT DU CONTRAT
               
                if($rest_a_payer == 0)
                {
                    $affected = DB::table('contrats')
                    ->where('id', $le_contrat->id)
                    ->update(['reste_a_payer' => $le_contrat->montant, ]);

                }
                else
                {
                    //ON REMET LE RESTE QU'IL FAUT PARCE QUE LE RESTE N'EST PAS NULL
                    $affected = DB::table('contrats')
                    ->where('id', $le_contrat->id)
                    ->update(['reste_a_payer' => $rest_a_payer, ]);
                }
            }
            else
            {
                
                $affected = DB::table('contrats')
                ->where('id', $le_contrat->id)
                ->update(['reste_a_payer' => $rest_a_payer, ]);
                
                if($rest_a_payer == 0)
                {
                    //METTRE A JOUR LA DATE DE SOLDE
                    $affected = DB::table('contrats')
                    ->where('id', $le_contrat->id)
                    ->update(['statut_solde' => 1, 
                        'date_solde' => Date('Y-m-d')
                    ]);
                }
              
            }
        }*/

        return redirect('facture')->with('success', 'Paiement enregistré');

    }

    public function EditPaiement(Request $request)
    {
        //dd($request->all());
        $ch = strval($request->paiement);
        if(strlen($ch) > 13)
        {
            //rediriger pour lui dire que c'est trop long
            return back()->with('error', 'données montant saisies trop long');
        }
        
        //FAIRE LES MISES A JOURS DES TABLES CONCERNEES
        $total_paiement = 0;

        $total_montant_facture = 0;

       $affected = DB::table('paiements')
        ->where('id', $request->id_paiement)
        ->update([
            'paiement' => $request->paiement, 
            
            'date_paiement' => $request->date_paiement, 
            'created_by' => auth()->user()->id, 
        ]);

        //Récuper tous les anciens montants
        $get_montants = DB::table('paiements')
        ->where('paiements.id_facture', $request->id_facture)
        ->join('factures', 'paiements.id_facture', '=', 'factures.id')
       
        ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
        
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
            ->update([ 'reglee' => 1, 
            'date_reglement' => date('Y-m-d')]); //LA FACTURE DEVIENT REGLEE DEFINITIVEMENT
        }

        //MISE A JOUR DE LA TABLE CONTRAT EN MODIFIANT LE reste_a_payer DE LA TABLE
        //Récuper toutes les factures réglée
        /*
        $get_montant_facture = DB::table('factures')
            
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')    
            ->where('factures.reglee', 1)
            ->where('factures.id_contrat', $request->id_contrat)
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
        }*/

        return redirect('facture')->with('success', 'Paiement modifié');

    } 

    public function GetPaimentByIdFacture($id)
    {
        /*
       */
        $get = DB::table('paiements')
            ->where('id_facture', $id)
            ->join('factures', 'paiements.id_facture', '=', 'factures.id')
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
            ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')  
           
            ->get( ['paiements.*', 'factures.numero_facture', 'contrats.titre_contrat', 'contrats.date_solde',
            'contrats.montant', 'contrats.reste_a_payer', 
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
        //dd($request->all());
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
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
            ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id') 
            ->where('paiements.id', $id)
            ->get(['paiements.*', 'contrats.montant', 'factures.id_contrat', 
                'factures.numero_facture', 'contrats.debut_contrat',
              'typeprestations.libele', 'contrats.reste_a_payer', 'contrats.date_solde']);
        //dd($get);
        return $get;
    }

    public function DeletePaiement(Request $request)
    {
        //dd($request->all());
        $delete = DB::table('paiements')->where('id', '=', $request->id_paiement)->delete();
        return view('admin/paiements_by_facture',
            [
                'id' => $request->id, 
                'success' => 'Paiement supprimé',
            ]
        );
        
    }
}
