@extends('layouts/base')
@php
    use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\PaiementController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\Calculator;

    $calculator = new Calculator();

    $prestationcontroller = new PrestationController();
  
    $paiementcontroller = new PaiementController();

    $facturecontroller =  new FactureController();

@endphp

@section('content')
    <div class="row">
        @if(session('success'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-success" >{{session('success')}}</p>
            </div>
        @endif
         @if(session('error'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-danger" >{{session('error')}}</p>
            </div>
        @endif
    <!-- left column -->
    
        <div class="col-md-6">
           @if(isset($id))
                @php
                    $my_own = $paiementcontroller->GetPaimentByIdFacture($id);
                @endphp

                <div class="box">
                    <div class="box-header">
                    <h3 class="box-title">Paiements effectués</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Montant</th>
                        
                            <th>Date de paiement</th>
                            <th>Numéro de facture</th>
                            @if(auth()->user()->id_role == 3)
                            @else
                                <th>Action</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($my_own as $my_own)
                                <tr>
                                <td>
                                    @php
                                        echo  number_format($my_own->paiement, 2, ".", " ")." XOF";
                                    @endphp
                                </td>
                                
                                <td>@php echo date('d/m/Y',strtotime($my_own->date_paiement)) @endphp</td>
                                
                                <td>{{$my_own->numero_facture}}</td>
                                @if(auth()->user()->id_role == 3)
                                @else
                                    @can("comptable")
                                        <td>
                                            <form action="edit_paiement_form" method="post">
                                                @csrf
                                                <input type="text" value={{$my_own->id}} style="display:none;" name="id_paiement">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                            </form>
                                        </td>
                                    @endcan
                                    @can("admin")
                                        <td>
                                            <form action="edit_paiement_form" method="post">
                                                @csrf
                                                <input type="text" value={{$my_own->id}} style="display:none;" name="id_paiement">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                            </form>
                                        </td>
                                    @endcan
                                @endif
                                
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                    </div>
                    <!-- /.box-body -->
                </div>
              <!-- /.box -->
            @endif
           
        </div>
        <!--/.col (left) -->
        
        
    </div>
    <!-- Main row -->  
    <div class="row">
       
    <!-- left column -->
     
        <!--/.col (left) -->
        <!-- right column -->
        <div class="col-md-6">
            @if(isset($id_edit))
                    <div class="box box-aeneas">
                    <div class="box-header with-border">
                        <b><h3 class="box-title">Paiements </h3><br>
                        (*)champ obligatoire</b>
                    </div>
                    @php
                    $retrive =  $paiementcontroller->GetById($id_edit)
                    
                    
                    @endphp
                    @foreach($retrive as $retrive)
                        <!-- form start -->
                        <form role="form" action="edit_paiement" method="post">
                            @csrf
                            <input type="text" value="{{$retrive->id_prestation}}" name="id_prestation" style="display:none;">
                            <input type="text" value="{{$retrive->montant}}" name="montant" style="display:none;">
                            <input type="text" value="{{$retrive->id_contrat}}" name="id_contrat" style="display:none;">
                            <input type="text" value="{{$retrive->id}}" name="id_paiement" style="display:none;">
                            <input type="text" value="{{$retrive->id_facture}}" name="id_facture" style="display:none;">
                        
                            <div class="box-body">

                                <div class="form-group">
                                    <label>PRESTATION DU :</label>
                                    <p><h3>@php echo date('d/m/Y',strtotime($retrive->date_prestation)) @endphp</h3></p>
                                </div>
                                <div class="form-group">
                                    <label>SERVICE :</label>
                                    <p><h3> {{$retrive->libele_service}} </h3></p>
                                </div>
                                <div class="form-group">
                                    <label>TYPE DE PRESTATION :</label>
                                    <p><h3> {{$retrive->libele}}</h3> </p>
                                </div>
                                <div class="form-group">
                                    <label>MONTANT RESTANT:</label>
                                    <p><h3>
                                            @php
                                                echo  number_format($retrive->reste_a_payer, 2, ".", " ")." XOF";
                                            @endphp
                                        </h3>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label >DATE DE SOLDE</label>
                                    <p><h3>@php echo date('d/m/Y',strtotime($retrive->date_solde)) @endphp</h3></p>
                                </div>

                                <div class="form-group">
                                    <label>Entrer le montant du paiement</label>
                                    <input type="number" class="form-control input-lg" name="paiement" value="{{$retrive->paiement}}">
                                </div>

                                <div class="form-group">
                                    <label>Date du paiement</label>
                                    <input type="date" class="form-control input-lg" name="date_paiement" value="{{$retrive->date_paiement}}">
                                </div>
                                
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                            <button type="submit" class="btn btn-primary">VALIDER</button>
                            </div>
                        </form>

                    @endforeach
                
                </div>		
            @endif
            
        </div>
        
    </div>
    <!-- Main row -->  

@endsection
     
    
   