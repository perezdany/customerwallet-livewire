@extends('layouts/base')

@php
    
    use App\Http\Controllers\ServiceController;
  use App\Http\Controllers\CategorieController;
  $categoriecontroller = new CategorieController();
    $servicecontroller = new ServiceController();

    $all = $servicecontroller->GetAll();
    
@endphp

@section('content')
  <div class="row">

    <div class="col-md-8">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nos services</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped table-hover">
          <thead>
          <tr>
            <th>Service</th>
            <th>Catégorie</th>
            <th>Description</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
              @foreach($all as $all)
                <tr>
                  <td>{{$all->libele_service}}</td>
                  <td><b>{{$all->libele_categorie}}</b></td>
                  <td>{{$all->description}}</td>
                  
                  <td>
                    <form action="edit_service_form" method="post">
                        @csrf
                        <input type="text" value={{$all->id}} style="display:none;" name="id_service">
                        <button type="submit" class="btn btn-primary" >
                        
                        <i class="fa fa-edit"></i>
                        </button>
                    </form>

                      <form action="delete_service" method="post">
                        @csrf
                        <input type="text" value={{$all->id}} style="display:none;" name="id_service">
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
              @endforeach
          </tbody>
          <tfoot>
          <tr>
              <th>Service</th>
              <th>Catégorie</th>
            <th>Description</th>
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
    @if(isset($id_service))
      <div class="col-md-4">
        <!-- general form elements -->
            @php
              $edit =  $servicecontroller->GetById($id_service);
            @endphp
            @foreach($edit as $edit)
                <div class="card card-aeneas">
                    <div class="card-header with-border">
                        <h3 class="card-title">MODIFIER LE SERVICE</h3><br>
                    </div>
            
                    <!-- form start -->
                    <form role="form" method="post" action="edit_service">
                        @csrf
                        <input type="text" value="{{$id_service}}" style="display:none;" name="id_service">
                        <div class="card-body">
                        
                            <div class="form-group">
                                <label>Nom du Service :</label>
                                <input type="text" class="form-control input-lg" value="{{$edit->libele_service}}" name="libele" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                            </div> 
                            <div class="form-group">
                                <label>Description :</label>
                                <textarea type="text" class="form-control input-lg" name="description" >{{$edit->description}}</textarea>
                            </div>  
                        

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">VALIDER</button>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </form>
                </div>
            @endforeach
        <!-- /.card -->
      </div>
    @endif
    <div class="col-md-4">
      <!-- general form elements -->
      <div class="card">
        <div class="card-header with-border">
          <h3 class="card-title">AJOUTER UN SERVICE</h3><br>
        </div>
      
        <!-- form start -->
        <form role="form" method="post" action="add_service">
          @csrf
          <div class="card-body">
              
              <div class="form-group">
                  <label>Nom du Service :</label>
                  <input type="text" maxlength="60" class="form-control input-lg" name="libele" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
              </div> 
              <div class="form-group">
                  <label>Description :</label>
                  <textarea type="text" class="form-control input-lg" name="description"></textarea>
              </div>  

                <div class="form-group">
                              <label>Catgégories (*)</label>
                              <select class="form-control input-lg select2"  multiple="multiple" name="categorie" required>
                              
                                  <!--liste des services a choisir -->
                              
                                  @php
                                      $get = $servicecontroller->GetAll();
                                      $categorie = $categoriecontroller->DisplayAll();
                                  @endphp
                                  @foreach( $categorie as $categorie)
                                      
                                      <option label="{{$categorie->id}}">{{$categorie->libele_categorie}}</option>
                                      
                                    
                                  @endforeach
                              
                              </select>
                      
                          
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
     
    
        <!-- left column -->
        <div class="col-md-2">
          
        </div>
        <!--/.col (left) -->
        <!-- right column -->
      
  </div>
  <!--/.col (right) -->
@endsection