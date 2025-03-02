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
use App\Models\Contrat_entr_service;
use App\Models\Prest_service_contrat;

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

    public $compare = ""; 
    public $annee_depuis = "";

    public $editContrat = []; //LA VARIABLE QUI RECUPERE LES DONNEES ENTREES DU FORMULAIRE

    public $editHasChanged;
    public $editOldValues = [];

    public $orderField = 'created_at';
    public $orderDirection = 'DESC';

    //FONCTION POUR FAIRE ORDRE DECROISSANT
    public function setOrderField($champ)
    {
        
        if($champ == $this->orderField)
        {
            if($this->orderDirection = 'ASC')
            {
                $this->orderDirection = 'DESC';
            }
            $this->orderDirection =  $this->orderDirection = 'DESC' ? 'ASC' : 'DESC';
            
        }
        else
        {
  
            $this->orderField = $champ;
            $this->orderDirection =  $this->orderDirection = 'DESC' ? 'ASC' : 'DESC';
            
            $this->reset('orderDirection');

        }
        //return $la;
    }
 

    //FUNCIOTN DE MODIFICATION
    public function EditContrat( Contrat $contrat)
    {
        //dd($contrat->toArray());
        $this->editContrat = $contrat->toArray();
      
        $this->editOldValues = $this->editContrat; //Mettre les valeurs ancienne dedans

        $this->dispatchBrowserEvent('showEditModal');
    }
    public function update()
    {
        //dd($this->editOldValues['annees']);
    }

    public function showUpdateButton()
    {
        
        $this->editHasChanged = false;
        
       if(
            
            $this->editContrat['titre_contrat'] != $this->editOldValues['titre_contrat'] OR
            $this->editContrat['montant'] != $this->editOldValues['montant'] OR
            $this->editContrat['debut_contrat'] != $this->editOldValues['debut_contrat'] OR
            $this->editContrat['jours'] != $this->editOldValues['jours'] OR
            $this->editContrat['mois'] != $this->editOldValues['mois'] OR
            $this->editContrat['annees'] != $this->editOldValues['annees'] OR
            $this->editContrat['id_entreprise'] != $this->editOldValues['id_entreprise'] OR
            $this->editContrat['reconduction'] != $this->editOldValues['reconduction']  OR
            $this->editContrat['avenant'] != $this->editOldValues['avenant'] OR
            $this->editContrat['path'] != $this->editOldValues['path'] OR
            $this->editContrat['proforma_file'] != $this->editOldValues['proforma_file'] OR
            $this->editContrat['bon_commande'] != $this->editOldValues['bon_commande'] OR 
            $this->editContrat['fin_contrat'] != $this->editOldValues['fin_contrat'] OR
            $this->editContrat['id_type_prestation'] != $this->editOldValues['id_type_prestation'] OR
            $this->editContrat['etat'] != $this->editOldValues['etat']
            /*OR
            $this->editContrat['jours'] !== $this->editOldValues['jours'] OR
            $this->editContrat['mois'] !== $this->editOldValues['mois'] OR
            $this->editContrat['annees'] !== $this->editOldValues['annees'] OR
            $this->editContrat['jours'] != $this->editOldValues['jours'] OR
            $this->editContrat['mois'] != $this->editOldValues['mois'] OR
            $this->editContrat['annee'] != $this->editOldValues['annee']*/
           
        )
        {
            //dd('id');
            $this->editHasChanged = true;
        }
       
        //dump($this->editContrat['annees'] == $this->editOldValues['annees']);
        return $this->editHasChanged;
   
    }

    //FONCTION QUI VA FAIRE LA MODIF
    public function updateContrat()
    {
        //dd(intval($this->editContrat['annees']));
        $jours = intval($this->editContrat['jours']);
        $annees = intval($this->editContrat['annees']);
        $mois = intval($this->editContrat['mois']);
       
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
           
        }
        else
        {
            if($this->editContrat['fin_contrat'] <= date('Y-m-d'))//EST CE QUE LA DATE DE FIN EST DEPASSEE
            {
                if($this->editContrat['reconduction'] == 1)//SI AUSSI C'EST RECONDUIT
                {
                    $date_fin = $calculator->FinContrat($jours, $this->editContrat['fin_contrat'], $mois, $annees);
                }
                else
                {
                    //ON MODIFIE JUSTE EN FONCTION DU JOURS QUI EST ENTRE 
                    $date_fin = $calculator->FinContrat($jours, $this->editContrat['debut_contrat'], $mois, $annees);
                }
                
            }
            else
            {
                //ON MODIFIE JUSTE EN FONCTION DU JOURS QUI EST ENTRE 
                $date_fin = $calculator->FinContrat($jours, $this->editContrat['debut_contrat'], $mois, $annees);
            }
            
           
        }
        //dd($date_fin);
        //ENREGISTRER LE FICHIER DU CONTRAT
        //IL FAUT SUPPRIMER L'ANCIEN FICHIER DANS LE DISQUE DUR
        $fichier = $this->editContrat['path'];
       
        
        $affected = DB::table('contrats')
        ->where('id',  $this->editContrat['id'])
        ->update([
            'titre_contrat'=>  $this->editContrat['titre_contrat'],
            'montant' =>  $this->editContrat['montant'], 
            'reste_a_payer' => $rest, 
            'debut_contrat' =>  $this->editContrat['debut_contrat'],
            'jours' => $this->editContrat['jours'],
            'mois' =>  $this->editContrat['mois'],
            'annees' =>  $this->editContrat['annees'],
            'fin_contrat' => $date_fin,
            'id_entreprise' =>  $this->editContrat['id_entreprise'],
            'reconduction' =>  $this->editContrat['reconduction'],
            'avenant' =>  $this->editContrat['avenant'],
            'etat' =>  $this->editContrat['etat'],
            //'id_contrat_parent' =>  $this->editContrat['id_contrat_parent'],
            'id_type_prestation' =>  $this->editContrat['id_type_prestation']
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
        $contratQuery = Contrat_entr_service::query()
        //->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
       ;

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
                $contratQuery->where('etat', $this->etat_contrat);
            }
            else
            {
               //dd('pi');
                $contratQuery->where('etat', $this->etat_contrat);
            }
        }

        if($this->service != "")
        {
           
            //dd($this->service);
            //dd($prestationQuery = Prest_service_contrat::query()->latest());
            $prestationQuery = Prest_service_contrat::query()
            //->join('contrats', 'prestation_services.contrat_id', '=', 'contrats.id')
           // ->join('services', 'prestation_services.service_id', '=', 'services.id')
            ->where('service_id', $this->service);
            
            //sdump($prestationQuery);
            return view('livewire.contrats.index',  ['prestations' => $prestationQuery->orderBy($this->orderField, $this->orderDirection)->paginate(8)])
            ->extends('layouts.base')
            ->section('content');
        }
        else
        {   
        
        }

        if($this->compare != "" AND $this->annee_depuis != "")
        {
            
            if($this->compare == "=")
            {
                $annee = $this->annee_depuis."-01-01";
                $annee_f = $this->annee_depuis."-12-31";
                $contratQuery->where("debut_contrat", '<', $annee_f)->where("debut_contrat", '>', $annee);
            }
            elseif($this->compare == "<")
            {
                $annee = $this->annee_depuis."-01-01";
                $contratQuery->where("debut_contrat", $this->compare,  $annee);
            }
            else
            {
                $annee = $this->annee_depuis."-12-31";
                $contratQuery->where("debut_contrat", $this->compare,  $annee);
            }
            
        }
        

        return view('livewire.contrats.index',  ['contrats' => $contratQuery->orderBy($this->orderField, $this->orderDirection)->paginate(6)])
        ->extends('layouts.base')
        ->section('content');
    }
}
