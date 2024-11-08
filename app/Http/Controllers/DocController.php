<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Doc;

use DB;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DocController extends Controller
{
    //Handle all documentaions


    public function RetriveGuide(Request $request)
    {
        if(Storage::disk('local')->exists($request->file))
        {
            //return Storage::download($request->file);

            return response()->file(Storage::path($request->file));
        }
        else
        {
            return redirect('welcome')->with('error', 'Le fichier n\'existe pas');
        }
    }

    public function GetFacture($id_facture)
    {
        $get = Doc::where('id_facture', $id_facture)->get();

        return $get;
    }

    public function GetDocByProspection($id)
    {
        $get = Doc::where('id_prospection', $id)->get();

        return $get;
    }

    //POUR OBTENIR LE FORMAT DU FICHIER: pathinfo($file, PATHINFO_EXTENSION);

    public function AddDocProspection(Request $request)
    {
        
        $fichier = $request->new_doc;
        //dd($request->new_doc);
        //Le vrai nom
        $file_name = $fichier->getClientOriginalName();
       
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getFilename(), PATHINFO_EXTENSION);

            if($extension != "pdf")
            {
                    return view('dash/prospect_about',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'error' => 'FORMAT DE FICHIER INCORRECT'
                    ]
                );
            }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Doc::where('libele', $file_name)->count();
            if($get_path == 0)
            {
               //dd($file_name);
                $path = $request->file('new_doc')->storeAs(
                    'docs', $file_name
                );

                $Insert = Doc::create([
        
                    'libele' =>  $file_name,
                    'path_doc' => $path,
                    'id_prospection' => $request->id_prospection,
                    
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
                $get_path = Doc::where('libele', $file_name)->count();
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

    public function ViewDoc(Request $request)
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

    public function DeleteDoc(Request $request)
    {
       
       
        //SUPPRIMER LE FICHIER DANS LE DOSSIER
        Storage::delete($request->file);

        $deleted = DB::table('docs')->where('id', '=', $request->id_doc)->delete();

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Elément supprimé'
            ]
        );
        }
}
