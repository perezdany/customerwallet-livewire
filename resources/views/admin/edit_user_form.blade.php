@extends('layouts/base')
@php

    use App\Http\Controllers\DepartementController;
    use App\Http\Controllers\UserController;

     use App\Http\Controllers\RoleController;

    $usercontroller = new UserController();
    $departementcontroller = new DepartementController();
    $rolecontroller =  new RoleController();
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
                    <b><h3 class="box-title">PROFILE UTILISATEUR </h3><br>
                    (*)champ obligatoire</b>
                </div>
                @php
                   
                    $retrive = $usercontroller->GetById($id_user);
                   
                @endphp
                @foreach($retrive as $user)
                     <!-- form start -->
                    <form role="form" action="edit_user" method="post">
                        @csrf
                        <input type="text" value="{{$user->id}}" name="id_user" style="display:none;">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control input-lg" name="email" value="{{$user->login}}">
                            </div>
                            <div class="form-group">
                                <label >Nom & Prénom(s)</label>
                                <input type="text" class="form-control  input-lg" name="nom" value="{{$user->nom_prenoms}}" onkeyup='this.value=this.value.toUpperCase()'>
                            </div>
                            <div class="form-group">
                                <label >Département</label>
                                <select class="form-control input-lg" name="departement">
                                    <option value={{$user->id_departement}}>{{$user->libele_departement}}</option>
                                        
                                    @php
                                        $get = $departementcontroller->GetAll();
                                    @endphp
                                    
                                    @foreach($get as $departement)
                                        <option value={{$departement->id}}>{{$departement->libele_departement}}</option>
                                        
                                    @endforeach
                                    
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Rôle:</label>
                                <select class="form-control input-lg" name="role">
                                @php
                                        $role = $rolecontroller->GetAll();
                                    @endphp
                                    
                                    @foreach($role as $role)
                                        <option value={{$role->id}}>{{$role->intitule}}</option>
                                        
                                    @endforeach
                                    
                                </select>
                                
                            </div>     
                            <div class="form-group">
                                <label >Fonction</label>
                                <input type="text" class="form-control  input-lg" name="poste" value="{{$user->poste}}" onkeyup='this.value=this.value.toUpperCase()'/>
                            </div>
                            
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">MODIFIER</button>
                        </div>
                    </form>

                    <form role="form" action="edit_password" method="post">
                        @csrf
                        <input type="text" value="{{$user->id}}" name="id_user" style="display:none;">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Mot de passe</label>
                                <input type="password" class="form-control  input-lg" required name="password" id="pwd1">
                            </div>
                            
                            <div class="form-group">
                                <label>Confirmer le mot de passe</label>
                                <input type="password" class="form-control  input-lg" required  id="pwd2" equired onkeyup="verifyPassword()">
                            </div>
                            <div class="col-md-12 form-group" id="match">            
                            </div>
                         
                                        
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="bt" >MODIFIER</button>
                        </div>
                           <script type="text/javascript">
                                        
                                /*UN SCRIPT QUI VA VERFIER SI LES DEUX PASSWORDS MATCHENT*/
                                function verifyPassword()
                                {
                                    var msg; 
                                    var str = document.getElementById("pwd1").value; 
                                    var button = document.getElementById("bt")

                                    var text1 = document.getElementById('pwd1').value;
                                    var text2 = document.getElementById('pwd2').value;
                                    
                                    
                                    if((text1 == text2))
                                    {  
                                       
                                        
                                        var theText = "<p style='color:green'>Correspond.</p>"; 
                                        button.removeAttribute("disabled");
                                        document.getElementById("match").innerHTML= theText; 
                                        
                                    }
                                    else
                                    {
                                       
                                        var theText = "<p style='color:red'>Ne correspond pas.</p>";
                                        document.getElementById("match").innerHTML= theText;
                                        button.setAttribute("disabled", "true");
                                    }
                                }
                                        
                            </script>     
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
     
    
   