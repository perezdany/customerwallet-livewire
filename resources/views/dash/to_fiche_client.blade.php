@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\StatutEntrepriseController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $all = $entreprisecontroller->DisplayCustomers();

    
@endphp

@section('content')
    @if(auth()->user()->id_role)
      <div class="row">
            <div class="col-md-3">
              <a href="form_add_contrat"><button class="btn btn-success"> <b>ENREGISTRER UN CONTRAT</b></button></a>
            
            </div>
            <div class="col-md-3">
                <a href="form_add_prestation"><button class="btn btn-primary"> <b>ENREGISTRER UNE PRESTATION</b></button></a>
            </div>
      </div>
    @endif
     
      <div class="row">
        @if(session('success'))
          <div class="col-md-12 card-header">
            <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
          </div>
        @endif
      
        <div class="col-md-12">
          <div class="card">
            
            <div class="card-header">
              <h3 class="card-title">Tableaux des clients</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-hover">
              <thead>
              <tr>
                <th>Nom</th>
                <th>Chiffre d'Affaire</th>
                <th>Nombre d'employés</th>
                <th>Client depuis le:</th>
                
              </tr>
              </thead>
              <tbody>
                  @foreach($all as $all)
                    <tr>
                      <td>
                        @if($all->id_statutentreprise == 2)
                          
                          <form method="post" action="display_fiche_customer">
                            @csrf
                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                            <button class="btn btn-default"> <b>{{$all->nom_entreprise}}</b></button>
                          </form>
                        @else
                          <form method="post" action="display_about_prospect">
                            @csrf
                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                            <button class="btn btn-default"> <b>{{$all->nom_entreprise}}</b></button>
                          </form>
                        @endif
                        
                          
                      </td>
                      <td>
                        {{$all->chiffre_affaire}}
                      </td>
                      <td>
                        {{$all->nb_employes}}  
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
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
       
       
      </div>
    <!--/.col (right) -->
@endsection