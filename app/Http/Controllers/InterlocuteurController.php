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
          ->join('professions', 'interlocuteurs.fonction', '=', 'professions.id')
          ->orderBy('updated_at', 'desc')
         
          ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*', 'professions.intitule']);
     
          return $get;
    }

    public function TableFilter(Request $request)
    {
        //dd($request->all());
        $id_entreprise = $request->entreprise;
        $fonction = $request->fonction;

        if($id_entreprise == "all")
        {
           
            if($fonction == "all")
            { //dd('ici');
                $intel = DB::table('interlocuteurs')
                ->join('utilisateurs', 'interlocuteurs.created_by', '=', 'utilisateurs.id')
                ->join('entreprises', 'interlocuteurs.id_entreprise', '=', 'entreprises.id')
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*', ]);

                 //ON RETOURNE A LA PAGE
                 return view('admin/interlocuteurs', compact('intel', 'id_entreprise', 'fonction'));
            }
            else
            {
                $intel = DB::table('interlocuteurs')
                ->join('utilisateurs', 'interlocuteurs.created_by', '=', 'utilisateurs.id')
                ->join('entreprises', 'interlocuteurs.id_entreprise', '=', 'entreprises.id')
                ->where('interlocuteurs.fonction', $fonction)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*', ]);
                return view('admin/interlocuteurs', compact('intel', 'id_entreprise', 'fonction'));
            }
        }
        else
        {
            if($fonction == "all")
            {
                $intel = DB::table('interlocuteurs')
                ->join('utilisateurs', 'interlocuteurs.created_by', '=', 'utilisateurs.id')
                ->join('entreprises', 'interlocuteurs.id_entreprise', '=', 'entreprises.id')
                ->where('interlocuteurs.id_entreprise', $id_entreprise)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*', ]);
                return view('admin/interlocuteurs', compact('intel', 'id_entreprise', 'fonction'));
            }
            else
            {
                $intel = DB::table('interlocuteurs')
                ->join('utilisateurs', 'interlocuteurs.created_by', '=', 'utilisateurs.id')
                ->join('entreprises', 'interlocuteurs.id_entreprise', '=', 'entreprises.id')
                ->where('interlocuteurs.id_entreprise', $id_entreprise)
                ->where('interlocuteurs.fonction', $fonction)
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*', ]);
                return view('admin/interlocuteurs', compact('intel', 'id_entreprise', 'fonction'));
            }
        }

       

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

    public function EditInterlocFormF(Request $request)
    {
        return view('admin/edit_interlocuteur_from_fiche',
            [
                'id' => $request->id_interlocuteur,
            ]
        );
    }

    public function EditInterlocFormFicheCustomer(Request $request)
    {
        
        return view('admin/edit_interlocuteur_from_fiche_customer',
            [
                'id' => $request->id_interlocuteur,
            ]
        );
    }

    public function DeleteInterlocuteur(Request $request)
    {
        $delete =  DB::table('interlocuteurs')->where('id', '=', $request->id_interlocuteur)->delete();

        return back()->with('success', 'Elément supprimé');

    }

    public function DeleteInterlocuteurInFiche(Request $request)
    {
        $delete =  DB::table('interlocuteurs')->where('id', '=', $request->id_interlocuteur)->delete();

        //return back()->with('success', 'Elément supprimé');

       return view('dash/prospect_about',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'success' => 'Elément supprimé'
                ]
            );
    }

    public function DeleteInterlocuteurFicheCustomer(Request $request)
    {
        $delete =  DB::table('interlocuteurs')->where('id', '=', $request->id_interlocuteur)->delete();

       return view('dash/fiche_customer',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'success' => 'Elément supprimé'
                ]
                );
    }
    public function GetById($id)
    {
        $get = DB::table('interlocuteurs')
        ->join('utilisateurs', 'interlocuteurs.created_by', '=', 'utilisateurs.id')
        ->join('entreprises', 'interlocuteurs.id_entreprise', '=', 'entreprises.id')
        ->join('professions', 'interlocuteurs.fonction', '=', 'professions.id')
        ->where('interlocuteurs.id', $id)
        ->get(['entreprises.nom_entreprise', 'utilisateurs.nom_prenoms', 'interlocuteurs.*', 'intitule']);
   
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

    public function EditInterlocuteurFiche(Request $request)
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

            return view('dash/prospect_about',
            [
                'id_entreprise' => $request->entreprise,
                'success' => 'Modification effectuée'
            ]);
    }

    public function EditInterlocuteurFicheCustomer(Request $request)
    {
       // dd($request->id_entreprise);
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

            return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->entreprise,
                'success' => 'Modification effectuée'
            ]
        );
    }

    public function CibleDisplayByIdEntreprise(Request $request)
    {
        $interloc = Interlocuteur::where('id_entreprise', $request->id_entreprise)->get();
        
        return view('dash/cibles', compact('interloc'));
    }

    public function DisplayByIdEntreprise(Request $request)
    {
        $interloc = Interlocuteur::where('id_entreprise', $request->id_entreprise)->get();
        
        return view('admin/entreprises', compact('interloc'));
    }

    public function DisplayByIdEntrepriseActif(Request $request)
    {
        $interloc = Interlocuteur::where('id_entreprise', $request->id_entreprise)->get();
        
        return view('dash/list_actifs', compact('interloc'));
    }

    public function DisplayByIdEntrepriseInactif(Request $request)
    {
        $interloc = Interlocuteur::where('id_entreprise', $request->id_entreprise)->get();
        
        return view('dash/list_inactifs', compact('interloc'));
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

    public function AddInterlocuteurCible(Request $request)
    {
        //dd('idz');
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
        

        return redirect('cibles')->with('success', 'Enregistrement effectué');
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
        $interloc = DB::table('interlocuteurs')
        ->join('professions', 'interlocuteurs.fonction', '=', 'professions.id')
        ->where('id_entreprise', $id)->get(['interlocuteurs.*', 'professions.intitule']);

        return $interloc;
    }
}
