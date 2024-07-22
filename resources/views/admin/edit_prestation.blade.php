@extends('layouts/base')
@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

     use App\Http\Controllers\ContratController;

     use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\TypePrestationController;

     use App\Http\Controllers\InterlocuteurController;

     use App\Http\Controllers\PrestationController;

     $prestationcontroller = new PrestationController();

     $servicecontroller = new ServiceController();

     $typeprestationcontroller = new TypePrestationController();

     $contratcontroller = new ContratController();

     $entreprisecontroller = new EntrepriseController();

     $interlocuteurcontroller = new InterlocuteurController();

     //Récuper la prestation par l'id fournit
     //dd($id);
     $get = $prestationcontroller->GetById($id);
     //dd($get);
     
@endphp

@section('content')
    <div class="row">
     @if(session('success'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-success" >{{session('success')}}</p>
            </div>
          @endif
    <!-- left column -->
        <div class="col-md-3"></div>
        @foreach($get as $get)
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-aeneas">
                <div class="box-header with-border">
                    <b><h3 class="box-title">MODIFIER LA PRESTATION</h3></b>
                </div>
                
                <!-- form start -->
                <form role="form" method="post" action="edit_prestation">
                    @csrf
                    <input type="text" value={{$id}} name="id_prestation" style="display:none;">
                    <div class="box-body">
                        <div class="form-group">
                        {{$get->localisation}}
                            <div class="form-group">
                                <label for="exampleInputEmail1">Date d'exécution(*)</label>
                                <input type="date" class="form-control  input-lg" required name="date_execute" value={{$get->date_prestation}} >
                            </div>

                            <div class="form-group">
                                <label>Adresse </label>
                                <input type="text" maxlength="100" class="form-control input-lg" name="localisation" value={{$get->localisation}} onkeyup="this.value=this.value.toUpperCase()">
                            </div>
                            
                            <label>Service (*)</label>
                            <select class="form-control input-lg" name="service" required>
                                <!--liste des services a choisir -->
                               
                                <option value={{$get->id_service}}>{{$get->libele_service}}</option>
                                 @php
                                    $service = $servicecontroller->GetAll();
                                @endphp
                                @foreach($service as $service)
                                    <option value={{$service->id}}>{{$service->libele_service}}</option>
                                    
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label>Type de prestation (*)</label>
                            <select class="form-control input-lg" name="type" required>
                               
                                 <option value={{$get->id_type_prestation}}>{{$get->libele}}</option>
                                @php
                                    $type = $typeprestationcontroller->GetAll();
                                @endphp
                                
                                @foreach($type as $type)
                                    <option value={{$type->id}}>{{$type->libele}}</option>
                                    
                                @endforeach
                            </select>
                        </div>
                     
                            
                        <div class="form-group">
                            <label>Choisissez le contrat(*) </label>
                            <!--Afficher les contrats que l'utilisateur a créé-->
                            <select class="form-control input-lg" name="contrat" required>
                                <option value={{$get->id_contrat}}>{{$get->titre_contrat}}</option>
                                @php
                                    $contrat = $contratcontroller->GetAll();
                                    
                                @endphp
                                
                                @foreach($contrat as $contrat)
                                    <option value={{$contrat->id}}>{{$contrat->titre_contrat}}</option>
                                    
                                @endforeach
                            </select>
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
            <!--/.col (left) -->
        @endforeach
     
        <div class="col-md-3"></div>
    </div>
    <!-- Main row -->  

@endsection
     
    
   