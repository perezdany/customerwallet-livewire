<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Livewire\WithPagination;

use App\Models\Interlocuteur;

class Interlocuteurs extends Component
{
    use WithPagination; //POUR LA PAGINATION
    protected $paginationTheme = "bootstrap";
    public $search = ""; 

    public $fonction, $entreprise;

    public function render()
    {
        $interlocuteurQuery = Interlocuteur::query();

        if($this->search != "")
        {
            $interlocuteurQuery->where("nom", "LIKE", "%".$this->search."%")
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

        return view('livewire.interlocuteurs.index', ['interlocuteurs' => $interlocuteurQuery->latest()->paginate(8)])
        ->extends('layouts.base')
        ->section('content');
    }
}
