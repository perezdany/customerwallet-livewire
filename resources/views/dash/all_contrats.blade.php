@extends('layouts/base')

@php
  //header("Refresh:0");
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;
    use App\Http\Controllers\StatutEntrepriseController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\CategorieController;

    $statutentreprisecontroller = new StatutEntrepriseController();

    $contratcontroller = new ContratController();

    $categoriecontroller = new CategorieController();

    $servicecontroller = new ServiceController();

    $all = $contratcontroller->RetriveAll();

    /*IMPORTANT ! ECRIRE UN CODE ICI POUR SI A CETTE DATE LE CONTRAT DOIT ETRE RECONDUIT ON ACTUALISE LA DATE DE FIN */
@endphp

@section('content')
      <div class="row">
        <div class="col-md-3">
          <a href="form_add_contrat"><button class="btn btn-success"> <b><i class="fa fa-plus"></i>CONTRAT</b></button></a>
                
        </div>   

        <div class="col-md-3">
         
        </div>
      </div>
    <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-green" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
            @if(session('error'))
            <div class="col-md-12 box-header">
              <p class="bg-warning" style="font-size:13px;">{{session('error')}}</p>
            </div>
          @endif
          @if(isset($message_error))
            <div class="col-md-12 box-header">
              <p class="bg-warning" style="font-size:13px;">{{$message_error}}</p>
            </div>
          @endif
           @if(isset($message_success))
            <div class="col-md-12 box-header">
              <p class="bg-green" style="font-size:13px;">{{$message_success}}</p>
            </div>
          @endif
        @if(isset($contrats))
            @php
              //dd('ici');
              //dd($contrats);
            @endphp
            <div class="col-md-12">
              <div class="box">
                  
                  <div class="box-header">
                    <h3 class="box-title">Bases de données des contrats</h3><br>
                   
                    <form role="form" method="post" action="make_filter_contrat">
                      @csrf
                       <a href="contrat" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a>&emsp;&emsp;&emsp;&emsp; <label>Filtrer par:</label>
                      <div class="box-body">
                        <div class="row">
                        
                          <div class="col-md-2">
                            <select class="form-control" name="entreprise">
                                @if($id_entreprise == "all")

                                  <option value="all">Entreprises</option>
                                @else

                                  @php
                                      $le_nom_entreprise = (new EntrepriseController())->GetById($id_entreprise);
                                     
                                  @endphp
                                  
                                  @foreach($le_nom_entreprise as $le_nom_entreprise)

                                      <option value={{$le_nom_entreprise->id}}>{{$le_nom_entreprise->nom_entreprise}}</option>
                                     
                                  @endforeach
                                  <option value="all">Toutes les Entreprises</option>
                                
                                @endif
                                
                                @php
                                  
                                  $get = (new EntrepriseController())->GetAll();
                                  
                                @endphp
                            
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>   
                          </div>    

                          <div class="col-md-2">
                      
                            <select class="form-control" name="reconduction">
                                @if($reconduction == "c")
                                  <option value="c">Renouvellement</option>
                                 <option value="0">Non</option>
                                <option value="1">Tacite</option>
                                <option value="2">Accord parties</option>

                                @else
                                  @if($reconduction == 0)
                                  <option value="0">Non</option>
                                  <option value="c">Renouvellement</option>
                                  <option value="1">Tacite</option>
                                  <option value="2">Accord parties</option>
                                  
                                  @else
                                    @if($reconduction == 1)
                                    <option value="0">Tacite</option>
                                    <option value="c">Renouvellement</option>
                                    <option value="1">Non</option>
                                    <option value="2">Accord parties</option>
                                    @endif

                                    @if($reconduction == 2)
                                    <option value="2">Accord parties</option>
                                    <option value="c">Renouvellement</option>
                                    <option value="0">Non</option>
                                    <option value="1">Tacite</option>
                                    
                                    @endif
                                  @endif
                                @endif
                                
                            </select>
                                                        
                          </div>

                          <div class="col-md-2">
                      
                            <select class="form-control" name="etat_contrat">
                                @if($etat == "c")
                                  <option value="c">Etat</option>
                                  <option value="0">En cours</option>
                                  <option value="1">Terminé</option>
                                @else 
                                  @if($etat == 0)
                                      <option value="0">En cours</option>
                                      <option value="1">Terminé</option> 
                                      <option value="c">--Rétablir--</option>
                                    @else
                                      <option value="1">Terminé</option>
                                      <option value="0">En cours</option>
                                      <option value="c">--Rétablir--</option>
                                    @endif 
                                @endif                       
                            </select>
                                                        
                          </div>
                          <div class="col-md-4">  
                          
                            <select class="form-control input-lg select2" name="service">
                              <!--liste des services a choisir -->
                              @if($service != "service")
                                @php
                                  //AFFICHER LE SERVICE SELECTIONNE
                                  //dd($service);
                                  $serv = DB::table('services')->where('id', $service)->get();
                                @endphp
                                @foreach($serv as $serv)
                                    <option value={{$serv->id}}>{{$serv->libele_service}}</option>    
                                @endforeach
                                <option value="service">--Rétablir--</option>
                                
                                @php
                                    $get = $servicecontroller->GetAll();
                                    $categorie = $categoriecontroller->DisplayAll();
                                @endphp
                                @foreach( $categorie as $categorie)
                                  
                                  <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                  @php
                                      $get = $servicecontroller->GetByCategorieNoSusp($categorie->id);
                                      
                                  @endphp
                                  @foreach($get as $serv)
                                      <option value={{$serv->id}}>{{$serv->libele_service}}</option>
                                  @endforeach
                                @endforeach
                              @else
                                  <option value="service">Service</option>
                                @php
                                    $get = $servicecontroller->GetAll();
                                    $categorie = $categoriecontroller->DisplayAll();
                                @endphp
                                @foreach( $categorie as $categorie)
                                  
                                  <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                  @php
                                      $get = $servicecontroller->GetByCategorieNoSusp($categorie->id);
                                      
                                  @endphp
                                  @foreach($get as $serv)
                                      <option value={{$serv->id}}>{{$serv->libele_service}}</option>
                                  @endforeach
                                @endforeach
                              @endif   
                            </select>
                      
                          </div>

                          <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                          </div>
                          
                        </div>
                        
                      </div>
                      <!-- /.box-body -->
                    </form>
                  

                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                  
                    <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                      <th>Titre de contrat</th>
                      <th>Entreprise</th>
                      <th>Début du contrat</th>
                      <th>Fin du contrat</th>
                      <th>Montant</th>	
                      <th>Service</th><!--VERIFIER SI LE CONTRAT EST RECONDUIT-->
                      <th>Fichier du contrat</th>
                      <th>Proforma</th>
                      <th>Bon de commande</th>
                      @if(auth()->user()->id_role == 3)
                      @else
                        <th>Modifier</th>
                      @endif
                    
                    </tr>
                    </thead>
                    <tbody>
                   
                        @foreach($contrats as $contrats)
                          <tr>
                         
                            <td>{{$contrats->titre_contrat}}</td>
                            <td>{{$contrats->nom_entreprise}}</td>
                  
                            
                            <td>@php echo date('d/m/Y',strtotime($contrats->debut_contrat)) @endphp</td>
                            <td>@php echo date('d/m/Y',strtotime($contrats->fin_contrat)) @endphp</td>
                            <td>
                              @php
                                echo  number_format($contrats->montant, 2, ".", " ")." XOF";
                              @endphp
                            
                            </td>  
                           
                            <td>
                            
                              @if($service != "service")
                                   @php
                                    
                                    //On va écrire un code pour detecter tous les services offerts
                                    $se = DB::table('prestation_services')
                                    ->join('prestations', 'prestation_services.prestation_id', '=', 'prestations.id')
                                    ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                                    ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id') 
                                    ->where('prestations.id_contrat', $contrats->id)
                                    ->where('services.id', $service)
                                    ->get(['services.libele_service', 'prestation_services.*']);
                                    
                                @endphp
                                <ul>
                                @foreach($se as $se_get)
                                    <li>{{$se_get->libele_service}}</li>
                                @endforeach
                                </ul>
                              @else
                                   @php
                                
                                    //On va écrire un code pour detecter tous les services offerts
                                    $se = DB::table('prestation_services')
                                    ->join('prestations', 'prestation_services.prestation_id', '=', 'prestations.id')
                                    ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                                    ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id') 
                                     ->where('prestations.id_contrat', $contrats->id)
                                    ->get(['services.libele_service', 'prestation_services.*']);
                                    
                                @endphp
                                <ul>
                                @foreach($se as $se_get)
                                    <li>{{$se_get->libele_service}}</li>
                                @endforeach
                                </ul>
                              @endif
                             
                            </td>
                            <td>
                             
                              <form action="download" method="post" enctype="multipart/form-data" target="blank">
                                @csrf
                                  
                                  <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file" value="{{$contrats->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>
                            <td>
                              
                              
                              <form action="view_contrat_proforma" method="post" enctype="multipart/form-data" target="blank">
                                @csrf
                                
                                  <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="proforma_file" value="{{$contrats->proforma_file}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>
                            <td>
                              <form action="view_bon_commande" method="post" enctype="multipart/form-data" traget="blank">
                                @csrf
                                
                                <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="proforma_file" value="{{$contrats->bon_commande}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>

                             @if(auth()->user()->id_role == 3)
                            @else
                            
                               
                              
                              <td>
                                 <!--MODIFICATION AVEC POPUP-->
                           
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#edit".$contrats->id.""; @endphp">
                              <i class="fa fa-edit"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "edit".$contrats->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Modifier </h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <form  method="post" action="edit_contrat" enctype="multipart/form-data">
                                        @csrf
                                        <input type="text" name="id_contrat" value="{{$contrats->id}}" style="display:none;">
                           
                                           <!--LES ELEMETS DU FILTRE-->
                                          
                                            <select class="form-control" name="reconduction" style="display:none;">
                                                @if($reconduction == "c")
                                                  <option value="c">Renouvellement</option>
                                                <option value="0">Non</option>
                                                <option value="1">Tacite</option>
                                                <option value="2">Accord parties</option>

                                                @else
                                                  @if($reconduction == 0)
                                                  <option value="0">Non</option>
                                                  <option value="c">Renouvellement</option>
                                                  <option value="1">Tacite</option>
                                                  <option value="2">Accord parties</option>
                                                  
                                                  @else
                                                    @if($reconduction == 1)
                                                    <option value="0">Tacite</option>
                                                    <option value="c">Renouvellement</option>
                                                    <option value="1">Non</option>
                                                    <option value="2">Accord parties</option>
                                                    @endif

                                                    @if($reconduction == 2)
                                                    <option value="2">Accord parties</option>
                                                    <option value="c">Renouvellement</option>
                                                    <option value="0">Non</option>
                                                    <option value="1">Tacite</option>
                                                    
                                                    @endif
                                                  @endif
                                                @endif
                                                
                                            </select>
                                            <select class="form-control" name="entreprise_filter" style="display:none;">
                                                @if($id_entreprise == "all")
                                                  <option value="all">Entreprises</option>
                                                @else

                                                  @php
                                                      $le_nom_entreprise = (new EntrepriseController())->GetById($id_entreprise);
                                                  @endphp
                                                  
                                                  @foreach($le_nom_entreprise as $le_nom_entreprise)
                                                      <option value={{$le_nom_entreprise->id}}>{{$le_nom_entreprise->nom_entreprise}}</option>
                                                    
                                                  @endforeach
                                                  <option value="all">Toutes les Entreprises</option>
                                                
                                                @endif
                                                
                                                @php
                                                  
                                                  $get = (new EntrepriseController())->GetAll();
                                                  
                                                @endphp
                                            
                                                @foreach($get as $entreprise)
                                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                    
                                                @endforeach
                                                
                                            </select>   

                                            <select class="form-control" name="etat_contrat" syle="display:none">
                                            @if($etat == "c")
                                              <option value="c">Etat</option>
                                              <option value="0">En cours</option>
                                              <option value="1">Terminé</option>
                                            @else 
                                              @if($etat == 0)
                                                  <option value="0">En cours</option>
                                                  <option value="1">Terminé</option> 
                                                  <option value="c">--Rétablir--</option>
                                                @else
                                                  <option value="1">Terminé</option>
                                                  <option value="0">En cours</option>
                                                  <option value="c">--Rétablir--</option>
                                                @endif 
                                            @endif                       
                                        </select>
                                            <select class="form-control input-lg" name="service" style="display:none;">
                                            <!--liste des services a choisir -->
                                            @if($service != "service")
                                              @php
                                                //AFFICHER LE SERVICE SELECTIONNE
                                                //dd($service);
                                                $serv = DB::table('services')->where('id', $service)->get();
                                              @endphp
                                              @foreach($serv as $serv)
                                                  <option value={{$serv->id}}>{{$serv->libele_service}}</option>    
                                              @endforeach
                                              <option value="service">--Rétablir--</option>
                                              
                                              @php
                                                  $get = $servicecontroller->GetAll();
                                                  $categorie = $categoriecontroller->DisplayAll();
                                              @endphp
                                              @foreach($categorie as $categorie)
                                                
                                                <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                                @php
                                                    $get = $servicecontroller->GetByCategorieNoSusp($categorie->id);
                                                    
                                                @endphp
                                                @foreach($get as $serv)
                                                    <option value={{$serv->id}}>{{$serv->libele_service}}</option>
                                                @endforeach
                                              @endforeach
                                            @else
                                                <option value="service">Service</option>
                                              @php
                                                  $get = $servicecontroller->GetAll();
                                                  $categorie = $categoriecontroller->DisplayAll();
                                              @endphp
                                              @foreach( $categorie as $categorie)
                                                
                                                <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                                @php
                                                    $get = $servicecontroller->GetByCategorieNoSusp($categorie->id);
                                                    
                                                @endphp
                                                @foreach($get as $serv)
                                                    <option value={{$serv->id}}>{{$serv->libele_service}}</option>
                                                @endforeach
                                              @endforeach
                                            @endif   
                                          </select>
                      
                                          <!--FIN ELEMENT DU FILTRE-->

                                          <div class="row">
                                            <div class="col-sm-6">  <label>Réfrence du contrat:</label></div>
                                            <div class="col-md-6"><input type="text"  maxlength="100" value="{{$contrats->titre_contrat}}" class="form-control "
                                             name="titre" placeholder="Ex: Contrat de sureté BICICI"/></div>
                                          </div> <br>
                        
                                          <div class=" row">
                                              <div class="col-sm-6"><label>Entreprise:</label></div>
                                              @php
                                                  $get = (new EntrepriseController())->GetAll();
                                              @endphp
                                              <div class="col-sm-6">
                                                <select class="form-control select2" name="entreprise">
                                                  @php
                                                      $get = (new EntrepriseController())->GetAll();
                                                  @endphp
                                                  <option value="{{$contrats->id_entreprise}}">{{$contrats->nom_entreprise}}</option>
                                                  @foreach($get as $entreprise)
                                                      <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                      
                                                  @endforeach
                                              </select>
                                            
                                              </div>
                                          </div>  <br>
                                            
                                          <div class=" row">
                                            <div class="col-sm-6"><label >Reconduction:</label></div>
                                            <div class="col-sm-6"> <select class="form-control" name="reconduction" required>
                                                @if($contrats->reconduction == 1)
                                                    <option value="1">TACITE</option>
                                                    <option value="0">NON</option>
                                                    
                                                    <option value="2">ACCORD PARTIES</option>
                                                @else
                                                    @if($contrats->reconduction == 0)
                                                    <option value="0">NON</option>
                                                        <option value="1">TACITE</option>       
                                                        <option value="2">ACCORD PARTIES</option>
                                                    @endif

                                                    @if($contrats->reconduction == 2)
                                                        <option value="2">ACCORD PARTIES</option>
                                                        <option value="0">NON</option>
                                                        <option value="1">TACITE</option>       
                                                        
                                                    @endif
                                                @endif
                                                
                                            </select></div>
                                          </div><br>
                                           <div class=" row">
                                              <div class="col-sm-6"><label>Avenant ?</label></div>
                                              <div class="col-sm-6">
                                              <select class="form-control " name="avenant" id="mySelectAvenant" onchange="griseFunction1()" >
                                              
                                                 @if($contrats->avenant == 0)
                                                    <option value="{{$contrats->avenant}}">NON</option>
                                              
                                                    <option value="1">OUI</option>
                                                  
                                                @else
                                                    @if($contrats->avenant == 1)
                                                        <option value="{{$contrats->avenant}}">OUI</option>
                                              
                                                    <option value="0">NON</option>
                                                    @endif
                                                  
                                                @endif
                                              
                                                
                                            </select>
                                                </div>
                                          </div><br>
                                            <div class="row">
                                              <div class="col-sm-6"> <label >Contrat Parent:</label></div>
                                              <div class="col-sm-6">
                                                <select class="form-control select2" name="contrat_parent" id="contratparent" disabled required>
                                                  @php
                                                      $getcontrat =  DB::table('contrats')
                                                      ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                                      ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                                                      ->orderBy('entreprises.nom_entreprise', 'asc')
                                                      ->where('contrats.id', $contrats->id_contrat_parent)
                                                      ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);
                                                  @endphp

                                                  @foreach($getcontrat as $getcontrat)
                                                      <option value={{$getcontrat->id}}>{{$getcontrat->titre_contrat}}/{{$getcontrat->nom_entreprise}}</option>
                                                      
                                                  @endforeach

                                                  @php
                                                      $getparent = ($contratcontroller)->GetContratParent();
                                                  @endphp
                                                  <option value="0">--Choisir le contrat--</option>
                                                  @foreach($getparent as $getparent)
                                                      <option value={{$getparent->id}}>{{$getparent->titre_contrat}}/{{$getparent->nom_entreprise}}</option>
                                                      
                                                  @endforeach
                                                  
                                              </select></div>
                                            </div><br>
                                              <script>
                                                  function griseFunction1() {
                                                      /* ce script permet d'activer les champ si l'utilisateur choisit autre*/
                                                      var val = document.getElementById("mySelectAvenant").value;
                                                      
                                                      if( val == '1')
                                                      {
                                                      document.getElementById("contratparent").removeAttribute("disabled");
                                                      
                                                      }
                                                      else
                                                      {
                                                      document.getElementById("contratparent").setAttribute("disabled", "disabled");
                                                      
                                                      }
                                                  
                                                  }
                                              </script>   
                                              <div class="row">
                                              <div class="col-sm-6"><label >Montant (XOF):</label></div>
                                              <div class="col-sm-6"><input type="number" class="form-control " required name="montant"  value="{{$contrats->montant}}">
                                              </div><br><br>
                                            <div class="row"> 
                                              <div class="col-sm-6"><label >Debut du contrat:</label></div>
                                              <div class="col-sm-6">
                                              <input type="date" class="form-control " required name="date_debut"  value="{{$contrats->debut_contrat}}">
                                              </div>
                                            </div><br>
                                            <div class="row">
                                              <div class="col-sm-6"><label>Fichier du contrat(PDF)</label></div>
                                              <div class="col-sm-6"> <input type="file" class="form-control" name="file"></div>
                                            </div><br>
                                            <div class="row">
                                                <div class="col-sm-6"><label>Facture proforma :</label></div>
                                                <div class="col-sm-6"><input type="file" class="form-control" name="file_proforma"></div>
                                            </div><br>
                                            <div class="row">
                                                <div class="col-sm-6"><label>Bon de commande(PDF) :</label></div>
                                                <div class="col-sm-6"><input type="file" class="form-control" name="bon_commande" ></div>
                                            </div><br>

                                          <div class="modal-footer">
                                          <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                          <button type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                        </form>
                                      
                                      </div>
                                    
                                      
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>    
                              <!-- /.modal -->
                                  

                              </td>
                            @endif  

                          </tr>
                        @endforeach
                    </tbody>
                  
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
          </div>
          <!-- /.col -->
        @else
          <div class="col-md-12">
              <div class="box">
                  
                  <div class="box-header">
                    <h3 class="box-title">Bases de données des contrats</h3><br>
                   
                    <form role="form" method="post" action="make_filter_contrat">
                      @csrf
                       <a href="contrat" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp;&emsp;&emsp; <label>Filtrer par:</label>
                      <div class="box-body">
                        <div class="row">
                        
                          <div class="col-md-2">
                            <select class="form-control" name="entreprise">
                                <option value="all">Entreprises</option>
                                @php
                                    $get = (new EntrepriseController())->GetAll();
                                @endphp
                              
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>   
                          </div>    

                          <div class="col-md-2">
                      
                            <select class="form-control" name="reconduction">
                            
                                <option value="c">Renouvellement</option>
                                <option value="0">Non</option>
                                <option value="1">Tacite</option>
                                <option value="2">Accord parties</option>
                            </select>
                                                        
                          </div>

                          <div class="col-md-2">
                      
                            <select class="form-control" name="etat_contrat">
                            
                              <option value="c">Etat</option>
                              <option value="0">En cours</option>
                              <option value="1">Terminé</option>
                            </select>
                                                        
                          </div>

                          <div class="col-md-4">
                      
                            <select class="form-control input-lg select2" name="service">
                                  <!--liste des services a choisir -->
                                  <option value="service">Service</option>
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

                          <div class="col-md-2">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                          </div>
                          
                        </div>

                      
                      </div>
                      <!-- /.box-body -->
                    </form>
                  

                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                      <th>Titre de contrat</th>
                       <th>Entreprise</th>
                      <th>Début du contrat</th>
                      <th>Fin du contrat</th>
                      <th>Montant</th>	
                      <th>Services</th><!--LA LISTE DES SERVICES -->
                      <th>Fichier du contrat</th>
                    
                      <th>Bond de commande</th>
                      @if(auth()->user()->id_role == 3)
                      @else
                        <th>Modifier</th>
                      @endif
                    
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($all as $all)
                          <tr>
                            <td>{{$all->titre_contrat}}</td>
                            <td>{{$all->nom_entreprise}}</td>
                            <td>@php echo date('d/m/Y',strtotime($all->debut_contrat)) @endphp</td>
                            <td>@php echo date('d/m/Y',strtotime($all->fin_contrat)) @endphp</td>
                            <td>
                              @php
                                echo  number_format($all->montant, 2, ".", " ")." XOF";
                              @endphp
                            
                            </td>  
                           
                            <td>
                              @php
                               
                                  //On va écrire un code pour detecter tous les services offerts
                                  $se = DB::table('prestation_services')
                                  ->join('prestations', 'prestation_services.prestation_id', '=', 'prestations.id')
                                  ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                                  ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id') 
                                  ->where('prestations.id_contrat', $all->id)
                                  ->get(['services.libele_service', 'prestation_services.*']);
                                  
                              @endphp
                              <ul>
                              @foreach($se as $se_get)
                                  <li>{{$se_get->libele_service}}</li>
                              @endforeach
                              </ul>
                            </td>
                            <td>
                              <form action="download" method="post" enctype="multipart/form-data" target="blank">
                                @csrf
                                
                                  <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file" value="{{$all->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>
                           
                             <td>
                              <form action="view_bon_commande" method="post" enctype="multipart/form-data" target="blank">
                                @csrf
                                
                                <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file_bon" value="{{$all->bon_commande}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>

                            @if(auth()->user()->id_role == 3)
                            @else
                    
                              <td>
                              <!--MODIFICATION AVEC POPUP-->
                           
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#edit".$all->id.""; @endphp">
                              <i class="fa fa-edit"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "edit".$all->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Modifier </h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <form  method="post" action="edit_contrat" enctype="multipart/form-data">
                                        @csrf
                                        <input type="text" name="id_entreprise" value="{{$all->id}}" style="display:none;">
                           
                                           <!--LES ELEMETS DU FILTRE-->
                                          <select class="form-control" name="reconduction" style="display:none;">
                                              <option value="c">Renouvellement</option>
                                              <option value="0">Non</option>
                                              <option value="1">Tacite</option>
                                              <option value="2">Accord parties</option>
                                          </select>
                                          <select class="form-control" name="entreprise_filter" style="display:none">
                                              <option value="all">Entreprises</option>
                                              @php
                                                  $get = (new EntrepriseController())->GetAll();
                                              @endphp
                                            
                                              @foreach($get as $entreprise)
                                                  <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                  
                                              @endforeach
                                              
                                          </select>   

                                          <select class="form-control" name="etat_contrat" style="display:none;">
                              
                                            <option value="c">Etat</option>
                                            <option value="0">En cours</option>
                                            <option value="1">Terminé</option>
                                          </select>

                                          <select class="form-control input-lg" name="service" style="display:none;">
                                              <!--liste des services a choisir -->
                                              <option value="service">Service</option>
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
                                          <!--FIN ELEMENT DU FILTRE-->

                                          <div class="row">
                                            <div class="col-sm-6">  <label>Réfrence du contrat:</label></div>
                                            <div class="col-md-6"><input type="text"  maxlength="100" value="{{$all->titre_contrat}}" class="form-control " name="titre" placeholder="Ex: Contrat de sureté BICICI"/></div>
                                          </div> <br>

                                          <div class=" row">
                                              <div class="col-sm-6"><label>Entreprise:</label></div>
                                              @php
                                                  $get = (new EntrepriseController())->GetAll();
                                              @endphp
                                              <div class="col-sm-6">
                                                <select class="form-control select2" name="entreprise">
                                                  @php2
                                                      $get = (new EntrepriseController())->GetAll();
                                                  @endphp
                                                  <option value="{{$all->id_entreprise}}">{{$all->nom_entreprise}}</option>
                                                  @foreach($get as $entreprise)
                                                      <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                      
                                                  @endforeach
                                              </select></div>
                                          </div>  <br>
                                                                                    <div class=" row">
                                              <div class="col-sm-6"><label>Avenant ?</label></div>
                                              <div class="col-sm-6"><select class="form-control " name="avenant" id="mySelectAvenant" onchange="griseFunction1()" >
                                                @if($all->avenant == 0)
                                                    <option value="{{$all->avenant}}">NON</option>
                                              
                                                    <option value="1">OUI</option>
                                                  
                                                @else
                                                    @if($all->avenant == 1)
                                                        <option value="{{$all->avenant}}">OUI</option>
                                              
                                                    <option value="0">NON</option>
                                                    @endif
                                                  
                                                @endif
                                              
                                                
                                            </select>
                                                </div>
                                          </div><br>
                                          <div class=" row">
                                            <div class="col-sm-6"><label >Reconduction:</label></div>
                                            <div class="col-sm-6"> <select class="form-control" name="reconduction" required>
                                                @if($all->reconduction == 1)
                                                    <option value="1">TACITE</option>
                                                    <option value="0">NON</option>
                                                    
                                                    <option value="2">ACCORD PARTIES</option>
                                                @else
                                                    @if($all->reconduction == 0)
                                                    <option value="0">NON</option>
                                                        <option value="1">TACITE</option>       
                                                        <option value="2">ACCORD PARTIES</option>
                                                    @endif

                                                    @if($all->reconduction == 2)
                                                        <option value="2">ACCORD PARTIES</option>
                                                        <option value="0">NON</option>
                                                        <option value="1">TACITE</option>       
                                                        
                                                    @endif
                                                @endif
                                                
                                            </select></div>
                                          </div><br>
                                           
                                            <div class="row">
                                              <div class="col-sm-6"> <label >Contrat Parent:</label></div>
                                              <div class="col-sm-6">
                                                <select class="form-control select2" name="contrat_parent" id="contratparent" disabled required>
                                                  @php
                                                      $getcontrat =  DB::table('contrats')
                                                      ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                                      ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                                                      ->orderBy('entreprises.nom_entreprise', 'asc')
                                                      ->where('contrats.id', $all->id_contrat_parent)
                                                      ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);
                                                  @endphp

                                                  @foreach($getcontrat as $getcontrat)
                                                      <option value={{$getcontrat->id}}>{{$getcontrat->titre_contrat}}/{{$getcontrat->nom_entreprise}}</option>
                                                      
                                                  @endforeach

                                                  @php
                                                      $getparent = ($contratcontroller)->GetContratParent();
                                                  @endphp
                                                  <option value="0">--Choisir le contrat--</option>
                                                  @foreach($getparent as $getparent)
                                                      <option value={{$getparent->id}}>{{$getparent->titre_contrat}}/{{$getparent->nom_entreprise}}</option>
                                                      
                                                  @endforeach
                                                  
                                              </select></div>
                                            </div><br>
                                              <script>
                                                  function griseFunction1() {
                                                      /* ce script permet d'activer les champ si l'utilisateur choisit autre*/
                                                      var val = document.getElementById("mySelectAvenant").value;
                                                      
                                                      if( val == '1')
                                                      {
                                                      document.getElementById("contratparent").removeAttribute("disabled");
                                                      
                                                      }
                                                      else
                                                      {
                                                      document.getElementById("contratparent").setAttribute("disabled", "disabled");
                                                      
                                                      }
                                                  
                                                  }
                                              </script>   
                                              <div class="row">
                                              <div class="col-sm-6"><label >Montant (XOF):</label></div>
                                              <div class="col-sm-6"><input type="number" class="form-control " required name="montant"  value="{{$all->montant}}">
                                              </div><br><br>
                                            <div class="row"> 
                                              <div class="col-sm-6"><label >Debut du contrat:</label></div>
                                              <div class="col-sm-6">
                                              <input type="date" class="form-control " required name="date_debut"  value="{{$all->debut_contrat}}">
                                              </div>
                                            </div><br>
                                            <div class="row">
                                              <div class="col-sm-6"><label>Fichier du contrat(PDF)</label></div>
                                              <div class="col-sm-6"> <input type="file" class="form-control" name="file"></div>
                                            </div><br>
                                            <div class="row">
                                                <div class="col-sm-6"><label>Facture proforma :</label></div>
                                                <div class="col-sm-6"><input type="file" class="form-control" name="file_proforma"></div>
                                            </div><br>
                                            <div class="row">
                                                <div class="col-sm-6"><label>Bon de commande(PDF) :</label></div>
                                                <div class="col-sm-6"><input type="file" class="form-control" name="bon_commande" ></div>
                                            </div><br>

                                          <div class="modal-footer">
                                          <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                          <button type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                        </form>
                                      
                                      </div>
                                    
                                      
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>    
                              <!-- /.modal -->
                                  

                              </td>
                            @endif  
                          </tr>
                            
                          
                          </tr>
                        @endforeach
                    </tbody>
                  
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
          </div>
          <!-- /.col -->
        @endif
        
    </div>
        
@endsection