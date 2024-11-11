@extends('layouts/base')

@php
    
    use App\Http\Controllers\PrestationController;

    $prestationcontroller = new PrestationController();

    $all = $prestationcontroller->GetAll();

    
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
                  <h3 class="box-title">Tableaux des prestations</h3>
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
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
       
    </div>
    <!--/.col (right) -->
@endsection