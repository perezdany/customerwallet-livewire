<?php

namespace App\Http\Livewire;

use Livewire\Component;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

use DB;
use App\Models\Facture;
use App\Models\Contrat;

use Livewire\WithPagination;
use Livewire\WithFileUploads;//POUR IMPORTER LES FICHIERS

class Factures extends Component
{
    use WithPagination; //POUR LA PAGINATION
    protected $paginationTheme = "bootstrap";

    use WithFileUploads;
    public $editFacture = []; //LA VARIABLE QUI RECUPERE LES DONNEES ENTREES DU FORMULAIRE

    public $editHasChanged;
    public $editOldValues = [];

    public $id_entreprise = "";
    public $search = ""; 
    public $id_contrat = "";

    public $etat = "";
    public $annulee = "";


    public function closeEditModal()
    {
        $this->dispatchBrowserEvent('closeEditModal');
    }

    public function confirmDelete($numero_facture, $id)
    {
        $this->dispatchBrowserEvent('showConfirmMessage', 
        
        ["message" => [
            "text" => "Vous êtes sur le point de supprimer la facture N° $numero_facture de la base de données.",
            "title" => "Êtes vous sûre de continuer?",
            "type" => "warning",
            "data" => ["id_facture" => $id]
            ]
        
        ]);
    }

    public function deleteFacture($id)
    {
        Facture::destroy($id);
        $this->dispatchBrowserEvent('showSuccessMessage', ["message" => "Elément supprimé avec succès !"]);
    }

    public function EditFacture(Facture $facture)
    {
        //dd( $this->editFacture);
        $this->editFacture = $facture->toArray();
      
        $this->editOldValues = $this->editFacture; //Mettre les valeurs ancienne dedans editOldValues

        $this->dispatchBrowserEvent('showEditModal');
    }

    public function showUpdateButton()
    {
        //dd('ici');
        $this->editHasChanged = false;
        
       if(
            $this->editFacture['numero_facture'] != $this->editOldValues['numero_facture'] OR
            $this->editFacture['date_emission'] != $this->editOldValues['date_emission'] OR
            $this->editFacture['montant_facture'] != $this->editOldValues['montant_facture'] OR
            $this->editFacture['id_contrat'] != $this->editOldValues['id_contrat'] OR
            $this->editFacture['file_path'] != $this->editOldValues['file_path']  OR
            $this->editFacture['annulee'] != $this->editOldValues['annulee'] 
           
        )
        {
            $this->editHasChanged = true;
        }

        return $this->editHasChanged;
   
    }

    public function updateFacture()
    {
        $affected = DB::table('factures')
        ->where('id',$this->editFacture['id'])
        ->update([ 'numero_facture' => $this->editFacture['numero_facture'], 
            
            'date_emission' => $this->editFacture['date_emission'], 
            'montant_facture' => $this->editFacture['montant_facture'], 
            'id_contrat' => $this->editFacture['id_contrat'],
            'annulee' => $this->editFacture['annulee']
        ]);

        
       /* $fichier = $this->editFacture['file_path'];
        //dd($this->editFacture);

        if($fichier != null)
        {
            
            //VERFIFIER LE FORMAT 
            $extension = pathinfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION);
            
            //dd($extension);
            if($extension != "pdf")
            {
                $this->dispatchBrowserEvent('showErrorMessage', ["message" => "Le format de fichier doit être PDF"]);
               
            }

            //VERIFIER SI L'ENREGISTREMENT A UN CHEMIN D'ACCES ENREGISTRE
            $get_path = Facture::where('id', $this->editFacture['id'])->get();
            foreach($get_path as $get_path)
            {
                if($get_path->file_path == null)
                {
                    //enregistrement de fichier dans la base
                    $file_name = $fichier->getClientOriginalName();
                
                    $path = $this->editContrat['file_path']->storeAs('factures',  $file_name);    
                            
                 

                    $affected = DB::table('factures')
                    ->where('id', $this->editFacture['id'])
                    ->update([
                        'file_path'=> $path,
                        
                    ]);

                    
                }
                else
                {
                    $get_path = Facture::where('id', $this->editFacture['id'])->get();
                    //SUPPRESSION DE L'ANCIEN FICHIER
                    //dd($get_path->path);
                    foreach($get_path as $get_path)
                    {
                        Storage::delete($get_path->file_path);
                    }
                   
                    $file_name = $fichier->getClientOriginalName();
                       
                    $path = $this->editContrat['file_path']->storeAs('factures',  $file_name);    
                            
               

                    $affected = DB::table('factures')
                    ->where('id', $this->editFacture['id'])
                    ->update([
                        'file_path'=> $path,
                        
                    ]);

                    
                }
            }
            
        }
        else
        {
            //dd($fichier);
        }*/

        $this->dispatchBrowserEvent('showSuccessMessage', ["message" => "Modification effectuée avec succès"]);

        $this->dispatchBrowserEvent("closeEditModal");
    }

   public function FilterByEntreprise()
   {
        //dd('ici');
        if($this->id_entreprise != "")
        {
            //dd($this->id_entreprise);
            $get = DB::table('factures')
            ->join('prestations', 'factures.id_contrat', '=', 'prestations.id')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')    
            ->get(['factures.*', 'prestations.localisation', 'prestations.id_contrat',
            'contrats.titre_contrat', 'entreprises.nom_entreprise'])
            ->where('entreprises.id', $this->id_entreprise);

        // dd($factureQuery);

            return view('admin/facture_by_client', ['by_entreprise' => $get]);
            
        }
        else
        {
            $factureQuery = Facture::query()
            ->latest();
        }

   }
    public function render()
    {
        if($this->editFacture != []) 
        {
            //Ca veut dire que des valeurs sont en train d'être modifié dans le formulaire de modification
            $this->showUpdateButton();
        }


        $factureQuery = Facture::query();


        if($this->search != "")
        {
            //sdd($this->search);
            $factureQuery->where("numero_facture", "LIKE", "%".$this->search."%");
        }

        if($this->etat != "")
        {
            //sdd($this->search);
            $factureQuery->where("reglee",  $this->etat);
        }

        if($this->annulee != "")
        {
            //sdd($this->search);
            $factureQuery->where("annulee",  $this->annulee);
        }

        if($this->id_contrat != "")
        {
            //sdd($this->search);
            $factureQuery->where("id_contrat", $this->id_contrat);
        }

        //dump($factureQuery);
        return view('livewire.factures.index', ['factures' => $factureQuery->latest()->paginate(8) ])
        ->extends('layouts.base')
        ->section('content');
    }
}
