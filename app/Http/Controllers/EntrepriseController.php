<?php
      /*
        <form class="form-horizontal">
                    
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-6 control-label"><b>TITRE DU CONTRAT :</b></label>
                    
                        <div class="col-sm-6">
                        <input type="text" class="form-control" disabled value="{{$contrats->titre_contrat}}">
                        </div>
                    
                    </div>
                    <div class="form-group">
                    <label class="col-sm-6 control-label"> <b>DEBUT DU CONTRAT :</b></label>
                    
                    
                        <div class="col-sm-6">
                        <input type="text" value="@php echo date('d/m/Y', strtotime($contrats->debut_contrat)) @endphp" class="form-control" disabled>
                        </div>
                    
                    </div>
                    <div class="form-group">
                        <label class="col-sm-6 control-label"><b>FIN DU CONTRAT :</b></label>
                    
                        <div class="col-sm-6">
                        <input class="form-control" disabled type="text" value="@php echo date('d/m/Y', strtotime($contrats->fin_contrat)) @endphp" >
                        </div>
                
                    </div>

                        <div class="form-group">
                        <label class="col-sm-6 control-label"><b>MONTANT :</b></label>
                    
                        <div class="col-sm-6">
                        <input class="form-control" disabled type="text" value="{{$contrats->montant}}" >
                        </div>
                
                    </div>
                
                </div>
    
        </form>
    */
    
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entreprise;

use DB;

use App\Models\Proposition;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class EntrepriseController extends Controller
{
    //Hendle Entreporise

    public function GetAll()
    {
        $get = DB::table('entreprises')
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
        ->orderBy('nom_entreprise', 'asc')
        ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

        return $get;
    }

    public function GetById($id)
    {
        $get = DB::table('entreprises')
        ->where('entreprises.id', $id)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
        ->orderBy('nom_entreprise', 'asc')
        ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

        return $get;
    }

    public function GetInactifs()
    {
        $get = DB::table('entreprises')
        ->where('entreprises.etat', 0)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->orderBy('nom_entreprise', 'asc')
        ->get(['entreprises.*', 'statutentreprises.libele_statut']);

        return $get;
    }

    public function GetActifs()
    {
        $get = DB::table('entreprises')
        ->where('entreprises.etat', 1)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->orderBy('nom_entreprise', 'asc')
        ->get(['entreprises.*', 'statutentreprises.libele_statut']);

        return $get;
    }

    public function TableFilter(Request $request)
    {
        $categorie = $request->categorie;
        $etat = $request->etat;
        //VERIFIER LES ELEMENTS QUE LE GARS A CHOISI
        //dd($request->all());
        if($request->categorie == "c")
        {
            if($request->etat == "c")
            {
                $entreprises = DB::table('entreprises')
                ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                ->orderBy('nom_entreprise', 'asc')
                ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

                //ON RETOURNE A LA PAGE CONTRAT
                return view('admin/entreprises', compact('entreprises', 'categorie', 'etat', ));
            }
            else
            {
               
                $entreprises = DB::table('entreprises')
                ->where('entreprises.etat', $request->etat)
                ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                ->orderBy('nom_entreprise', 'asc')
                ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

                //ON RETOURNE A LA PAGE CONTRAT
                return view('admin/entreprises', compact('entreprises', 'categorie', 'etat', ));         
               
            }

           
        }
        else
        {
            //dd('ici');
            if($request->categorie == "3")
            {
                //dd('l');
                $entreprises = DB::table('entreprises')
                ->where('entreprises.id_statutentreprise', $categorie)
                ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                ->orderBy('nom_entreprise', 'asc')
                ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

                //ON RETOURNE A LA PAGE CONTRAT
                return view('admin/entreprises', compact('entreprises', 'categorie', 'etat', ));
            }
            else
            {
               //dd('tt');
                if($request->etat == "c")
                {
                    //dd($statut);
                    $entreprises = DB::table('entreprises')
                    ->where('entreprises.id_statutentreprise', $categorie)
                    ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                    ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                    ->orderBy('nom_entreprise', 'asc')
                    ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

                    //ON RETOURNE A LA PAGE CONTRAT
                    return view('admin/entreprises', compact('entreprises', 'categorie', 'etat', ));
                }
                else
                {
                    //dd('la');
                    $entreprises = DB::table('entreprises')
                    ->where('entreprises.id_statutentreprise', $categorie)
                    ->where('entreprises.etat', $request->etat)
                    ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                    ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                    ->orderBy('nom_entreprise', 'asc')
                    ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

                    //ON RETOURNE A LA PAGE CONTRAT
                    return view('admin/entreprises', compact('entreprises', 'categorie', 'etat', ));
                }
              

              
            }

            
        }

    }

    public function AddEntreprise(Request $request)
    {
        //dd($request->all());
        $Insert = Entreprise::create([
           
            'nom_entreprise'=> $request->nom_entreprise,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'adresse' => $request->adresse,
            'activite' => $request->activite,
            'telephone' => $request->tel,
            'id_pays' => $request->pays,
            'etat' => 0,
            'id_statutentreprise' => 1,
             'created_by' => auth()->user()->id, 
        ]);

        //dd($request->chiffre);

        //Recuperer l'enregistrement
       $get = Entreprise::where('nom_entreprise', '=', $request->nom_entreprise)->get();

       return $get;
    }

    public function AddClient(Request $request)
    {
        //dd($request->all());
        $Insert = Entreprise::create([
           
            'nom_entreprise'=> $request->nom_entreprise,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'adresse' => $request->adresse,
            'activite' => $request->activite,
            'telephone' => $request->tel,
            'id_pays' => $request->pays,
            'etat' => 1,
            'id_statutentreprise' => 2,
            'client_depuis' => date('Y-m-d'),
             'created_by' => auth()->user()->id, 
        ]);

        //dd($request->chiffre);

        //Recuperer l'enregistrement
       $get = Entreprise::where('nom_entreprise', '=', $request->nom_entreprise)->get();

       return $get;
    }


    public function AddProspect(Request $request)
    {
        //dd($request->nom_entreprise);
        $Insert = Entreprise::create([
           
            'nom_entreprise' => $request->nom_entreprise,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'adresse' => $request->adresse,
            'activite' => $request->activite,
            'telephone' => $request->tel,
            'etat' => 0,
            'id_pays' => $request->pays,
            'adresse_email' => $request->email,
            'id_statutentreprise' => 1,
             'created_by' => auth()->user()->id, 
        ]);

        return redirect('prospects')->with('success', 'Enregistrement effectué');
    }

    public function DetectNewCustomer()
    {
        //RECUPER LES CLIENT OU LE STATUT EST DONC CLIENT DONC 2
        $get = Entreprise::where('id_statutentreprise', 2)
        ->orderBy('client_depuis', 'desc')
        ->get();
        return $get;
    }

    public function SaveEntreprise(Request $request)
    {
       //dd($request->statut );
        if(isset($request->entreprise) AND  $request->entreprise == 0)
        {
            return back()->with('error', 'Vous n\'avez pas choisi l\'entreprise');
        }

        //SI ON CHOISI LE STATUT CLIENT IL FAUT METTRE LA DATE
        if($request->statut == 2)
        { 
            $Insert = Entreprise::create([
           
                'nom_entreprise'=> $request->nom_entreprise,
                'id_statutentreprise' => $request->statut,
                'chiffre_affaire' => $request->chiffre, 
                'nb_employes' => $request->nb_emp,
                'etat' => $request->optionsradios,
                'adresse' => $request->adresse,
                'activite' => $request->activite,
                'telephone' => $request->tel,
                'id_pays' => $request->pays,
                 'created_by' => auth()->user()->id,
                 'client_depuis' => $request->depuis,
                 'adresse_email' => $request->email,

            ]);

    
        }
        else
        {
           
            $Insert = Entreprise::create([
           
                'nom_entreprise'=> $request->nom_entreprise,
                'id_statutentreprise' => $request->statut,
                'chiffre_affaire' => $request->chiffre, 
                'nb_employes' => $request->nb_emp,
                'etat' => $request->optionsradios,
                'adresse' => $request->adresse,
                'telephone' => $request->tel,
                'activite' => $request->activite,
                'id_pays' => $request->pays,
                'adresse_email' => $request->email,
                 'created_by' => auth()->user()->id,
            ]);

            
        }
      
        return redirect('entreprises')->with('success', 'Enregistrement effectué');
        
    }

    public function DisplayRecap(Request $request)
    {
        //dd($request->id_entreprise);
        return view('admin/entreprises',
            [
                'display_recap' => $request->id_entreprise,
            ]
        );
    }

    public function DisplayProspRecap(Request $request)
    {
        //dd($request->id_entreprise);
        return view('dash/prospects',
            [
                'display_recap' => $request->id_entreprise,
            ]
        );
    }


    public function EditEntrForm(Request $request)
    {
        //dd($request->id_entreprise);
        return view('admin/entreprises',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function EditEntrProspForm(Request $request)
    {
        //dd($request->id_entreprise);
        return view('dash/prospects',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }


    public function EditEntrActifForm(Request $request)
    {
        //dd($request->id_entreprise);
        return view('dash/list_actifs',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }


    public function EditEntrInactifForm(Request $request)
    {
        //dd($request->id_entreprise);
        return view('dash/list_inactifs',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }


    public function EditEntreprise(Request $request)
    {
       

        $affected= DB::table('entreprises')
        ->where('id', $request->id_entreprise)
        ->update([
           
            'nom_entreprise'=> $request->nom_entreprise,
            'id_statutentreprise' => $request->statut,
            'client_depuis' => $request->depuis,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'etat' => $request->optionsradios,
            'adresse' => $request->adresse,
            'telephone' => $request->tel,
            'activite' => $request->activite,
            'adresse_email' => $request->email,
        ]);

      
        return redirect('entreprises')->with('success', 'Modificaiton effectuée');
    }

    public function EditEntrepriseWithFilterList(Request $request)
    {
       //dd($request->all());

       //MODIFIE LA TABLE
        $affected= DB::table('entreprises')
        ->where('id', $request->id_entreprise)
        ->update([
           
            'nom_entreprise'=> $request->nom_entreprise,
            'id_statutentreprise' => $request->statut,
            'client_depuis' => $request->depuis,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'etat' => $request->optionsradios,
            'adresse' => $request->adresse,
            'telephone' => $request->tel,
            'activite' => $request->activite,
            'adresse_email' => $request->email,
        ]);

        //ON APPLIQUE A NOUVEAU LE FILTRE QUI ETAIT
        $categorie = $request->categorie;
        $etat = $request->etat;
        //VERIFIER LES ELEMENTS QUE LE GARS A CHOISI
        //dd($request->all());
        if($request->categorie == "c")
        {
            if($request->etat == "c")
            {
                $entreprises = DB::table('entreprises')
                ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                ->orderBy('nom_entreprise', 'asc')
                ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

              
            }
            else
            {
               
                $entreprises = DB::table('entreprises')
                ->where('entreprises.etat', $request->etat)
                ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                ->orderBy('nom_entreprise', 'asc')
                ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);
               
            }

           
        }
        else
        {
            //dd('ici');
            if($request->categorie == "3")
            {
                //dd('l');
                $entreprises = DB::table('entreprises')
                ->where('entreprises.id_statutentreprise', $categorie)
                ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                ->orderBy('nom_entreprise', 'asc')
                ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

            
            }
            else
            {
               //dd('tt');
                if($request->etat == "c")
                {
                    //dd($statut);
                    $entreprises = DB::table('entreprises')
                    ->where('entreprises.id_statutentreprise', $categorie)
                    ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                    ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                    ->orderBy('nom_entreprise', 'asc')
                    ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

                    //ON RETOURNE A LA PAGE CONTRAT
                    return view('admin/entreprises', compact('entreprises', 'categorie', 'etat', ));
                }
                else
                {
                    //dd('la');
                    $entreprises = DB::table('entreprises')
                    ->where('entreprises.id_statutentreprise', $categorie)
                    ->where('entreprises.etat', $request->etat)
                    ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
                    ->join('pays', 'entreprises.id_pays', '=', 'pays.id')
                    ->orderBy('nom_entreprise', 'asc')
                    ->get(['entreprises.*', 'statutentreprises.libele_statut', 'pays.nom_pays']);

                  
                }
              
            }
            
        }

       
        $message_success = "Modification effecutée";
      //ON RETOURNE A LA PAGE CONTRAT
      return view('admin/entreprises', compact('entreprises', 'categorie', 'etat', 'message_success'));
    }


    public function EditProspect(Request $request)
    {
       

        $affected= DB::table('entreprises')
        ->where('id', $request->id_entreprise)
        ->update([
           
            'nom_entreprise'=> $request->nom_entreprise,
            'activite' => $request->activite,
            'client_depuis' => $request->depuis,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'adresse' => $request->adresse,
            'telephone' => $request->tel,
             'adresse_email' => $request->email,
        ]);

      
        return redirect('prospects')->with('success', 'Modificaiton effectuée');
    }

    public function EditEntrInactif(Request $request)
    {
       

        $affected= DB::table('entreprises')
        ->where('id', $request->id_entreprise)
        ->update([
           
            'nom_entreprise'=> $request->nom,
            'id_statutentreprise' => $request->statut,
            'client_depuis' => $request->depuis,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'etat' => $request->optionsradios,
            'activite' => $request->activite,
            'adresse' => $request->adresse,
            'telephone' => $request->tel,
            'adresse_email' => $request->email,
        ]);

      
        return redirect('inactifs')->with('success', 'Modificaiton effectuée');
    }

    public function EditEntrActif(Request $request)
    {
       

        $affected= DB::table('entreprises')
        ->where('id', $request->id_entreprise)
        ->update([
           
            'nom_entreprise'=> $request->nom,
            'id_statutentreprise' => $request->statut,
            'client_depuis' => $request->depuis,
            'chiffre_affaire' => $request->chiffre, 
            'nb_employes' => $request->nb_emp,
            'activite' => $request->activite,
            'etat' => $request->optionsradios,
            'adresse' => $request->adresse,
            'telephone' => $request->tel,
             'adresse_email' => $request->email,
        ]);

      
        return redirect('actifs')->with('success', 'Modificaiton effectuée');
    }

    public function DisplayCustomers()
    {
        $get = DB::table('entreprises')
        ->where('entreprises.id_statutentreprise', 2)
        
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->get(['entreprises.*', 'statutentreprises.libele_statut']);

        return $get;
    }

    public function DisplayProspects()
    {
        $get = DB::table('entreprises')
        ->where('entreprises.id_statutentreprise', 1)
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->join('utilisateurs', 'entreprises.created_by', '=', 'utilisateurs.id')
        ->get(['entreprises.*', 'statutentreprises.libele_statut', 'utilisateurs.nom_prenoms']);

        return $get;
    }

    Public function DeleteEntreprise(Request $request)
    {
        $deleted = DB::table('entreprises')->where('id', '=', $request->id_entreprise)->delete();

        //SUPPRIMER TOUTES LES PROPOSITIONs DE CE CLIENT
        $propal_customer = DB::table('propositions')->get();
        foreach($propal_customer as $propal_customer)
        {
            $deleted_propal = DB::table('propositions')->where('id_client', '=', $request->id_entreprise)->delete();

            if($deleted_propal != 0)
            {
                //SUPPRIMER LE FICHIER DANS LE DOSSIER
                Storage::delete($propal_customer->path_doc);
            }
        }

        return redirect('entreprises')->with('success', 'Elément supprimé');
    }

    Public function DeleteProspect(Request $request)
    {
        $deleted = DB::table('entreprises')->where('id', '=', $request->id_entreprise)->delete();

        //SUPPRIMER TOUTES LES PROPOSITIONs DE CE CLIENT
        $propal_customer = DB::table('propositions')->get();
        foreach($propal_customer as $propal_customer)
        {
            $deleted_propal = DB::table('propositions')->where('id_client', '=', $request->id_entreprise)->delete();
            
            if($deleted_propal != 0)
            {
                //SUPPRIMER LE FICHIER DANS LE DOSSIER
                Storage::delete($propal_customer->path_doc);
            }

            
        }

        return redirect('prospects')->with('success', 'Elément supprimé');
    }

    public function GetAboutThisTable(Request $request)
    {
        
        return view('dash/about_this',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GetProspAboutThisTable(Request $request)
    {
        
       /* return view('dash/prosp_about_this',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );*/

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }
    
    //IMPRIMER LE RAPPORT 
    public function GoRapport(Request $request)
    {
        
        return view('dash/print_fiche',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GoRapportClient(Request $request)
    {
        
        return view('dash/print_fiche_customer',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GetFicheCustomer(Request $request)
    {
        
        return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->id_entreprise,
            ]
        );
    }

    public function GetAboutThis($id)
    {
        $get = DB::table('contrats')
        ->where('entreprises.id', $id)
    
        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
        ->join('statutentreprises', 'entreprises.id_statutentreprise', '=', 'statutentreprises.id')
        ->join('prospections', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('services', 'prospections.service_propose', '=', 'services.id')
        ->get(['entreprises.*', 'statutentreprises.libele_statut', ]);

        return $get;
    }

}
