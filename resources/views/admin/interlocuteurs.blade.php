@extends('layouts/base')

@php
   
  use App\Http\Controllers\InterlocuteurController;

  $interlocuteurcontroller = new InterlocuteurController();

  $all =  $interlocuteurcontroller-> GetAll();

  //dd($all);
@endphp

@section('content')
     <div class="row">
      
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
        <div class="col-md-12">
          <div class="box">
               <div class="box-header">
                    <h3 class="box-title">Interlocuteurs</h3>
                </div>    
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Nom & Prénom(s)</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
                            <th>Entreprise</th>
                        
                            <th>Ajouté par</th>
                                
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all as $all)
                                <tr>
                                    
                                    <td>{{$all->titre}} {{$all->nom}}</td>
                                    <td>{{$all->tel}}</td>
                                    <td>{{$all->email}}</td>
                                    <td>{{$all->fonction}}</td>
                                    <td>{{$all->nom_entreprise}}</td>
                                    <td>{{$all->nom_prenoms}}</td>
                                    <td>
                                        <form action="edit_interlocuteur_form" method="post">
                                            @csrf
                                            <input type="text" value={{$all->id}} style="display:none;" name="id_interlocuteur">
                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                        </form>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Nom & Prénom(s)</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
                            <th>Entreprise</th>
                        
                            <th>Ajouté par</th>
                                
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
		  </div>
		
@endsection