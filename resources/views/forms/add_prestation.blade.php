@extends('layouts/dash')
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
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-success" >{{session('success')}}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-danger" >{{session('error')}}</p>
            </div>
        @endif
        <!-- CODE POUR LES RESTRICTIONS-->

        <div class="row">
            <div class="col-md-3"></div>

            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-aeneas">
                    <div class="box-header with-border">
                    <h3 class="box-title"> <b>ENREGISTRER UNE PRESTATION</b></h3><br><b>(*) champ obligatoire</b>
                    </div>
                    
                    <!-- form start -->
                    <form role="form" method="post" action="add_prestation">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label>Service (*)</label>
                                <select class="form-control input-lg select2" multiple="multiple" name="service[]"
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
                                <select class="form-control input-lg" name="type" required>
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
                            <div class="form-group">
                                <label for="exampleInputEmail1">Date d'exécution(*)</label>
                                <input type="date" class="form-control  input-lg" required name="date_execute">
                            </div>
                                
                            <div class="form-group">
                                <label>Choisissez le contrat(*) </label>
                                <!--Afficher les contrats que l'utilisateur a créé-->
                                <select class="form-control input-lg" name="contrat" required>
                                    @php
                                        $contrat = $contratcontroller->RetriveAll();
                                        
                                    @endphp
                                    <option value="0">--Choisir le contrat--</option>
                                    @foreach($contrat as $contrat)
                                        <option value={{$contrat->id}}>{{$contrat->titre_contrat}}</option>
                                        
                                    @endforeach
                                </select>
                            </div>
                                
                            <div class="form-group">
                                <label>Adresse </label>
                                <input type="text" required maxlength="100" class="form-control input-lg" 
                                name="localisation" placeholder="Ex: Cocody Angré Cocovico">
                            </div>
                        
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-3"></div>
        </div>
                 
    </div>
    <!-- Main row -->  

@endsection
     
    
   