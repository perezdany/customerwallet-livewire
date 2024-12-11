<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB; 

use App\Models\Facture;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

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

    public function FactureByCustomer(Request $request)
    {
        return view('admin/factures',
        [
            'id_entreprise' => $request->id_entreprise,
        ]
        );
    }

    public function GetById($id)
    {
        $get = DB::table('factures')
            
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')       
            ->where('factures.id', $id)
            ->get(['factures.*', 'prestations.localisation', 'prestations.date_prestation', 'prestations.id_contrat',
            'contrats.titre_contrat', 'contrats.date_solde', 
            'contrats.montant', 'contrats.reste_a_payer',  
             'typeprestations.libele',  'entreprises.nom_entreprise']);
       
        return $get;
    }

    public function GetByIdEntreprise($id)
    {
        $get = DB::table('factures')
            
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')       
            ->where('entreprises.id', $id)
            ->get(['factures.*', 'prestations.localisation', 'prestations.date_prestation', 'prestations.id_contrat',
            'contrats.titre_contrat', 'contrats.date_solde', 
            'contrats.montant', 'contrats.reste_a_payer',  
             'typeprestations.libele',  'entreprises.nom_entreprise']);
       
        return $get;
        //dd($get);
    }

    public function GetAll()
    {
        $get = DB::table('factures')
            
            ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
            
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')   
            ->get(['factures.*', 'prestations.localisation', 'prestations.date_prestation', 'contrats.titre_contrat', 'contrats.date_solde',
            'contrats.montant', 'contrats.reste_a_payer',  
             'typeprestations.libele',  'entreprises.nom_entreprise']);

        return $get;
    }

    public function AddFacture(Request $request)
    {
        //dd('ici');
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

        //ENREGISTRER LE FICHIER DE LA FACTURE

        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;
        // dd($fichier->getClientOriginalName());
        //dd($fichier);
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
            
            //dd($extension);
            if($extension != "pdf")
            {
                return back()->with('error', 'Facture enregistrée, mais LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
            }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Facture::where('id', $Insert->id)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->file_path == null)
                {
                    //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures', $file_name
                    );

                    $affected = DB::table('factures')
                    ->where('id', $Insert->id)
                    ->update([
                        'file_path'=> $path,
                        
                    ]);

                    
                }
                else
                {
                    $get_path = Facture::where('id', $Insert->id)->get();
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    foreach($get_path as $get_path)
                    {
                        Storage::delete($get_path->file_path);
                    }
                   


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures', $file_name
                    );

                    $affected = DB::table('factures')
                    ->where('id', $Insert->id)
                    ->update([
                       'file_path'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
        
        }


       return redirect('facture')->with('success', 'Facture enregistrée');

    }

    public function UploadFileFacutre(Request $request)
    {
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;
        //dd($fichier);

        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
            

            if($extension != "pdf")
            {
                return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
            }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Facture::where('id', $request->id_facture)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->file_path == null)
                {
                    //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures', $file_name
                    );

                    $affected = DB::table('factures')
                    ->where('id',  $request->id_facture)
                    ->update([
                        'file_path'=> $path,
                        
                    ]);

                    
                }
                else
                {
                    $get_path = Facture::where('id',  $request->id_facture)->get();
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    foreach($get_path as $get_path)
                    {
                        Storage::delete($get_path->file_path);
                    }
                   


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures', $file_name
                    );

                    $affected = DB::table('factures')
                    ->where('id', $Insert->id)
                    ->update([
                       'file_path'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
            return redirect('facture')->with('error', 'Vous devez choisir un fichier');
        }

        return redirect('facture')->with('success', 'Fichier enregistré');
    }

    public function ViewFile(Request $request)
    {
        //dd($request->file);
        if(Storage::disk('local')->exists($request->file))
        {
            return response()->file(Storage::path($request->file));
        }
        else
        {
            return redirect('facture')->with('error', 'Le fichier n\'existe pas');
        }
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
           
            ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')   
            ->where('date_reglement', '<', $today)
            ->where('reglee', 0)
            ->take(3)
            ->get(['factures.*', 'prestations.localisation', 'prestations.date_prestation', 'contrats.titre_contrat', 'contrats.date_solde',
            'contrats.montant', 'contrats.reste_a_payer', 
             'typeprestations.libele',  'entreprises.nom_entreprise']);

        return $get;
    }

    public function GetByIdContrat($id)
    {
        
    }
}
