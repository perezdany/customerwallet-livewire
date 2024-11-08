<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\Interlocuteur;

class InterlocuteurController extends Controller
{
    //Handle Interlocuteur

    public function GetAll()
    {
        $get = DB::table('interlocuteurs')
          ->join('utilisateurs', 'interlocuteurs.created_by', '=', 'utilisateurs.id')
          ->join('entreprises', 'interlocuteurs.id_entreprise', '=', 'entreprises.id')
          ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*', ]);
     
          return $get;
    }

    public function AddInterlocuteurWithClient(Request $request, $client)
    {
        //dd($request->nom);
        $Insert = Interlocuteur::create([
           'titre' => $request->titre,
            'nom' => $request->nom, 
            'tel' => $request->tel,
             'email' => $request->email, 
             'fonction' => $request->fonction, 
             
             'id_entreprise' => $client,
              'created_by' => auth()->user()->id,
       ]);

       //Recuperer l'enregistrement
       $get = Interlocuteur::where('tel', '=', $request->tel)->get();

       return $get;
       //return redirect('welcome')->with('success', 'Enregistrement effectué');
    }

    public function EditInterlocForm(Request $request)
    {
        return view('admin/edit_interlocuteur',
            [
                'id' => $request->id_interlocuteur,
            ]
        );
    }

    public function GetById($id)
    {
        $get = DB::table('interlocuteurs')
        ->join('utilisateurs', 'interlocuteurs.created_by', '=', 'utilisateurs.id')
        ->join('entreprises', 'interlocuteurs.id_entreprise', '=', 'entreprises.id')
        ->where('interlocuteurs.id', $id)
        ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*',]);
   
        return $get;
    }

    public function EditInterlocuteur(Request $request)
    {
        $affected = DB::table('interlocuteurs')
        ->where('id', $request->id_interlocuteur)
        ->update([
            'titre' => $request->titre,
             'nom' => $request->nom, 
             'tel' => $request->tel,
              'email' => $request->email, 
              'fonction' => $request->fonction, 
              
              'id_entreprise' => $request->entreprise,
              
            ]);

        return redirect('interlocuteurs')->with('success', 'Modification effectuée');
    }

    public function DisplayByIdEntreprise(Request $request)
    {
        $interloc = Interlocuteur::where('id_entreprise', $request->id_entreprise)->get();
        
        return view('admin/entreprises', compact('interloc'));
    }

    public function AddInterlocuteur(Request $request)
    {
        if(strval($request->entreprise) == "0")
        {
            return back()->with('error', 'Choisissez l\'entreprise ');
        }
        
        //dd($request->nom);
        $Insert = Interlocuteur::create([
            'titre' => $request->titre,
             'nom' => $request->nom, 
             'tel' => $request->tel,
              'email' => $request->email, 
              'fonction' => $request->fonction, 
              
              'id_entreprise' => $request->entreprise,
               'created_by' => auth()->user()->id,
        ]);
        

        return redirect('interlocuteurs')->with('success', 'Enregistrement effectué');
    }

    public function AddInterlocuteurInFiche(Request $request)
    {
        if(strval($request->entreprise) == "0")
        {
            return back()->with('error', 'Choisissez l\'entreprise ');
        }
        
        //dd($request->nom);
        $Insert = Interlocuteur::create([
            'titre' => $request->titre,
             'nom' => $request->nom, 
             'tel' => $request->tel,
              'email' => $request->email, 
              'fonction' => $request->fonction, 
              
              'id_entreprise' => $request->entreprise,
               'created_by' => auth()->user()->id,
        ]);
        

        return view('dash/prospect_about',
        [
            'id_entreprise' => $request->entreprise,
            'success' => 'Nouvel interlocuteur ajouté'
        ]
    );
    }
    public function AddInterlocuteurInFicheCustomer(Request $request)
    {
        if(strval($request->entreprise) == "0")
        {
            return back()->with('error', 'Choisissez l\'entreprise ');
        }
        
        //dd($request->nom);
        $Insert = Interlocuteur::create([
            'titre' => $request->titre,
             'nom' => $request->nom, 
             'tel' => $request->tel,
              'email' => $request->email, 
              'fonction' => $request->fonction, 
              
              'id_entreprise' => $request->entreprise,
               'created_by' => auth()->user()->id,
        ]);
        

        return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->entreprise,
                'success' => 'Nouvel interlocuteur ajouté'
            ]
        );
    }

    public function InterlocuteurWithIdEntreprise($id)
    {
        $interloc = Interlocuteur::where('id_entreprise', $id)->get();

        return $interloc;
    }
}
