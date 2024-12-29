<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;
use Livewire\WithFileUploads;//POUR IMPORTER LES FICHIERS

use App\Http\Controllers\ContratController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\Calculator;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use App\Models\Contrat;

use App\Models\Service;

use App\Models\Prestation;
use App\Models\Prestation_service;

use DB;

class Contrats extends Component
{
    use WithPagination; //POUR LA PAGINATION
    protected $paginationTheme = "bootstrap";

    use WithFileUploads;
    public $jours;
    public $mois;
    public $annee;
    public $search = ""; 

    public $reconduction = "";
    public $id_entreprise = "";
    public $etat_contrat = "";
    public $service = "";

    public $editContrat = []; //LA VARIABLE QUI RECUPERE LES DONNEES ENTREES DU FORMULAIRE

    public $editHasChanged;
    public $editOldValues = [];

    //FUNCIOTN DE MODIFICATION
    public function EditContrat( Contrat $contrat)
    {
        //dd( $this->editContrat);
        $this->editContrat = $contrat->toArray();
      
        $this->editOldValues = $this->editContrat; //Mettre les valeurs ancienne dedans

        $this->dispatchBrowserEvent('showEditModal');
    }

    public function showUpdateButton()
    {
        //dd('ici');
        $this->editHasChanged = false;
        
       if(
            $this->editContrat['titre_contrat'] != $this->editOldValues['titre_contrat'] OR
            $this->editContrat['montant'] != $this->editOldValues['montant'] OR
            $this->editContrat['debut_contrat'] != $this->editOldValues['debut_contrat'] OR
            $this->editContrat['id_entreprise'] != $this->editOldValues['id_entreprise'] OR
            $this->editContrat['reconduction'] != $this->editOldValues['reconduction']  OR
            $this->editContrat['avenant'] != $this->editOldValues['avenant'] OR
            $this->editContrat['path'] != $this->editOldValues['path'] OR
            $this->editContrat['proforma_file'] != $this->editOldValues['proforma_file'] OR
            $this->editContrat['bon_commande'] != $this->editOldValues['bon_commande']
           
        )
        {
            $this->editHasChanged = true;
        }

        return $this->editHasChanged;
   
    }

    //FONCTION QUI VA FAIRE LA MODIF
    public function updateContrat()
    {
        //dd($this->editContrat);

        $contrat = Contrat::find($this->editContrat['id']);

        $calculator = new Calculator();
        
        //ATTENTION ON DOIT VOIR SI Y A EU DES PAIEMENTS ET RECUPERER LE TOTAL POUR ADAPTER AU NOUVEAU MONTANT SIPOSSIBLE

        //Récuperer tous les paiements du contrat et la somme totale
        $tot_paiement =   $calculator->SommePaiementContrat($this->editContrat['id']);

        //Faire la différence pour le reste_a_payer
        $rest = $this->editContrat['montant'] - $tot_paiement;
       
         //Calcul de la date de fin de contrat
         if($this->jours == "null" AND $this->mois == "null" AND $this->annee == "null")
         {
            $date_fin = "NULL";
         }
         else
         {
            $date_fin = $calculator->FinContrat($this->jours, $this->editContrat['debut_contrat'], $this->mois, $this->annee);
         }
        //dd($editContrat['montan']);
        //ENREGISTRER LE FICHIER DU CONTRAT
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $this->editContrat['path'];
       
         /*
        if($fichier != null)
        {   $expl = explode("/", $fichier);
            //dd($expl[2]);
           
        
            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Contrat::where('id', $this->editContrat['id'])->get();
            foreach($get_path as $get_path)
            {
                if($get_path->path == null)
                {
                    if(Storage::disk('local')->exists($this->editContrat['path']))
                    {
                        $extension = pathinfo($expl[2]->getClientOriginalName(), PATHINFO_EXTENSION);
                        //dd($fichier->getClientOriginalName());
                        if($extension != "pdf")
                        {
                            $this->dispatchBrowserEvent('showErrorMessage', ["message" => "Le format de fichier doit être PDF"]);
                        }
                        //enregistrement de fichier dans la base
                        $file_name = $expl[2]->getClientOriginalName();
                        
                        $path = $this->editContrat['path']->storeAs('fichiers/contrat',  $file_name);
                        
                        $affected = DB::table('contrats')
                        ->where('id', $this->editContrat['id'])
                        ->update([
                            'path'=> $path,
                            
                        ]);
                        //dd($affected);
                        
                    }
                    else
                    {

                    }
                }
                else
                {
                    if(Storage::disk('local')->exists($this->editContrat['path']))
                    {
                       
                    }
                    else
                    {
                        //dd($expl[2]);
                        $extension = pathinfo($expl[2]->getClientOriginalName(), PATHINFO_EXTENSION);
                        //dd($fichier->getClientOriginalName());
                        if($extension != "pdf")
                        {
                            $this->dispatchBrowserEvent('showErrorMessage', ["message" => "Le format de fichier doit être PDF"]);
                        }
                        $file_name = $expl[2]->getClientOriginalName();
                        
                        $path = $this->editContrat['path']->storeAs('fichiers/contrat',  $file_name);
    
                        $affected = DB::table('contrats')
                        ->where('id', $this->editContrat['id'])
                        ->update([
                            'path'=> $path,
                            
                        ]);
                        
                    } 
                    
                    
                }
            }
            
           
            
        }
        else
        {
           
        }

        //ENREGISTRER LA FACTURE PROFORMA
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier_proforma = $this->editContrat['proforma_file'];
        if( $fichier_proforma != null)
        {
            if(Storage::disk('local')->exists($this->editContrat['proforma_file']))
            {
                $expl = explode("/", $fichier_proforma);
                //VERFIFIER LE FORMAT 
                $extension = pathinfo($expl[2]->getClientOriginalName(), PATHINFO_EXTENSION);

                if($extension != "pdf")
                {
                    $this->dispatchBrowserEvent('showErrorMessage', ["message" => "Le format de fichier doit être PDF"]);
                }
                //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                $get_path_prof = Contrat::where('id', $this->editContrat['id'])->get();
                foreach($get_path_prof as $get_path_prof)
                {
                    if($get_path_prof->proforma_file == null)
                    {
                        //enregistrement de fichier dans la base
                        $file_name_prof =$expl[2]->getClientOriginalName();
                    
                      

                        $affected = DB::table('contrats')
                        ->where('id',  $this->editContrat['id'])
                        ->update([
                            'proforma_file'=> $path,
                            
                        ]);

                    }
                    else
                    {

                        //SUPPRESSION DE L'ANCIEN FICHIER
                        //dd($get_path->path);
                        $get_path_prof = Contrat::where('id', $this->editContrat['id'])->get();
                        foreach($get_path_prof as $get_path_prof)
                        {
                            Storage::delete($get_path_prof->proforma_file);
                        }


                        $file_name_prof = $expl[2]->getClientOriginalName();
                        
                        $path = $this->editContrat['proforma_file']->storeAs('factures/proforma',  $file_name);    
                    
                        $affected = DB::table('contrats')
                        ->where('id',  $this->editContrat['id'])
                        ->update([
                            'proforma_file'=> $path,
                            
                        ]);


                        
                    }
                }
            }
            else
            {

            }
        
        }
        else
        {
        
        }

        //LE FICHIER DE BON DE COMMANDE
        $fichier_commande = $this->editContrat['bon_commande'] ;
        if($fichier_commande != null)
        {
            if(Storage::disk('local')->exists($this->editContrat['bon_commande']))
            {
                //VERFIFIER LE FORMAT 
                $expl = explode("/", $fichier_commande);
                //VERFIFIER LE FORMAT 
                $extension = pathinfo( $expl[2]->getClientOriginalName(), PATHINFO_EXTENSION);
                
                if($extension != "pdf")
                {
                    $this->dispatchBrowserEvent('showErrorMessage', ["message" => "Le format de fichier doit être PDF"]);
                }
                //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
                $get_bon_commande = Contrat::where('id', $this->editContrat['id'])->get();
                foreach($get_bon_commande as $get_bon_commande)
                {
                    if($get_bon_commande->bon_commande == null)
                    {
                        //enregistrement de fichier dans la base
                        $file_name =  $expl[2]->getClientOriginalName();
                    
                        $path = $this->editContrat['bon_commande']->storeAs('fichiers/bon_commande',  $file_name);    
                        
                        $affected = DB::table('contrats')
                        ->where('id',  $this->editContrat['id'])
                        ->update([
                            'bon_commande'=> $path,
                            
                        ]);      
                        
                    }
                    else
                    {

                        //SUPPRESSION DE L'ANCIEN FICHIER
                        //dd($get_path->path);
                        $get_path = Contrat::where('id', $this->editContrat['id'])->get();
                        foreach($get_path as $get_path)
                        {
                            Storage::delete($get_path->bon_commande);
                        }

                        $file_name =  $expl[2]->getClientOriginalName();
                        
                        $path = $this->editContrat['bon_commande']->storeAs('fichiers/bon_commande',  $file_name);    
                    
                        $affected = DB::table('contrats')
                        ->where('id',  $this->editContrat['id'])
                        ->update([
                            'bon_commande'=> $path,
                            
                        ]);                          
                    }
                }
            }
            else
            {

            }
            
        }
        else
        {
        
        }*/

        //$contrat->fill($this->editContrat);

        $affected = DB::table('contrats')
        ->where('id',  $this->editContrat['id'])
        ->update([
            'titre_contrat'=>  $this->editContrat['titre_contrat'],
            'montant' =>  $this->editContrat['montant'], 
            'reste_a_payer' => $rest, 
            'debut_contrat' =>  $this->editContrat['debut_contrat'],
            'fin_contrat' => $date_fin,
            'id_entreprise' =>  $this->editContrat['id_entreprise'],
            'reconduction' =>  $this->editContrat['reconduction'],
            'avenant' =>  $this->editContrat['avenant'],
            'id_contrat_parent' =>  $this->editContrat['id_contrat_parent']
        ]);

 
        $this->dispatchBrowserEvent('showSuccessMessage', ["message" => "Modification effectuée avec succès"]);
 
        $this->dispatchBrowserEvent("closeEditModal");
    }

    public function updated()
    {
       // dd($this->etat_contrat);
    }

    public function render()
    {
       //dd($this->etat_contrat);
        $contratQuery = Contrat::query()
        //->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->orderBy('contrats.created_at', 'DESC');

        //dump($contratQuery);

        if($this->editContrat != []) 
        {
            //Ca veut dire que des valeurs sont en train d'être modifié dans le formulaire de modification
            $this->showUpdateButton();
        }

        if($this->search != "")
        {
           
            $contratQuery->where("titre_contrat", "LIKE", "%".$this->search."%");
        
           
        }

        if($this->id_entreprise != "")
        {
           
            $contratQuery->where("id_entreprise", $this->id_entreprise);
        
        }


        if($this->reconduction != "")
        {
            //dd('idi');
            $contratQuery->where("reconduction", $this->reconduction);
        
        }

        if($this->etat_contrat != "")
        {
           
            if($this->etat_contrat == 1)
            {
                //dd('p');
                $contratQuery->where("contrats.fin_contrat", "<", date('Y-m-d'));
            }
            else
            {
               //dd('pi');
                $contratQuery->where("contrats.fin_contrat", ">", date('Y-m-d'));
            }
           
        
           
        }

        if($this->service != "")
        {
            //dd($this->service);
            $prestationQuery = Prestation_service::query()
            ->join('contrats', 'prestation_services.contrat_id', '=', 'contrats.id')
           // ->join('services', 'prestation_services.service_id', '=', 'services.id')
            ->where('prestation_services.service_id', $this->service)
            ->orderBy('contrats.created_at', 'DESC');
            //sdump($prestationQuery);
            return view('livewire.contrats.index',  ['prestations' => $prestationQuery->paginate(8)])
            ->extends('layouts.base')
            ->section('content');
        }
        else
        {   
           

        }


        return view('livewire.contrats.index',  ['contrats' => $contratQuery->paginate(8)])
        ->extends('layouts.base')
        ->section('content');
    }
}
