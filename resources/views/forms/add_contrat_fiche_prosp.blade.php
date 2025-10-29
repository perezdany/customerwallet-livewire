@extends('layouts/base')
@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

     use App\Http\Controllers\ContratController;

     use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\TypePrestationController;

     use App\Http\Controllers\InterlocuteurController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\CategorieController;

    use App\Http\Controllers\Calculator;

    $calculator = new Calculator();

    $facturecontroller = new FactureController();

    $categoriecontroller = new CategorieController();

    $servicecontroller = new ServiceController();

    $typeprestationcontroller = new TypePrestationController();

    $contratcontroller = new ContratController();

    $entreprisecontroller = new EntrepriseController();

    $interlocuteurcontroller = new InterlocuteurController();

    $my_own =  $facturecontroller->FactureDateDepassee();
    $count_non_reglee = $calculator->CountFactureNonRegleDepasse();

     
@endphp

@section('content')
   
    <div class="row">
         @if(session('success'))
            <div class="col-md-12 card-header" style="font-size:13px;">
              <p class="bg-success" >{{session('success')}}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="col-md-12 card-header" style="font-size:13px;">
              <p class="bg-danger" >{{session('error')}}</p>
            </div>
        @endif
         @if(isset($error))
                <div class="col-md-12 card-header">
                <p class="bg-danger" style="font-size:13px;">{{$error}}</p>
                </div>
            @endif

             @if(isset($success))
                <div class="col-md-12 card-header">
                <p class="bg-success" style="font-size:13px;">{{$success}}</p>
                </div>
            @endif

        <!-- CODE POUR LES RESTRICTIONS-->

        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <div class="card card-aeneas">
                <div class="card-header with-border">
                    <h3 class="card-title"><b>ENREGISTRER UN CONTRAT</b></h3><br>(*) <b>champ obligatoire
                </div>
                
                <!-- form start -->
               <form role="form" method="post" action="fiche_add_contrat_with_prest" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                  <input type="text" value="{{$id_entreprise}}" style="display:none;" name="id_entreprise">
                    <div class="col-md-6">
                       
                      <div class="card-body">
                        <div class="form-group">
                          <label>Entreprise:</label>
                          <select class="form-control " name="entreprise">
                            @php
                              $nom = $entreprisecontroller->GetById($id_entreprise)
                            @endphp
                            @foreach($nom as $nom)
                                  
                                  <option value={{$nom->id}}>{{$nom->nom_entreprise}}</option>
                            @endforeach
        
                              
                          </select>
                            
                        </div>    

                        <div class="form-group">
                          <label>Titre</label>
                          <input type="text"  class="form-control " name="titre"  required 
                          onkeyup='this.value=this.value.toUpperCase()' placeholder="Ex: 202317854/SUPPORT/TTR/01"/>
                        </div>
                  
                        <div class="form-group">
                          <label >Montant (XOF)</label>
                          <input type="text" class="form-control  " required name="montant">
                        </div>
                  
                        <div class="form-group">
                          <label>Debut du contrat</label>
                          <input type="date" class="form-control  " required name="date_debut">
                        </div>

                        <div class="form-group">
                          <label>Durée du contrat</label>
                          <!--FAIRE DES CALCULS POUR DETERMINER LA FIN DU CONTRAT-->
                            <div class="row">
                              <div class="col-md-3">
                                <input type="number" class="form-control" placeholder="jours" value="0" min="0" max="31" name="jour" >
                              </div>
                              <div class="col-md-4">
                                <input type="number" class="form-control" placeholder="mois" value="0" min="0" max="12" name="mois">
                              </div>
                              <div class="col-md-5">
                                <input type="number" class="form-control" placeholder="année" value="0" min="0" max="10" name="annee">
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Fichier du contrat(PDF)</label>
                              <input type="file" class="form-control" name="file">
                        </div>

                        <div class="form-group">
                            <label>Facture Proforma(PDF)</label>
                              <input type="file" class="form-control" name="file_proforma" required>
                        </div>

                        
                      
                      </div>
                    </div>
                    <div class="col-md-6">

                      <div class="card-body">
                        <div class="card-header with-border">
                          <h3 class="card-title"> <b>PRESTATION</b></h3><br>
                        </div>

                        <div class="form-group">
                            <label>Service (*)</label>
                            <select class="form-control  select2" multiple="multiple" name="service[]"
                                style="width: 100%;" data-placeholder="--Selectionnez le service--" required>
                                <!--liste des services a choisir -->
                                
                                @php
                                    $get = $servicecontroller->GetAll();
                                    $categorie = $categoriecontroller->DisplayAll();
                                @endphp
                                @foreach( $categorie as $categorie)
                                    
                                    <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
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
                            <label>Type de facturation (*)</label>
                            <select class="form-control " name="type" required>
                                <!--liste des services a choisir -->
                                @php
                                    $get = $typeprestationcontroller->GetAll();
                                @endphp
                                <option value="0">--Choisir le type--</option>
                                @foreach($get as $type)
                                    <option value={{$type->id}}>{{$type->libele}}</option>
                                    
                                @endforeach
                            </select>
                        </div>

                      </div>


                    </div>

                  </div>
                  
                  <!-- /.card-body -->

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">VALIDER</button>
                  </div>
                </form>
                </div>
                <!-- /.card -->
            </div>

            <div class="col-md-2"></div>
        </div>
       

           
    </div>
    <!-- Main row -->  

@endsection
     
    
   