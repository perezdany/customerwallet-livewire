@extends('layouts/base')
@php
    use App\Http\Controllers\InterlocuteurController;
    use App\Http\Controllers\EntrepriseController;


    $interlocuteurcontroller = new InterlocuteurController();
    $entreprisecontroller = new EntrepriseController();
@endphp

@section('content')
    <div class="row">
     @if(session('success'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-success" >{{session('success')}}</p>
            </div>
          @endif
    <!-- left column -->
        <div class="col-md-3">
        </div>
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
            <div class="box box-aeneas">
                <div class="box-header with-border">
                    <b><h3 class="box-title"> INTERLOCUTEUR</h3><br>
                    (*)champ obligatoire</b>
                </div>
                @php
                   
                    $retrive = $interlocuteurcontroller->GetById($id);
                   
                @endphp
                @foreach($retrive as $interlocuteur)
                     <!-- form start -->
                    <form role="form" action="edit_interlocuteur_fiche" method="post">
                        @csrf
                        <input type="text" value="{{$interlocuteur->id}}" name="id_interlocuteur" style="display:none;">
                        <input type="text" value="{{$interlocuteur->id_entreprise}}" name="id_entreprise" style="display:none;">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputFile">Titre :</label>
                                <select class="form-control input-lg" name="titre">
                                <option value="{{$interlocuteur->titre}}">{{$interlocuteur->titre}}</option>
                                    <option value="M">M</option>
                                    <option value="Mme">Mme</option>
                                    <option value="Mlle">Mlle</option>
                                </select>
                                
                            </div>
                            <div class="form-group">
                                    <label >Nom & Prénom(s)</label>
                                    <input type="text" maxlength="60" value="{{$interlocuteur->nom}}" class="form-control  input-lg" name="nom" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control input-lg" name="email" value="{{$interlocuteur->email}}"  >
                                </div>

                            <div class="form-group">
                                    <label>Téléphone (*)</label>
                                    <input type="text" maxlength="30" class="form-control input-lg" name="tel" placeholder="(+225)0214578931" value="{{$interlocuteur->tel}}"  >
                                </div>

                                <div class="form-group">
                                    <label>Fonction</label>
                                    <input type="text" maxlength="60" class="form-control input-lg" name="fonction" onkeyup="this.value=this.value.toUpperCase()" value="{{$interlocuteur->fonction}}" >
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputFile">Choisissez l'entreprise :</label>
                                    <select class="form-control input-lg" name="entreprise">
                                        @php
                                            $get = $entreprisecontroller->GetAll();
                                        @endphp
                                        <option value={{$interlocuteur->id_entreprise}}>{{$interlocuteur->nom_entreprise}}</option>
                                        @foreach($get as $entreprise)
                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                            
                                        @endforeach
                                        
                                    </select>
                                
                                </div>  
                                
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">MODIFIER</button>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        
                    </form>
                @endforeach
               
            </div>		
        </div>
        <!--/.col (right) -->
         <div class="col-md-3">
        </div>
    </div>
    <!-- Main row -->  

@endsection
     
    
   