<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Suivi;

use DB;

class SuiviController extends Controller
{
    //Handle suivis

    public function AddSuivi(Request $request)
    {
        //dd($request->all());
        $Insert = Suivi::create([
            'date_activite' => $request->date_activite, 
            'heure_action' => $request->heure_action, 
            'action' => $request->action, 
            'name_interl' => $request->name_interl,
            'tel_interl' => $request->tel_interl, 
            'comment' => $request->comment, 
            'id_utilisateur' => auth()->user()->id, 
            'id_entreprise' => $request->entreprise,
              
       ]);

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->entreprise,
                'message_success' => 'Enregistrement effectué avec succès'
            ]
        );
    }

    public function AddSuiviForCustomer(Request $request)
    {
        $Insert = Suivi::create([
            'date_activite' => $request->date_activite, 
            'heure_action' => $request->heure_action, 
            'action' => $request->action, 
            'name_interl' => $request->name_interl,
            'tel_interl' => $request->tel_interl, 
            'comment' => $request->comment, 
            'id_utilisateur' => auth()->user()->id, 
            'id_entreprise' => $request->entreprise,
              
       ]);

        return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->entreprise,
                'message_success' => 'Enregistrement effectué avec succès'
            ]
        );
    }

    public function EditSuiviForCustomer(Request $request)
    {
        //dd($request->all());
        $affected =  DB::table('suivis')
        ->where('id', $request->id_suivi)
        ->update([
            'date_activite' => $request->date_activite, 
            'heure_action' => $request->heure_action, 
            'action' => $request->action, 
            'name_interl' => $request->name_interl,
            'tel_interl' => $request->tel_interl, 
            'comment' => $request->comment,   
            ]);

        return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->id_entreprise,
                'message_success' => 'Modification effectuée avec succès'
            ]
        );
    }

    public function EditSuivi(Request $request)
    {
        //dd($request->all());
        $affected =  DB::table('suivis')
        ->where('id', $request->id_suivi)
        ->update([
            'date_activite' => $request->date_activite, 
            'heure_action' => $request->heure_action, 
            'action' => $request->action, 
            'name_interl' => $request->name_interl,
            'tel_interl' => $request->tel_interl, 
            'comment' => $request->comment,   
            ]);

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->id_entreprise,
                'message_success' => 'Modification effectuée avec succès'
            ]
        );
        
    }

    public function MyOwn()
    {
       $get =  DB::table('suivis')
        ->where('suivis.created_by', auth()->user()->id)
        ->join('prospections', 'suivis.id_prospection', '=', 'prospections.id')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
      
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->get(['suivis.*', 'prospections.date_prospection', 'interlocuteurs.nom', 'entreprises.nom_entreprise', ]);

        return $get;
    }

    public function GetAll()
    {
        $get =  DB::table('suivis')
        ->join('prospections', 'suivis.id_prospection', '=', 'prospections.id')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
      
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->get(['suivis.*', 'prospections.date_prospection', 'interlocuteurs.nom', 'entreprises.nom_entreprise', ]);

        return $get;
    }

    public function EditSuiviForm(Request $request)
    {
        return view('admin/edit_suivi',
            [
                'id' => $request->id_suivi,
            ]
        );
    }

    public function GetById($id)
    {
        $get =  DB::table('suivis')
        ->where('suivis.id', $id)
        ->join('prospections', 'suivis.id_prospection', '=', 'prospections.id')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
     
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->get(['suivis.*', 'prospections.date_prospection', 'interlocuteurs.nom', 'entreprises.nom_entreprise', ]);

        return $get;
    }

   

    public function GosuiviPage(Request $request)
    {
        
        return view('admin/display_suivis',
            [
                'id' => $request->id_prospection,
            ]
        );
    }

    public function GetSuiviByIdEntreprise($id)
    {
        $get =  DB::table('suivis')
        ->where('suivis.id_entreprise', $id)
        ->join('entreprises', 'suivis.id_entreprise', '=', 'entreprises.id')
    
        ->get(['suivis.*',  'entreprises.nom_entreprise', ]);

        return $get;
    }

    public function DeleteSuivi(Request $request)
    {
        //dd('df');
        $deleted = DB::table('suivis')->where('id', '=', $request->id_suivi)->delete();

        return view('dash/prospect_about',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'success' => 'Elément supprimé'
                ]
            );
    }

    public function DeleteSuiviCustomer(Request $request)
    {
        //dd('l');
        $deleted = DB::table('suivis')->where('id', '=', $request->id_suivi)->delete();

        return view('dash/fiche_customer',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'success' => 'Elément supprimé'
                ]
            );
    }
}
