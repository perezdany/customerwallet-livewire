@extends('layouts/base')

@php
    
    use App\Http\Controllers\ServiceController;

    $servicecontroller = new ServiceController();

    $all = $servicecontroller->GetAll();
    
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
                  <h3 class="box-title">Nos services</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
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
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
          <!-- /.row -->
		<div class="row">
          <div class="col-md-5">
            
             <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">AJOUTER UN SERVICE</h3><br>
              </div>
            
              <!-- form start -->
              <form role="form" method="post" action="add_service">
                @csrf
                <div class="box-body">
                   
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

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                    </div>
                </div>
                <!-- /.box-body -->

               
              </form>
            </div>
            <!-- /.box -->
          </div>
      
          <!-- left column -->
          <div class="col-md-2">
           
          </div>
          <!--/.col (left) -->
          <!-- right column -->
          <div class="col-md-5">
            <!-- general form elements -->
            @if(isset($id_service))
                @php
               
                    $edit =  $servicecontroller->GetById($id_service);
                @endphp
                @foreach($edit as $edit)
                    <div class="box box-aeneas">
                        <div class="box-header with-border">
                            <h3 class="box-title">MODIFIER LE SERVICE</h3><br>
                        </div>
                
                        <!-- form start -->
                        <form role="form" method="post" action="edit_service">
                            @csrf
                            <input type="text" value="{{$id_service}}" style="display:none;" name="id_service">
                            <div class="box-body">
                            
                                <div class="form-group">
                                    <label>Nom du Service :</label>
                                    <input type="text" class="form-control input-lg" value="{{$edit->libele_service}}" name="libele" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                                </div> 
                                <div class="form-group">
                                    <label>Description :</label>
                                    <textarea type="text" class="form-control input-lg" name="description" >{{$edit->description}}</textarea>
                                </div>  
                            

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">VALIDER</button>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </form>
                    </div>
                @endforeach
                
            @endif
            
            <!-- /.box -->
		  </div>
    </div>
    <!--/.col (right) -->
@endsection