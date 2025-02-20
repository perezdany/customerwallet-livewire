<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

use App\Models\Proposition;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class PropalController extends Controller
{
    //Documents propositions

    public function GetById($id)
    {
        $get = Proposition::where('id', $id)
        ->get();
        return $get;
    }

    public function GetAll()
    {
        $get = Proposition::all()
        ->get();
        return $get;
    }

    public function GetByIdEntreprise($id)
    {
        $get = Proposition::where('id_prospection', $id)
        ->get();
        return $get;
    }


    public function AddPropal(Request $request)
    {
        //dd('ici');

        $fichier = $request->new_doc;
        //dd($request->new_doc);
        //Le vrai nom
        $file_name = $fichier->getClientOriginalName();
       
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            /*$extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);

            if($extension != "pdf")
            {
                    return view('dash/prospect_about',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'error' => 'FORMAT DE FICHIER INCORRECT'
                    ]
                );
            }*/

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Proposition::where('libele', $file_name)->count();
            if($get_path == 0)
            {
               //dd($file_name);
                $path = $request->file('new_doc')->storeAs(
                    'docs/propal', $file_name
                );

                $Insert = Proposition::create([
        
                    'libele' =>  $file_name,
                    'path_doc' => $path,
                    'id_prospection' => $request->id_prospection,
                    'id_client' => $request->id_entreprise,
                    'rejete' => 0,
                    'id_utilisateur' => auth()->user()->id
                ]);

                return view('dash/prospect_about',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'success' => 'Fichier enregistré'
                    ]
                );

            }
            else//LE FICHIER EXISTE 
            {
                $get_path = Proposition::where('libele', $file_name)->count();
                //SUPPRESSION DE L'ANCIEN FICHIER
                //dd($get_path->path);
                foreach($get_path as $get_path)
                {
                    Storage::delete($get_path->path_doc);
                }
                                    
                $path = $request->file('new_doc')->storeAs(
                    'docs', $file_name
                );


                $get_doc = Doc::where('libele', $file_name)->get();
                foreach($get_doc as $get_doc)
                {
                    $affected = DB::table('docs')
                    ->where('id', $get_doc->id)
                    ->update([
                        'path_doc'=> $path,
                        
                    ]);
                }
                

                return view('dash/prospect_about',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'success' => 'Fichier enregistré'
                    ]
                );
            }
           
        }
        else
        {
            return back()->with('error', 'Vous devez choisir un fichier');
        }
    }

    public function AddPropalCustomer(Request $request)
    {
        //dd('ici');

        $fichier = $request->new_doc;
        //dd($request->new_doc);
        //Le vrai nom
        $file_name = $fichier->getClientOriginalName();
       
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);

           /* if($extension != "pdf")
            {
                    return view('dash/fiche_customer',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'error' => 'FORMAT DE FICHIER INCORRECT'
                    ]
                );
            }*/

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Proposition::where('libele', $file_name)->count();
            if($get_path == 0)
            {
               //dd($file_name);
                $path = $request->file('new_doc')->storeAs(
                    'docs/propal', $file_name
                );

                $Insert = Proposition::create([
        
                    'libele' =>  $file_name,
                    'path_doc' => $path,
                    'id_prospection' => $request->id_prospection,
                    'id_client' => $request->id_entreprise,
                    'rejete' => 0,
                    'id_utilisateur' => auth()->user()->id
                ]);

                return view('dash/fiche_customer',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'success' => 'Fichier enregistré'
                    ]
                );

            }
            else//LE FICHIER EXISTE 
            {
                $get_path = Proposition::where('libele', $file_name)->count();
                //SUPPRESSION DE L'ANCIEN FICHIER
                //dd($get_path->path);
                foreach($get_path as $get_path)
                {
                    Storage::delete($get_path->path_doc);
                }
                                    
                $path = $request->file('new_doc')->storeAs(
                    'docs', $file_name
                );


                $get_doc = Doc::where('libele', $file_name)->get();
                foreach($get_doc as $get_doc)
                {
                    $affected = DB::table('docs')
                    ->where('id', $get_doc->id)
                    ->update([
                        'path_doc'=> $path,
                        
                    ]);
                }
                

                return view('dash/fiche_customer',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'success' => 'Fichier enregistré'
                    ]
                );
            }
           
        }
        else
        {
            return back()->with('error', 'Vous devez choisir un fichier');
        }
    }

    public function RefreshPropal(Request $request)
    {
        //dd($request->all());
        $affected = DB::table('propositions')->where('id', $request->id_propal)
        ->update(['rejete' => $request->rejete, 'motif' => $request->motif]);

        return view('dash/prospect_about',
        [
            'id_entreprise' => $request->id_entreprise,
            'success' => 'La proposition a été actualisé'
        ]
    );
    }

    public function ViewDocPropal(Request $request)
    {
        if(Storage::disk('local')->exists($request->file))
        {
            //return Storage::download($request->file);
            //return response()->file($request->file);
            return response()->file(Storage::path($request->file));
        }
        else
        {
            return view('dash/prospect_about',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'error' => 'Fichier introuvable'
                ]
            );
        }
    }

    public function ViewDocPropalCustomer(Request $request)
    {
        if(Storage::disk('local')->exists($request->file))
        {
            //return Storage::download($request->file);
            //return response()->file($request->file);
            return response()->file(Storage::path($request->file));
        }
        else
        {
            return view('dash/fiche_customer',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'error' => 'Fichier introuvable'
                ]
            );
        }
    }

    public function DeleteDocPropalCustomer(Request $request)
    {
       
        //SUPPRIMER LE FICHIER DANS LE DOSSIER
        Storage::delete($request->file);

        $deleted = DB::table('propositions')->where('id', '=', $request->id_doc)->delete();

        return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Elément supprimé'
            ]
        );
    }

    public function DeleteDocPropal(Request $request)
    {
       
        //SUPPRIMER LE FICHIER DANS LE DOSSIER
        Storage::delete($request->file);

        $deleted = DB::table('propositions')->where('id', '=', $request->id_doc)->delete();

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Elément supprimé'
            ]
        );
    }
}
