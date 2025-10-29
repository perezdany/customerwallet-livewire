@extends('layouts/base')

@php
   
  use App\Http\Controllers\TypePrestationController;

  $typeprestationcontroller = new TypePrestationController();

  $all =  $typeprestationcontroller-> GetAll();

              
            
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
                    <h3 class="card-title">Type de prestation</h3>
                </div>    
                <!-- /.card-header -->
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>N°</th>
                            <th>Type de prestation</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all as $all)
                                <tr>
                                    
                                    <td>{{$all->id}}</td>
                                    <td>{{$all->libele}}</td>
                                    
                                    <td>
                                        <form action="edit_typeprest_form" method="post">
                                            @csrf
                                            <input type="text" value={{$all->id}} style="display:none;" name="id_typeprest">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                        </form>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                           <th>N°</th>
                            <th>Type de prestation</th>
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
            <div class="card card-aeneas">
              <div class="card-header with-border">
                <h3 class="card-title">AJOUTER UN TYPE</h3><br>(*) champ obligatoire
              </div>
            
              <!-- form start -->
              <form role="form" method="post" action="add_type_prestation">
                @csrf
                <div class="card-body">

                  <div class="form-group">
                      <label>Type</label>
                      <input type="text" onkeyup='this.value=this.value.toUpperCase()' maxlength="30"
                       class="form-control input-lg" name="libele" placeholder="ONE SHOT"/>
                  </div>  
                

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">VALIDER</button>
                </div>
              </form>
            </div>
        </div>
    </div>


    <div class="row">
      
        
        

        <div class="col-md-6">
          @if(isset($id_edit))
            
              <div class="card card-aeneas">
              <div class="card-header with-border">
                <h3 class="card-title">MODIFIER UN TYPE </h3><br>(*) champ obligatoire
              </div>
              @php
                $retrive =  $typeprestationcontroller->GetById($id_edit)
              @endphp
              <!-- form start -->
              <form role="form" method="post" action="edit_typeprest">
                @csrf
                <div class="card-body">
                  @foreach($retrive as $retrive)
                  <input type="text" value="{{$retrive->id}}" style="display:none;" name="id_type_prestation">
                   <div class="form-group">
                      <label>Type:</label>
                      <input type="text" onkeyup='this.value=this.value.toUpperCase()' maxlength="30"
                       class="form-control input-lg" name="libele" value="{{$retrive->libele}}"/>
                    </div>  
                  @endforeach

                 
                

                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">VALIDER</button>
                </div>
              </form>
            </div>
          @endif
            
        </div>
    </div>
		
@endsection