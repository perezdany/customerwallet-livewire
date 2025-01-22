@extends('layouts/base')
@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

     use App\Http\Controllers\ContratController;

     use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\TypePrestationController;

     use App\Http\Controllers\InterlocuteurController;

     use App\Http\Controllers\ProspectionController;

      use App\Http\Controllers\CategorieController;

      $categoriecontroller = new CategorieController();

     $servicecontroller = new ServiceController();

     $typeprestationcontroller = new TypePrestationController();

     $contratcontroller = new ContratController();

     $entreprisecontroller = new EntrepriseController();

     $interlocuteurcontroller = new InterlocuteurController();
    
    $prospectioncontroller = new ProspectionController();
     
@endphp

@section('content')
    <div class="row">
     @if(session('success'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-success" >{{session('success')}}</p>
            </div>
    @endif
    <!-- left column -->
    <div class ="row">
        <div class="col-md-2">
            <!-- general form elements -->
           
        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-8">
            <div class="box box-aeneas">
                <div class="box-header with-border">
                    <h3 class="box-title"><b> MODIFIER UNE PROSPECTION </b></h3><br>
                    <b>(*)champ obligatoire</b>
                </div>
                @php
                    $retrive =  $prospectioncontroller->GetById($id);
                @endphp
                @foreach($retrive as $retrive)
                      <!-- form start -->
                    <form role="form" action="edit_prospection" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text" value="{{$retrive->id}}" name="id_prospection" style="display:none;">
                        <input type="text" value="{{$retrive->interlocuteur}}" name="id_interlocuteur" style="display:none;">
                        <div class="box-body">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Service Proposé (*)</label>
                                    <select class="form-control  select2" data-placeholder ="Dérouler pour voir les service" multiple="multiple" name="service_propose[]" >
                                            
                                        <!--liste des services a choisir -->
                                        
                                        @php
                                            $get = $servicecontroller->GetAll();
                                            $categorie = $categoriecontroller->DisplayAll();
                                        @endphp
                                        @foreach( $categorie as $categorie)
                                            
                                            <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                        
                                            <!--ON AFFICHE MAINTENANT LES SERVICE DE LA BASE-->
                                            @php
                                                
                                                $get = $servicecontroller->GetByCategorie($categorie->id);
                                            @endphp
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        @endforeach
                                    
                                    </select>
                                
                                </div>
                                <div class="form-group">
                                    <label >Date de la prospection</label>
                                    <input type="date" class="form-control  " name="date_prospect" value="{{$retrive->date_prospection}}">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Durée de la prospection (Jr)</label>
                                    <input type="number" max="365" min="1" class="form-control " name="duree" value="{{$retrive->duree_jours}}">
                                </div>
                                    <!--CALCULER LA DATE DE FIN DE LA PROSPECTION ET AJOUTER-->
                                <div class="box-header">
                                    <h3 class="box-title"><b> L'ENTREPRISE </b></h3>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Choisissez l'entreprise si existant ou sélectionnez Autre:</label>
                                    <select class="form-control " name="entreprise">
                                        @php
                                            $get = $entreprisecontroller->GetAll();
                                        @endphp
                                        <option value={{$retrive->id_entreprise}}>{{$retrive->nom_entreprise}}</option>
                                        @foreach($get as $entreprise)
                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                            
                                        @endforeach
                                        <option value="autre">Autre</option>
                                    </select>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Saisir le nom de l'entreprise</label>
                                    <input type="text" class="form-control " name="entreprise_name" onkeyup="this.value=this.value.toUpperCase()">
                                    
                                </div>
                            </div>
                        
                            <!-- 
                                <div class="col-md-6">           
                                    <div class="box-header">
                                    <h3 class="box-title"> <b> INFORMATIONS DE L'INTERLOCUTEUR </b></h3>
                                    </div>
                                
                                        <div class="form-group">
                                            <label for="exampleInputFile">Choisissez l'interlocuteur si existant ou sélectionnez Autre:</label>
                                            <select class="form-control " name="interlocuteur">
                                                @php
                                                    $interlocuteur = $interlocuteurcontroller->GetAll();
                                                    
                                                @endphp
                                                <option value={{$retrive->id}}>{{$retrive->nom}}/{{$retrive->fonction}}/{{$retrive->tel}}</option>
                                                @foreach($interlocuteur as $interlocuteur)
                                                    <option value={{$interlocuteur->id}}>{{$interlocuteur->nom}}/{{$interlocuteur->fonction}}/{{$interlocuteur->tel}}</option>
                                                    
                                                @endforeach

                                                <option value="autre">Autre</option>

                                            </select>
                                            
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputFile">Titre :</label>
                                            <select class="form-control " name="titre">
                                                <option value="{{$retrive->titre}}">{{$retrive->titre}}</option>
                                                <option value="M">M</option>
                                                <option value="Mme">Mme</option>
                                                <option value="Mlle">Mlle</option>
                                            </select>
                                            
                                        </div>
                                        <div class="form-group">
                                                <label >Nom & Prénom(s)</label>
                                                <input type="text" class="form-control  " name="nom" onkeyup="this.value=this.value.toUpperCase()" value="{{$retrive->nom}}">
                                        </div>

                                        <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" class="form-control " name="email" value="{{$retrive->email}}">
                                            </div>

                                        <div class="form-group">
                                                <label>Téléphone (*)</label>
                                                <input type="text" class="form-control " name="tel" placeholder="(+225)0214578931" value="{{$retrive->tel}}" required>
                                            </div>

                                        <div class="form-group">
                                                <label>Fonction</label>
                                                <input type="text" class="form-control " name="fonction" onkeyup="this.value=this.value.toUpperCase()" value="{{$retrive->fonction}}">
                                            </div>  
                                
                                </div>
                            -->
                              <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Facture Proforma</label>
                                        
                                        <input type="file" class="form-control" name="fileproforma" >
                                    
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="form-group">
                                       <label>Compte Rendu</label>
                                       
                                        <input type="file" class="form-control" name="file" >
                                    </div>
                                     
                                </div>
                        </div>


                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">VALIDER</button>
                        </div>
                    </form>
                @endforeach
              
            </div>		
        </div>
        <!--/.col (right) -->

         <div class="col-md-2">
            <!-- general form elements -->
           
        </div>
    </div>
    <!-- Main row -->  

@endsection
     
    
   