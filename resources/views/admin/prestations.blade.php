@extends('layouts/base')

@php
   
  use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\PaiementController;
     use App\Http\Controllers\FactureController;

     use App\Http\Controllers\Calculator;

    $prestationcontroller = new PrestationController();

    $calculator = new Calculator();
   
    $paiementcontroller = new PaiementController();
    $facturecontroller = new FactureController();


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
                          
                          @if(auth()->user()->id_role == 3)
                          @else
                             <th>Action</th>
                          @endif
                         
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
                                      
                                       
                                     
                                        @if(auth()->user()->id_role == 3)
                                         
                                        @else
                                          <td>
                                            @if(auth()->user()->id_role == 2)
                                              <form action="display_facture" method="post">
                                                  @csrf
                                                  <input type="text" value={{$all->id}} style="display:none;" name="id_prestation">
                                                  <button type="submit" class="btn btn-success"><i class="fa fa-ticket"></i></button>
                                              </form>
                                            @else

                                            @endif

                                          
                                            <form action="edit_prestation_form" method="post">
                                                @csrf
                                                <input type="text" value={{$all->id}} style="display:none;" name="id_prestation">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                            </form>

                                          </td>
                                        @endif

                                     
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
                          
                          @if(auth()->user()->id_role == 3)
                          @else
                            <th>Action</th>
                          @endif
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


    <!--AFFICHAGE DES FACTURES DE LA PRESTATION SELECTIONNEE-->
    <div class="row">
        <div class="col-md-8">
          @if(isset($id_prestation))
                @php
                    $my_own = $facturecontroller->DisplayByIdPrestation($id_prestation);
                @endphp

                <div class="box">
                    <div class="box-header">
                    <h3 class="box-title">Facture de la prestation</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Facture N°</th>

                            <th>Emise le:</th>
                            <th>Date de règlement</th>
                            <th>Montant</th>
                            <th>Contrat</th>
                            <th>Etat facture</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($my_own as $my_own)
                                <tr>
                                <td>{{$my_own->numero_facture}}</td>
                                <td>@php echo date('d/m/Y',strtotime($my_own->date_emission)) @endphp</td>
                                <td>@php echo date('d/m/Y',strtotime($my_own->date_reglement)) @endphp</td>
                                <td>
                                    @php
                                        echo  number_format($my_own->montant_facture, 2, ".", " ")." XOF";
                                    @endphp
                                </td>
                                
                                <td>{{$my_own->titre_contrat}}</td>
                                 <td>
                                    @if($my_own->reglee == 0)
                                      <p class="bg-warning">
                                        <b>Facture non réglée</b>
                                      </p>
                                    @endif
                                    @if($my_own->reglee == 1)
                                      <p class="bg-success">
                                        <b>Facture réglée</b>
                                      </p>
                                    @endif
                                 
                                 </td>
                                <td>
                                  @if(auth()->user()->id_role != 3)
                                      @if($my_own->reglee == 0)
                                          @if(auth()->user()->id_role != 2)
                                          <form action="paiement_form" method="post">
                                            @csrf
                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                          </form>
                                        @endif
                                      @else
                                      
                                      @endif
                                      
                                      <form action="edit_facture_form" method="post">
                                          @csrf
                                          <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                          <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                      </form>
                                  @endif
                                 
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Facture N°</th>

                            <th>Emise le:</th>
                            <th>Date de règlement</th>
                            <th>Montant</th>
                            <th>Contrat</th>
                            <th>Etat facture</th>
                            <th>Action</th>
                        </tr>
                        </tfoot>
                    </table>
                    </div>
                    <!-- /.box-body -->
                </div>
              <!-- /.box -->
          @endif
           
        </div>
    </div>
		
@endsection