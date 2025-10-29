@extends('layouts/base')

@php
    
    use App\Http\Controllers\PrestationController;

    $prestationcontroller = new PrestationController();

    $all = $prestationcontroller->GetAll();

    
@endphp

@section('content')
     <div class="row">
         @if(session('success'))
            <div class="col-md-12 card-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Tableaux des prestations</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                 <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Type de prestation</th>
                            <th>Lieu</th>
                            <th>Entreprise</th>
                            <th>Fin de contrat</th>
                            <th>Prestation</th>
                           
                            <th>Ajpouté par</th>	
                            
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
                              
                                    <td>{{$all->nom_prenoms}}</td>
                           
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
       
    </div>
    <!--/.col (right) -->
@endsection