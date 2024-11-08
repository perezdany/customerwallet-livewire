@extends('layouts/base')

@php
   
use App\Http\Controllers\InterlocuteurController;

$interlocuteurcontroller = new InterlocuteurController();

use App\Http\Controllers\EntrepriseController;

$entreprisecontroller = new EntrepriseController();

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

           @if(session('error'))
            <div class="col-md-12 box-header">
              <p class="bg-warning" style="font-size:13px;">{{session('error')}}</p>
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
                       
                    </table>
                </div>
                <!-- /.box-body -->
                
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-6">
            <div class="box box-aeneas">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>ENREGISTRER UN INTERLOCUTEUR</b> </h3><br>
                    <b>(*)champ obligatoire</b>
                </div>
            
                <!-- form start -->
                <form role="form" action="add_referant" method="post">
                    @csrf
                    <div class="box-body">
                        
                        <div class="box-header">
                            <b><h3 class="box-title">L'ENTREPRISE</h3></b>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Choisissez l'entreprise :</label>
                            <select class="form-control input-lg" name="entreprise">
                                @php
                                    $get = $entreprisecontroller->GetAll();
                                @endphp
                                <option value="0">--Selectionnez Une entreprise--</option>
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>
                            
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="exampleInputFile">Titre :</label>
                            <select class="form-control input-lg" name="titre">
                                <option value="M">M</option>
                                <option value="Mme">Mme</option>
                                <option value="Mlle">Mlle</option>
                            </select>
                            
                        </div>
                        <div class="form-group">
                                <label >Nom & Prénom(s)</label>
                                <input type="text"  maxlength="100" class="form-control  input-lg" name="nom" onkeyup="this.value=this.value.toUpperCase()">
                        </div>

                        <div class="form-group">
                                <label>Email</label>
                                <input type="email"  maxlength="30" class="form-control input-lg" name="email" >
                            </div>

                        <div class="form-group">
                                <label>Téléphone (*)</label>
                                <input type="text"  maxlength="30"   class="form-control input-lg" name="tel" placeholder="(+225)0214578931" >
                            </div>

                        <div class="form-group">
                                <label>Fonction</label>
                                <input type="text" class="form-control input-lg"  maxlength="60" name="fonction" onkeyup="this.value=this.value.toUpperCase()">
                            </div>  
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                    </div>
                </form>
            </div>		
          <!-- /.box -->
        </div>
        
	</div>
		
@endsection