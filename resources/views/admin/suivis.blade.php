@extends('layouts/base')

@php
    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\ProspectionController;

    use App\Http\Controllers\SuiviController;

    $prospectioncontroller = new ProspectionController();

    $suivicontroller = new SuiviController();

    $my_own = $suivicontroller->MyOwn();

    $all = $suivicontroller->GetAll();
@endphp

@section('content')
    <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
          <div class="col-md-3">
          </div>
      
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">ENREGISTRER UN SUIVI</h3><br>(*) champ obligatoire
              </div>
            
              <!-- form start -->
              <form role="form" method="post" action="add_suivi">
                @csrf
                <div class="box-body">

                  <div class="form-group">
                      <label>Le titre du SUIVI</label>
                      <input type="text" onkeyup='this.value=this.value.toUpperCase()' maxlength="20"
                       class="form-control input-lg" name="titre_suivi" placeholder="SUIVI1"/>
                  </div>  
                  <div class="form-group">
                    <label>Décrire en deux phrase l'activité</label>
                    <textarea class="form-control input-lg"  name="activite"></textarea>
                  </div>
            
                  <div class="form-group">
                    <label >Selectionner la prospection:</label>
                    <select class="form-control  input-lg" name="prospection">
                       @php
                            $prospection = $prospectioncontroller->GetAll();
                            
                        @endphp
                        
                        @foreach($prospection as $prospection)
                            <option value={{$prospection->id}}>@php echo date('d/m/Y',strtotime($prospection->date_prospection));  @endphp/{{$prospection->nom_entreprise}}</option>
                            
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
          <!-- right column -->
          <div class="col-md-3">
		  </div>
    </div>
    <!--/.col (right) -->


    <div class="row">
      
        @if(auth()->user()->id_departement < 4)

            <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Mes suivis</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                   <th>Titre</th>
                    <th>description</th>
                     <th>Date de la prospection</th>
                      <th>Interlocuteur</th>
                       <th>Entreprise</th>
                       
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($my_own as $my_own)
                        <tr>
                          <td>{{$my_own->titre}}</td>
                          <td>{{$my_own->activite}}</td>
                          <td>@php echo date('d/m/Y',strtotime($my_own->date_prospection)) @endphp</td>
                          <td>{{$my_own->nom}}</td>
                         <td>{{$my_own->nom_entreprise}}</td>
                     
                          
                          <td>
                            <form action="edit_suivi_form" method="post">
                                @csrf
                                <input type="text" value={{$my_own->id}} style="display:none;" name="id_suivi">
                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                 <th>Titre</th>
                    <th>description</th>
                     <th>Date de la prospection</th>
                      <th>Interlocuteur</th>
                       <th>Entreprise</th>
                      
                    <th>Action</th>
                  </tr>
                  </tfoot>
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
                  <h3 class="box-title">Suivis effectués</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                      <th>Titre</th>
                    <th>description</th>
                     <th>Date de la prospection</th>
                      <th>Interlocuteur</th>
                       <th>Entreprise</th>
                        <th>Service proposé</th>
                    @if(auth()->user()->id_role == 3)
                    @else
                        <th>Action</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                          <td>{{$all->titre}}</td>
                          <td>{{$all->activite}}</td>
                          <td>@php echo date('d/m/Y',strtotime($all->date_prospection)) @endphp</td>
                          <td>{{$all->nom}}</td>
                         <td>{{$all->nom_entreprise}}</td>
                          <td>{{$all->libele_service}}</td>
                          @if(auth()->user()->id_role == 3)
                          @else
                            <td>
                              <form action="edit_suivi_form" method="post">
                                  @csrf
                                  <input type="text" value={{$all->id}} style="display:none;" name="id_suivi">
                                  <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                              </form>
                            </td>
                          @endif
                          
                      @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                   <th>Titre</th>
                    <th>description</th>
                     <th>Date de la prospection</th>
                      <th>Interlocuteur</th>
                       <th>Entreprise</th>
                        <th>Service proposé</th>
                   @if(auth()->user()->id_role == 3)
                    @else
                        <th>Action</th>
                    @endif
                  </tr>
                  </tfoot>
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
         
          @endif
    </div>
          <!-- /.row -->
	
@endsection