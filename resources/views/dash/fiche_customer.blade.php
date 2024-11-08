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

    $contratcontroller = new ContratController();
    $entreprisecontroller = new EntrepriseController();
    $prestationcontroller = new PrestationController();
    $prospectioncontroller = new ProspectionController();
    $facturecontroller = new FactureController();
    $interlocuterController = new InterlocuteurController();
    $documentController = new DocController();
    $categoriecontroller = new CategorieController();
    $servicecontroller = new ServiceController();
    

@endphp

@section('content')

    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
             
             
            <div class="col-md-3">
                <a href="fiche"><button class="btn btn-default"> <b>RETOUR</b></button></a>
            </div>
             <div class="col-md-3">
                <a href="prestation"><button class="btn btn-warning"> <b>PRESTAIONS</b></button></a>
             </div>
            <div class="col-md-3">
               <a href="form_add_contrat"><button class="btn btn-primary"> <b>AJOUTER UN CONTRAT</b></button></a>
            </div>

             <div class="col-md-3"></div>
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
            @if(session('success'))
                <div class="col-md-12 box-header">
                <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
                </div>
            @endif

             @if(session('error'))
                <div class="col-md-12 box-header">
                <p class="bg-danger" style="font-size:13px;">{{session('error')}}</p>
                </div>
            @endif

            @if(isset($error))
                <div class="col-md-12 box-header">
                <p class="bg-danger" style="font-size:13px;">{{$error}}</p>
                </div>
            @endif

             @if(isset($success))
                <div class="col-md-12 box-header">
                <p class="bg-success" style="font-size:13px;">{{$success}}</p>
                </div>
            @endif
           
        </div>
  

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
                        <h3 class="box-title"><b>{{$nom->nom_entreprise}}</b></h3>
                        </div>
                        <!-- /.box-header -->
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
                        <div class="box-body">
                            <form action="fiche_edit_contrat_form" method="post" >
                                    @csrf
                                    <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                    <input type="text" value={{$id_entreprise}} style="display:none;" name="id_entreprise">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-edit">MODIFIER</i></button>
                            </form>
                        </div>


                        <!--LES FICHIERS ET LES FACTURES DANS LA TABLE CONTRAT-->
                           
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>FICHIER DU CONTRAT</b></h3>
                            </div>
                             <div class="no-padding">
                                <table class="table table-hover box-body">
                                
                                    <tr>
                                        <th>Nom</th>
                                    
                                        <th style="width: 40px">Aperçu</th>
                                    </tr>
                                    <!--LES FICHIERS ET LES FACTURES-->
                                    <tr>
                                        <td> <label>{{$contrats->path}}</label>  </td>
                                        
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
                                    
                                        <th style="width: 40px">Aperçu</th>
                                    </tr>
                                    <!--LES FICHIERS ET LES FACTURES-->
                                    <tr>
                                        <td> <label>{{$contrats->proforma_file}}</label>  </td>
                                        
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
                                    </tr>
                                
                                </table>
                            </div>
                           

                      
                        
                    @endforeach
                    <div class="box-header">
                        <h3 class="box-title"><b>PRESTATIONS REALISEES</b></h3>
                    </div> 
                    <div class="no-padding">

                            <!-- /.box-header -->
                        <div class="box-body">
                            <table  class="table table-hover box-body">
                                <thead>
                                <tr>
                                <th>Date </th>
                                <th>Type de prestation</th>
                                <th>Lieu</th>
                                
                                <th>Fin de contrat</th>
                                <th>Prestation</th>
                                 
                                @if(auth()->user()->id_role == 3)
                                @else
                                    <th>Action</th>
                                @endif
                                
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($prestations as $prestations)
                                        <tr>
                                            <td>@php echo date('d/m/Y',strtotime($prestations->date_prestation)) @endphp</td>
                                            <td>{{$prestations->libele}}</td>
                                            <td>{{$prestations->localisation}}</td>
                                            
                                            <td>@php echo date('d/m/Y',strtotime($prestations->fin_contrat));  @endphp</td>
                                            <td>
                                                @php
                                                    //On va écrire un code pour detecter tous les services offerts
                                                    $se = DB::table('prestation_services')
                                                    ->join('prestations', 'prestation_services.prestation_id', '=', 'prestations.id')
                                                    ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                                                    ->where('prestation_id',$prestations->id)    
                                                    ->get(['services.libele_service', 'prestation_services.*']);
                                                @endphp
                                                <ul>
                                                @foreach($se as $se_get)
                                                    
                                                        
                                                        <form action="delete_service_fiche_customer" method="post" >
                                                                <li>{{$se_get->libele_service}}</li>
                                                            @csrf
                                                            <div class="box-body">
                                                                <div class="form-group col-sm-6">
                                                                    <input type="text" value="{{$contrats->id}}" style="display:none;" name="id_prospection">
                                                                    <input type="text" value="{{$se_get->id}}" style="display:none;" name="id_service">
                                                                    <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                                </div>

                                                            </div>
                                                        
                                                        </form>
                                                    
                                                @endforeach
                                                </ul>
                                            
                                            </td>
                                            
                                            
                                            
                                                @if(auth()->user()->id_role == 3)
                                                
                                                @else
                                                <td>
                                                    @if(auth()->user()->id_role == 2)
                                                    <form action="display_facture" method="post">
                                                        @csrf
                                                        <input type="text" value={{$prestations->id}} style="display:none;" name="id_prestation">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-ticket"></i></button>
                                                    </form>
                                                    @else

                                                    @endif

                                                
                                                    <form action="edit_prestation_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$prestations->id}} style="display:none;" name="id_prestation">
                                                        <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                    </form>

                                                </td>
                                                @endif

                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                            </table>
                        </div>
                            <!-- /.box-body -->
                 
                    </div>
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
                                            
                                        </tr>
                                        <!--LES FICHIERS ET LES FACTURES-->
                                        
                                        @foreach($se as $se_get)
                                            <tr>
                                                <td>  <span class="text"><b>{{$se_get->libele_service}}</b></span></td>
                                            
                                                <td>
                                                        @php 
                        
                                                        echo "<b>".date('d/m/Y',strtotime($se_get->created_at))."</b> à <b>".date('H:i:s',strtotime($se_get->created_at))."</b>" ;
                                                
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
                                    
                                    <th style="width: 40px">Aperçu</th>
                                </tr>
                                <!--LES FICHIERS ET LES FACTURES-->
                                <tr>
                                    <td>  <span class="text">{{$prospections->facture_path}}</span> </td>
                                    
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
                                  
                                    <th style="width: 40px">Aperçu</th>
                                </tr>
                               
                                <tr>
                                    <td>  <span class="text">{{$prospections->path_cr}}</span> </td>
                                
                                    
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
                          
                            
                        </tr>
                        <!--LES FICHIERS ET LES FACTURES-->
                        
                        @foreach($interlocuteurs as $interlocuteurs)
                            <tr>
                                <td> {{$interlocuteurs->titre}} {{$interlocuteurs->nom}}</td>
                            
                                <td>
                                    {{$interlocuteurs->tel}}
                                </td>
                                <td>{{$interlocuteurs->email}}</td>
                                <td>{{$interlocuteurs->fonction}}</td>
                               
                            </tr>
                        
                        @endforeach
                    
                    </table>
       
                    <hr>
                    <div class="box-body">
                        <!--DEUXIEMEN PARTIE DU FORMULAIRE-->
                        <form action="add_referant_in_fiche_customer" method="post">         
                            @csrf
                            <div class="box-header">
                                <h3 class="box-title"><b>AJOUTER UN INTERLOCUTEUR </b></h3>
                            </div> 

                            <div class="form-group">
                                @php
                                    $nom = $entreprisecontroller->GetById($id_entreprise)
                                @endphp
                              
                                        
                                <select class="form-control input-lg" name="entreprise">
                                    @foreach($nom as $nom)
                                        <option value={{$id_entreprise}}>{{$nom->nom_entreprise}}</option>

                                    @endforeach
                                   
                                </select>
                                
                            </div>        

                            <div class="form-group">
                                <label for="exampleInputFile">Titre :</label>
                                <select class="form-control input-lg" name="titre" id="grise1" >
                                    <option value="M">M</option>
                                    <option value="Mme">Mme</option>
                                    <option value="Mlle">Mlle</option>
                                </select>
                                
                            </div>
                            <div class="form-group">
                                    <label >Nom & Prénom(s)</label>
                                    <input type="text" maxlength="100" required id="grise2" class="form-control  input-lg" name="nom" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" id="grise5" maxlength="30" class="form-control input-lg" name="email" >
                            </div>

                            <div class="form-group">
                                    <label>Téléphone (*)</label>
                                    <input type="text" required id="grise3" maxlength="30"   class="form-control input-lg" name="tel" placeholder="(+225)0214578931" >
                                </div>

                            <div class="form-group">
                                    <label>Fonction</label>
                                    <input required type="text" class="form-control input-lg"  id="grise4" maxlength="60" name="fonction" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <button class="btn btn-primary" >Ajouter</button>  
                        </form>
                   </div>
                   
                </div>
                <hr>
                <!-- /.box -->

                
            </div>
            
            <div class="col-md-2"></div>
        </div>
        <!--/.col (right) -->
    @endif
    
@endsection 
 
 
 
          