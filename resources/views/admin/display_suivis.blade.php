@extends('layouts/base')

@php
    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\SuiviController;

    $suivicontroller = new SuiviController();
    //dd($id);
    $all = $suivicontroller->GetSuiviByIdProspection($id);

    //dd($all);

@endphp

@section('content')
    <div class="row">
      
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
                                  <button type="submit" class="btn btn-success">MODIFIER</button>
                              </form>
                            </td>
                          @endif
                          
                      @endforeach
                  </tbody>
                  
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
    </div>
          <!-- /.row -->
	
@endsection