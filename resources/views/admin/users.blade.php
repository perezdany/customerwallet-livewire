@extends('layouts/base')

@php
    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\UserController;

    use App\Http\Controllers\DepartementController;

    use App\Http\Controllers\RoleController;

    $usercontroller = new UserController();

    $departementcontroller = new DepartementController();

    $all =  $usercontroller->GetAll();

    $rolecontroller =  new RoleController();

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
                  <h3 class="box-title">Utilisateurs</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Nom & Prénoms</th>
                    <th>Email</th>
                    <th>Département</th>
                    <th>Poste</th>
                    
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->nom_prenoms}}</td>
                          <td>{{$all->login}}</td>
                          <td>{{$all->libele_departement}}</td>
                          <td>{{$all->poste}}</td>
                          
                          <td>
                            @if($all->active == true)
                              <form action="disable_user" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_user">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i></button>
                              </form>
                            @else
                              <form action="enable_user" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_user">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                              </form>
                            @endif
                            <form action="edit_user_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_user">
                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                  <th>Nom & Prénoms</th>
                    <th>Email</th>
                    <th>Département</th>
                    <th>Poste</th>
                    
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
          <!-- /.row -->
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
                    <label>Département:</label>
                    <select class="form-control input-lg" name="departement">
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
                      <label>Email</label>
                      <input type="text" class="form-control input-lg" name="login"/>
                  </div>  
                  <div class="form-group">
                    <label>Nom & Prénoms</label>
                    <input type="text"  class="form-control input-lg" name="nom_prenoms" onkeyup='this.value=this.value.toUpperCase()'/>
                  </div>

                  <div class="form-group">
                    <label>Fonction</label>
                    <input type="text"  class="form-control input-lg" name="poste" onkeyup='this.value=this.value.toUpperCase()'/>
                  </div>
            
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

                        <div class="box-footer">
                          <button type="submit" class="btn btn-primary" id="bt">VALIDER</button>
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