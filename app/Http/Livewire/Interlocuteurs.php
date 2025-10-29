<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;

use App\Models\Interlocuteur;
use App\Models\Interlocuteur_entr;

class Interlocuteurs extends Component
{
    use WithPagination; //POUR LA PAGINATION
    protected $paginationTheme = "bootstrap";
    public $search = ""; 
    public  $la;
    public $fonction, $entreprise;

    public $orderField = 'created_at';
    public $orderDirection = 'DESC';

    public $editInterlocuteur = []; //LA VARIABLE QUI RECUPERE LES DONNEES ENTREES DU FORMULAIRE

    public $editHasChanged;
    public $editOldValues = [];


    //FONCTION POUR FAIRE ORDRE DECROISSANT
    public function setOrderField($champ)
    {
       
        if($champ == $this->orderField)
        {
            if($this->orderDirection = 'ASC')
            {
                $this->orderDirection =  $this->orderDirection = 'DESC';
                //dump($champ." ".$this->orderDirection);
            }
            $this->orderDirection =  $this->orderDirection = 'DESC' ? 'ASC' : 'DESC';

        }
        else
        {
            $this->orderField = $champ;
            $this->orderDirection =  $this->orderDirection = 'DESC' ? 'ASC' : 'DESC';
            //$this->reset('orderDirection');
                
            //$this->reset('orderDirection');
        }

        //dd($this->orderDirection);
    }
    public function showUpdateButton()
    {
       // dd(editInterlocuteur['titre']);
        $this->editHasChanged = false;
        
       if(
            $this->editInterlocuteur['titre'] != $this->editOldValues['titre'] OR
            $this->editInterlocuteur['nom'] != $this->editOldValues['nom'] OR
            $this->editInterlocuteur['tel'] != $this->editOldValues['tel'] OR
            $this->editInterlocuteur['email'] != $this->editOldValues['email'] OR
            $this->editInterlocuteur['fonction'] != $this->editOldValues['fonction']  OR
            $this->editInterlocuteur['id_entreprise'] != $this->editOldValues['id_entreprise'] 
           
        )
        {
            $this->editHasChanged = true;
        }

        return $this->editHasChanged;
   
    }

    public function updateInterlocuteur()
    {
        $interlocuteur = Interlocuteur::find($this->editInterlocuteur['id']);

        $interlocuteur ->fill($this->editInterlocuteur);

        $interlocuteur ->save();

      
        $this->dispatchBrowserEvent('showSuccessMessage', ["message" => "Modification effectuée avec succès"]);

        $this->dispatchBrowserEvent("closeEditModal");
    }

    public function editInterlocuteur(Interlocuteur $interlocuteur)
    {
       
        $this->editInterlocuteur = $interlocuteur->toArray();
       
        $this->editOldValues = $this->editInterlocuteur; //Mettre les valeurs ancienne dedans editOldValues
        //dd( $this->editInterlocuteur);
        $this->dispatchBrowserEvent('showEditModal');
    }

    public function render()
    {
        if($this->editInterlocuteur != []) 
        {
            //Ca veut dire que des valeurs sont en train d'être modifié dans le formulaire de modification
            $this->showUpdateButton();
        }

        $interlocuteurQuery = Interlocuteur_entr::query();
       
        if($this->search != "")
        {
            $interlocuteurQuery->where("nom", "LIKE", "%".$this->search."%")
            ->orwhere('email', "LIKE", "%". $this->search."%")
            ->orwhere('nom_entreprise', "LIKE", "%". $this->search."%")
            ->orwhere('intitule', "LIKE", "%". $this->search."%")
            ->orwhere('nom_prenoms', "LIKE", "%". $this->search."%")
            ->orwhere('tel', "LIKE", "%". $this->search."%")
            ->orwhere('nom', "LIKE", "%". $this->search."%")
            ->orwhere('email', "LIKE", "%". $this->search."%");
           
        }

        if($this->fonction != "")
        {
            //dd('id');
            $interlocuteurQuery->where("fonction", $this->fonction);
           
        }

        if($this->entreprise != "")
        {
            //dd('id');
            $interlocuteurQuery->where("id_entreprise", $this->entreprise);
           
        }

        return view('livewire.interlocuteurs.index', 
        ['interlocuteurs' => $interlocuteurQuery->orderBy($this->orderField, $this->orderDirection)->paginate(8)])
        ->extends('layouts.base')
        ->section('content');
    }
}
