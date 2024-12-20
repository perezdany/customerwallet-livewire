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

    public function GetAll($id)
    {
        $get = DB::table('contrats')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
    
        ->where('entreprises.id' , $id)
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

        
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

    public function GetContratParent()
    {
        //Prendre tous les contrats qui ne sont pas considérés comme avenant
        $get = DB::table('contrats')
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
        ->orderBy('entreprises.nom_entreprise', 'asc')
        ->where('avenant', 0)
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

        return $get;
    }

    public function AddContrat(Request $request)
    {
        //dd('ici');
        if($request->entreprise == 0)
        {
            return redirect('contrat')->with('error', 'Vous n\'avez pas choisi l\'entreprise');
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
                return redirect('contrat')->with('error', 'montant saisies trop long');
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
                     'reconduction' => $request->reconduction, 
                     'statut_solde' => 0,
                      'created_by' => auth()->user()->id,
                ]);
        
            }

        
            //VERIFIER SI IL N'EST PAS CLIENT CHANGE SONT STATUT A MEME TEMPS ET IL DEVIENT ACTIF EN MEME TEMPS
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
                        ->update([ 'id_statutentreprise' => 2, 'etat' => 1,
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
                 'reconduction' => $request->reconduction, 
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
                return redirect('contrat')->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
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
                        'fichiers/contrat', $file_name
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
                    return redirect('contrat')->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
                }
                //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                $get_path_prof = Contrat::where('id', $Insert->id)->get();
                foreach($get_path_prof as $get_path_prof)
                {
                    if($get_path_prof->proforma_file == null)
                    {
                        //enregistrement de fichier dans la base
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
            return redirect('contrat')->with('error', 'Choisissez impérativement le type de prestation');
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

        return redirect('contrat')->with('success', 'Enregistrement effectué');

    }

    public function GoFormContratProspect(Request $request)
    {
        return view('forms/add_contrat_fiche_prosp',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GoContratByCustomer(Request $request)
    {
        return view('dash/contrats',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function TableFilter(Request $request)
    {
        //dd($request->all());
        $reconduction = $request->reconduction;
        $etat = $request->etat_contrat;
        $id_entreprise = $request->entreprise;
        $service = $request->service;
        
        if($id_entreprise == "all")
        {
           
           
            //VERIFIER CE QUE L'UTILISATEUR A CHOISI POUR FILTRER
            if($request->reconduction == "c")//Il n'a PAS CHOISI RECONDUCTION
            {   
               
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->orderBy('contrats.updated_at', 'desc')
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        //dd('test2');
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
              
                        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                        ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('services.id', $request->service)
                        ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                   
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                       
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
            
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);
                            
                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
    
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                       
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')     
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                       
           
                    
                    }
                }
            }
            else // IL A CHOISI RECONDUCTION
            {
               
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //dd('ici2');
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->where('contrats.reconduction', $request->reconduction)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
                        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('contrats.reconduction', $request->reconduction)
                        ->where('services.id', $request->service)
                        ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                    
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            //dd('test1');
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')
                           
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->where('contrats.reconduction', $request->reconduction) 
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                       
           
                    
                    }
                }

            }

        }
        else
        {
            //IL A CHOISI UNE ENTREPRISE
          
            if($request->reconduction == "c")//Il n'a PAS CHOISI RECONDUCTION
            {
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->where('contrats.id_entreprise', $request->entreprise)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('contrats.id_entreprise', $request->entreprise)
                        ->where('services.id', $request->service)
                        ->get(['contrats.*',  'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    }
                }
            }
            else // IL A CHOISI RECONDUCTION
            {
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->where('contrats.id_entreprise', $request->entreprise)
                        ->where('contrats.reconduction', $request->reconduction)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);
                        
                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
                        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('contrats.reconduction', $request->reconduction)
                        ->where('contrats.id_entreprise', $request->enntreprise)
                        ->where('services.id', $request->service)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    }
                }

            }
           
        }
            
    }

    public function AddContratPrest(Request $request)
    {
        //dd($request->all());

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
        if(strlen($ch) > 20)
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
            $add = (new EntrepriseController())->AddClient($request);

            foreach($add as $add)
            {
                if(isset($request->contrat_parent))
                {
                    $Insert = Contrat::create([
           
                        'titre_contrat'=> $request->titre,
                         'montant' => intval($request->montant), 
                         'reste_a_payer' => intval($request->montant), 
                         'debut_contrat' => $date_debut,
                         'fin_contrat' => $date_fin,
                         'id_entreprise' => $add->id,
                         'reconduction' => $request->reconduction, 
                         'avenant' => $request->avenant, 
                         'statut_solde' => 0,
                         'id_contrat_parent' => $request->contrat_parent,
                          'created_by' => auth()->user()->id,
                    ]);
                }
                else
                {
                    
                    $Insert = Contrat::create([
            
                        'titre_contrat'=> $request->titre,
                        'montant' => intval($request->montant), 
                        'reste_a_payer' => intval($request->montant), 
                        'debut_contrat' => $date_debut,
                        'fin_contrat' => $date_fin,
                        'id_entreprise' => $add->id,
                        'reconduction' => $request->reconduction, 
                        'avenant' => $request->avenant, 
                        'statut_solde' => 0,
                        'created_by' => auth()->user()->id,
                    ]);
                
                }
    
                
        
            }

        }  
        else //ENTREPRISE PAS NOUVELLE
        {
            if(isset($request->contrat_parent))
            {
                $Insert = Contrat::create([
       
                    'titre_contrat'=> $request->titre,
                     'montant' => intval($request->montant), 
                     'reste_a_payer' => intval($request->montant), 
                     'debut_contrat' => $date_debut,
                     'fin_contrat' => $date_fin,
                     'id_entreprise' => $request->entreprise,
                     'reconduction' => $request->reconduction, 
                     'avenant' => $request->avenant, 
                     'statut_solde' => 0,
                     'id_contrat_parent' => $request->contrat_parent,
                      'created_by' => auth()->user()->id,
                ]);
            }
            else
            {
                
                $Insert = Contrat::create([
        
                    'titre_contrat'=> $request->titre,
                    'montant' => intval($request->montant), 
                    'reste_a_payer' => intval($request->montant), 
                    'debut_contrat' => $date_debut,
                    'fin_contrat' => $date_fin,
                    'id_entreprise' => $request->entreprise,
                    'reconduction' => $request->reconduction, 
                    'avenant' => $request->avenant, 
                    'statut_solde' => 0,
                    'created_by' => auth()->user()->id,
                ]);
            
            }

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
                        'fichiers/contrat', $file_name
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


        //LE FICHIER DE BON DE COMMANDE
        $fichier_commande = $request->bon_commande;  
        if($fichier_commande != null)
        {
                //VERFIFIER LE FORMAT 
                
                //VERFIFIER LE FORMAT 
                $extension = pathinfo($fichier_commande->getClientOriginalName(), PATHINFO_EXTENSION);
              
                if($extension != "pdf")
                {
                    return back()->with('error', 'LE FORMAT DE FICHIER DOIT ETRE UN FORMAT PDF!!');
                }
                //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                $get_bon_commande = Contrat::where('id', $Insert->id)->get();
                foreach($get_bon_commande as $get_bon_commande)
                {
                    if($get_bon_commande->bon_commande == null)
                    {
                        //enregistrement de fichier dans la base
                        $file_name = $fichier_commande->getClientOriginalName();
                    
                                
                        $path = $request->file('bon_commande')->storeAs(
                            'fichiers/bon_commande', $file_name
                        );
    
                        $affected = DB::table('contrats')
                        ->where('id', $Insert->id)
                        ->update([
                            'bon_commande'=> $path,
                            
                        ]);
    
                        
                    }
                    else
                    {

                        //SUPPRESSION DE L'ANCIEN FICHIER
                        //dd($get_path->path);
                        $get_path = Contrat::where('id', $Insert->id)->get();
                        foreach($get_path as $get_path)
                        {
                            Storage::delete($get_path->bon_commande);
                        }

    
                        $file_name = $fichier_commande->getClientOriginalName();
                    
                                
                        $path = $request->file('bon_commande')->storeAs(
                            'fichiers/bon_commande', $file_name
                        );
    
                        $affected = DB::table('contrats')
                        ->where('id', $Insert->id)
                        ->update([
                            'bon_commande'=> $path,
                            
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

       
        //AJOUTER LA PREMIERE PRESTATION DE CE CONTRAT
        
        $insert_prestation = Prestation::create([
             'date_prestation' => $date_debut, 
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
                        'fichiers/contrat', $file_name
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

       
        if($fichier_proforma != null)
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
        //dd($request->all());
        return view('admin/edit_contrat',
            [
                'id' => $request->id_contrat,
                'reconduction' => $request->reconduction,
                'etat' => $request->etat_contrat,
                'id_entreprise' => $request->entreprise,
                'service' => $request->service,
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
        //ddd($request->all());
        
        //dd($request->all());
        $jours = $request->jours;
        $annee = $request->annee;
        $mois = $request->mois;
        $date_debut = $request->date_debut;

        //VERIFIER LA TAILLE DE LA CHAINE MONTANT
        $ch = strval($request->montant);
        if(strlen($ch) > 13)
        {
            //rediriger pour lui dire que c'est trop long
            $message_error = 'Données saisies trop long!';
            return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service', 'message_error'));
            //return back()->with('error', 'données montant saisies trop long');
        }

        $calculator = new Calculator();
        
        //ATTENTION ON DOIT VOIR SI Y A EU DES PAIEMENTS ET RECUPERER LE TOTAL POUR ADAPTER AU NOUVEAU MONTANT SIPOSSIBLE

        //Récuperer tous les paiements du contrat et la somme totale
        $tot_paiement =   $calculator->SommePaiementContrat($request->id_contrat);

        //Faire la différence pour le reste_a_payer
        $rest = $request->montant - $tot_paiement;
        if(isset($request->contrat_parent))
        {
           // dd('d');
            $affected = DB::table('contrats')
            ->where('id', $request->id_contrat)
            ->update([
                'titre_contrat'=> $request->titre,
                'montant' => $request->montant, 
                'reste_a_payer' => $rest, 
                'debut_contrat' => $date_debut,
                'id_entreprise' => $request->entreprise,
                'date_solde' => $request->date_solde, 
                'avenant' => $request->avenant,
                'id_contrat_parent'=>$request->contrat_parent,
                'statut_solde' => 0,
                'created_by' => auth()->user()->id,
            ]);
        }
        else
        {
            //dd('de');
            $affected = DB::table('contrats')
            ->where('id', $request->id_contrat)
            ->update([
                'titre_contrat'=> $request->titre,
                'montant' => $request->montant, 
                'reste_a_payer' => $rest, 
                'debut_contrat' => $date_debut,
                'id_entreprise' => $request->entreprise,
                'date_solde' => $request->date_solde, 
                'avenant' => $request->avenant,
           
                'statut_solde' => 0,
                'created_by' => auth()->user()->id,
            ]);
        
        }

        //dd($affected);
        //ENREGISTRER LE FICHIER DU CONTRAT
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $request->file;
        if( $fichier != null)
        {
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);

            if($extension != "pdf")
            {
                $message_error = 'Format de Fichier incorrect';
                return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service', 'message_error'));
                //redirect('contrat')->with('error', 'Modification effectuée');
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
                        'fichiers/contrat', $file_name
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
                    $message_error = 'Format de Fichier incorrect';
                    return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service', 'message_error'));
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

        //LE FICHIER DE BON DE COMMANDE
        $fichier_commande = $request->bon_commande;  
        if($fichier_commande != null)
        {
            //VERFIFIER LE FORMAT 
            
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier_commande->getClientOriginalName(), PATHINFO_EXTENSION);
            
            if($extension != "pdf")
            {
                $message_error = 'Format de Fichier incorrect';
                return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service', 'message_error'));
            }
            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_bon_commande = Contrat::where('id', $request->id_contrat)->get();
            foreach($get_bon_commande as $get_bon_commande)
            {
                if($get_bon_commande->bon_commande == null)
                {
                    //enregistrement de fichier dans la base
                    $file_name = $fichier_commande->getClientOriginalName();
                
                            
                    $path = $request->file('bon_commande')->storeAs(
                        'fichiers/bon_commande', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $request->id_contrat)
                    ->update([
                        'bon_commande'=> $path,
                        
                    ]);

                    
                }
                else
                {

                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    $get_path = Contrat::where('id', $Insert->id)->get();
                    foreach($get_path as $get_path)
                    {
                        Storage::delete($get_path->bon_commande);
                    }


                    $file_name = $fichier_commande->getClientOriginalName();
                
                            
                    $path = $request->file('bon_commande')->storeAs(
                        'fichiers/bon_commande', $file_name
                    );

                    $affected = DB::table('contrats')
                    ->where('id', $request->id_contrat)
                    ->update([
                        'bon_commande'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
        
        }

        $reconduction = $request->reconduction;
        $etat = $request->etat_contrat;
        $id_entreprise = $request->entreprise_filter;
        $service = $request->service;
        
        //APPLIQUER LZ FILTRE QUI ETAIT LA D'ABORD
        if($id_entreprise == "all")
        {
           
            //VERIFIER CE QUE L'UTILISATEUR A CHOISI POUR FILTRER
            if($request->reconduction == "c")//Il n'a PAS CHOISI RECONDUCTION
            {   
              
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        //dd('test2');
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
              
                        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                        ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('services.id', $request->service)
                        ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
            
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
    
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                       
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')     
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                           // return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                       
           
                    
                    }
                }
            }
            else // IL A CHOISI RECONDUCTION
            {
               
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //dd('ici2');
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->where('contrats.reconduction', $request->reconduction)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
                        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('contrats.reconduction', $request->reconduction)
                        ->where('services.id', $request->service)
                        ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                    
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            //dd('test1');
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')
                           
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->where('contrats.reconduction', $request->reconduction) 
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                       
           
                    
                    }
                }

            }

        }
        else
        {
            //IL A CHOISI UNE ENTREPRISE
          
            if($request->reconduction == "c")//Il n'a PAS CHOISI RECONDUCTION
            {
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->where('contrats.id_entreprise', $request->entreprise)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('contrats.id_entreprise', $request->entreprise)
                        ->where('services.id', $request->service)
                        ->get(['contrats.*',  'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->where('contrats.id_entreprise', $request->entreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    }
                }
            }
            else // IL A CHOISI RECONDUCTION
            {
                if($request->etat_contrat == "c")//PAS CHOISI ETAT CONTRAT
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                       //AUCUN CHOIX
                        $contrats = DB::table('contrats')
                
                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                        ->where('contrats.id_entreprise', $request->entreprise)
                        ->where('contrats.reconduction', $request->reconduction)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);
                        
                        //ON RETOURNE A LA PAGE CONTRAT
                       // return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                    else
                    {   
                        //SERVICE SEULEMENT EST CHOISI
                        $contrats = DB::table('prestations')
                        ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                        ->where('contrats.reconduction', $request->reconduction)
                        ->where('contrats.id_entreprise', $request->enntreprise)
                        ->where('services.id', $request->service)
                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                        //ON RETOURNE A LA PAGE CONTRAT
                        //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                    
                    }
                }
                else
                {
                    if($request->service == "service")//IL N'A PAS NON PLUS CHOISI LE SERVICE
                    {
                        //EN COURS OU TERMINE
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                           // return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('contrats')
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                            ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    
                    }
                    else
                    {   
                         //SERVICE EST CHOISI ET ETAT CONTRAT EST CHOISI
                        if($request->etat_contrat == 0)
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'>', date('Y-m-d'))
                            ->where('contrats.reconduction', $request->reconduction)
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                            //return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));
                        }
                        else
                        {
                            $contrats = DB::table('prestations')
                            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
                                ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                ->join('prestation_services', 'prestation_services.prestation_id', '=', 'prestations.id')
                                ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrats.fin_contrat' ,'<', date('Y-m-d'))
                            
                            ->where('contrats.id_entreprise', $request->enntreprise)
                            ->where('services.id', $request->service)
                            ->get(['contrats.*', 'entreprises.nom_entreprise', ]);

                            //ON RETOURNE A LA PAGE CONTRAT
                           // return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service'));

                        }
                    }
                }

            }
           
        }


        $message_success = 'Modification effecutée';
        return view('dash/all_contrats', compact('contrats', 'reconduction', 'etat', 'id_entreprise', 'service', 'message_success'));
        //return redirect('contrat')->with('success', 'Modification effectuée');

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
                          'fichiers/contrat', $file_name
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
                        
                                    
                            $path = $request->file('file_proforma')->storeAs(
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
                            
                                    
                            $path = $request->file('file_proforma')->storeAs(
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

    public function ViewBon(Request $request)
    {
        //dd($request->all());
        if(Storage::disk('local')->exists($request->file_bon))
        {
            return response()->file(Storage::path($request->file_bon));
        }
        else
        {
            return redirect('contrat')->with('error', 'Le fichier n\'existe pas');
        }

    }
}
