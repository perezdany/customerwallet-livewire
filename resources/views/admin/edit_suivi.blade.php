@extends('layouts/base')

@php
    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\ProspectionController;

    use App\Http\Controllers\SuiviController;

    $prospectioncontroller = new ProspectionController();

    $suivicontroller = new SuiviController();

    $my_own = $suivicontroller->MyOwn();

    $all = $suivicontroller->GetAll();

    $suivi = $suivicontroller->GetById($id);
@endphp

@section('content')
    <div class="row">
          <div class="col-md-3">
          </div>
      
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-aeneas">
              <div class="card-header with-border">
                <h3 class="card-title">MODIFIER UN SUIVI</h3><br>(*) champ obligatoire
              </div>

                @foreach($suivi as $suivi)
                    <!-- form start -->
                    <form role="form" method="post" action="edit_suivi">
                        @csrf
                        <input type="text" name="id_suivi" style="display:none" value="{{$suivi->id}}">
                        <div class="card-body">

                            <div class="form-group">
                                <label>Le titre du SUIVI</label>
                                <input type="text" value="{{$suivi->titre}}" onkeyup='this.value=this.value.toUpperCase()' class="form-control input-lg" name="titre_suivi" placeholder="SUIVI1"/>
                            </div>  
                            <div class="form-group">
                                <label>Décrire en deux phrase l'activité</label>
                                <textarea class="form-control input-lg"  name="activite">{{$suivi->activite}}</textarea>
                            </div>
                        
                            <div class="form-group">
                                <label >Selectionner la prospection:</label>
                                <select class="form-control  input-lg" name="prospection">
                                @php
                                        $prospection = $prospectioncontroller->GetAll();
                                        
                                    @endphp
                                    <option value ="{{$suivi->id_prospection}}">{{$suivi->nom_entreprise}}/{{$suivi->libele_service}}</option>
                                    @foreach($prospection as $prospection)
                                        <option value={{$prospection->id}}>{{$prospection->nom_entreprise}}/{{$prospection->libele_service}}</option>
                                        
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                        </div>
                    </form>
                @endforeach
              
            </div>
            <!-- /.card -->
          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-3">
		  </div>
    </div>
    <!--/.col (right) -->

@endsection