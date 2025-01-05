@extends('layouts/base')


@php
    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\UserController;

    use App\Http\Controllers\DepartementController;

    use App\Http\Controllers\RoleController;

    use App\Models\Permission;

    $usercontroller = new UserController();

    $departementcontroller = new DepartementController();

    $all =  $usercontroller->GetAll();

    $rolecontroller =  new RoleController();

@endphp

@section('content')
    <div class="row">
          <div class="col-md-3">
          </div>
      
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">AJOUTER UN UTILISATEUR</h3><br>(*) champ obligatoire
              </div>
            
              <!-- form start -->
              <form role="form" method="post" action="add_user">
                @csrf
                <div class="box-body">
                  <div class="form-group">
                    <label>Département(*):</label>
                    <select class="form-control " name="departement" required>
                      @php
                            $get = $departementcontroller->GetAll();
                        @endphp
                        
                        @foreach($get as $departement)
                            <option value={{$departement->id}}>{{$departement->libele_departement}}</option>
                            
                        @endforeach
                    </select>
                      
                  </div>  

                  <div class="form-group">
                    <label>Rôle(*):</label>
                    <select class="form-control " name="role" required>
                      @php
                            $role = $rolecontroller->GetAll();
                        @endphp
                        <option value="">Aucun</option>
                        @foreach($role as $role)
                            <option value={{$role->id}}>{{$role->intitule}}</option>
                            
                        @endforeach
                        
                    </select>
                      
                  </div>     

                  <div class="form-group">
                      <label>Email(*)</label>
                      <input type="text" class="form-control " name="login" required/>
                  </div>  
                  <div class="form-group">
                    <label>Nom & Prénoms(*)</label>
                    <input type="text"  class="form-control " name="nom_prenoms" onkeyup='this.value=this.value.toUpperCase()' required/>
                  </div>

                  <div class="form-group">
                    <label>Fonction</label>
                    <input type="text"  class="form-control " name="poste" onkeyup='this.value=this.value.toUpperCase()' required/>
                  </div>
                    <div class="box-header">
                        <h3 class="box-title">Permissions</h3>
                    </div>
                    <!-- checkbox -->
                  
                      <div class="form-group box-body">
                        @php
                          $permissions = Permission::all()
                        @endphp
                        @foreach($permissions as $permissions)
                          <label>
                              <input type="checkbox" class="minimal" id="{{$permissions->libele}}" value="{{$permissions->id}}" name="{{$permissions->libele}}">
                            {{$permissions->libele}}
                          </label>
                          
                        @endforeach
                      
                        
                      </div>
                  

                    

                   <!--<div class="form-group">
                        <label>Mot de passe</label>
                            <input type="password" class="form-control  " required name="password" id="pwd1">
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" class="form-control  " required  id="pwd2" equired onkeyup="verifyPassword()">
                        </div>
                        <div class="col-md-12 form-group" id="match">            
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
                                    
                        </script> -->

                         <div class="box-footer">
                          <button type="submit" class="btn btn-primary" id="bt">VALIDER</button>
                        </div>
                    </div>    

                    
                </div>
                <!-- /.box-body -->

               
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

@endsection