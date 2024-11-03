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
        $Insert = Suivi::create([
            'titre' =>$request->titre_suivi, 
            'activite' => $request->activite, 
            'id_prospection' => $request->prospection, 
            'created_by' => auth()->user()->id
              
       ]);

       return redirect('suivi')->with('succes', 'Enregistrement effecuté');
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

    public function EditSuivi(Request $request)
    {
        //dd($request->id_suivi);
        $affected =  DB::table('suivis')
        ->where('suivis.id', $request->id_suivi)
        ->update([
            'titre' =>$request->titre_suivi, 
            'activite' => $request->activite, 
            'id_prospection' => $request->prospection, 
            
        ]);
        
        return redirect('suivi')->with('success', 'Modificaiton effectuée');
        
    }

    public function GosuiviPage(Request $request)
    {
        
        return view('admin/display_suivis',
            [
                'id' => $request->id_prospection,
            ]
        );
    }

    public function GetSuiviByIdProspection($id)
    {
        $get =  DB::table('suivis')
        ->where('suivis.id_prospection', $id)
        ->join('prospections', 'suivis.id_prospection', '=', 'prospections.id')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
    
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->get(['suivis.*', 'prospections.date_prospection', 'interlocuteurs.nom', 'entreprises.nom_entreprise', ]);

        return $get;
    }
}
