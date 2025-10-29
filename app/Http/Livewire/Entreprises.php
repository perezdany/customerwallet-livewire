<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;

use App\Models\Entreprise;

use App\Models\Service;

use App\Http\Controllers\EntrepriseController;

class Entreprises extends Component
{
    use WithPagination; //POUR LA PAGINATION
    protected $paginationTheme = "bootstrap";

    public $search = ""; 
    public $compare = ""; 
    public $annee_depuis = "";
    public $editEntreprise = []; //LA VARIABLE QUI RECUPERE LES DONNEES ENTREES DU FORMULAIRE

    public $entrepriseDetail = []; //POUR LES DETAILS DE L'ENTREPRISE

    public $categorie= "", $etat="";

    public $orderField = 'created_at';
    public $orderDirection = 'DESC';

    protected $rules = [
        'editEntreprise.nom_entreprise' => 'required|max:100|string|unique:nom_entreprise',
        'editEntreprise.adresse' => 'required|max:60|string',
        'editEntreprise.id_statutentreprise' => 'required',
        'editEntreprise.telephone' => 'required|string|min:14',
       
    ];
    
    public $editHasChanged;
    public $editOldValues = [];

    
    //FONCTION POUR FAIRE ORDRE DECROISSANT
    public function setOrderField($champ)
    {
        
        if($champ == $this->orderField)
        {
            $this->orderDirection =  $this->orderDirection = 'DESC' ? 'ASC' : 'DESC';
        }
        else
        {
            $this->orderField = $champ;
            $this->orderDirection =  $this->orderDirection = 'DESC' ? 'ASC' : 'DESC';
            //dump($this->orderDirection);
            $this->reset('orderDirection');

        }
        //return $la;
    }

  
    public function showUpdateButton()
    {
        //dd('ici');
        $this->editHasChanged = false;
        
        if(
            $this->editEntreprise['nom_entreprise'] != $this->editOldValues['nom_entreprise'] OR
            $this->editEntreprise['adresse'] != $this->editOldValues['adresse'] OR
            $this->editEntreprise['chiffre_affaire'] != $this->editOldValues['chiffre_affaire'] OR
            $this->editEntreprise['nb_employes'] != $this->editOldValues['nb_employes'] OR
            $this->editEntreprise['telephone'] != $this->editOldValues['telephone']  OR
            $this->editEntreprise['mobile'] != $this->editOldValues['mobile']  OR
            $this->editEntreprise['adresse_email'] != $this->editOldValues['adresse_email'] OR
            $this->editEntreprise['activite'] != $this->editOldValues['activite'] OR
            $this->editEntreprise['etat'] != $this->editOldValues['etat'] OR
            $this->editEntreprise['id_statutentreprise'] != $this->editOldValues['id_statutentreprise'] OR
            $this->editEntreprise['site_web'] != $this->editOldValues['site_web'] OR
            $this->editEntreprise['dirigeant'] != $this->editOldValues['dirigeant'] OR
            $this->editEntreprise['particulier'] != $this->editOldValues['particulier'] OR
            $this->editEntreprise['date_creation'] != $this->editOldValues['date_creation'] 
        )
        {
            $this->editHasChanged = true;
        }

        return $this->editHasChanged;
   
    }

    public function render()
    {
        $entrepriseQuery = Entreprise::query();

        if($this->search != "")
        {
            $entrepriseQuery->where("nom_entreprise", "LIKE", "%".$this->search."%")
            ->orwhere('adresse', "LIKE", "%". $this->search."%");
        }
    
        if($this->categorie != "")
        {
            //dd('id');
            $entrepriseQuery->where("id_statutentreprise", $this->categorie);
           
        }

        if($this->etat != "")
        {
            //dd('o');
            $entrepriseQuery->where("etat", $this->etat);
        }
      
       
        if($this->editEntreprise != []) 
        {
            //Ca veut dire que des valeurs sont en train d'être modifié dans le formulaire de modification
            $this->showUpdateButton();
        }

        if($this->compare != "" AND $this->annee_depuis != "")
        {
            
            if($this->compare == "=")
            {
                $annee = $this->annee_depuis."-01-01";
                $annee_f = $this->annee_depuis."-12-31";
                $entrepriseQuery->where("client_depuis", '<', $annee_f)->where("client_depuis", '>', $annee);
            }
            elseif($this->compare == "<")
            {
               
                $annee = $this->annee_depuis."-01-01";
                $entrepriseQuery->where("client_depuis", $this->compare,  $annee);
            }
            else
            {
                $annee = $this->annee_depuis."-12-31";
                $entrepriseQuery->where("client_depuis", $this->compare,  $annee);
            }
            
        }
        
    
        return view('livewire.entreprises.index',  
            ['entreprises' => $entrepriseQuery->orderBy($this->orderField, $this->orderDirection)->paginate(10)])
            ->extends('layouts.base')
            ->section('content');
           
    }


    public function EditEntreprise(Entreprise $entreprise)
    {
        $this->editEntreprise = $entreprise->toArray();
      
        $this->editOldValues = $this->editEntreprise; //Mettre les valeurs ancienne dedans

        //VOIR SI C'EST UN PARTICULIER ET AFFICHER SON FORMULAIRE
        if($this->editOldValues['particulier'] == "0")
        {
            $this->dispatchBrowserEvent('showEditModal');

        }
        else
        {   
            //dd('ici');
            $this->dispatchBrowserEvent('showEditModalParticulier');
        }
        //$this->dispatchBrowserEvent("closeEditModal");

    }

    //FONCTION PIOUR AFFIHCER LES INTYERLOCUTEURS
    public function showInterlocuteurs()
    {
        //dd('i');
        $this->dispatchBrowserEvent('showInterlocuteursModal');
    }

  
    public function updateEntreprise()
    {
        //$this->validate();//VALIDATION DES INFOS

        $entreprise = Entreprise::find($this->editEntreprise['id']);

       // dd($this->editEntreprise);

        $entreprise->fill($this->editEntreprise);

        $entreprise->save();

        $this->dispatchBrowserEvent('showSuccessMessage', ["message" => "Modification effectuée avec succès"]);

        $this->dispatchBrowserEvent("closeEditModal");
        $this->dispatchBrowserEvent("closeEditModalParticulier");
       
    }

    public function Detail(Entreprise $entreprise)
    {
        //dd('iic');
        $this->entrepriseDetail = $entreprise->toArray();
        //dd($this->entrepriseDetail['particulier']);
        if($this->entrepriseDetail['particulier'] == 0)
        {
            $this->dispatchBrowserEvent('showDetail');
        }
        else
        {
            //dd('ici');
            $this->dispatchBrowserEvent('showDetailParticulier');
        }
        
    }

    public function closeEditModal()
    {
        $this->dispatchBrowserEvent('closeEditModal');
    }

    public function confirmDelete($nom_entreprise, $id)
    {
        $this->dispatchBrowserEvent('showConfirmMessage', 
        
        ["message" => [
            "text" => "Vous êtes sur le point de supprimer $nom_entreprise de la base de données.",
            "title" => "Êtes vous sûre de continuer?",
            "type" => "warning",
            "data" => ["id_entreprise" => $id]
            ]
        
        ]);
    }

    public function deleteEntreprise($id)
    {
        //PASSER A LA SUPPRESSION

        $try_delete = (new EntrepriseController())->TryDelete($id);
        //dd($try_delete);
        if($try_delete)
        {
            //dd('ici');
            Entreprise::destroy($id);
            $this->dispatchBrowserEvent('showSuccessMessage', ["message" => "Elément supprimé avec succès !"]);
        }
        else
        {
            //dd('icio');
            $this->dispatchBrowserEvent('showErrorMessage', ["message" => "Vous ne pouvez pas suppriemer cette entreprise car elle a des contrats!"]);
           
        }
       
    }
}