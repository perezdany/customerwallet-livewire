<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Calculator;

use App\Http\Controllers\EntrepriseController;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


use App\Models\Contrat;
use App\Models\Prestation;
use App\Models\Prestation_service;

use DB; 

class ContratController extends Controller
{
    //Handle contrat

    public function GetAll()
    {
        $get = DB::table('contrats')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
        ->join('prestations', 'prestations.id_contrat', '=', 'contrats.id')
        ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
        
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', 
        'typeprestations.libele']);


        return $get;
    }

    public function GetAllNoSolde()
    {
        $today = date('Y-m-d');
        
        $get = DB::table('contrats')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
        ->where('statut_solde', 0)
        ->where('fin_contrat', '>', $today )
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise',]);


        return $get;
    }

    public function AddContrat(Request $request)
    {
        if($request->entreprise == 0)
        {
            return back()->with('error', 'Vous n\'avez pas choisi l\'entreprise');
        }
        $jours = $request->jour;
        $annee = $request->annee;
        $mois = $request->mois;
        $date_debut = $request->date_debut;

       //VERIFIER LA TAILLE DE LA CHAINE MONTANT
       $ch = strval($request->montant);
        if(strlen($ch) > 13)
        {
                //rediriger pour lui dire que c'est trop long
                return back()->with('error', 'montant saisies trop long');
        }
        $calculator = new Calculator();
        //dd($jours);
        //Calcul de la date de fin de contrat
        $date_fin = $calculator->FinContrat($jours, $date_debut, $mois, $annee);
       
        //VERIFIER SI ON A AFFAIRE A UNE NOUVELLE ENTREPRISE
        if($request->entreprise == "autre")
        {
            $add = (new EntrepriseController())->AddEntreprise($request);

            foreach($add as $add)
            {
                $Insert = Contrat::create([
           
                    'titre_contrat'=> $request->titre,
                     'montant' => intval($request->montant), 
                     'reste_a_payer' => intval($request->montant), 
                     'debut_contrat' => $date_debut,
                     'fin_contrat' => $date_fin,
                     'id_entreprise' => $add->id,
                     'date_solde' => $request->date_solde, 
                     'statut_solde' => 0,
                      'created_by' => auth()->user()->id,
                ]);
        
            }
        
        }  
        else //ENTREPRISE PAS NOUVELLE
        {
            $Insert = Contrat::create([
           
                'titre_contrat'=> $request->titre,
                 'montant' => $request->montant, 
                 'reste_a_payer' => $request->montant, 
                 'debut_contrat' => $date_debut,
                 'fin_contrat' => $date_fin,
                 'id_entreprise' => $request->entreprise,
                 'date_solde' => $request->date_solde, 
                 'statut_solde' => 0,
                  'created_by' => auth()->user()->id,
            ]);
    
        }     
        
        
            //ENREGISTRER LE FICHIER DU CONTRAT

            //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
            $fichier = $request->file;

            

            if( $fichier != null)
            {
                //VERFIFIER LE FORMAT 
                $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);

                if($extension != "pdf")
                {
                    return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
                }

                //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                $get_path = Contrat::where('id', $Insert->id)->get();
                foreach($get_path as $get_path)
                {
                    if($get_path->path == null)
                    {
                        //enregistrement de fichier dans la base
                        $file_name = $fichier->getClientOriginalName();
                        
                                
                        $path = $request->file('file')->storeAs(
                            'fichiers', $file_name
                        );
    
                        $affected = DB::table('contrats')
                        ->where('id', $Insert->id)
                        ->update([
                            'path'=> $path,
                            
                        ]);
    
                        
                    }
                    else
                    {
                        $get_path = Contrat::where('id', $Insert->id)->get();
                        //SUPPRESSION DE L'ANCIEN FICHIER
                        //dd($get_path->path);
                        foreach($get_path as $get_path)
                        {
                            Storage::delete($get_path->path);
                        }
                       
    
    
                        $file_name = $fichier->getClientOriginalName();
                        
                                
                        $path = $request->file('file')->storeAs(
                            'fichiers', $file_name
                        );
    
                        $affected = DB::table('contrats')
                        ->where('id', $Insert->id)
                        ->update([
                            'path'=> $path,
                            
                        ]);
    
                        
                    }
                }
                
            }
            else
            {
            
            }

            //ENREGISTRER LA FACTURE PROFORMA
            //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
            $fichier_proforma = $request->file_proforma;

            
            if( $fichier_proforma != null)
            {
                    //VERFIFIER LE FORMAT 
                    $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);

                    if($extension != "pdf")
                    {
                            return view('dash/fiche_customer',
                            [
                                'id_entreprise' => $request->id_entreprise,
                                'error' => 'FORMAT DE FICHIER INCORRECT'
                            ]
                        );
                    }
                    //VERFIFIER LE FORMAT 
                    $extension = pathinfo($fichier_proforma->getFilename(), PATHINFO_EXTENSION);

                    if($extension != "pdf")
                    {
                        return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
                    }
                    //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                    $get_path_prof = Contrat::where('id', $Insert->id)->get();
                    foreach($get_path_prof as $get_path_prof)
                    {
                            if($get_path_prof->proforma_file == null)
                            {
                                //enregistrement de fichier dans la base
                                $file_name_prof = $fichier_proforma->getClientOriginalName();
                            
                                        
                                $path = $request->file('file')->storeAs(
                                    'factures/proforma', $file_name_prof
                                );
            
                                $affected = DB::table('contrats')
                                ->where('id', $Insert->id)
                                ->update([
                                    'proforma_file'=> $path,
                                    
                                ]);
            
                                
                            }
                            else
                            {

                                //SUPPRESSION DE L'ANCIEN FICHIER
                                //dd($get_path->path);
                                $get_path_prof = Contrat::where('id', $Insert->id)->get();
                                foreach($get_path_prof as $get_path_prof)
                                {
                                    Storage::delete($get_path_prof->proforma_file);
                                }

            
                                $file_name_prof = $fichier_proforma->getClientOriginalName();
                                
                                        
                                $path = $request->file('file')->storeAs(
                                    'factures/proforma', $file_name_prof
                                );
            
                                $affected = DB::table('contrats')
                                ->where('id', $Insert->id)
                                ->update([
                                    'proforma_file'=> $path,
                                    
                                ]);
            
                                
                            }
                    }
                
            }
            else
            {
            
            }

        return back()->with('success', 'Enregistrement effectué');
    }

    public function GoFormContratProspect(Request $request)
    {
        return view('forms/add_contrat_fiche_prosp',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function AddContratPrest(Request $request)
    {
        //dd('ici');
        if($request->entreprise == 0)
        {
            return back()->with('error', 'Vous n\'avez pas choisi l\'entreprise');
        }
        $jours = $request->jour;
        $annee = $request->annee;
        $mois = $request->mois;
        $date_debut = $request->date_debut;

       //VERIFIER LA TAILLE DE LA CHAINE MONTANT
       $ch = strval($request->montant);
        if(strlen($ch) > 13)
        {
                //rediriger pour lui dire que c'est trop long
                return back()->with('error', 'montant saisies trop long');
        }
        $calculator = new Calculator();
        //dd($jours);
        //Calcul de la date de fin de contrat
        $date_fin = $calculator->FinContrat($jours, $date_debut, $mois, $annee);
       
        //VERIFIER SI ON A AFFAIRE A UNE NOUVELLE ENTREPRISE
        if($request->entreprise == "autre")
        {
            $add = (new EntrepriseController())->AddEntreprise($request);

            foreach($add as $add)
            {
                $Insert = Contrat::create([
           
                    'titre_contrat'=> $request->titre,
                     'montant' => intval($request->montant), 
                     'reste_a_payer' => intval($request->montant), 
                     'debut_contrat' => $date_debut,
                     'fin_contrat' => $date_fin,
                     'id_entreprise' => $add->id,
                     'date_solde' => $request->date_solde, 
                     'statut_solde' => 0,
                      'created_by' => auth()->user()->id,
                ]);
        
            }

        
            //VERIFIER SI IL N'EST PAS CLIENT CHANGE SONT STATUT A MEME TEMPS
            //RECUPER L'(ENTREPRISE)
            $recup_entreprise = (new ContratController())->GetById($Insert->id);
            foreach($recup_entreprise as $recup_entreprise)
            {
            
                $entreprise = (new EntrepriseController())->GetById($recup_entreprise->id_entreprise);
                foreach($entreprise as $entreprise)
                {
                
                    if($entreprise->id_statutentreprise == 1)
                    {
                        //dd($entreprise);
                        $affected = DB::table('entreprises')
                        ->where('id', $entreprise->id)
                        ->update([ 'id_statutentreprise' => 2, 
                            'client_depuis' => date('Y-m-d') 
                    ]);
                    }
                }
                
            }
        
        }  
        else //ENTREPRISE PAS NOUVELLE
        {
            $Insert = Contrat::create([
           
                'titre_contrat'=> $request->titre,
                 'montant' => $request->montant, 
                 'reste_a_payer' => $request->montant, 
                 'debut_contrat' => $date_debut,
                 'fin_contrat' => $date_fin,
                 'id_entreprise' => $request->entreprise,
                 'date_solde' => $request->date_solde, 
                 'statut_solde' => 0,
                  'created_by' => auth()->user()->id,
            ]);

            //VERIFIER SI IL N'EST PAS CLIENT CHANGE SONT STATUT A MEME TEMPS
            //RECUPER L'(ENTREPRISE)
            $recup_entreprise = (new ContratController())->GetById($Insert->id_entreprise);
            foreach($recup_entreprise as $recup_entreprise)
            {
            
                $entreprise = (new EntrepriseController())->GetById($recup_entreprise->id_entreprise);
                foreach($entreprise as $entreprise)
                {
                
                    if($entreprise->id_statutentreprise == 1)
                    {
                        //dd($entreprise);
                        $affected = DB::table('entreprises')
                        ->where('id', $entreprise->id)
                        ->update([ 'id_statutentreprise' => 2, 
                            'client_depuis' => date('Y-m-d') 
                    ]);
                    }
                }
                
            }
    
        }     
        
        
        //ENREGISTRER LE FICHIER DU CONTRAT

        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;
       
        if( $fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);

            if($extension != "pdf")
            {
                return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
            }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Contrat::where('id', $Insert->id)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->path == null)
                {
                    //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'fichiers', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $Insert->id)
                    ->update([
                        'path'=> $path,
                        
                    ]);

                    
                }
                else
                {
                    $get_path = Contrat::where('id', $Insert->id)->get();
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    foreach($get_path as $get_path)
                    {
                        Storage::delete($get_path->path);
                    }
                    


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'fichiers', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $Insert->id)
                    ->update([
                        'path'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
        
        }

        //ENREGISTRER LA FACTURE PROFORMA
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier_proforma = $request->file_proforma;

        
        if( $fichier_proforma != null)
        {
                //VERFIFIER LE FORMAT 
                
                //VERFIFIER LE FORMAT 
                $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);
              
                if($extension != "pdf")
                {
                    return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
                }
                //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                $get_path_prof = Contrat::where('id', $Insert->id)->get();
                foreach($get_path_prof as $get_path_prof)
                {
                    if($get_path_prof->proforma_file == null)
                    {
                        //enregistrement de fichier dans la base
                        $file_name_prof = $fichier_proforma->getClientOriginalName();
                    
                                
                        $path = $request->file('file')->storeAs(
                            'factures/proforma', $file_name_prof
                        );
    
                        $affected = DB::table('contrats')
                        ->where('id', $Insert->id)
                        ->update([
                            'proforma_file'=> $path,
                            
                        ]);
    
                        
                    }
                    else
                    {

                        //SUPPRESSION DE L'ANCIEN FICHIER
                        //dd($get_path->path);
                        $get_path_prof = Contrat::where('id', $Insert->id)->get();
                        foreach($get_path_prof as $get_path_prof)
                        {
                            Storage::delete($get_path_prof->proforma_file);
                        }

    
                        $file_name_prof = $fichier_proforma->getClientOriginalName();
                        
                                
                        $path = $request->file('file')->storeAs(
                            'factures/proforma', $file_name_prof
                        );
    
                        $affected = DB::table('contrats')
                        ->where('id', $Insert->id)
                        ->update([
                            'proforma_file'=> $path,
                            
                        ]);
    
                        
                    }
                }
            
        }
        else
        {
        
        }

        //LA PRESTATION MAINTENANT
        if($request->type == 0)
        {
            return back()->with('error', 'Choisissez impérativement le type de prestation');
        }

       
        
        

        $insert_prestation = Prestation::create([
             'date_prestation' => $request->date_execute, 
             'id_type_prestation' => $request->type,
              'localisation' => $request->localisation, 
              'id_contrat' => $Insert->id, 
              
               'created_by' => auth()->user()->id,
        ]);

        //IMPLEMENTATION DE LA RELATION PLUSIEURS A PLUSIEURS
        //Etant donné qu'on peut sélectionner plusieurs services lors de l'enregistrement de la prospection
        //$insert_prestation->services()->attach($request->service);//Il sera lié au IDS des différents ser1vices services selectionnés
        for($a = 0; $a < count($request->service); $a++)
        {
            
            $insert_services = Prestation_service::create([
    
                'service_id' =>  $request->service[$a],
                'prestation_id' => $insert_prestation->id,

            ]);
        }

        return back()->with('success', 'Enregistrement effectué');
    }

    public function AddContratFromFiche(Request $request)
    {
      
        $jours = $request->jour;
        $annee = $request->annee;
        $mois = $request->mois;
        $date_debut = $request->date_debut;

       //VERIFIER LA TAILLE DE LA CHAINE MONTANT
       $ch = strval($request->montant);
        if(strlen($ch) > 13)
        {
            return view('forms/add_contrat_fiche_prosp',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'error' => 'Montant saisi trp long'
                ]
            );
        }
        $calculator = new Calculator();
        //dd($jours);
        //Calcul de la date de fin de contrat
        $date_fin = $calculator->FinContrat($jours, $date_debut, $mois, $annee);
       
        //VERIFIER SI ON A AFFAIRE A UNE NOUVELLE ENTREPRISE
        if($request->entreprise == "autre")
        {
            $add = (new EntrepriseController())->AddEntreprise($request);

            foreach($add as $add)
            {
                $Insert = Contrat::create([
           
                    'titre_contrat'=> $request->titre,
                     'montant' => intval($request->montant), 
                     'reste_a_payer' => intval($request->montant), 
                     'debut_contrat' => $date_debut,
                     'fin_contrat' => $date_fin,
                     'id_entreprise' => $add->id,
                     'date_solde' => $request->date_solde, 
                     'statut_solde' => 0,
                      'created_by' => auth()->user()->id,
                ]);
        
            }
        
        }  
        else //ENTREPRISE PAS NOUVELLE
        {
            $Insert = Contrat::create([
           
                'titre_contrat'=> $request->titre,
                 'montant' => $request->montant, 
                 'reste_a_payer' => $request->montant, 
                 'debut_contrat' => $date_debut,
                 'fin_contrat' => $date_fin,
                 'id_entreprise' => $request->entreprise,
                 'date_solde' => $request->date_solde, 
                 'statut_solde' => 0,
                  'created_by' => auth()->user()->id,
            ]);
    
        }     
        
        
        //ENREGISTRER LE FICHIER DU CONTRAT

        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;
       
        if( $fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);

            if($extension != "pdf")
            {
                return view('forms/add_contrat_fiche_prosp',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'error' => 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!'
                    ]
                );
            }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Contrat::where('id', $Insert->id)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->path == null)
                {
                    //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'fichiers', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $Insert->id)
                    ->update([
                        'path'=> $path,
                        
                    ]);

                    
                }
                else
                {
                    $get_path = Contrat::where('id', $Insert->id)->get();
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    foreach($get_path as $get_path)
                    {
                        Storage::delete($get_path->path);
                    }
                    


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'fichiers', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $Insert->id)
                    ->update([
                        'path'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
        
        }

        //ENREGISTRER LA FACTURE PROFORMA
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier_proforma = $request->file_proforma;

       
        if( $fichier_proforma != null)
        {
            //VERFIFIER LE FORMAT 
            
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);
            
            if($extension != "pdf")
            {
                return view('forms/add_contrat_fiche_prosp',
                    [
                        'id_entreprise' => $request->id_entreprise,
                        'error' => 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!'
                    ]
                );
            }
            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path_prof = Contrat::where('id', $Insert->id)->get();
            foreach($get_path_prof as $get_path_prof)
            {
                if($get_path_prof->proforma_file == null)
                {
                    //enregistrement de fichier dans la base
                    $file_name_prof = $fichier_proforma->getClientOriginalName();
                    //dd($file_name_prof);
                            
                    $path = $request->file('file_proforma')->storeAs(
                        'factures/proforma', $file_name_prof
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $Insert->id)
                    ->update([
                        'proforma_file'=> $path,
                        
                    ]);

                    
                }
                else
                {

                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    $get_path_prof = Contrat::where('id', $Insert->id)->get();
                    foreach($get_path_prof as $get_path_prof)
                    {
                        Storage::delete($get_path_prof->proforma_file);
                    }


                    $file_name_prof = $fichier_proforma->getClientOriginalName();
                    
                            
                    $path = $request->file('file_proforma')->storeAs(
                        'factures/proforma', $file_name_prof
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $Insert->id)
                    ->update([
                        'proforma_file'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
        
        }

        //LA PRESTATION MAINTENANT
        if($request->type == 0)
        {
            return view('forms/add_contrat_fiche_prosp',
                [
                    'id_entreprise' => $request->id_entreprise,
                    'error' => 'Choissez le type!'
                ]
            );
        }

       
        //VERIFIER SI IL N'EST PAS CLIENT CHANGE SONT STATUT A MEME TEMPS
        //RECUPER L'(ENTREPRISE)
        $recup_entreprise = (new ContratController())->GetById($Insert->id);
        foreach($recup_entreprise as $recup_entreprise)
        {
          
            $entreprise = (new EntrepriseController())->GetById($recup_entreprise->id_entreprise);
            foreach($entreprise as $entreprise)
            {
              
                if($entreprise->id_statutentreprise == 1)
                {
                    //dd($entreprise);
                    $affected = DB::table('entreprises')
                    ->where('id', $entreprise->id)
                    ->update([ 'id_statutentreprise' => 2, 
                        'client_depuis' => date('Y-m-d') 
                ]);
                }
            }
            
        }
        

        $insert_prestation = Prestation::create([
             'date_prestation' => $request->date_execute, 
             'id_type_prestation' => $request->type,
              'localisation' => $request->localisation, 
              'id_contrat' => $Insert->id, 
              
               'created_by' => auth()->user()->id,
        ]);

        //IMPLEMENTATION DE LA RELATION PLUSIEURS A PLUSIEURS
        //Etant donné qu'on peut sélectionner plusieurs services lors de l'enregistrement de la prospection
        //$insert_prestation->services()->attach($request->service);//Il sera lié au IDS des différents ser1vices services selectionnés
        for($a = 0; $a < count($request->service); $a++)
        {
            
            $insert_services = Prestation_service::create([
    
                'service_id' =>  $request->service[$a],
                'prestation_id' => $insert_prestation->id,

            ]);
        }

        return view('forms/add_contrat_fiche_prosp',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Enregistrement effectué'
            ]
        );
    }

    public function MyOwnContrat($id)
    {
        $get = DB::table('contrats')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
        ->where('contrats.created_by', '=', $id)
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise',]);

        return $get;
    }

    public function RetriveAll()
    {
        $get = DB::table('contrats')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
       
        
        
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

   
        return $get;
    }

    public function GetById($id)
    {
       $get = Contrat::where('contrats.id', $id)
       ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
       ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise',]);

        return $get;
    }

    public function EditContratForm(Request $request)
    {
        return view('admin/edit_contrat',
            [
                'id' => $request->id_contrat,
            ]
        );
    }
    public function EditContratFromFiche(Request $request)
    {
        return view('forms/edit_contrat_from_fiche',
            [
                'id' => $request->id_contrat,
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }


    public function EditContrat(Request $request)
    {
        $jours = $request->jours;
        $annee = $request->annee;
        $mois = $request->mois;
        $date_debut = $request->date_debut;

        //VERIFIER LA TAILLE DE LA CHAINE MONTANT
        $ch = strval($request->montant);
        if(strlen($ch) > 13)
        {
            //rediriger pour lui dire que c'est trop long
            return back()->with('error', 'données montant saisies trop long');
        }

        $calculator = new Calculator();
        
        //ATTENTION ON DOIT VOIR SI Y A EU DES PAIEMENTS ET RECUPERER LE TOTAL POUR ADAPTER AU NOUVEAU MONTANT SIPOSSIBLE

        //Récuperer tous les paiements du contrat et la somme totale
        $tot_paiement =   $calculator->SommePaiementContrat($request->id_contrat);

        //Faire la différence pour le reste_a_payer
        $rest = $request->montant - $tot_paiement;

        $affected = DB::table('contrats')
        ->where('id', $request->id_contrat)
        ->update([
            'titre_contrat'=> $request->titre,
             'montant' => $request->montant, 
             'reste_a_payer' => $rest, 
             'debut_contrat' => $date_debut,
             'id_entreprise' => $request->entreprise,
             'date_solde' => $request->date_solde, 
             'statut_solde' => 0,
              'created_by' => auth()->user()->id,
        ]);


        //ENREGISTRER LE FICHIER DU CONTRAT
          //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
          $fichier = $request->file;


            if( $fichier != null)
            {
                //VERFIFIER LE FORMAT 
                $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);

                if($extension != "pdf")
                {
                    redirect('contrat')->with('error', 'Modification effectuée');
                }
              //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
              $get_path = Contrat::where('id', $request->id_contrat)->get();
              foreach($get_path as $get_path)
              {
                  if($get_path->path == null)
                  {
                       //enregistrement de fichier dans la base
                      $file_name = $fichier->getClientOriginalName();
                      
                              
                      $path = $request->file('file')->storeAs(
                          'fichiers', $file_name
                      );
  
                      $affected = DB::table('contrats')
                      ->where('id', $request->id_contrat)
                      ->update([
                          'path'=> $path,
                          
                      ]);
  
                     
                  }
                  else
                  {
                      //SUPPRESSION DE L'ANCIEN FICHIER
                      //dd($get_path->path);
                      Storage::delete($get_path->path);
  
  
                      $file_name = $fichier->getClientOriginalName();
                      
                              
                      $path = $request->file('file')->storeAs(
                          'fichiers', $file_name
                      );
  
                      $affected = DB::table('contrats')
                      ->where('id', $request->id_contrat)
                      ->update([
                          'path'=> $path,
                          
                      ]);
  
                     
                  }
              }
             
            }
            else
            {
            
            }

          //ENREGISTRER LA FACTURE PROFORMA
            //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
            $fichier_proforma = $request->file_proforma;

            
            if( $fichier_proforma != null)
            {
                    
                    //VERFIFIER LE FORMAT 
                    $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);

                    if($extension != "pdf")
                    {
                        return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
                    }
                    //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                    $get_path_prof = Contrat::where('id', $Insert->id)->get();
                    foreach($get_path_prof as $get_path_prof)
                    {
                            if($get_path_prof->proforma_file == null)
                            {
                                //enregistrement de fichier dans la base
                                $file_name_prof = $fichier_proforma->getClientOriginalName();
                            
                                        
                                $path = $request->file('file')->storeAs(
                                    'factures/proforma', $file_name_prof
                                );
            
                                $affected = DB::table('contrats')
                                ->where('id', $Insert->id)
                                ->update([
                                    'proforma_file'=> $path,
                                    
                                ]);
            
                                
                            }
                            else
                            {

                                //SUPPRESSION DE L'ANCIEN FICHIER
                                //dd($get_path->path);
                                $get_path_prof = Contrat::where('id', $Insert->id)->get();
                                foreach($get_path_prof as $get_path_prof)
                                {
                                    Storage::delete($get_path_prof->proforma_file);
                                }

            
                                $file_name_prof = $fichier_proforma->getClientOriginalName();
                                
                                        
                                $path = $request->file('file')->storeAs(
                                    'factures/proforma', $file_name_prof
                                );
            
                                $affected = DB::table('contrats')
                                ->where('id', $Insert->id)
                                ->update([
                                    'proforma_file'=> $path,
                                    
                                ]);
            
                                
                            }
                    }
                
            }
            else
            {
            
            }


        return redirect('contrat')->with('success', 'Modification effectuée');

    }

    public function EditContratFiche(Request $request)
    {
        $jours = $request->jours;
        $annee = $request->annee;
        $mois = $request->mois;
        $date_debut = $request->date_debut;

        //VERIFIER LA TAILLE DE LA CHAINE MONTANT
        $ch = strval($request->montant);
        if(strlen($ch) > 13)
        {
            //rediriger pour lui dire que c'est trop long
            return back()->with('error', 'données montant saisies trop long');
        }

        $calculator = new Calculator();
        
        //ATTENTION ON DOIT VOIR SI Y A EU DES PAIEMENTS ET RECUPERER LE TOTAL POUR ADAPTER AU NOUVEAU MONTANT SIPOSSIBLE

        //Récuperer tous les paiements du contrat et la somme totale
        $tot_paiement =   $calculator->SommePaiementContrat($request->id_contrat);

        //Faire la différence pour le reste_a_payer
        $rest = $request->montant - $tot_paiement;

        $affected = DB::table('contrats')
        ->where('id', $request->id_contrat)
        ->update([
            'titre_contrat'=> $request->titre,
             'montant' => $request->montant, 
             'reste_a_payer' => $rest, 
             'debut_contrat' => $date_debut,
             'id_entreprise' => $request->entreprise,
             'date_solde' => $request->date_solde, 
             'statut_solde' => 0,
              'created_by' => auth()->user()->id,
        ]);


        //ENREGISTRER LE FICHIER DU CONTRAT
          //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
            $fichier = $request->file;
            
            if( $fichier != null)
            {
                //VERFIFIER LE FORMAT 
                $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
            
                if($extension != "pdf")
                {
                        return view('dash/fiche_customer',
                        [
                            'id_entreprise' => $request->id_entreprise,
                            'error' => 'FORMAT DE FICHIER INCORRECT'
                        ]
                    );
                }
              //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
              $get_path = Contrat::where('id', $request->id_contrat)->get();
              foreach($get_path as $get_path)
              {
                  if($get_path->path == null)
                  {
                       //enregistrement de fichier dans la base
                      $file_name = $fichier->getClientOriginalName();
                      
                              
                      $path = $request->file('file')->storeAs(
                          'fichiers', $file_name
                      );
  
                      $affected = DB::table('contrats')
                      ->where('id', $request->id_contrat)
                      ->update([
                          'path'=> $path,
                          
                      ]);
  
                     
                  }
                  else
                  {
                        $get_path = Contrat::where('id', $request->id_contrat)->get();
                      //SUPPRESSION DE L'ANCIEN FICHIER
                      //dd($get_path->path);
                      foreach($get_path as $get_path)
                      {
                        Storage::delete($get_path->path);
                      }
  
                      $file_name = $fichier->getClientOriginalName();
                      
                              
                      $path = $request->file('file')->storeAs(
                          'fichiers', $file_name
                      );
  
                      $affected = DB::table('contrats')
                      ->where('id', $request->id_contrat)
                      ->update([
                          'path'=> $path,
                          
                      ]);
  
                     
                  }
              }
             
            }
            else
            {
            
            }

          //ENREGISTRER LA FACTURE PROFORMA
            //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
            $fichier_proforma = $request->file_proforma;

            
            if( $fichier_proforma != null)
            {
                    //VERFIFIER LE FORMAT 
                    $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);
                    //dd($extension);

                    if($extension != "pdf")
                    {
                            return view('dash/fiche_customer',
                            [
                                'id_entreprise' => $request->id_entreprise,
                                'error' => 'FORMAT DE FICHIER INCORRECT'
                            ]
                        );
                    }
                    //VERFIFIER LE FORMAT 
                    $extension = pathinfo($fichier_proforma->getClientOriginalName(), PATHINFO_EXTENSION);
                    //dd($extension);
                    if($extension != "pdf")
                    {
                        return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
                    }
                    //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                    $get_path_prof = Contrat::where('id', $request->id_contrat)->get();
                    foreach($get_path_prof as $get_path_prof)
                    {
                        if($get_path_prof->proforma_file == null)
                        {
                            //enregistrement de fichier dans la base
                            $file_name_prof = $fichier_proforma->getClientOriginalName();
                        
                                    
                            $path = $request->file('file')->storeAs(
                                'factures/proforma', $file_name_prof
                            );
        
                            $affected = DB::table('contrats')
                            ->where('id', $request->id_contrat)
                            ->update([
                                'proforma_file'=> $path,
                                
                            ]);
        
                            
                        }
                        else
                        {

                            //SUPPRESSION DE L'ANCIEN FICHIER
                            //dd($get_path->path);
                            $get_path_prof = Contrat::where('id', $request->id_contrat)->get();
                            foreach($get_path_prof as $get_path_prof)
                            {
                                Storage::delete($get_path_prof->proforma_file);
                            }

        
                            $file_name_prof = $fichier_proforma->getClientOriginalName();
                            
                                    
                            $path = $request->file('file')->storeAs(
                                'factures/proforma', $file_name_prof
                            );
        
                            $affected = DB::table('contrats')
                            ->where('id', $request->id_contrat)
                            ->update([
                                'proforma_file'=> $path,
                                
                            ]);
        
                            
                        }
                    }
                
            }
            else
            {
            
            }


            return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Modification effectuée'
            ]
        );

    }

    public function ContratEnCours()
    {
        $today =  date('Y-m-d');

   
        $get = DB::table('contrats')
        ->where('fin_contrat', '>', $today)
       ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
       ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
       ->join('prestations', 'prestations.id_contrat', '=', 'contrats.id')
        ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', 
        'typeprestations.libele',]);

        return $get;
    }

    public function GetContratByIdEntr($id)
    {
        $get = Contrat::where('contrats.id_entreprise', $id)
       ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
       ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise',]);
        //dd($get);
        return $get;
    
    }

    public function UploadContrat(Request $request)
    {
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;


        if( $fichier != null)
        {
             //VERFIFIER LE FORMAT 
             $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
            
             if($extension != "pdf")
             {
                 return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
             }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Contrat::where('id', $request->id_contrat)->get();
            foreach($get_path as $get_path)
            {
                if($get_path->path == null)
                {
                     //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'fichiers', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $request->id_contrat)
                    ->update([
                        'path'=> $path,
                        
                    ]);

                    return redirect('contrat')->with('success', 'Fichier enregistré');

                }
                else
                {
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    Storage::delete($get_path->path);


                    $file_name = $fichier->getClientOriginalName();
                    
                            
                    $path = $request->file('file')->storeAs(
                        'fichiers', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $request->id_contrat)
                    ->update([
                        'path'=> $path,
                        
                    ]);

                    return redirect('contrat')->with('success', 'Fichier enregistré');
                }
            }
           
        }
        else
        {
            return redirect('contrat')->with('error', 'Vous devez choisir un fichier');
        }
    }

    public function DownloadContrat(Request $request)
    {
        //dd($request->file);
        if(Storage::disk('local')->exists($request->file))
        {
            return response()->file(Storage::path($request->file));
        }
        else
        {
            return redirect('contrat')->with('error', 'Le fichier n\'existe pas');
        }

    }

    public function ViewProformaContrat(Request $request)
    {
        //dd($request->proforma_file);
        if(Storage::disk('local')->exists($request->proforma_file))
        {
            return response()->file(Storage::path($request->proforma_file));
        }
        else
        {
            return redirect('contrat')->with('error', 'Le fichier n\'existe pas');
        }

    }
}
