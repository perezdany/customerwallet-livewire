@extends('layouts/base')
@php

    use App\Http\Controllers\DepartementController;
    use App\Http\Controllers\UserController;

     use App\Http\Controllers\RoleController;

     use App\Models\Permission;

    $usercontroller = new UserController();
    $departementcontroller = new DepartementController();
    $rolecontroller =  new RoleController();
@endphp

@section('content')
    <div class="row">
   
      
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header with-border">
                    <b><h3 class="card-title">MODIFICATION DES INFOS PERSONNELLES </h3><br></b>
                </div>
                @php
                   
                    $retrive = $usercontroller->GetById($id_user);
                   
                @endphp
                @foreach($retrive as $user)
                     <!-- form start -->
                    <form role="form" action="edit_user" method="post">
                        @csrf
                        <input type="text" value="{{$user->id}}" name="id_user" style="display:none;">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control " name="email" value="{{$user->login}}">
                            </div>
                            <div class="form-group">
                                <label >Nom & Prénom(s)</label>
                                <input type="text" class="form-control  " name="nom" value="{{$user->nom_prenoms}}" onkeyup='this.value=this.value.toUpperCase()'>
                            </div>
                            <div class="form-group">
                                <label >Département</label>
                                <select class="form-control " name="departement">
                                    <option value={{$user->departements_id}}>{{$user->libele_departement}}</option>
                                        
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
                                <select class="form-control " name="role">
                                  <option value={{$user->roles_id}}>{{$user->intitule}}</option>
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
                                <input type="text" class="form-control  " name="poste" value="{{$user->poste}}" onkeyup='this.value=this.value.toUpperCase()'/>
                            </div>
                           
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                        </div>
                    </form>
                   
                @endforeach
               
            </div>      
        </div>
        <!--/.col (right) -->
         <div class="col-md-6">
            <div class="card card-aeneas">
                @foreach($retrive as $user)
                     <!-- form start -->
                    <div class="card-header with-border">
                            <b><h3 class="card-title">Réinitialiser le mot de passe</h3><br></b>
                    </div>
                    <form role="form" action="reset_password" method="post">
                        @csrf
                        <input type="text" value="{{$user->id}}" name="id_user" style="display:none;">
                         <div class="card-body">
                            Mot de passe par défaut(123456)
                        </div>
                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">REINITIALISER LE MOT DE PASSE</button>
                        </div>
                    </form><hr>
                        <div class="card-header">
                            <h3 class="card-title">Permissions</h3>
                        </div>
                    <form role="form" action="update_permissions" method="post">
                        @csrf
                        <input type="text" value="{{$user->id}}" name="id_user" style="display:none;">
                        <div class="card-body">
                            <div class="form-group">
                                @php
                                    $permissions = Permission::all()
                                   
                                @endphp
                                <!--<div class="form-group">
                                    <div class="form-check">
                                   
                                    </div>
                                    <div class="form-check">
                                   
                                    </div>
                                    
                                </div>-->
                                <div class="form-group">
                                
                                @foreach($permissions as $permissions)
                                <label>
                                    @php
                                        $per= DB::table('permission_utilisateur')->where('utilisateur_id', $user->id)->where('permission_id', $permissions->id)->count();
                                    @endphp
                                    @if($per != 0)
                                        <div class="form-check">
                                        <input class="form-check-input" name="{{$permissions->libele}}" value="{{$permissions->id}}" checked type="checkbox">
                                        <label class="form-check-label">{{$permissions->libele}}</label>
                                        <!--<input type="checkcard" class="minimal"  name="" checked>-->
                                        </div>
                                    @else
                                        <div class="form-check">
                                        <input class="form-check-input" name="{{$permissions->libele}}" value="{{$permissions->id}}" type="checkbox">
                                        <label class="form-check-label">{{$permissions->libele}}</label>
                                        <!--<input type="checkcard" class="minimal"  {{$permissions->libele}}  name="">-->
                                        </div>
                                    @endif
                                   
                                   
                                </label>
                                
                                @endforeach
                                </div>
                            
                                
                            </div>
                     
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="bt" >METTRE A JOUR LES PERMISSIONS</button>
                        </div>
                     
                    </form><hr>

                     <div class="card-header">
                        <h3 class="card-title">Modifier le mot de passe</h3>
                    </div>
                    <form role="form" action="edit_password" method="post">
                        @csrf
                        <input type="text" value="{{$user->id}}" name="id_user" style="display:none;">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Mot de passe</label>
                                <input type="password" class="form-control  " required name="password" id="pwd1">
                            </div>
                            
                            <div class="form-group">
                                <label>Confirmer le mot de passe</label>
                                <input type="password" class="form-control  " required  id="pwd2" equired onkeyup="verifyPassword()">
                            </div>
                            <div class="col-md-12 form-group" id="match">            
                            </div>
                         
                                        
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary" id="bt" >MODIFIER LE MOT DE PASSE</button>
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
    </div>
    <!-- Main row -->  

@endsection
     
    
   