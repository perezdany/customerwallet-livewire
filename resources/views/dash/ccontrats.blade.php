@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\StatutEntrepriseController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $all = $entreprisecontroller->DisplayCustomers();

    
@endphp

@section('content')
      
      <div class="row">
        <div class="col-md-3">
          <a href="form_add_contrat"><button class="btn btn-success"> <b>ENREGISTRER UN CONTRAT</b></button></a>
                
        </div>   

        <div class="col-md-3">
          <a href="all_contrats"><button class="btn btn-primary"> <b>TOUS LES CONTRATS</b></button></a>
        </div>
      </div>
      <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
            <div class="col-md-12">
              <div class="box">
               
                <div class="box-header">
                  <h3 class="box-title">Tableaux des clients</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Nom</th>
                    
                    <th>Client depuis le:</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>
                            <form method="post" action="display_contrat_customer">
                                @csrf
                                <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                                <button class="btn btn-default"> <b>{{$all->nom_entreprise}}</b></button>
                            </form>
                             
                          </td>
                          
                          
                          <td>
                            @php 
                                if($all->client_depuis != NULL)
                                {
                                    echo date('d/m/Y',strtotime($all->client_depuis)) ;
                                }
                                
                            @endphp
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
        </div>
       
    </div>
    <!--/.col (right) -->
@endsection