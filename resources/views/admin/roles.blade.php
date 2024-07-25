@extends('layouts/base')

@php
   
  use App\Http\Controllers\RoleController;

  $rolecontroller = new RoleController();

  $all =  $rolecontroller-> GetAll();

  //dd($all);
@endphp

@section('content')
     <div class="row">
      
         @if(session('success'))
         
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
        <div class="col-md-6">
          <div class="box">
               <div class="box-header">
                    <h3 class="box-title">Autorisations des utilisateus</h3>
                </div>    
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            
                            <th>Rôle</th>
                              <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all as $all)
                                <tr>
                                    
                                    <td>{{$all->intitule}}</td>
                                    <td>{{$all->specifite}}</td>
                                    
                                    <td>
                                        <form action="edit_role_form" method="post">
                                            @csrf
                                            <input type="text" value={{$all->id}} style="display:none;" name="id_role">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                        </form>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                           <th>Rôle</th>
                              <th>Description</th>
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

        <div class="col-md-6">
           <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">AJOUTER UN ROLE</h3><br>(*) champ obligatoire
              </div>
            
              <!-- form start -->
              <form role="form" method="post" action="add_role">
                @csrf
                <div class="box-body">

                  <div class="form-group">
                      <label>Le titre</label>
                      <input type="text" onkeyup='this.value=this.value.toUpperCase()' maxlength="30"
                       class="form-control input-lg" name="intitule" placeholder="Lecture/Ecriture"/>
                  </div>  
                
                    <div class="form-group">
                      <label>Description</label>
                      <textarea class="form-control input-lg" name="specifite"></textarea>
                  </div>  

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                  <button type="submit" class="btn btn-primary">VALIDER</button>
                </div>
              </form>
            </div>
        </div>
    </div>

    <div class="row">
         <div class="col-md-6">
            @if(isset($id_edit))
              <div class="box box-aeneas">
                <div class="box-header with-border">
                  <h3 class="box-title">MODIFIER UN ROLE</h3><br>(*) champ obligatoire
                </div>
                @php
                 
                  $retrive = $rolecontroller->GetById($id_edit);
                  
                @endphp
                <!-- form start -->
                <form role="form" method="post" action="edit_role">
                  @csrf
                  <div class="box-body">
                    
                    @foreach($retrive as $retrive)
                        <input type="text" value="{{$retrive->id}}" style="display:none;" name="id_role">
                        <div class="form-group">
                            <label>Le titre</label>
                            <input type="text" onkeyup='this.value=this.value.toUpperCase()' maxlength="30"
                            class="form-control input-lg" name="intitule"  value="{{$retrive->intitule}}"/>
                        </div>  
                  
                        <div class="form-group">
                          <label>Description</label>
                          <textarea class="form-control input-lg" name="specifite">{{$retrive->specifite}}</textarea>
                        </div>  
                    @endforeach
                    

                  </div>
                  <!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">VALIDER</button>
                  </div>
                </form>
              </div>
            @endif
           
        </div>
    </div>
		
@endsection