<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Calculator;

use App\Http\Controllers\EntrepriseController;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


use App\Models\Contrat;

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

        return back()->with('success', 'Enregistrement effectué');
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
        ->join('prestations', 'prestations.id_contrat', '=', 'contrats.id')
        ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
        
        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', 
         'typeprestations.libele']);

   
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
        $tot_paiement =   $calculator->SommePaiementContrat($request->d_contrat);

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

        return redirect('contrat')->with('success', 'Modification effectuée');

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
            return Storage::download($request->file);
        }
        else
        {
            return redirect('contrat')->with('error', 'Le fichier n\'existe pas');
        }

    }
}
