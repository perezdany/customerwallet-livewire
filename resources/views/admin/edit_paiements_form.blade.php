@extends('layouts/base')
@php

    use App\Http\Controllers\DepartementController;
    use App\Http\Controllers\UserController;

    use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\PaiementController;


    $prestationcontroller = new PrestationController();
    $usercontroller = new UserController();
    $departementcontroller = new DepartementController();
    $paiementcontroller = new PaiementController();

    

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
    
    </div>
    <!-- Main row -->  
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
                            
                            <input type="text" value="{{$retrive->montant}}" name="montant" style="display:none;">
                            <input type="text" value="{{$retrive->id_contrat}}" name="id_contrat" style="display:none;">
                        
                            <div class="box-body">

                                <div class="form-group">
                                    <label>PRESTATION DU :</label>
                                    <p><h3>@php echo date('d/m/Y',strtotime($retrive->date_prestation)) @endphp</h3></p>
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
     
    
   