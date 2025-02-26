<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prospection;
use App\Models\Docfacture;
use App\Models\Compterendu;
use App\Models\Prospection_service;
use App\Models\Profession;
use App\Models\Interlocuteur;

use App\Http\Controllers\Calculator;

use App\Http\Controllers\InterlocuteurController;

use App\Http\Controllers\EntrepriseController;

use DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProspectionController extends Controller
{
    //Handle prospection

    public function GetAll()
    {
        $get = DB::table('prospections')
       
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
        //->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->join('utilisateurs', 'prospections.id_utilisateur', '=', 'utilisateurs.id')
        ->get(['prospections.*', 'entreprises.nom_entreprise', 'utilisateurs.nom_prenoms']);

        return $get;

    }

    public function AddProspection(Request $request)
    {
        //dd($request->all());
        
        if(strval($request->entreprise) == "0")
        {
            
            
            return back()->with('error', 'Choisissez l\'entreprise ');
        }
        else
        {
            //dd("las");
            $calculator = new Calculator();
    
            //Calcul de la date de fin de prospection
            $date_fin = $calculator->FinProspection(31, $request->date_prospect);
    
            
            //VOIR SI L'INTERLOCUTEUR EXISTE OU PAS EN VUE DE LE CREER
    
            if($request->entreprise == "autre")//pas entreprise c'est une nouvelle entreprise
            {
                //dd($request->entreprise);
                $add_client  = (new EntrepriseController())->AddEntreprise($request);
    
                foreach($add_client as $client)
                {
                    if($request->interlocuteur == "autre")//L'interlocuteur n'existe pas 
                    {
                        if($request->fonction == "autre")
                        {
                            $p = Profession::create(['intitule' => $request->new_fonction]);
                            //AJOUTER MAINTENANT L'INTERLOCUTEUR
                            $interl = Interlocuteur::create([
                                'titre' => $request->titre,
                                 'nom' => $request->nom, 
                                 'tel' => $request->tel,
                                  'email' => $request->email, 
                                  'fonction' => $p->id, 
                                  'id_entreprise' => $client->id,
                                   'created_by' => auth()->user()->id,
                            ]);

                            $insert = Prospection::create([
                                    
                                'date_prospection' => $request->date_prospect,
                                'date_fin' => $date_fin, 
                                'duree_jours' => 31,
                                'id_entreprise' => $client->id, 
                                'interlocuteur' => $interl->id,
                                'id_utilisateur' => auth()->user()->id,
                                
                            ]);
                        }
                        else
                        {
                            $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $client->id);
                            foreach($add as $interlocuteur)
                            {
                                $insert = Prospection::create([
                                    
                                    'date_prospection' => $request->date_prospect,
                                    'date_fin' => $date_fin, 
                                    'duree_jours' => 31,
                                    'id_entreprise' => $client->id, 
                                    'interlocuteur' => $interlocuteur->id,
                                    'id_utilisateur' => auth()->user()->id,
                                    
                                ]);
                            }
                        }
                    }
                    else //interlocuteur pas nouveau
                    {
                        $insert = Prospection::create([
                            
                            'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => 31,
                            'id_entreprise' => $client->id,  
                            'interlocuteur' => $request->interlocuteur,
                            'id_utilisateur' => auth()->user()->id,
                                
                        ]);

                            
                    }
                    
                }
                
            }
            else//entreprise pas nouvelle
            {
                
                if($request->interlocuteur == "autre")//L'interlocuteur n'existe pas 
                {
                    if($request->fonction == "autre")
                    {
                        $p = Profession::create(['intitule' => $request->new_fonction]);
                        //AJOUTER MAINTENANT L'INTERLOCUTEUR
                        $interl = Interlocuteur::create([
                            'titre' => $request->titre,
                             'nom' => $request->nom, 
                             'tel' => $request->tel,
                              'email' => $request->email, 
                              'fonction' => $p->id, 
                              'id_entreprise' => $request->entreprise,
                               'created_by' => auth()->user()->id,
                        ]);

                        $insert = Prospection::create([
                                
                            'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => 31,
                            'id_entreprise' => $request->entreprise, 
                            'interlocuteur' => $interl->id,
                            'id_utilisateur' => auth()->user()->id,
                            
                        ]);
                    }
                    else
                    {
                        $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $client->id);
                        foreach($add as $interlocuteur)
                        {
                            $insert = Prospection::create([
                                
                                'date_prospection' => $request->date_prospect,
                                'date_fin' => $date_fin, 
                                'duree_jours' => 31,
                                'id_entreprise' => $request->entreprise, 
                                'interlocuteur' => $interlocuteur->id,
                                'id_utilisateur' => auth()->user()->id,
                                
                            ]);
                        }
                    }
                }
                else //interlocuteur pas nouveau
                {
                    $insert = Prospection::create([
                        
                        'date_prospection' => $request->date_prospect,
                        'date_fin' => $date_fin, 
                        'duree_jours' => 31, 
                        'id_entreprise' => $request->entreprise,  
                        'interlocuteur' => $request->interlocuteur,
                        'id_utilisateur' => auth()->user()->id,
                            
                    ]);

                    
                }
            }
            
            //IMPLEMENTATION DE LA RELATION PLUSIEURS A PLUSIEURS
            //Etant donné qu'on peut sélectionner plusieurs services lors de l'enregistrement de la prospection
            //$insert->services()->attach($request->service_propose);//Il sera lié au IDS des différents ser1vices services selectionnés

            for($a = 0; $a < count($request->service_propose); $a++)
            {
                
                $insert_services = Prospection_service::create([
        
                    'service_id' =>  $request->service_propose[$a],
                    'prospection_id' => $insert->id,
    
                ]);
            }

        }

        //AJOUT DU CR

        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;

    
        if( $fichier != null)
        {
        //VERFIFIER LE FORMAT 
        $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
        //dd($extension);
        if($extension != "pdf")
        {
            return redirect('prospection')->with('error', 'FORMAT DE FICHIER INCORRECT');
        }
            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Prospection::where('id', $insert->id)->get();
            
            foreach($get_path as $get_path)
            {
                if($get_path->path_cr == null)
                {
                
                    //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'crs', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $insert->id)
                    ->update([
                        'path_cr'=> $path,
                        
                    ]);

                


                }
                else
                {
                
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    Storage::delete($get_path->path_cr);


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'crs', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $insert->id)
                    ->update([
                        'path_cr'=> $path,
                        
                    ]);

                
                }
            }
        
        }
        else
        {
        
        }

        //AJOUT PROFORMA

        $fichier_proforma = $request->fileproforma;

        //dd( $fichier_proforma);
        if( $fichier_proforma != null)
        {
        //VERFIFIER LE FORMAT 
        $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);
        //dd($extension);
        if($extension != "pdf")
        {
            return redirect('prospection')->with('error', 'FORMAT DE FICHIER INCORRECT');
        }
            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Prospection::where('id', $insert->id)->get();
            //dd( $get_path);
            foreach($get_path as $get_path)
            {
                if($get_path->facture_path == null)
                {
                //dd('ici');
                    //enregistrement de fichier dans la base
                    $file_name = $fichier_proforma->getClientOriginalName();
                    
                            
                    $path = $request->file('fileproforma')->storeAs(
                        'factures/proforma', $file_name
                    );

                
                    $affected = DB::table('prospections')
                    ->where('id', $insert->id)
                    ->update([
                        'facture_path'=> $path,
                        
                    ]);

                    //dd( $affected);
                }
                else
                {
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    Storage::delete($get_path->facture_path);


                    $file_name = $fichier_proforma->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures/proforma', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $insert->id)
                    ->update([
                        'facture_path'=> $path,
                        
                    ]);

                
                }
            }
        
        }
        else
        {
        
        }
       

        return redirect('prospection')->with('success', 'Enregistrement effectué');
    }
    public function MyOwnProspection($id)
    {
        $get = DB::table('prospections')
          ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
          ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
          
          ->where('prospections.id_utilisateur', '=', $id)
          ->get(['prospections.*', 'entreprises.nom_entreprise', 'interlocuteurs.titre', 'interlocuteurs.nom', 'interlocuteurs.tel', 'interlocuteurs.fonction', ]);

         return $get;
    }

    public function RetriveAll()
    {
        $get = DB::table('prospections')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        
        ->join('utilisateurs', 'prospections.id_utilisateur', '=', 'utilisateurs.id')
        ->get(['prospections.*', 'entreprises.nom_entreprise', 'interlocuteurs.titre', 'interlocuteurs.nom', 'interlocuteurs.fonction', 'interlocuteurs.tel',  'utilisateurs.nom_penoms']);

        return $get;
    }

    public function EditProspForm(RequEst $request)
    {
        return view('admin/edit_prospection',
            [
                'id' => $request->id_prospection,
            ]
        );
    }

    
    public function GetById($id_prospection)
    {
        $get = DB::table('prospections')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        
        ->where('prospections.id', '=', $id_prospection)
        ->get(['prospections.*', 'entreprises.nom_entreprise', 'interlocuteurs.titre', 'interlocuteurs.nom', 'interlocuteurs.tel', 'interlocuteurs.email', 'interlocuteurs.fonction', ]);

        return $get;

    }

    public function EditProspection(Request $request)
    {
        //dd($request->all());
        $calculator = new Calculator();
        
        //Calcul de la date de fin de contrat
        $date_fin = $calculator->FinProspection($request->duree, $request->date_prospect);
       
        
        //VOIR SI L'INTERLOCUTERU EXISTE OU PAS EN VUE DE LE CREER
        if($request->entreprise == "autre")//L'entrprise n'y est pas
        {
           
            $add_client  = (new EntrepriseController())->AddEntreprise($request);

            foreach($add_client as $client)
            {
                if($request->interlocuteur == "autre")//L'interloctueur n'existe pas 
                {
                   /* if($request->fonction == "autre")
                    {
                        $p = Profession::create(['intitule' => $request->new_fonction]);
                        //AJOUTER MAINTENANT L'INTERLOCUTEUR
                        $interl = Interlocuteur::create([
                            'titre' => $request->titre,
                             'nom' => $request->nom, 
                             'tel' => $request->tel,
                              'email' => $request->email, 
                              'fonction' => $p->id, 
                              'id_entreprise' => $request->entreprise,
                               'created_by' => auth()->user()->id,
                        ]);

                       
                        $affected =  DB::table('prospections')
                        ->update([
                             
                            'date_prospection' => $request->date_prospect,
                             'date_fin' => $date_fin, 
                             'duree_jours' => $request->duree, 
                             'id_entreprise' => $client->id, 
                             'interlocuteur' => $interl->id,
                       ]);
                    }
                    else
                    {
                        $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $client->id);
        
                        foreach($add as $interlocuteur)
                        {
                           
                            $affected =  DB::table('prospections')
                            ->update([
                                 
                                'date_prospection' => $request->date_prospect,
                                 'date_fin' => $date_fin, 
                                 'duree_jours' => $request->duree, 
                                 'id_entreprise' => $client->id, 
                                 'interlocuteur' => $interlocuteur->id,
                           ]);
                          
                          
                        }
                      
                    }
                   */
                        
                }
                else //interlocuteur pas nouveau
                {
                    $affected =  DB::table('prospections')
                    ->where('id', $request->id_prospection)
                        ->update([
                        
                        'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => $request->duree, 
                            'id_entreprise' => $client->id,  
                            
                            'id_utilisateur' => auth()->user()->id,
                            
                    ]);

                    /*$Interloc = DB::table('interlocuteurs')
                    ->where('id', $request->id_interlocuteur)
                       ->update([
                        'titre' => $request->titre,
                         'nom' => $request->nom, 
                         'tel' => $request->tel,
                          'email' => $request->email, 
                          'fonction' => $request->fonction, 
                          
                           
                        ]);*/

                }
               
            }
            
        }
        else//entreprise pas nouvelle
        {
           
           
            if($request->interlocuteur == "autre")//L'interlocuteur n'existe pas 
            {
            
               if($request->fonction == "autre")
                {
                     /*
                    $p = Profession::create(['intitule' => $request->new_fonction]);
                    //AJOUTER MAINTENANT L'INTERLOCUTEUR
                    $interl = Interlocuteur::create([
                        'titre' => $request->titre,
                         'nom' => $request->nom, 
                         'tel' => $request->tel,
                          'email' => $request->email, 
                          'fonction' => $p->id, 
                          'id_entreprise' => $request->entreprise,
                           'created_by' => auth()->user()->id,
                    ]);

                   
                    $affected =  DB::table('prospections')
                    ->update([
                         
                        'date_prospection' => $request->date_prospect,
                        'date_fin' => $date_fin, 
                        'duree_jours' => $request->duree, 
                        'id_entreprise' => $client->id, 
                        'interlocuteur' => $interl->id,
                   ]);*/
                }
                else
                {
                   // $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $client->id);
                      $affected =  DB::table('prospections')
                        ->update([
                             
                            'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => $request->duree, 
                            'id_entreprise' => $client->id, 
                            //'interlocuteur' => $interlocuteur->id,
                       ]);
                    /*foreach($add as $interlocuteur)
                    {
                       
                        $affected =  DB::table('prospections')
                        ->update([
                             
                            'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => $request->duree, 
                            'id_entreprise' => $client->id, 
                            //'interlocuteur' => $interlocuteur->id,
                       ]);
                      
                      
                    }*/
                  
                }
               
                    
            }
            else //interlocuteur pas nouveau
            {
                //dd($request->service_propose);
                $affected =  DB::table('prospections')
                ->where('id', $request->id_prospection)
                ->update([
                    
                    'date_prospection' => $request->date_prospect,
                    'date_fin' => $date_fin, 
                    'duree_jours' => $request->duree, 
                    'id_entreprise' => $request->entreprise,  
                    'id_utilisateur' => auth()->user()->id,
                        
                ]);

                /*$Interloc = DB::table('interlocuteurs')
                ->where('id', $request->id_interlocuteur)
                ->update([
                    'titre' => $request->titre,
                    'nom' => $request->nom, 
                    'tel' => $request->tel,
                    'email' => $request->email, 
                    'fonction' => $request->fonction, 
                 ]);*/

                 //dd($affected);
            }
        }

        //IMPLEMENTATION DE LA RELATION PLUSIEURS A PLUSIEURS
        //Etant donné qu'on peut sélectionner plusieurs services lors de l'enregistrement de la prospection

        //INSERER DANS LA TABLE MANY TO MANY SI IL A CHOISI UN OU PLUSIEURS SERVICE
      
        if($request->service_propose == false)//L'utilisateur peut ne pas rempli
        {
           
        }
        else
        {
            for($a = 0; $a < count($request->service_propose); $a++)
            {
                
                $Insert = Prospection_service::create([
        
                    'service_id' =>  $request->service_propose[$a],
                    'prospection_id' => $request->id_prospection,

                ]);
            }
        }
   
        //AJOUT DU CR

        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;
       
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
            

            if($extension != "pdf")
            {
                  return redirect('prospection')->with('error', 'Format de fichier incorrect!');
            }
            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Prospection::where('id', $request->id_prospection)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->path_cr == null)
                {
                    
                      //enregistrement de fichier dans la base
                     $file_name = $fichier->getClientOriginalName();
                     
                             
                     $path = $request->file('file')->storeAs(
                         'crs', $file_name
                     );
 
                     $affected = DB::table('prospections')
                     ->where('id', $request->id_prospection)
                     ->update([
                         'path_cr'=> $path,
                         
                     ]);
 
                     return redirect('prospection')->with('success', 'Fichier enregistré');
 
                }
                else
                {
                   
                     //SUPPRESSION DE L'ANCIEN FICHIER
                     //dd($get_path->path);
                     Storage::delete($get_path->path_cr);
 
 
                     $file_name = $fichier->getClientOriginalName();
                     
                             
                     $path = $request->file('file')->storeAs(
                         'crs', $file_name
                     );
 
                     $affected = DB::table('prospections')
                     ->where('id', $request->id_prospection)
                     ->update([
                         'path_cr'=> $path,
                         
                     ]);
 
                 
                }
            }
            
        }
        else
        {
        
        }

         //AJOUT DE PROFORMA
         
        $fichier_proforma = $request->fileproforma;
        //dd($fichier_proforma);

        if( $fichier_proforma != null)
        { 
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);
            //dd($extension);
            if($extension != "pdf")
            {
                  return redirect('prospection')->with('error', 'Format de fichier incorrect!');
            }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Prospection::where('id', $request->id_prospection)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->facture_path == null)
                {
                
                    //enregistrement de fichier dans la base
                    $file_name = $fichier_proforma->getClientOriginalName();
                    //dd( $file_name);
                            
                    $path = $request->file('fileproforma')->storeAs(
                        'factures/proforma', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $request->id_prospection)
                    ->update([
                        'facture_path'=> $path,
                        
                    ]);

                    

                }
                else
                {
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    Storage::delete($get_path->facture_path);


                    $file_name = $fichier_proforma->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures/proforma', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $request->id_prospection)
                    ->update([
                        'facture_path'=> $path,
                        
                    ]);

                
                }
            }
        
        }
        else
        {
        
        }
       
        return redirect('prospection')->with('success', 'Modification effectué');

    }

    public function GetProspectionByIdEntr($id)
    {
        
        $get = DB::table('prospections')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('utilisateurs', 'prospections.id_utilisateur', '=', 'utilisateurs.id')
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
      
        
        ->where('entreprises.id', '=', $id)
        ->get(['prospections.*', 'entreprises.nom_entreprise', 'entreprises.adresse', 
        'entreprises.chiffre_affaire', 'entreprises.nb_employes','interlocuteurs.titre', 
        'entreprises.activite',
        'interlocuteurs.nom', 'interlocuteurs.tel', 'interlocuteurs.email', 
        'interlocuteurs.fonction', 'utilisateurs.nom_prenoms']);
      
        return $get;

    }

    public function UploadProsp(Request $request)
    {
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;


        if( $fichier != null)
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
            $get_path = Prospection::where('id', $request->id_prospection)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->path == null)
                {
                     //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'crs', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $request->id_prospection)
                    ->update([
                        'path_cr'=> $path,
                        
                    ]);

                    return redirect('prospection')->with('success', 'Fichier enregistré');

                }
                else
                {
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    Storage::delete($get_path->path);


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'crs', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $request->id_prospection)
                    ->update([
                        'path_cr'=> $path,
                        
                    ]);

                    return redirect('prospection')->with('success', 'Fichier enregistré');
                }
            }
           
        }
        else
        {
            return redirect('prospection')->with('error', 'Vous devez choisir un fichier');
        }
    }

    public function RetriveProsp(Request $request)
    {
        if(Storage::disk('local')->exists($request->file))
        {
            //return Storage::download($request->file);
            return response()->file(Storage::path($request->file));
        }
        else
        {
            return redirect('prospection')->with('error', 'Le fichier n\'existe pas');
        }
    }

    public function UploadProforma(Request $request)
    {
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;


        if( $fichier != null)
        {
            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Prospection::where('id', $request->id_prospection)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->path == null)
                {
                     //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures/proforma', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $request->id_prospection)
                    ->update([
                        'facture_path'=> $path,
                        
                    ]);

                    return redirect('prospection')->with('success', 'Fichier enregistré');

                }
                else
                {
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    Storage::delete($get_path->path);


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'factures/proforma', $file_name
                    );

                    $affected = DB::table('prospections')
                    ->where('id', $request->id_prospection)
                    ->update([
                        'facture_path'=> $path,
                        
                    ]);

                    return redirect('prospection')->with('success', 'Fichier enregistré');
                }
            }
           
        }
        else
        {
            return back()->with('error', 'Vous devez choisir un fichier');
        }
    }

    public function DownloadProforma(Request $request)
    {
        //dd($request->file);
        if(Storage::disk('local')->exists($request->file))
        {
            //return Storage::download($request->file);
            //return response()->file($request->file);
            return response()->file(Storage::path($request->file));
        }
        else
        {
            return redirect('prospection')->with('error', 'Le fichier n\'existe pas');
        }
    }

    public function AddProformaInFiche(Request $request)
    {
        //dd('if');
        $fichier = $request->new_doc_proforma;
        //dd($request->new_doc_proforma);
        //Le vrai nom
        $file_name = $fichier->getClientOriginalName();
        //dd($request->new_doc_proforma->getClientOriginalName());
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);
            //dd($extension);
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
            $get_path = Docfacture::where('libele', $file_name)->count();
            if($get_path == 0)
            {
               //dd($file_name);
                $path = $request->file('new_doc_proforma')->storeAs(
                    'factures/proforma', $file_name
                );

                $Insert = Docfacture::create([
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
            else
            {
                $get_path = Docfacture::where('libele', $file_name)->get();

                //SUPPRESSION DE L'ANCIEN FICHIER
                //dd($get_path);
                foreach($get_path as $get_path)
                {
                    Storage::delete($get_path->path_doc);
                }
               
                $path = $request->file('new_doc_proforma')->storeAs(
                    'factures/proforma', $file_name
                );

                //MODIFIER LA LIGNE DANS LA TABLE
                $get_doc = Docfacture::where('libele', $file_name)->get();

                foreach($get_doc as $get_doc)
                {
                    $affected = DB::table('docfactures')
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

    public function AddNewcr(Request $request)
    {
        $fichier = $request->new_doc_cr;
        //dd($request->new_doc);
        //Le vrai nom
        $file_name = $fichier->getClientOriginalName();
       
        if($fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($file_name, PATHINFO_EXTENSION);

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
            $get_path = Compterendu::where('libele', $file_name)->count();

            if($get_path == 0)
            {
               
               //dd($file_name);
                $path = $request->file('new_doc_cr')->storeAs(
                    'crs', $file_name
                );

                $Insert = Compterendu::create([
        
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
            else
            {
              
                $get_path = Compterendu::where('libele', $file_name)->get();

                //SUPPRESSION DE L'ANCIEN FICHIER
                //dd($get_path);
                foreach($get_path as $get_path)
                {
                    Storage::delete($get_path->path_doc);
                }
               
                $path = $request->file('new_doc_cr')->storeAs(
                    'crs', $file_name
                );

                //MODIFIER LA LIGNE DANS LA TABLE
                $get_doc = Docfacture::where('libele', $file_name)->get();

                foreach($get_doc as $get_doc)
                {
                    dd($get_doc->id);
                    $affected = DB::table('compterendus')
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
    }

    public function DeleteProfInFiche(Request $request)
    {
        Storage::delete($request->file);

        $deleted = DB::table('docfactures')->where('id', '=', $request->id_doc)->delete();

        return view('dash/prospect_about',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'success' => 'Elément supprimé'
                ]
            );
    }

    public function DeleteCrInFiche(Request $request)
    {
        Storage::delete($request->file);

        $deleted = DB::table('compterendus')->where('id', '=', $request->id_doc)->delete();

        return view('dash/prospect_about',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'success' => 'Elément supprimé'
                ]
            );
    }

}
