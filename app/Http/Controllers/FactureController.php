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
        ->join('contrats', 'factures.id_contrats', '=', 'contrats.id')
        ->where('contrats.id', $id)
        ->get(['factures.*', 'contrats.titre_contrat']);

        return $get;

    }


    public function FactureByPrestation(Request $request)
    {
        return view('admin/contrats',
        [
            'id_contrat' => $request->id_contrat,
        ]
        );
    }

    public function FactureByCustomer(Request $request)
    {
        $get = DB::table('factures')
            
        ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
     
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')       
        ->where('entreprises.id', $request->id_entreprise)
        ->get(['factures.*',  'contrats.titre_contrat', 'contrats.date_solde', 
        'contrats.montant', 'contrats.reste_a_payer',  'entreprises.nom_entreprise']);
   
    //dd($get);
       // @livewire('entreprises', ['id_entreprise' => $get,]);
        
        return view('admin/factures',
        [
            'id_entreprise' => $get,
        ]
        );
    }

    public function GetById($id)
    {
        $get = DB::table('factures')
            
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
         
            
            ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
            
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')       
            ->where('factures.id', $id)
            ->get(['factures.*',
            'contrats.titre_contrat', 'contrats.date_solde', 
            'contrats.montant', 'contrats.reste_a_payer',  'contrats.debut_contrat' ,
             'typeprestations.libele',  'entreprises.nom_entreprise']);
       
        return $get;
    }

    public function GetByIdEntreprise($id)
    {
        $get = DB::table('factures')
            
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
            
            ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
            
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')       
            ->where('entreprises.id', $id)
            ->get(['factures.*',
            'contrats.titre_contrat', 'contrats.date_solde', 
            'contrats.montant', 'contrats.reste_a_payer',  
             'typeprestations.libele',  'entreprises.nom_entreprise']);
       
        return $get;
        //dd($get);
    }

    public function GetAll()
    {
        //dd('ici');
        $get = DB::table('factures')
            
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
            
            ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
      
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
            
            ->get(['factures.*', 
            'contrats.titre_contrat', 'contrats.date_solde', 
            'contrats.montant', 'contrats.reste_a_payer',  
             'typeprestations.libele',  'entreprises.nom_entreprise']);
        //dd($get);
        return $get;
    }

    public function TableFilter(Request $request)
    {
        //dd($request->all());
        $id_entreprise = $request->entreprise;
        $etat = $request->etat;
        if($request->entreprise == "all")//TOUT LE MONDE
        {
            if($request->etat == "c")
            {
                $factures = DB::table('factures')
                ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                
                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                ->orderBy('factures.date_emission', 'desc')
                ->get(['factures.*', 
                'contrats.titre_contrat', 'contrats.date_solde', 
                'contrats.montant', 'contrats.reste_a_payer',  
                'typeprestations.libele',  'entreprises.nom_entreprise']);
                
                return view('admin/factures', compact('factures', 'id_entreprise', 'etat'));
            }
            else
            {
                //dd('ii/');
                $factures = DB::table('factures')
                ->where('factures.reglee', $etat)
                ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
               
                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                ->orderBy('factures.date_emission', 'desc')
                ->get(['factures.*', 
                'contrats.titre_contrat', 'contrats.date_solde', 
                'contrats.montant', 'contrats.reste_a_payer',  
                'typeprestations.libele',  'entreprises.nom_entreprise']);
               
                return view('admin/factures', compact('factures', 'id_entreprise', 'etat'));
            }

        }
        else
        {
            if($request->etat == "c")
            {
                $factures = DB::table('factures')
                ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
               
                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                ->where('contrats.id_entreprise', $id_entreprise)
                ->orderBy('factures.date_emission', 'desc')
                ->get(['factures.*',
                'contrats.titre_contrat', 'contrats.date_solde', 
                'contrats.montant', 'contrats.reste_a_payer',  
                'typeprestations.libele',  'entreprises.nom_entreprise']);
               
                return view('admin/factures', compact('factures', 'id_entreprise', 'etat'));
            }
            else
            {
                $factures = DB::table('factures')
                ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
              
                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                ->where('factures.reglee', $etat)
                ->where('contrats.id_entreprise', $id_entreprise)
                ->orderBy('factures.date_emission', 'desc')
                ->get(['factures.*', 
                'contrats.titre_contrat', 'contrats.date_solde', 
                'contrats.montant', 'contrats.reste_a_payer',  
                'typeprestations.libele',  'entreprises.nom_entreprise']);
                
                return view('admin/factures', compact('factures', 'id_entreprise', 'etat'));
            }
        }
    }

    public function AddFacture(Request $request)
    {
        //dd($request->all());
        if($request->id_contrat == 0)
        {
            return back()->with('error', 'Choisissez impérativement la prestation');
        }

        //LA DATE DE REGLEMENT EST PAR DEFAUT TROIS JOURS APRES
        $timestamp = strtotime($request->date_emission);

        //POUR DES RAISONS TEMPORAIRES POUR LE REMPLISSAGE JE COMMENTE LA LIGNE CI DESSOUS
        //$date_reglement = date('Y-m-d', strtotime('+3 days',  $timestamp));
        
        //dd($date_reglement);
        $Insert = Facture::create([
            'numero_facture' => $request->numero_facture, 
            'date_reglement' => $request->date_reglement,
             'date_emission' => $request->date_emission, 
             'montant_facture' => $request->montant_facture, 
             'id_contrat' => $request->id_contrat,
              'reglee' => 0,
                'annulee' => 0,
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

    public function DeleteFacture(Request $request)
    {
        //dd($request->all());
        //VERIFIER SI IL EST LIE A UN CONTRAT

         //ON REFAIT LE FILTRE QUI Y ETAIT AVANT DE SE REVENIR SUR LA PAGE
         //dd($request->all());
         $id_entreprise = $request->id_entreprise;
         $etat = $request->etat;
         //dd($id_entreprise);
         if($request->id_entreprise == "all")//TOUT LE MONDE
         {

             if($request->etat == "c")
             {
                 $factures = DB::table('factures')
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
               
             }
             else
             {
                
                 $factures = DB::table('factures')
                 ->where('factures.reglee', $etat)
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
                
             }
 
         }
         else
         {
             if($request->etat == "c")
             {
                 $factures = DB::table('factures')
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 ->join('contrats', 'contrats.id_contrat', '=', 'contrats.id')
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->where('contrats.id_entreprise', $id_entreprise)
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 'contrats.localisation', 'contrats.date_prestation', 'contrats.id_contrat',
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
                 
             }
             else
             {
                 $factures = DB::table('factures')
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 ->join('contrats', 'contrats.id_contrat', '=', 'contrats.id')
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->where('factures.reglee', $etat)
                 ->where('contrats.id_entreprise', $id_entreprise)
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 'contrats.localisation', 'contrats.date_prestation', 'contrats.id_contrat',
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
                 
             }
         }

        $message_success = 'Facture supprimée';

        $delete = DB::table('factures')->where('id', '=', $request->id_facture)->delete();
         return view('admin/factures', compact('id_entreprise', 'factures', 'etat', 'message_success'));
        //return redirect('facture')->with('id_entreprise', $id_entreprise)->with('factures', $factures)->with('etat', $etat)
        //->with('message_success', $message_success);
       

        //return redirect('facture')->with('success', 'Facture supprimée');
    }

    public function UploadFileFacutre(Request $request)
    {
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;
        //dd($request->file);

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
                    ->where('id', $request->id_facture)
                    ->update([
                       'file_path'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
            return back()->with('error', 'Vous devez choisir un fichier');
        }

        return back()->with('success', 'Fichier enregistré');
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
        //dd($request->all());
        return view('forms/add_facture',
            [
                'id_edit' => $request->id_facture,
                'etat' => $request->etat,
                'id_entreprise' => $request->entreprise,
            ]
        );
    }

    public function EditFacture(Request $request)
    {
        
        //dd($request->all());
        
        $affected = DB::table('factures')
        ->where('id', $request->id_facture)
        ->update([ 'numero_facture' => $request->numero_facture, 
            
            'date_emission' => $request->date_emission, 
            'montant_facture' => $request->montant_facture, 
            'id_contrat' => $request->id_contrat,]);

             //ENREGISTRER LE FICHIER DE LA FACTURE
         //dd( $affected);
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;

       // dd($fichier);
        // dd($fichier->getClientOriginalName());
        //dd($fichier);
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
            
            //dd($extension);
            if($extension != "pdf")
            {
                $message_error = 'Facture enregistrée, mais LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!';
                return view('admin/factures', compact('id_entreprise', 'factures', 'etat', 'message_error'));
               
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
                    ->where('id', $request->id_facture)
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

        //ON REFAIT LE FILTRE QUI Y ETAIT AVANT DE SE REVENIR SUR LA PAGE
         //dd($request->all());
         $id_entreprise = $request->id_entreprise;
         $etat = $request->etat;
         //dd($id_entreprise);
         if($request->id_entreprise == "all")//TOUT LE MONDE
         {

             if($request->etat == "c")
             {
                 $factures = DB::table('factures')
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 ->join('contrats', 'contrats.id_contrat', '=', 'contrats.id')
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 'contrats.localisation', 'contrats.date_prestation', 'contrats.id_contrat',
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
               
             }
             else
             {
                
                 $factures = DB::table('factures')
                 ->where('factures.reglee', $etat)
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 ->join('contrats', 'contrats.id_contrat', '=', 'contrats.id')
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 'contrats.localisation', 'contrats.date_prestation', 'contrats.id_contrat',
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
                
             }
 
         }
         else
         {
             if($request->etat == "c")
             {
                 $factures = DB::table('factures')
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 ->join('contrats', 'contrats.id_contrat', '=', 'contrats.id')
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->where('contrats.id_entreprise', $id_entreprise)
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 'contrats.localisation', 'contrats.date_prestation', 'contrats.id_contrat',
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
                 
             }
             else
             {
                 $factures = DB::table('factures')
                 ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
                 ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
                 ->join('contrats', 'contrats.id_contrat', '=', 'contrats.id')
                 ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id') 
                 ->where('factures.reglee', $etat)
                 ->where('contrats.id_entreprise', $id_entreprise)
                 ->orderBy('factures.date_emission', 'desc')
                 ->get(['factures.*', 'contrats.localisation', 'contrats.date_prestation', 'contrats.id_contrat',
                 'contrats.titre_contrat', 'contrats.date_solde', 
                 'contrats.montant', 'contrats.reste_a_payer',  
                 'typeprestations.libele',  'entreprises.nom_entreprise']);
 
                 
             }
         }

        $message_success = 'Facture modifiée';
        return view('admin/factures', compact('id_entreprise', 'factures', 'etat', 'message_success'));
        //return redirect('facture')->with('id_entreprise', $id_entreprise)->with('factures', $factures)->with('etat', $etat)
        //->with('message_success', $message_success);
   
        
    }

    //FACTURE DATE DEPASSEE
    public function FactureDateDepassee()
    {
        $today = date('Y-m-d');
        
        $get = DB::table('factures')
            
            ->join('contrats', 'factures.id_contrat', '=', 'contrats.id')
           
            ->join('typeprestations', 'contrats.id_type_prestation', '=', 'typeprestations.id')
           
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')   
            ->where('date_reglement', '<', $today)
            ->where('reglee', 0)
            ->take(3)
            ->get(['factures.*', 'contrats.titre_contrat', 'contrats.date_solde',
            'contrats.montant', 'contrats.reste_a_payer', 
             'typeprestations.libele',  'entreprises.nom_entreprise']);

        //dd($get);

        return $get;
    }

    public function GetByIdContrat($id)
    {
        
    }
}
