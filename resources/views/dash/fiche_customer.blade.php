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

   
  
@endphp

@section('content')

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
                        <form action="add_referant_in_fiche_customer" method="post">  
                            @csrf
                        
                            <!-- /.box-body -->
                            <div class="box-body">
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
                                        <label>Nom & Prénom(s)</label>
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
        <!-- /.modal-dialog -->

        <div class="col-md-2"></div>
        <div class="col-md-8">

            <div class="col-md-3">
                <a href="fiche"><button class="btn btn-default" > <b>RETOUR</b></button></a>
            </div>
             
            @can("edit")
                <div class="col-md-3">
                   <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add"><b><i class="fa fa-plus"></i>INTERLOCUTEUR</b></button>
                </div>
                <div class="col-md-3">
                    <a href="form_add_contrat"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i>CONTRAT</b></button></a>
                </div>
            @endcan

            <div class="col-md-3"><a href="form_add_prospection">
                @if(isset($id_entreprise))

                  <form method="post" action="go_print_rapport_clt" target="blank">
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
            $prospections = $prospectioncontroller->GetProspectionByIdEntr($id_entreprise);

            $contrats = $contratcontroller->GetContratByIdEntr($id_entreprise);
           
            $prestations = $prestationcontroller->GetPrestationByIdEntr($id_entreprise);

            $count_contrat = $contrats->count();
            $count_prospection = $prospections->count();
            
        @endphp

        <div class="row">
          
            <div class="col-md-2"></div>
            <!-- left column -->
            <div class="col-md-8">
                <!-- Horizontal Form -->
                <div class="box box-info">
                  
                    <div class="box-header with-border" style="text-align:center">
                    @php
                        $nom = $entreprisecontroller->GetById($id_entreprise)
                    @endphp
                    @foreach($nom as $nom)
                        <h3 class="box-title"><b>{{$nom->nom_entreprise}}</b>
                             @if($nom->etat == 0)
                                    <span class="bg-red">INACTIF</span>
                                @else
                                @endif
                        </h3>
                        </div>
                        <!-- /.box-header -->
                         <div class="box-body">
                            <div class="form-group">
                                 <label class="col-sm-6 control-label"> <b>CLIENT DEPUIS LE :</b></label>
                            
                                <div class="col-sm-6">
                                    <input type="text" value="@php echo date('d/m/Y', strtotime($nom->client_depuis)) @endphp" class="form-control" disabled>
                                </div><br><br>
                                
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label"> <b>ADRESSE GEROGRAPHIQUE DE L'ENTREPRISE :</b></label>
                            
                                <div class="col-sm-6">
                                    <input type="text" value="{{$nom->adresse}}" class="form-control" disabled>
                                </div><br><br>
                                
                            </div>
                            <div class="form-group">
                                <label class="col-sm-6 control-label"><b>AJOUTE PAR :</b></label>
                            
                                <div class="col-sm-6">
                                    <input class="form-control" disabled type="text" value="{{$nom->nom_prenoms}}">
                                </div><br><br>
                            
                            </div>
                        
                        </div>

                    @endforeach

                    <div class="box-header" style="text-align:center">
                        <h3 class="box-title"><b>Contrats</b></h3>
                    </div>
                    @if($count_contrat == 0)
                        <div class="box-header" style="text-align:center">
                            <h3 class="box-title"><b>Pas de Contrat</b></h3>
                            <hr>
                        </div> 
                    @endif
                    @foreach($contrats as $contrats)
                        <!--Contrats-->
                        <div class="no-padding">
                            <table class="table table-hover box-body">
                            
                                <tr>
                                    <th>Référence du contrat</th>
                                    <th>Début du contrat</th>
                                    <th>Fin du contrat</th>
                                    <th>Montant</th>
                                    <!--LES RESTRICTIONS -->
                                        <th>Action</th>
                                    
                                    
                                </tr>
                                <!--LES FICHIERS ET LES FACTURES-->
                                <tr>
                                    <td> {{$contrats->titre_contrat}}  </td>
                                    <td>
                                        @php 
                                            echo date('d/m/Y',strtotime($contrats->debut_contrat));
                                        @endphp
                                        </td>
                                    <td>
                                        @php 
                                        echo date('d/m/Y',strtotime($contrats->fin_contrat)) ;
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                             echo  number_format($contrats->montant, 2, ".", " ")." XOF";
                                        @endphp
                                        
                                    </td>

                                    <td>
                                      
                                        @can("edit")
                                            <form action="fiche_edit_contrat_form" method="post" >
                                                @csrf
                                                <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                                <input type="text" value={{$id_entreprise}} style="display:none;" name="id_entreprise">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                            </form>
                                        @endcan
                                    </td>
                                
                                    
                                </tr>
                            
                            </table>
                        </div>

                        <!--LES FICHIERS ET LES FACTURES DANS LA TABLE CONTRAT-->
                           
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>FICHIER DE CONTRAT</b></h3>
                            </div>
                            <div class="no-padding">
                                <table class="table table-hover box-body">
                                
                                    <tr>
                                        <th>Nom</th>
                                        <th>Ajouté le :</th>
                                        <th style="width: 40px">Aperçu</th>
                                    </tr>
                                    <!--LES FICHIERS ET LES FACTURES-->
                                    <tr>
                                        @if($contrats->path == null)

                                        @else
                                            <td>
                                            
                                                @php
                                                    $pieces = explode("/", $contrats->path);
                                                    echo $pieces[2];
                                                @endphp
                                            </td>
                                        <td>
                                        @php 
                                            echo "<b>".date('d/m/Y',strtotime($contrats->created_at))."</b>" ;
                                        @endphp
                                        </td>
                                        <td>
                                            
                                            <form action="view_contrat" method="post" enctype="multipart/form-data">

                                                @csrf
                                                <div class="box-body">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                                        
                                                        <input type="text" class="form-control" name="file" value="{{$contrats->path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>

                                            
                                                    </div>

                                                </div>
                                            
                                            </form>

                                        </td>
                                        @endif
                                       
                                    </tr>
                                
                                </table>
                            </div>
                             <hr>

                            <div class="box-header with-border">
                                <h3 class="box-title"><b>FACTURE PROFORMA :</b></h3>
                            </div>
                            <div class="no-padding">
                                <table class="table table-hover box-body">
                                
                                    <tr>
                                        <th>Nom</th>
                                        
                                        <th>Ajouté le :</th>
                                        <th style="width: 40px">Aperçu</th>
                                    </tr>
                                    <!--LES FICHIERS ET LES FACTURES-->
                                    <tr>
                                        @if($contrats->proforma_file != null)
                                             <td> 
                                            
                                            @php
                                            $pieces = explode("/", $contrats->proforma_file);
                                            echo $pieces[2];
                                            @endphp
                                            
                                         </td>

                                        <td>
                                            @php 
                                                echo "<b>".date('d/m/Y',strtotime($contrats->created_at))."</b> " ;
                                            @endphp
                                        </td>
                                        <td>
                                            <form action="view_contrat_proforma" method="post" enctype="multipart/form-data">

                                                @csrf
                                                <div class="box-body">
                                                    <div class="form-group col-sm-6">
                                                        <input type="text" value="{{$contrats->id}}" style="display:none;" name="id_contrat">
                                                       
                                                    <input type="text" class="form-control" name="proforma_file" value="{{$contrats->proforma_file}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </div>

                                                </div>
                                            
                                            </form>
                                        </td>
                                        @else
                                        
                                        @endif
                                       
                                    </tr>
                                
                                </table>
                            </div>

                         <div class="box-header with-border">
                        <h3 class="box-title"><b>PRESTATIONS DU CONTRAT {{$contrats->titre_contrat}}</b></h3>
                    </div> 
                    <div class="no-padding">
                         @php
                            //On va écrire un code pour detecter tous les services offerts
                            $se = DB::table('prestation_services')
                            ->join('contrats', 'prestation_services.contrat_id', '=', 'contrats.id')
                            ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                            ->where('contrat_id', $contrats->id)    
                            ->get(['services.libele_service', 'prestation_services.*',]);

                          
                        @endphp
                            <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table  class="table box-body">
                            
                                <tr>
                                <th>Service </th>
                                <th>Action</th>
                                </tr>
                           
                                <tbody>
                                     @foreach($se as $se_get)
                                        <tr>
                                        
                                            <td>{{$se_get->libele_service}}</td>
                                            <td>
                                                @can("delete")	
                                                    <form action="delete_service_fiche_customer" method="post" >
                                                        @csrf
                                                        <div class="box-body">
                                                            <div class="form-group">
                                                                <input type="text" value="{{$contrats->id}}" style="display:none;" name="id_prospection">
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
                                </tbody>
                                
                            </table>
                        </div>
                            <!-- /.box-body -->
                 
                    </div>
                    <hr><hr>
                    @endforeach
                    
                     <div class="box-header" >
                            <h3 class="box-title"><b>PROSPECTIONS</b></h3>
                    </div> 
                    @if($count_prospection == 0)
                        <div class="box-header" style="text-align:center">
                            <h3 class="box-title"><b>Pas de Prospection réalisée</b></h3>
                        </div> 
                        <hr>
                    @endif
                    @foreach($prospections as $prospections)
                      

                        <!-- form start  INFO SUR LA PROPESCTION DANS LA TABLE-->
                        <div class="form-horizontal">
                         
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
                                             
                                            </tr>
                                        
                                        @endforeach
                                    
                                    </table>
                                </div>

                            
                                
                                
                                </ul>
                            </div>
                        </div>

                     
                        <hr>

                         <!--LES FICHIERS ET LES FACTURES DANS LA TABLE PROSPECTION-->

                        <div class="box-header with-border">
                            <h3 class="box-title"><b>FACTURE PROFORMA</b></h3>
                        </div>
                           
                        <div class="no-padding">
                            <table class="table table-hover box-body">
                               
                                <tr>
                                    <th>Nom</th>
                                    <th>Ajouté le :</th>
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
                                                echo "<b>".date('d/m/Y',strtotime($prospections->created_at))."</b> " ;
                                            @endphp
                                        </td>
                                        <td>
                                            <form action="edit_prospect_form" method="post">
                                                @csrf
                                                <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                            </form>
                                        </td>
                                        <td>
                                            
                                            <form action="download_facture_proforma" method="post" enctype="multipart/form-data">

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
                                                echo "<b>".date('d/m/Y',strtotime($prospections->created_at))."</b>" ;
                                            @endphp
                                        </td>
                                        <td>
                                            
                                            <form action="download_facture_proforma" method="post" enctype="multipart/form-data">

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

                        

                        <!--LES CR DE VISITE DANS LA TABLE PROPSECTION-->
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>COMPTE RENDU DE VISITE</b></h3>
                        </div>
                         
                        <div class="form-group no-padding">
                            <table class="table table-hover box-body">
                               
                                <tr>
                            
                                    <th>Nom</th>
                                    <th>Ajouté le</th>
                                    <th style="width: 40px">Aperçu</th>
                                </tr>
                               
                              <tr>
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
                                        <td>
                                            <form action="edit_prospect_form" method="post">
                                                @csrf
                                                <input type="text" value={{$prospections->id}} style="display:none;" name="id_prospection">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit">Aller a la page prospections pour modifier</i></button>
                                            </form>
                                        <td>
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
                                         <td>
                                            @php 
                                                echo "<b>".date('d/m/Y',strtotime($select->created_at))."</b>" ;
                                            @endphp
                                        </td>
                                        <td>
                                            
                                            <form action="download_facture_proforma" method="post" enctype="multipart/form-data">

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

                       
                        @php
                            $docs = $documentController->GetDocByProspection($prospections->id);  
                        @endphp

                        <!--AUTRE DOCS-->
                    
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>AUTRE DOCUMENTS (facture supplémentaires & autres)</b></h3>
                        </div>
                        <div class="box-body no-padding">
                            <table class="table table-hover">
                                <tr>
                            
                                    <th>Nom</th>
                                    
                                    <th style="width: 40px">Aperçu</th>
                                </tr>
                                @foreach($docs as $docs)
                                    <!--LES FICHIERS ET LES FACTURES-->
                                <tr>
                                    <td>  <span class="text">{{$docs->libele}}</span> </td>
                                   
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
                       
                        @php
                            $propal = $propalcontroller->GetByIdEntreprise($prospections->id);  
                        @endphp
                        <!--LES PROPOSITIONS -->

                        <!--PROPOSITION-->
                        
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>PROPOSITIONS</b></h3>
                        </div>
                        <div class="box-body no-padding">
                            <table class="table table-hover">
                                <tr>
                            
                                    <th>Nom</th>
                                    <th>Ajouté le :</th>
                                    <!--LES RESTRICTIONS -->
                                    <th>Supprimer</th>
                                    <th style="width: 40px">Aperçu</th>

                                </tr>
                                @foreach($propal as $propal)
                                    <!--LES FICHIERS ET LES FACTURES-->
                                <tr>
                                    <td>  <span class="text">{{$propal->libele}}</span> </td>
                                    <td>
                                        @php 
                                            echo "<b>".date('d/m/Y',strtotime($propal->created_at))."</b> " ;
                                        @endphp
                                    </td>
                                    <td>
                                        @can("delete")
                                            <form action="delete_doc_propal_cust" method="post" enctype="multipart/form-data">

                                                @csrf
                                                <input type="text" value="{{$prospections->id}}" style="display:none;" name="id_prospection">
                                                <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                <input type="text" value="{{$propal->id}}" style="display:none;" name="id_doc">
                                                <input type="text" class="form-control" name="file" value="{{$propal->path_doc}}" style="display:none;">
                                                <button type="submit" class="btn btn-sx btn-danger"><i class="fa fa-trash-o"></i></button>
                                            </form>
                                        
                                        @endcan
                                            
                                    </td>
                                    <td>
                                        
                                        <form action="download_docs_propal_cust" method="post" enctype="multipart/form-data" class="col-sm-6">

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
                    
                        @can("edit")	
                            <div class="box-body">
                                <form action="add_doc_proposition_cust" method="post" enctype="multipart/form-data" class="col-sm-12">

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
                            
                       
                    @endforeach
                    <hr>

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
     
                                    @can("edit")
                                        <form action="edit_interlocuteur_form_fichec" method="post">
                                            @csrf
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
                                                    <form action="delete_interlocuteur_from_fichec" method="post">
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
                               
                         
                                </td>
                            </tr>
                        
                        @endforeach
                    
                    </table>
       
                    <hr>

                   
                </div>
                <hr>
                <!-- /.box -->

            </div>
            
            <div class="col-md-2"></div>
        </div>
        <!--/.col (right) -->
    @endif
    
@endsection 
 
 
 
          