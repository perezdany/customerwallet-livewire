@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    $contratcontroller = new ContratController();

    $all = $contratcontroller->RetriveAll();
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
                  <h3 class="box-title">Bases de données des contrats</h3>
            </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Titre de contrat</th>
                    <th>Entreprise</th>
                    <th>Type de contrat</th>
                    
                    <th>Début du contrat</th>
                    <th>Fin du contrat</th>
                    <th>Montant</th>	
                    <th>Reste à payer</th>
                    <th>Date de solde</th>
                   
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->titre_contrat}}</td>
                          <td>{{$all->nom_entreprise}}</td>
                          <td>{{$all->libele}}</td>
                          
                          <td>@php echo date('d/m/Y',strtotime($all->debut_contrat)) @endphp</td>
                           <td>@php echo date('d/m/Y',strtotime($all->fin_contrat)) @endphp</td>
                          <td>
                            @php
                              echo  number_format($all->montant, 2, ".", " ")." XOF";
                            @endphp
                           
                          </td>  
                          <td>@php echo  number_format($all->reste_a_payer, 2, ".", " ")." XOF";@endphp</td>
                          
                          <td>
                            @php 
                              if($all->statut_solde == 0)
                              {
                                echo date('d/m/Y',strtotime($all->date_solde)) ;
                              }
                              else
                              {
                                echo '<p class="bg-success">Soldé</p>';
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
        
@endsection