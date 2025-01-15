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
                            <th>Modifier</th>
                            <th>Supp</th>
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
                                    
                                    <td>
                                        <form action="edit_paiement_form" method="post">
                                            @csrf
                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_paiement">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                        </form>
                                    </td>
                                    @can("comptable")
                                        @can("delete")
                                            <td>

                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$my_own->id.""; @endphp">
                                                 <i class="fa fa-times"></i>
                                                </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$my_own->id.""; @endphp">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Supprimer </h4>
                                                        </div>
                                                        <form action="delete_paiement" method="post">
                                                        <div class="modal-body">
                                                            <p>Voulez-vous supprimer le paiement du montant de {{$my_own->paiement}} XOF?</p>
                                                            @csrf
                                                            @csrf
                                                            <input type="text" value="{{$id}}" style="display:none;" name="id">
                                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_paiement">
                                                            
                                                        </div>
                                                        
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-outline">Supprimer</button>
                                                        </div>
                                                        </form>
                                                    </div>
                                                    <!-- /.modal-content -->
                                                    </div>
                                                    <!-- /.modal-dialog -->
                                                </div>
                                                <!-- /.modal -->
                                            </td>
                                        @endcan
                                    @endcan

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
        <!-- right column -->
        <div class="col-md-6">
            @php
                $retrive = $facturecontroller->GetById($id);
                
                //dd($retrive);
            @endphp
            <div class="box box-aeneas">
                <div class="box-header with-border">
                    <b><h3 class="box-title">Paiements </h3><br>
                    (*)champ obligatoire</b>
                </div>
              
                @foreach($retrive as $retrive)
                     <!-- form start -->
                    <form role="form" action="do_paiement" method="post">
                        @csrf
                        <input type="text" value="{{$id}}" name="id_facture" style="display:none;">
                        <input type="text" value="{{$retrive->montant_facture}}" name="montant_facture" style="display:none;">
                        <input type="text" value="{{$retrive->id_contrat}}" name="id_contrat" style="display:none;">
                        <input type="text" value="{{$retrive->id}}" name="id_facture" style="display:none;">
                     
                        <div class="box-body">

                            <div class="form-group">
                                <label>CONTRAT DU :</label>
                                <p><h3>@php echo date('d/m/Y',strtotime($retrive->debut_contrat)) @endphp</h3></p>
                            </div>
                            <!--<div class="form-group">
                                <label>SERVICE :</label>
                                <p><h3>  </h3></p>
                            </div>-->
                             <div class="form-group">
                                <label>TYPE DE PRESTATION :</label>
                                <p><h3> {{$retrive->libele}}</h3> </p>
                            </div>
                            <div class="form-group">
                                <label>Numéro facture</label>
                                <input type="text" maxlength="30" readonly="true" class="form-control input-lg" name="numero_facture" value="{{$retrive->numero_facture}}">
                            </div>
                            <div class="form-group">
                                <label>MONTANT RESTANT DE LA FACTURE:</label>
                                <!--CODE POUR DETECTER LES TOUS LES PAIEMENTS DE CETTE FACTURE ET RETOURNER LE RESTE-->
                                <p>
                                        @php
                                             $rest  = $calculator->RetrunMontantRest($retrive->id, $retrive->montant_facture);
                                        @endphp
                                        <input type="text" class="form-control input-lg"  value="{{$rest}}"  id="lereste" disabled>
                                    
                                </p>
                            </div>
                           

                            <div class="form-group">
                                <label>Entrer le montant du paiement</label>
                                <input type="number" class="form-control input-lg" name="paiement" required id="mt" onkeyup="VerifRest()">
                            </div>
                            <div class="form-group" id="message">
                                
                            </div>

                            <div class="form-group">
                                <label>Date du paiement</label>
                                <input type="date" class="form-control input-lg" name="date_paiement" required>
                            </div>
                            
                           
                            
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary" id="bt" disabled="disabled">VALIDER</button>
                        </div>
                        <script>
                            function VerifRest() {
                               
                                    /* ce script permet de vérifier si le montant saisi est trop élevé et l'obliger a saisir un montant plus bas*/
                                    var val = document.getElementById("lereste").value;
                                    var val2 = document.getElementById("mt").value;

                                    var button = document.getElementById("bt")

                                    var diff = val - val2;
                                    //alert(diff)

                                    if((diff < 0))
                                    {  
                                    
                                        var theText = "<p style='color:red'>MONTANT SUPERIEUR AU RESTE !.</p>";
                                        document.getElementById("message").innerHTML= theText;
                                        button.setAttribute("disabled", "true");
                                        
                                    }
                                    else
                                    {
                                       button.removeAttribute("disabled");
                                       var theText = "<p style='color:red'></p>";
                                        document.getElementById("message").innerHTML= theText;
                                      
                                    }
                                
                                }
                        </script>
                    </form>

                @endforeach
               
            </div>		
        </div>
        
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
                            <input type="text" value="{{$retrive->id_prestation}}" name="id_prestation" style="display:none;">
                            <input type="text" value="{{$retrive->montant}}" name="montant" style="display:none;">
                            <input type="text" value="{{$retrive->id_contrat}}" name="id_contrat" style="display:none;">
                            <input type="text" value="{{$retrive->id}}" name="id_paiement" style="display:none;">
                            <input type="text" value="{{$retrive->id_facture}}" name="id_facture" style="display:none;">
                        
                            <div class="box-body">

                                <div class="form-group">
                                    <label>PRESTATION DU :</label>
                                    <p><h3>@php echo date('d/m/Y',strtotime($retrive->debut_contrat)) @endphp</h3></p>
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
                                    <input type="number" class="form-control input-lg" required name="paiement" value="{{$retrive->paiement}}">
                                </div>

                                <div class="form-group">
                                    <label>Date du paiement</label>
                                    <input type="date" class="form-control input-lg" required name="date_paiement" value="{{$retrive->date_paiement}}">
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
     
    
   