@extends('layouts/base')

@php
    use App\Http\Controllers\ControllerController;

   
    use App\Http\Controllers\DepartementController;

    $departementcontroller = new DepartementController();

    $all = $departementcontroller->GetAll();
    
@endphp

@section('content')
     <div class="row">
         @if(session('success'))
            <div class="col-md-12 card-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
            <div class="col-md-8">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Départements</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead >
                  <tr>
                    <th>Nom du Département</th>
                   
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->libele_departement}}</td>
                          
                          
                          <td>
                            <form action="edit_depart_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="libele">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                    <th>Nom du Département</th> 
                    <th>Action</th>
                  </tr>
                  </tfoot>
                  </table>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
             
            <div class="col-md-4">
            
              <!-- general form elements -->
              <div class="card card-aeneas">
                <div class="card-header with-border">
                  <h3 class="card-title">AJOUTER UN DEPARTEMENT</h3><br>
                </div>
              
                <!-- form start -->
                <form role="form" method="post" action="add_departement">
                  @csrf
                  <div class="card-body">
                    
                      <div class="form-group">
                          <label>Nom du Département :</label>
                          <input type="text"  maxlength="60" class="form-control input-lg" name="libele" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                      </div>  

                      <div class="card-footer">
                          <button type="submit" class="btn btn-primary">VALIDER</button>
                      </div>
                  </div>
                  <!-- /.card-body -->

                
                </form>
              </div>
              <!-- /.card -->
            </div>
        </div>
          <!-- /.row -->
		<div class="row">
         <div class="col-md-5">
            <!-- general form elements -->
            @if(isset($id_departement))
                @php
                    $edit =  $departementcontroller->GetById($id_departement);
                @endphp
                @foreach($edit as $edit)
                    <div class="card card-aeneas">
                        <div class="card-header with-border">
                            <h3 class="card-title">MODIFIER LE DEPARTEMENT</h3><br>
                        </div>
                
                        <!-- form start -->
                        <form role="form" method="post" action="edit_departement">
                            @csrf

                            <div class="card-body">
                            
                                <input type="text" value="{{$id_departement}}" style="display:none;" name="id_departement">
                                <div class="form-group">
                                    <label>Nom du Département :</label>
                                    <input type="text" value="{{$edit->libele_departement}}"class="form-control input-lg" name="libele" required  onkeyup='this.value=this.value.toUpperCase()'/>
                                </div>  
                            

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">VALIDER</button>
                                </div>
                            </div>
                            <!-- /.card-body -->

                        
                        </form>
                    </div>
                @endforeach
                
            @endif
          </div>
      
          <!-- left column -->
          <div class="col-md-2">
           
          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-5">
           
          </div>
            <!-- /.card -->
		  </div>
    </div>
    <!--/.col (right) -->
@endsection