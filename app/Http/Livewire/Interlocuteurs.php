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
            //dd('ici');
            $this->orderField = $champ;
            $this->orderDirection =  $this->orderDirection = 'DESC' ? 'ASC' : 'DESC';
            //$this->reset('orderDirection');
                
            //$this->reset('orderDirection');
        }

        //dd($this->orderDirection);
    }


    public function render()
    {
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
