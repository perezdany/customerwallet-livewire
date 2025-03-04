@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\ProspectionController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\InterlocuteurController;

    use App\Http\Controllers\DocController;

    use App\Http\Controllers\CategorieController;

    use App\Http\Controllers\PropalController;

    use App\Http\Controllers\SuiviController;

    $contratcontroller = new ContratController();
    $entreprisecontroller = new EntrepriseController();
    $prestationcontroller = new PrestationController();
    $prospectioncontroller = new ProspectionController();
    $facturecontroller = new FactureController();
    $interlocuterController = new InterlocuteurController();
    $documentController = new DocController();
    $categoriecontroller = new CategorieController();
    $servicecontroller = new ServiceController();
    $propalcontroller = new PropalController();
    $suivicontroller = new SuiviController();

@endphp

@section('content')
   
    <div class="row">
      
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="col-md-3">
                <a href="prospects"><button class="btn btn-default"> <b>RETOUR</b></button></a>
            </div>
            @can("edit")
                 <div class="col-md-3">
                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add"><b><i class="fa fa-plus"></i>INTERLOCUTEUR</b></button>
                </div>
            @endcan
            @can("manager-commercial")
                <div class="col-md-3">
                    <a href="form_add_prospection"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i>PROSPECTION</b></button></a>
                </div>
               
            @endcan

             @can("commercial")
                <div class="col-md-3">
                    <a href="form_add_prospection"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i>PROSPECTION</b></button></a>
                </div>
               
            @endcan

            @can("manager")
                <div class="col-md-3">
                    <a href="form_add_prospection"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i>PROSPECTION</b></button></a>
                </div>
            
            @endcan

            @can("admin")
                <div class="col-md-3">
                    <a href="form_add_prospection"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i>PROSPECTION</b></button></a>
                </div>
               
            @endcan
            

             <div class="col-md-3"><a href="form_add_prospection">
                @if(isset($id_entreprise))

                   <form method="post" action="go_print_rapport" target="blank"> 
                            @csrf
                            <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                            <button class="btn btn-success"> <b>RAPPORT FICHE</b></button></a>
                    </form>
                @endif
               
             </div>
        </div>
        <div class="col-md-2"></div>
    </div><br>
   
    @if(isset($id_entreprise))
        
        @php
            //dd('ici');
            $prospections = $prospectioncontroller->GetProspectionByIdEntr($id_entreprise);
           //dd($prospections);
            $count_prospection = $prospections->count();
            //dd($count_prospection);
        @endphp

        <div class="row">
          
            <!--<div class="col-md-2"></div>-->
            <!-- left column -->
            <div class="col-md-8">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    @can("edit")
                        <div class="box-body">
                            <form action="go_contrat_form" method="post" >
                                    @csrf
                                    <input type="text" value={{$id_entreprise}} style="display:none;" name="id_entreprise">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-plus"></i>CONTRAT</button>
                            </form>
                        </div>
                    @endcan
                    <div class="box-header with-border" style="text-align:center">
                        @php
                            $nom = $entreprisecontroller->GetById($id_entreprise);

                        @endphp
                        @foreach($nom as $nom)
                                <h3 class="box-title"><b>{{$nom->nom_entreprise}}</b>
                                    @if($nom->etat == 0)
                                        <span class="bg-red">INACTIF</span>
                                    @else
                                    @endif
                                </h3><br>
                                    <h4 class="box-title"><b>ORDRE D'AFFICHAGE DES INFORMATIONS:</b>
                                Services proposés--Facture proforma--Compte rendu de visite--Autre documents de la prospection
                                --Propositions de la prospection--Nouvelles propositions--Nouveaux documents--Interlocuteurs</h4>
                    
                        @endforeach
                    </div>
                    <!-- /.box-header -->


                    @if($count_prospection != 0) 
                        
                        @foreach($prospections as $prospections)

                            <!-- form start  INFO SUR LA PROPESCTION DANS LA TABLE-->
                            <div class="form-horizontal">
                            
                                <div class="box-body">
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"><b>DATE :</b></label>
                                    
                                        <div class="col-sm-6">
                                        <input type="text" class="form-control" disabled value="@php echo date('d/m/Y', strtotime($prospections->date_prospection)) @endphp">
                                        </div>
                                    
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>ADRESSE DE L'ENTREPRISE :</b></label>
                                    
                                    
                                        <div class="col-sm-6">
                                        <input type="text" value="{{$prospections->adresse}}" class="form-control" disabled>
                                        </div>
                                    
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>CHIFFRE D'AFFAIRE :</b></label>
                                    
                                    
                                        <div class="col-sm-6">
                                        <input type="text" value="{{$prospections->chiffre_affaire}}" class="form-control" disabled>
                                        </div>
                                    
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>NOMBRE D'EMPLOYES :</b></label>
                                    
                                    
                                        <div class="col-sm-6">
                                        <input type="text" value="{{$prospections->nb_employes}}" class="form-control" disabled>
                                        </div>
                                    
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>ACTIVITE :</b></label>
                                    
                                    
                                        <div class="col-sm-6">
                                        <input type="text" value="{{$prospections->activite}}" class="form-control" disabled>
                                        </div>
                                    
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"><b>EN CHARGE DE LA PROSPECTION :</b></label>
                                    
                                        <div class="col-sm-6">
                                        <input class="form-control" disabled type="text" value="{{$prospections->nom_prenoms}}">
                                        </div>
                                
                                    </div>
                                
                                </div>
                
                            </div>
                        
                            <div class="box-header">
                                <h3 class="box-title"><b>Service(s) proposé(s)</b></h3>
                            </div>
                            
                            <div class="form-group ">
                                <div class="box-body">
                                    @php
                                        //On va écrire un code pour detecter tous les services offerts
                                        $se = DB::table('prospection_services')
                                        ->join('prospections', 'prospection_services.prospection_id', '=', 'prospections.id')
                                        ->join('services', 'prospection_services.service_id', '=', 'services.id') 
                                        ->where('prospection_id', $prospections->id)    
                                        ->get(['services.libele_service', 'prospection_services.*']);
                                    @endphp

                                    <div class="form-group no-padding">
                                        <table class="table table-hover box-body">
                                        
                                            <tr>
                                        
                                                <th>Nom</th>
                                                
                                                <th style="width: 40px">Ajouté le :</th>
                                                <!--LES RESTRICTIONS -->
                                                @can("delete")
                                                <th>Supprimer</th>
                                                    
                                                @endcan
                                                    
                                            </tr>
                                            <!--LES FICHIERS ET LES FACTURES-->
                                            
                                            @foreach($se as $se_get)
                                                <tr>
                                                    <td>  <span class="text"><b>{{$se_get->libele_service}}</b></span></td>
                                                
                                                    <td>
                                                        @php 
                            
                                                            echo "<b>".date('d/m/Y',strtotime($se_get->created_at))."</b>" ;
                                                    
                                                        @endphp
                                                    </td>
                                                    <td>
                                                        @can("delete")	
                                                            <form action="delete_service_many_to_many" method="post" >

                                                                @csrf
                                                                <div class="box-body">
                                                                    <div class="form-group col-sm-6">
                                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                                        <input type="text" value="{{$se_get->id}}" style="display:none;" name="id_service">
                                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                    </div>

                                                                </div>
                                                            
                                                            </form>
                                                            
                                                        @endcan
                                                            
                                                    
                                                    

                                                    </td>
                                                </tr>
                                            
                                            @endforeach
                                        
                                        </table>
                                    </div>

                                </div>
                            </div>

                            @can("edit")
                                <div class="box-body">
                                    <form action="add_service_in_fiche" method="post" class="col-sm-12">
                                        @csrf
                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                        <div class="form-group">
                                            <label>Ajouter un service</label>
                                            <select class="form-control  select2" data-placeholder ="--Selctionner un service--" multiple="multiple" name="service_propose[]" required>
                                            
                                                <!--liste des services a choisir -->
                                            
                                                @php
                                                    $get = $servicecontroller->GetAll();
                                                    $categorie = $categoriecontroller->DisplayAll();
                                                @endphp
                                                @foreach( $categorie as $categorie)
                                                    
                                                    <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                                    @php
                                                        $get = $servicecontroller->GetByCategorieNoSusp($categorie->id);
                                                        
                                                    @endphp
                                                    @foreach($get as $service)
                                                        <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                        
                                                    @endforeach
                                                @endforeach
                                            
                                            </select>
                                        
                                            
                                        </div>
                                        <button class="btn btn-primary">Ajouter</button>
                                    </form>
                                </div>
                            @endcan
                            
                            <hr>

                            <!--LES FICHIERS ET LES FACTURES DANS LA TABLE PROSPECTION-->

                            <div class="box-header with-border">
                                <h3 class="box-title"><b>FACTURE PROFORMA</b></h3>
                            </div>
                            
                            <div class="no-padding">
                                <table class="table table-hover box-body">
                                
                                    <tr>
                                        <th>Nom</th>
                                        <th>Ajouté le:</th>
                                    @can("delete")
                                            <th>Supprimer</th>
                                    @endcan
                                        <th style="width: 40px">Aperçu</th>
                                    </tr>
                                    <!--LES FICHIERS ET LES FACTURES-->
                                    <tr>
                                        @if($prospections->facture_path == null)
                                        
                                        @else
                                            <td>
                                                @php
                                                        $pieces = explode("/", $prospections->facture_path);
                                                        echo $pieces[2];
                                                @endphp
                                            </td>
                                            <td>
                                                @php 
                                                    echo "<b>".date('d/m/Y',strtotime($prospections->created_at))."</b>" ;
                                                @endphp
                                            </td>
                                            @can("edit")
                                                <td>
                                                    @if(auth()->user()->id != $prospections->id_utilisateur)
                                                        @can("procuration")
                                                            <form action="edit_prospect_form" method="post">
                                                                @csrf
                                                                <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                                            </form>
                                                        @endcan
                                                    @else
                                                        @can("edit")
                                                            <form action="edit_prospect_form" method="post">
                                                                @csrf
                                                                <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                                            </form>
                                                        @endcan
                                                    @endif
                                                
                                                </td>
                                            @endcan
                                            
                                            <td>
                                                <form action="download_facture_proforma" method="post" enctype="multipart/form-data" target="blank">

                                                    @csrf
                                                    <div class="box-body">
                                                        <div class="form-group col-sm-6">
                                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                            <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                            <input type="text" class="form-control" name="file" value="{{$prospections->facture_path}}"  style="display:none;">
                                                            <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                        </div>

                                                    </div>
                                                
                                                </form>
                                            </td>
                                        @endif
                                        
                                    </tr>
                                
                                </table>
                            </div>

                            <!--LES AUTRES PROFORMA-->
                            <div class="no-padding">
                                <table class="table table-hover box-body">
                                    @php
                                        $select = DB::table('docfactures')
                                            ->where('id_prospection', $prospections->id)
                                            ->get();
                                    @endphp
                                
                                    @foreach($select as $select)
                                        <tr>
                                            <td>  <span class="text">{{$select->libele}}</span> </td>
                                            <td>
                                                @php 
                                                    echo "<b>".date('d/m/Y',strtotime($select->created_at))."</b>" ;
                                                @endphp
                                            </td>
                                            @if(auth()->user()->id != $prospections->id_utilisateur)
                                                @can("procuration")
                                                    @can("delete")
                                                        <td>
                                                            <form action="delete_prof_in_fiche" method="post" >
                                                                @csrf
                                                                <div class="box-body">
                                                                    <div class="form-group col-sm-6">
                                                                    <input type="text" value="{{$select->id}}" style="display:none;" name="id_doc">
                                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                        <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                    </div>

                                                                </div>
                                                            </form>
                                                        </td>
                                                    @endcan
                                                
                                                @endcan
                                            @else
                                                @can("delete")
                                                    <td>
                                                        <form action="delete_prof_in_fiche" method="post" >
                                                            @csrf
                                                            <div class="box-body">
                                                                <div class="form-group col-sm-6">
                                                                <input type="text" value="{{$select->id}}" style="display:none;" name="id_doc">
                                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                    <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </td>
                                                @endcan
                                            @endif
                                        
                                            <td>
                                                <form action="download_facture_proforma" method="post" enctype="multipart/form-data" target="blank">

                                                    @csrf
                                                    <div class="box-body">
                                                        <div class="form-group col-sm-6">
                                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                            <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                            <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                            <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                        </div>

                                                    </div>
                                                
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                
                                
                                </table>
                            </div>
                            @if(auth()->user()->id != $prospections->id_utilisateur)
                                @can("procuration")
                                    @can("edit")
                                        <!--SI ON VEUT AJOUETR UN AUTRE DOCUMENT de FACTURE PROFORMA -->
                                        <div class="box-body">
                                            <form action="add_new_doc_proforma" method="post" enctype="multipart/form-data" class="col-sm-12">

                                                @csrf
                                                <div class="box-body ">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <label class="control-label">Ajouter une facture :</label>
                                                        <input type="file" class="form-control" name="new_doc_proforma" required>
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                    </div>

                                                </div>
                                                
                                            </form>
                                        </div>
                                    @endcan
                                        
                                @endcan
                            @else
                                @can("edit")
                                    <!--SI ON VEUT AJOUETR UN AUTRE DOCUMENT de FACTURE PROFORMA -->
                                    <div class="box-body">
                                        <form action="add_new_doc_proforma" method="post" enctype="multipart/form-data" class="col-sm-12">

                                            @csrf
                                            <div class="box-body ">
                                                <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <label class="control-label">Ajouter une facture :</label>
                                                    <input type="file" class="form-control" name="new_doc_proforma" required>
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                </div>

                                            </div>
                                            
                                        </form>
                                    </div>
                                @endcan
                            @endif
                        
                            <!--LES CR DE VISITE DANS LA TABLE PROPSECTION-->
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>COMPTE RENDU DE VISITE</b></h3>
                            </div>
                            
                            <div class="form-group ">
                                <table class="table table-hover box-body">
                                    <tr>
                                        <th>Nom du fichier</th>                            
                                    </tr>
                                
                                    <tr class="no-padding">
                                        @if($prospections->path_cr == null)
                                            
                                        @else
                                        
                                            <td>  
                                                <span class="text">
                                                    @php
                                                        $pieces = explode("/", $prospections->path_cr);
                                                        echo $pieces[1];
                                                    @endphp
                                                </span> 
                                            </td>
                                            <td>
                                                @php 
                                                    echo "<b>".date('d/m/Y',strtotime($prospections->created_at))."</b>" ;
                                                @endphp
                                            </td>
                                            @if(auth()->user()->id != $prospections->id_utilisateur)
                                                
                                                @can("procuration")
                                                    @can("edit")
                                                        <td>
                                                            <form action="edit_prospect_form" method="post">
                                                                @csrf
                                                                <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                                            </form>
                                                        <td>
                                                    @endcan
                                                @endcan
                                            @else
                                                @can("edit")
                                                    <td>
                                                        <form action="edit_prospect_form" method="post">
                                                            @csrf
                                                            <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                                        </form>
                                                    <td>
                                        
                                                @endcan
                                            @endif
                                        
                                        
                                            <td>    
                                                <form action="download_prospect" method="post" enctype="multipart/form-data">
        
                                                    @csrf
                                                    <div class="box-body">
                                                        <div class="form-group col-sm-6">
                                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                            <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                            <input type="text" class="form-control" name="file" value="{{$prospections->path_cr}}" style="display:none;">
                                                            <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                        </div>
        
                                                    </div>
                                                
                                                </form>
        
                                            </td>
                                        @endif
                                    
                                    </tr>
                                
                                </table>
                            </div>

                            <!--LES AUTRES CR DE VISITE-->
                            <div class="no-padding">
                                <table class="table table-hover box-body">
                                @php
                                    $select = DB::table('compterendus')
                                    ->where('id_prospection', $prospections->id)
                                    ->get();
                                @endphp
                                
                                    <!--LES FICHIERS ET LES FACTURES-->
                                    @foreach($select as $select)
                                        <tr>
                                            <td>  <span class="text">{{$select->libele}}</span> </td>
                                            
                                            @if(auth()->user()->id != $prospections->id_utilisateur)
                                                @can("procuration")
                                                    @can("delete")
                                                        <td>
                                                            <form action="delete_cr_in_fiche" method="post" >

                                                                @csrf
                                                                <div class="box-body">
                                                                    <div class="form-group col-sm-6">
                                                                    <input type="text" value="{{$select->id}}" style="display:none;" name="id_doc">
                                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                        <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                    </div>

                                                                </div>
                                                            
                                                            </form>
                                                            
                                                        </td>
                                                    @endcan
                                                @endcan
                                            @else
                                                @can("delete")
                                                    <td>
                                                        <form action="delete_cr_in_fiche" method="post" >

                                                            @csrf
                                                            <div class="box-body">
                                                                <div class="form-group col-sm-6">
                                                                <input type="text" value="{{$select->id}}" style="display:none;" name="id_doc">
                                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                    <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                </div>

                                                            </div>
                                                        
                                                        </form>
                                                        
                                                    </td>
                                                @endcan
                                            @endif
                                        
                                        
                                            <td>
                                                <form action="download_facture_proforma" method="post" enctype="multipart/form-data" target="blank">
                                                    @csrf
                                                    <div class="box-body">
                                                        <div class="form-group col-sm-6">
                                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                            
                                                            <input type="text" class="form-control" name="file" value="{{$select->path_doc}}"  style="display:none;">
                                                            <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                
                                </table>
                            </div>


                            <!--SI ON VEUT AJOUETR UN AUTRE DOCUMENT -->
                            @if(auth()->user()->id != $prospections->id_utilisateur)
                                @can("procuration")
                                    @can("edit")
                                        <div class="box-body">
                                            <form action="add_new_doc_cr" method="post" enctype="multipart/form-data" class="col-sm-12">

                                                @csrf
                                                <div class="box-body ">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <label class="control-label">Ajouter un CR de visite :</label>
                                                        <input type="file" class="form-control" name="new_doc_cr" required>
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                    </div>

                                                </div>
                                                
                                            </form>
                                        </div>

                                    @endcan
                                @endcan
                            @else
                                @can("edit")
                                    <div class="box-body">
                                        <form action="add_new_doc_cr" method="post" enctype="multipart/form-data" class="col-sm-12">

                                            @csrf
                                            <div class="box-body ">
                                                <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <label class="control-label">Ajouter un CR de visite :</label>
                                                    <input type="file" class="form-control" name="new_doc_cr" required>
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                </div>

                                            </div>
                                            
                                        </form>
                                    </div>
                                @endcan
                            @endif
                        
                            <!--AUTRE DOCS-->
                            @php
                                $docs = $documentController->GetDocByProspection($prospections->id);  
                            @endphp
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>AUTRE DOCUMENTS (facture supplémentaires & autres)</b></h3>
                            </div>
                            <div class="box-body no-padding">
                                <table class="table table-hover">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Ajouté le :</th>
                                        <th>Supprimer</th>
                                        <th style="width: 40px">Aperçu</th>
                                    </tr>
                                    @foreach($docs as $docs)
                                        <!--LES FICHIERS ET LES FACTURES-->
                                    <tr>
                                        <td>  <span class="text">{{$docs->libele}}</span> </td>
                                        <td>
                                            @php 
                                                echo "<b>".date('d/m/Y',strtotime($docs->created_at))."</b>" ;
                                            @endphp
                                        </td>
                                        @if(auth()->user()->id != $prospections->id_utilisateur)
                                            @can("procuration")
                                                @can("delete")
                                                    <td>
                                                        <form action="delete_doc" method="post" enctype="multipart/form-data">

                                                            @csrf
                                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                            <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                            <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                                            <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                                            <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                                        </form>
                                                    </td>
                                                @endcan
                                            @endcan
                                        @else
                                            @can("delete")
                                                <td>
                                                    <form action="delete_doc" method="post" enctype="multipart/form-data">

                                                        @csrf
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                                        <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                                        <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                                    </form>
                                                </td>
                                            @endcan
                                    
                                        @endif
                                            
                                        <td>
                                            
                                            <form action="download_docs" method="post" enctype="multipart/form-data" class="col-sm-6">

                                                @csrf
                                                <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                                <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </table>
                            </div>
                            <hr>

                            <!--SI ON VEUT AJOUETR UN AUTRE DOCUMENT -->
                            @if(auth()->user()->id != $prospections->id_utilisateur)
                                @can("procuration")
                                    @can("edit")
                                        <div class="box-body">
                                            <form action="add_new_doc" method="post" enctype="multipart/form-data" class="col-sm-12">

                                                @csrf
                                                <div class="box-body ">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <label class="control-label">Ajouter un document :</label>
                                                        <input type="file" class="form-control" name="new_doc" required>
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                    </div>

                                                </div>
                                                
                                            </form>
                                        </div>
                                    @endcan
                                @endcan
                            @else
                                @can("edit")
                                    <div class="box-body">
                                        <form action="add_new_doc" method="post" enctype="multipart/form-data" class="col-sm-12">

                                            @csrf
                                            <div class="box-body ">
                                                <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <label class="control-label">Ajouter un document :</label>
                                                    <input type="file" class="form-control" name="new_doc" required>
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                </div>

                                            </div>
                                            
                                        </form>
                                    </div>
                                @endcan
                            @endif
                        
                            @php
                                $propal = $propalcontroller->GetByIdEntreprise($prospections->id);  
                            @endphp

                            <!--PROPOSITION-->
                        
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>PROPOSITIONS DE LA PROSPECTIONS</b></h3>
                            </div>
                            <div class="box-body no-padding">
                                <table class="table table-hover">
                                    <tr>
                                
                                        <th>Nom</th>
                                        <th>Ajouté le :</th>
                                        <th>Actualisation :</th>
                                        <th>Supprimer</th>
                                        <th style="width: 40px">Aperçu</th>
                                    </tr>
                                    @foreach($propal as $propal)
                                        <!--LES FICHIERS ET LES FACTURES-->
                                    <tr>
                                        <td>  <span class="text">{{$propal->libele}}</span> </td>
                                        <td>
                                            @php 
                                                echo "<b>".date('d/m/Y',strtotime($propal->created_at))."</b>" ;
                                            @endphp
                                        </td>
                                        <td>
                                        <button type="button" class="btn btn-primary" 
                                        data-toggle="modal" data-target="#actu{{$propal->id}}"><b><i class="fa fa-plus"></i></b></button>
                                            <div class="modal modal-default fade" id="actu{{$propal->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">{{$propal->libele}}</h4>
                                                        </div>
                                                    
                                                        <div class="modal-body">
                                                            <!-- form start -->
                                                            <form action="actual_propal" method="post">  
                                                                @csrf
                                                                <input type="text" value="{{$propal->id}}" name="id_propal" style="display:none;">
                                                                <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                <div class="form-group">   
                                                                    <label>Rejeté?</label>
                                                                    <select class="form-control " name="rejete">
                                                                        @if($propal->rejete == "0")
                                                                            <option value="0">NON</option>
                                                                            <option value="1">OUI</option>
                                                                        @else
                                                                            <option value="1">OUI</option>
                                                                            <option value="0">NON</option>
                                                                        @endif
                                                                    </select>
                                                                    
                                                                </div>        

                                                                <div class="form-group">
                                                                    <label>Motif :</label>
                                                                    <p>{{$propal->motif}}</p>
                                                                    <textarea name="motif" class="form-control"></textarea>
                                                                </div>
                                                            
                                                                <script>
                                                                    function newFonction()
                                                                    {
                                                                        
                                                                        var f = document.getElementById("grise4").value;
                                                                        //alert(f);
                                                                        if(f == 'autre')
                                                                        {
                                                                            document.getElementById("newf").removeAttribute("disabled");
                                                                        }
                                                                        else{
                                                                            document.getElementById("newf").setAttribute("disabled", "disabled");
                                                                        }
                                                                    }
                                                                </script>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                                                    <button type="submit" class="btn btn-primary">Actualiser</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div> 
                                        </td>
                                        @if(auth()->user()->id != $prospections->id_utilisateur)
                                            @can("procuration")
                                                @can("delete")
                                                    <td>
                                                        <form action="delete_doc_propal" method="post" enctype="multipart/form-data">

                                                            @csrf
                                                            <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                            <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                            <input type="text" value="{{$propal->id}}" style="display:none;" name="id_doc">
                                                            <input type="text" class="form-control" name="file" value="{{$propal->path_doc}}" style="display:none;">
                                                            <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                                        </form>
                                                    </td>
                                                @endcan
                                            @endcan
                                        @else
                                            @can("delete")
                                                <td>
                                                    <form action="delete_doc_propal" method="post" enctype="multipart/form-data">

                                                        @csrf
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <input type="text" value="{{$propal->id}}" style="display:none;" name="id_doc">
                                                        <input type="text" class="form-control" name="file" value="{{$propal->path_doc}}" style="display:none;">
                                                        <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                                    </form>
                                                </td>
                                            @endcan
                                        @endif
                                        
                                        <td>
                                            
                                            <form action="download_docs_propal" method="post" enctype="multipart/form-data" class="col-sm-6">

                                                @csrf
                                                <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                <input type="text" value="{{$propal->id}}" style="display:none;" name="id_doc">
                                                <input type="text" class="form-control" name="file" value="{{$propal->path_doc}}" style="display:none;">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                </table>
                            </div>
                            <hr>

                            <!--SI ON VEUT AJOUETR UNE PROPOSITION -->
                            @if(auth()->user()->id != $prospections->id_utilisateur)
                                @can("procuration")
                                    @can("edit")
                                        <div class="box-body">
                                            <form action="add_doc_proposition" method="post" enctype="multipart/form-data" class="col-sm-12">

                                                @csrf
                                                <div class="box-body ">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <label class="control-label">Ajouter une proposition :</label>
                                                        <input type="file" class="form-control" name="new_doc" required>
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                    </div>

                                                </div>
                                                
                                            </form>
                                        </div>
                                    @endcan
                                @endcan
                            @else
                                @can("edit")
                                    <div class="box-body">
                                        <form action="add_doc_proposition" method="post" enctype="multipart/form-data" class="col-sm-12">

                                            @csrf
                                            <div class="box-body ">
                                                <div class="form-group col-sm-6">
                                                    <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <label class="control-label">Ajouter une proposition :</label>
                                                    <input type="file" class="form-control" name="new_doc" required>
                                                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                                </div>

                                            </div>
                                            
                                        </form>
                                    </div>
                                @endcan
                            @endif
                            <hr>
                                
                        @endforeach
                
                    @endif    
                     <!--LES PROPOSITIONS -->
                    @php
                        $propal = $propalcontroller->GetByEntreprise($id_entreprise);  
                    @endphp
            
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>NOUVELLES PROPOSITIONS</b></h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-hover">
                            <tr>
                        
                                <th>Nom</th>
                                <th>Ajouté le :</th>
                                <th>Actualisation :</th>
                                <th>Supprimer</th>
                                <th style="width: 40px">Aperçu</th>
                            </tr>
                            @foreach($propal as $propal)
                                <!--LES FICHIERS ET LES FACTURES-->
                            <tr>
                                <td>  <span class="text">{{$propal->libele}}</span> </td>
                                <td>
                                    @php 
                                        echo "<b>".date('d/m/Y',strtotime($propal->created_at))."</b>" ;
                                    @endphp
                                </td>
                                <td>
                                <button type="button" class="btn btn-primary" 
                                data-toggle="modal" data-target="#actu{{$propal->id}}"><b><i class="fa fa-plus"></i></b></button>
                                    <div class="modal modal-default fade" id="actu{{$propal->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title">{{$propal->libele}}</h4>
                                                </div>
                                            
                                                <div class="modal-body">
                                                    <!-- form start -->
                                                    <form action="actual_propal" method="post">  
                                                        @csrf
                                                        <input type="text" value="{{$propal->id}}" name="id_propal" style="display:none;">
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <div class="form-group">   
                                                            <label>Rejeté?</label>
                                                            <select class="form-control " name="rejete">
                                                                @if($propal->rejete == "0")
                                                                    <option value="0">NON</option>
                                                                    <option value="1">OUI</option>
                                                                @else
                                                                    <option value="1">OUI</option>
                                                                    <option value="0">NON</option>
                                                                @endif
                                                            </select>
                                                            
                                                        </div>        

                                                        <div class="form-group">
                                                            <label>Motif :</label>
                                                            <p>{{$propal->motif}}</p>
                                                            <textarea name="motif" class="form-control"></textarea>
                                                        </div>
                                                    
                                                        <script>
                                                            function newFonction()
                                                            {
                                                                
                                                                var f = document.getElementById("grise4").value;
                                                                //alert(f);
                                                                if(f == 'autre')
                                                                {
                                                                    document.getElementById("newf").removeAttribute("disabled");
                                                                }
                                                                else{
                                                                    document.getElementById("newf").setAttribute("disabled", "disabled");
                                                                }
                                                            }
                                                        </script>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Actualiser</button>
                                                        </div>
                                                    </form>
                                                </div>
                                                
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                    </div> 
                                </td>
                          
                                <td>
                                    @can("delete")
                                        
                                            <form action="delete_doc_propal" method="post" enctype="multipart/form-data">

                                                @csrf
                                            
                                                <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                <input type="text" value="{{$propal->id}}" style="display:none;" name="id_doc">
                                                <input type="text" class="form-control" name="file" value="{{$propal->path_doc}}" style="display:none;">
                                                <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                    
                                    @endcan
                                </td>
                              
                                <td>
                                    
                                    <form action="download_docs_propal" method="post" enctype="multipart/form-data" target="blank" class="col-sm-6">

                                        @csrf
                                        
                                        <input type="text" value="{{$propal->id}}" style="display:none;" name="id_doc">
                                        <input type="text" class="form-control" name="file" value="{{$propal->path_doc}}" style="display:none;">
                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                    </form>
                                </td>
                            </tr>
                            
                            @endforeach
                        </table>
                    </div>
                    <hr>

                    @can("edit")	
                        <div class="box-body">
                            <form action="add_doc_proposition" method="post" enctype="multipart/form-data" class="col-sm-12">

                                @csrf
                                <div class="box-body ">
                                    <div class="form-group col-sm-6">
                                        
                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                        <label class="control-label">Ajouter une nouvelle proposition :</label>
                                        <input type="file" class="form-control" name="new_doc" required>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                    </div>

                                </div>
                                
                            </form>
                        </div>
                    @endcan

                    <hr>
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>NOUVEAUX DOCUMENTS</b></h3>
                    </div>
                    @php
                        $docs = $documentController->GetDocByEntreprise($id_entreprise);  
                        //dd($docs);
                    @endphp
                    <div class="box-body no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Nom</th>
                                <th>Ajouté le :</th>
                                <th>Supprimer</th>
                                <th style="width: 40px">Aperçu</th>
                            </tr>
                            @foreach($docs as $docs)
                                <!--LES FICHIERS ET LES FACTURES-->
                                <tr>
                                    <td>  <span class="text">{{$docs->libele}}</span> </td>
                                    <td>
                                        @php 
                                            echo "<b>".date('d/m/Y',strtotime($docs->created_at))."</b>" ;
                                        @endphp
                                    </td>
                                    <td>
                                    @if(auth()->user()->id != $docs->id_utilisateur)
                                        @can("procuration")
                                            @can("delete")
                                                
                                                <form action="delete_doc" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                                    <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                                    <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                                
                                            @endcan
                                        @endcan
                                    @else
                                  
                                        @can("delete")
                                            
                                            <form action="delete_doc" method="post" enctype="multipart/form-data">
                                                @csrf
                                                <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                                <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                                <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                            
                                        @endcan
                                    </td>
                                    @endif
                                        
                                    <td>
                                        <form action="download_docs" method="post" enctype="multipart/form-data" target="blank" class="col-sm-6">
                                            @csrf
                                            <input type="text" value="{{$docs->id}}" style="display:none;" name="id_doc">
                                            <input type="text" class="form-control" name="file" value="{{$docs->path_doc}}" style="display:none;">
                                            <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            
                            @endforeach
                        </table>
                    </div>
                    @can("edit")
                        <div class="box-body">
                            <form action="add_other_doc_prospect" method="post" enctype="multipart/form-data" class="col-sm-12">
                                    
                                @csrf
                                <div class="box-body ">
                                    <div class="form-group col-sm-6">
                                        
                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                        <label class="control-label">Ajouter un nouveau document :</label>
                                        <input type="file" class="form-control" name="new_doc" required>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                    </div>

                                </div>
                                
                            </form>
                        </div>
                    @endcan

                    @php    
                        $interlocuteurs =  $interlocuterController->InterlocuteurWithIdEntreprise($id_entreprise);
                    @endphp
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>Interlocuteur(s)</b></h3>
                    </div>

                    <table class="table table-hover box-body">        
                        <tr>
                            <th>Nom</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
                            <th>Action</th>
                        </tr>
                        <!--LES FICHIERS ET LES FACTURES-->
                        @foreach($interlocuteurs as $interlocuteurs)
                            <tr>
                                <td> {{$interlocuteurs->titre}} {{$interlocuteurs->nom}}</td>
                            
                                <td>
                                    {{$interlocuteurs->tel}}
                                </td>
                                <td>{{$interlocuteurs->email}}</td>
                                <td>{{$interlocuteurs->intitule}}</td>

                                <td>
                                    @if(auth()->user()->id != $interlocuteurs->created_by)
                                        @can("procuration")
                                            @can("edit")
                                                <form action="edit_interlocuteur_form_fiche" method="post">
                                                    @csrf
                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                    <input type="text" value={{$interlocuteurs->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                </form>
                                            @endcan
                                            @can("delete")
                                                <!--SUPPRESSION AVEC POPUP-->
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interlocuteurs->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$interlocuteurs->id.""; @endphp">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Supprimer </h4>
                                                    </div>
                                                        <form action="delete_interlocuteur_from_fiche" method="post">
                                                        <div class="modal-body">
                                                        <p>Voulez-vous supprimer {{$interlocuteurs->nom}}?</p>
                                                        @csrf
                                                        <input type="text" value="{{$interlocuteurs->id_entreprise}}" name="id_entreprise" style="display:none;">
                                                        <input type="text" value={{$interlocuteurs->id}} style="display:none;" name="id_interlocuteur">
                                                        </div>
                                                    
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-outline">Supprimer</button>
                                                        </div>
                                                    </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                                
                                                
                                            @endcan
                                        @endcan
                                    @else
                                        @can("edit")
                                            <form action="edit_interlocuteur_form_fiche" method="post">
                                                @csrf
                                                <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                <input type="text" value={{$interlocuteurs->id}} style="display:none;" name="id_interlocuteur">
                                                <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                            </form>
                                        @endcan
                                        @can("delete")
                                            <!--SUPPRESSION AVEC POPUP-->
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interlocuteurs->id.""; @endphp">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            <div class="modal modal-danger fade" id="@php echo "interloc".$interlocuteurs->id.""; @endphp">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title">Supprimer </h4>
                                                </div>
                                                    <form action="delete_interlocuteur_from_fiche" method="post">
                                                    <div class="modal-body">
                                                    <p>Voulez-vous supprimer {{$interlocuteurs->nom}}?</p>
                                                    @csrf
                                                    <input type="text" value="{{$interlocuteurs->id_entreprise}}" name="id_entreprise" style="display:none;">
                                                    <input type="text" value={{$interlocuteurs->id}} style="display:none;" name="id_interlocuteur">
                                                    </div>
                                                
                                                    <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                    <button type="submit" class="btn btn-outline">Supprimer</button>
                                                    </div>
                                                </form>
                                                </div>
                                                <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                            </div>
                                            <!-- /.modal -->
                                            
                                            
                                        @endcan
                                    @endif
                                </td>
                            </tr>
                        
                        @endforeach
                    
                    </table>

                    <hr>
                </div>
        
            </div>
            
            <div class="col-md-4">
                <div class="box box-info table-responsive">
                   
                    <div class="box-header with-border" style="text-align:center">
                      
                        <h3 class="box-title"><b>SUIVIS</b></h3>
                    </div>
                    @php
                        $suivis =  $suivicontroller->GetSuiviByIdEntreprise($id_entreprise);
                    @endphp
                    <div class="box-header with-border">
                        @can("edit")
                            <div class="col-md-3">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addsuivi"><b><i class="fa fa-plus"></i>SUIVI</b></button>
                            </div>
                        @endcan
                    </div>

                    <table class="table table-hover box-body">        
                        <tr>
                            <th>Date-Heure</th>
                            <th>Action</th>
                            <th>Interlocuteur</th>
                            <th>Commentaire</th>
                            <th>Mod/Supp.</th>
                        </tr>
                        <!--LES FICHIERS ET LES FACTURES-->
                        @foreach($suivis as $suivi)
                            <tr>
                                <td> 
                                    @php 
                                        echo "<b>".date('d/m/Y',strtotime($suivi->date_activite))."</b>";
                                    @endphp 
                                    {{$suivi->heure_action}}
                                </td>
                            
                                <td>
                                    {{$suivi->action}}
                                </td>
                                <td>{{$suivi->name_interl}}</td>
                                <td>{{$suivi->comment}}</td>

                                <td>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            @can("edit")
                                                <button type="submit" data-toggle="modal" data-target="#edit{{$suivi->id}}" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                            @endcan
                                            <div class="modal modal-default fade" id="edit{{$suivi->id}}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Modification</h4>
                                                        </div>
                                                    
                                                        <div class="modal-body">
                                                            <!-- form start -->
                                                            <form action="edit_suivi" method="post">  
                                                                @csrf
                                                                <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                <input type="text" value={{$suivi->id}} style="display:none;" name="id_suivi">
                                                                <div class="form-group">
                                                                
                                                                    @php
                                                                        $nom = $entreprisecontroller->GetById($id_entreprise);
                                                                    @endphp
                                                                    
                                                                    <select class="form-control " name="entreprise" style="display:none;">
                                                                        @foreach($nom as $nom)
                                                                            <option value={{$id_entreprise}}>{{$nom->nom_entreprise}}</option>

                                                                        @endforeach
                                                                    
                                                                    </select>
                                                                    
                                                                </div>        

                                                                <div class="form-group">
                                                                    <label for="">Date:</label>
                                                                    <input type="date" class="form-control" name="date_activite" value="{{$suivi->date_activite}}" required>
                                                                </div>
                                    
                                                                <div class="form-group">
                                                                    <label for="">Heure:</label>
                                                                    <input type="time" id="appt" class="form-control" value="{{$suivi->heure_action}}" name="heure_action" min="00:00" max="23:59" required />
                                                                </div>
                                                            
                                                                <div class="form-group">
                                                                    <label>Action menée:</label>
                                                                    <textarea name="action" class="form-control" required>{{$suivi->action}}</textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                        <label>Nom de l'interlocuteur:</label>
                                                                        <input type="text" required onkeyup="this.value=this.value.toUpperCase()" 
                                                                        maxlength="100" class="form-control" value="{{$suivi->name_interl}}" name="name_interl" placeholder="M. KOFFI JEAN">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Téléphone (*)</label>
                                                                    <input type="text" required maxlength="30" value="{{$suivi->tel_interl}}"  class="form-control " name="tel_interl" placeholder="(+225)0214578931" >
                                                                </div>


                                                                <div class="form-group">
                                                                    <label>Commentaire:(*)</label>
                                                                    <textarea name="comment" class="form-control" equired>{{$suivi->comment}}</textarea>
                                                                </div>  
                                                        
                                                            
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                                                    <button type="submit" class="btn btn-success">Valider les modifications</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                            </div> 
                                        </div>
                                        <div class="col-xs-6">
                                            @can("delete")
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$suivi->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                                    
                                                <div class="modal modal-danger fade" id="@php echo "".$suivi->id.""; @endphp">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Supprimer </h4>
                                                    </div>
                                                     <form action="delete_suivi_from_fiche" method="post">
                                                        <div class="modal-body">
                                                        <p>Voulez-vous supprimer cet enregistrement?</p>
                                                        @csrf
                                                        <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                        <input type="text" value={{$suivi->id}} style="display:none;" name="id_suivi">
                                                        </div>
                                                    
                                                        <div class="modal-footer">
                                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-outline">Supprimer</button>
                                                        </div>
                                                    </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                </div>
                                                <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            @endcan
                                        </div>
                                        
                                     
                                    </div>
                                </td>
                            </tr>
                        
                        @endforeach
                    
                    </table>

                </div>            
            </div>
        </div>
        <!--/.col (right) -->
    @endif
    <div class="row">
        <div class="modal modal-default fade" id="add">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ajouter un interlocuteur </h4>
                    </div>
                 
                    <div class="modal-body">
                        <!-- form start -->
                        <form action="add_referant_in_fiche" method="post">  
                             @csrf
                            <div class="box-header">
                                <h3 class="box-title"><b>AJOUTER UN INTERLOCUTEUR </b></h3>
                            </div> 

                            <div class="form-group">
                                @php
                                    $nom = $entreprisecontroller->GetById($id_entreprise)
                                @endphp
                            
                                        
                                <select class="form-control " name="entreprise" style="display:none;">
                                    @foreach($nom as $nom)
                                        <option value={{$id_entreprise}}>{{$nom->nom_entreprise}}</option>

                                    @endforeach
                                
                                </select>
                                
                            </div>        

                            <div class="form-group">
                                <label for="exampleInputFile">Titre :</label>
                                <select class="form-control " name="titre" id="grise1" >
                                    <option value="M">M</option>
                                    <option value="Mme">Mme</option>
                                    <option value="Mlle">Mlle</option>
                                </select>
                                
                            </div>
                            <div class="form-group">
                                    <label >Nom & Prénom(s)</label>
                                    <input type="text" maxlength="100" required id="grise2" class="form-control  " name="nom" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="grise5" maxlength="30" class="form-control " name="email" >
                            </div>

                            <div class="form-group">
                                    <label>Téléphone (*)</label>
                                    <input type="text" required id="grise3" maxlength="30"   class="form-control " name="tel" placeholder="(+225)0214578931" >
                                </div>

                                <div class="form-group">
                                    <label>Fonction (Choisir "Autre" si inexistant)</label>
                                        <select class="form-control"  onchange="newFonction();" name="fonction" id="grise4" required>
                                        @php
                                            $f = DB::table('professions')->orderBy('id', 'asc')->get();
                                        @endphp
                                        @foreach($f as $f)
                                            <option value="{{$f->id}}">{{$f->intitule}}</option>
                                        @endforeach
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                        <label>Fonction:(*)</label>
                                        <input type="text" disabled="disabled" required id="newf" maxlength="60"   class="form-control " name="new_fonction" onkeyup="this.value=this.value.toUpperCase()" >
                                </div>  
                            </div>
                            <script>
                                function newFonction()
                                {
                                    
                                    var f = document.getElementById("grise4").value;
                                    //alert(f);
                                    if(f == 'autre')
                                    {
                                        document.getElementById("newf").removeAttribute("disabled");
                                    }
                                    else{
                                        document.getElementById("newf").setAttribute("disabled", "disabled");
                                    }
                                }
                            </script>
                            <div class="modal-footer">
                                <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
                <!-- /.modal-content -->
            </div>
        </div> 

        <div class="modal modal-default fade" id="addsuivi">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Enregistrer une nouvelle action menée</h4>
                    </div>
                 
                    <div class="modal-body">
                        <!-- form start -->
                        <form action="add_suivi" method="post">  
                             @csrf
                           
                            <div class="form-group">
                              
                                @php
                                    $nom = $entreprisecontroller->GetById($id_entreprise);
                                @endphp
                                
                                <select class="form-control " name="entreprise" style="display:none;">
                                    @foreach($nom as $nom)
                                        <option value={{$id_entreprise}}>{{$nom->nom_entreprise}}</option>

                                    @endforeach
                                
                                </select>
                                
                            </div>        

                            <div class="form-group">
                                <label for="">Date:</label>
                                <input type="date" class="form-control" name="date_activite" required>
                            </div>
 
                            <div class="form-group">
                                <label for="">Heure:</label>
                                <input type="time" id="appt" class="form-control" name="heure_action" min="00:00" max="23:59" required />
                            </div>
                           
                            <div class="form-group">
                                <label>Action menée:</label>
                                <textarea name="action" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                    <label>Nom de l'interlocuteur:</label>
                                    <input type="text" required onkeyup="this.value=this.value.toUpperCase()" 
                                    maxlength="100" class="form-control" name="name_interl" placeholder="M. KOFFI JEAN">
                            </div>

                            <div class="form-group">
                                <label>Téléphone (*)</label>
                                <input type="text" required maxlength="30"   class="form-control " name="tel_interl" placeholder="(+225)0214578931" >
                            </div>


                            <div class="form-group">
                                <label>Commentaire:(*)</label>
                                <textarea name="comment" class="form-control" required></textarea>
                            </div>  
                    
                          
                            <div class="modal-footer">
                                <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                <button type="submit" class="btn btn-primary">Ajouter</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
                <!-- /.modal-content -->
            </div>
        </div> 
        
    </div>
    
@endsection 
 