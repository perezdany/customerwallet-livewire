@extends('layouts/base')

@php

    use App\Http\Controllers\PaysController;

     use App\Http\Controllers\CibleController;

    $ciblecontroller = new CibleController();

   

    $payscontroller = new PaysController();

    $all = $ciblecontroller->GetAll();
@endphp

@section('content')
      
      <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
            @if(session('error'))
            <div class="col-md-12 box-header">
              <p class="bg-warning" style="font-size:13px;">{{session('error')}}</p>
            </div>
          @endif

            <div class="col-md-6">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Entreprise Cibles</h3>
                </div>
                <!-- /.box-header -->
                 <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    
                    <th>Pays</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->nom}}</td>
                          
                          <td>{{$all->adresse}}</td>
                          
                          <td>{{$all->nom_pays}}</td>
                          <td>
                            <form action="edit_cible_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                            </form>
                             <form action="delete_cible" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                  
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col --> 

            <div class="col-md-6">

            

              <!-- general form elements -->
              @if(isset($id_entreprise))
                  @php
                      $edit =  $ciblecontroller->GetById($id_entreprise);
                  @endphp
                  @foreach($edit as $edit)
                      <div class="box box-aeneas">
                          <div class="box-header with-border">
                            <h3 class="box-title">MODIFIER UNE ENTREPRISE CIBLE</h3><br>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                          </div>
                      
                          <!-- form start -->
                          <form role="form" method="post" action="edit_entreprise_cible">
                            <div class="box-body">
                              @csrf
                              <input type="text" name="id_entreprise" value="{{$edit->id}}" style="display:none;">
                              <div class="box-body">
                              
                                  <div class="form-group">
                                      <label>Nom :</label>
                                      <input type="text" class="form-control input-lg" value="{{$edit->nom}}" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                                  </div> 
                                 

                                  <div class="form-group">
                                      <label>Adresse :</label>
                                      <input type="text" class="form-control input-lg" value="{{$edit->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" />
                                  </div>

                             
                                  <div class="form-group">
                                    <label >Téléphone (fixe/mobile):</label>
                                    <input type="text"  maxlength="18" class="form-control  input-lg" value="{{$edit->contact}}" name="tel" placeholder="+225 27 47 54 45 68">
                                  </div>

                                  
                                <div class="form-group">
                                    <label>Pays :</label>
                                    <select class="form-control input-lg" name="pays" required>
                                      <option value={{$edit->id_pays}}>{{$edit->nom_pays}}</option>
                                        @php
                                            $pays = $payscontroller->DisplayAll();
                                        @endphp
                                        @foreach($pays as $pays)
                                            <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                            
                                        @endforeach
                                        
                                    </select>
                                </div>

                                  <div class="box-footer">
                                      <button type="submit" class="btn btn-primary">VALIDER</button>
                                  </div>
                              </div>
                            </div>  <!-- /.box-body -->
                            
                          </form>
                      </div>
                  @endforeach
                  
              @endif

            </div>
      </div>
          <!-- /.row -->
    <div class="row"></div>
    <div class="row">
      
        <div class="col-md-6">
          <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">AJOUTER UNE ENTREPRISE CIBLE</h3><br>
                  <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                
              </div>
           
              <!-- form start -->
              <form role="form" method="post" action="add_entreprise_cible">
                @csrf
                <div class="box-body">
                    
                    <div class="form-group">
                        <label>Nom :</label>
                        <input type="text" class="form-control input-lg" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                    </div> 
                   
                     <div class="form-group">
                      <label >Adresse:</label>
                      <input type="text" maxlength="18" class="form-control  input-lg" name="adresse" placeholder="COCODY" onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                     <div class="form-group">
                      <label >Téléphone (fixe/mobile):</label>
                      <input type="text"  maxlength="18" class="form-control  input-lg" name="tel" placeholder="+225 27 47 54 45 68">
                    </div>
                    
                    <div class="form-group">
                        <label>Pays :</label>
                        <select class="form-control input-lg" name="pays" reuqired>
                            @php
                                $pays = $payscontroller->DisplayAll();
                            @endphp
                            @foreach($pays as $pays)
                                <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                
                            @endforeach
                            
                        </select>
                    </div>
                    
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">ENREGISTRER</button>
                    </div>
                </div>
                <!-- /.box-body -->
              </form>
              
             
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->
        

        <!-- right column -->
        <div class="col-md-6">
        </div>
          <!-- /.box -->
		  
    </div>
    <!--/.col (right) -->
      <!--/.col (right) -->
@endsection