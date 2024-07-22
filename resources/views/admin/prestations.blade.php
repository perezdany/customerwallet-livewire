@extends('layouts/base')

@php
   
  use App\Http\Controllers\PrestationController;

  $prestationcontroller = new PrestationController();

  //LES DIFFERENTES REQUETES EN FONCTION DU DEPARTEMENT
  $my_own = $prestationcontroller->MyOwnPrestation(auth()->user()->id);

  $all = $prestationcontroller-> GetAll();
@endphp

@section('content')
     <div class="row">
      
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif

            @if(session('error'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-danger" >{{session('error')}}</p>
            </div>
        @endif
        
        <div class="col-md-12">
          <div class="box">
          
                 <div class="box-header">
                      <h3 class="box-title">Prestations réalisées</h3>
                    </div>    
                      <!-- /.box-header -->
                    <div class="box-body">
                      <table id="example1" class="table table-bordered table-striped table-hover">
                          <thead>
                          <tr>
                          <th>Date</th>
                          <th>Type de prestation</th>
                          <th>Lieu</th>
                          <th>Entreprise</th>
                          <th>Fin de contrat</th>
                          <th>Prestation</th>
                          <th>Description de la prestation</th>	
                          <th>Ajouté par</th>	
                          <th>Action</th>
                          </tr>
                          </thead>
                          <tbody>
                              @foreach($all as $all)
                                  <tr>
                                      <td>@php echo date('d/m/Y',strtotime($all->date_prestation)) @endphp</td>
                                      <td>{{$all->libele}}</td>
                                      <td>{{$all->localisation}}</td>
                                      <td>{{$all->nom_entreprise}}</td>
                                      <td>@php echo date('d/m/Y',strtotime($all->fin_contrat));  @endphp</td>
                                      <td>{{$all->libele_service}}</td>
                                      <td>{{$all->description}}</td>
                                       <td>{{$all->nom_prenoms}}</td>
                                      <td>
                                          @if(auth()->user()->id_role != 2)
                                              <form action="paiement_form" method="post">
                                                @csrf
                                                <input type="text" value={{$all->id}} style="display:none;" name="id_prestation">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                            </form>
                                          @endif
                                          <form action="edit_prestation_form" method="post">
                                              @csrf
                                              <input type="text" value={{$all->id}} style="display:none;" name="id_prestation">
                                              <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                          </form>
                                          
                                      </td>
                                  </tr>
                              @endforeach
                          </tbody>
                          <tfoot>
                          <tr>
                         <th>Date</th>
                          <th>Type de prestation</th>
                          <th>Lieu</th>
                          <th>Entreprise</th>
                          <th>Fin de contrat</th>
                          <th>Prestation</th>
                          <th>Description de la prestation</th>	
                          <th>Ajouté par</th>	
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
		
@endsection