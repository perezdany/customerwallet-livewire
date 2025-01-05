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
        
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Utilisateurs</h3>
                  <div class="box-tools">
                    <a href="form_add_user" class="mr-4 d-block"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i> UTILISATEUR</b></button></a><br>
                  </div> 
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
                      <th>Activer/Désactiver</th>
                      <th>Supprimer</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->nom_prenoms}}</td>
                          <td>{{$all->login}}</td>
                          <td>{{$all->libele_departement}}
                             
                          </td>
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
                          </td>
                          
                          <td>
                           
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
                   <th>Activer/Désactiver</th>
                    <th>Supprimer</th>
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
	
@endsection