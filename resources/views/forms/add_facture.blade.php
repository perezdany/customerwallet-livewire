@extends('layouts/base')

@php
   
  use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\PaiementController;
     use App\Http\Controllers\FactureController;
       use App\Http\Controllers\ContratController;

     use App\Http\Controllers\Calculator;

    $prestationcontroller = new PrestationController();

    $calculator = new Calculator();
   
    $paiementcontroller = new PaiementController();
    $facturecontroller = new FactureController();
     
    $contratcontroller = new ContratController();


  //LES DIFFERENTES REQUETES EN FONCTION DU DEPARTEMENT
  //$my_own = $prestationcontroller->MyOwnPrestation(auth()->user()->id);


@endphp

@section('content')
    <!--FAIRE ALLERTE POUR AFFICHER LES FACTURES DONT LA DATE EST ECHUE ET AFFICHER EN HAUT ENSUITE METTRE LE TOTAL DES FACTURES NON PAYEES EN BAS -->
   
    <!--AFFICHAGE DES FACTURESE-->
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
      
    </div>

    <div class="row">
         <div class="col-md-3">
          

        </div>

        <div class="col-md-6">
              @if(isset($id_edit))
                 <!-- general form elements MODIFICATION D'UNE FACTURE-->
                <div class="box box-aeneas">
                    <div class="box-header with-border">
                        <h3 class="box-title"> <b>MODIFIER UNE FACTURE</b></h3><br><b>(*) champ obligatoire</b>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    @php
                        $facture_edit = $facturecontroller->GetById($id_edit);
                    @endphp

                    @foreach($facture_edit as $facture_edit)
                        <!-- form start -->
                        <form role="form" method="post" action="edit_facture" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body">
                                <input type="text" value="{{$id_entreprise}}" name="id_entreprise" style="display:none;">
                                <input type="text" value="{{$etat}}" name="etat" style="display:none;">
                                <input type="text" value="{{$facture_edit->id}}" name="id_facture" style="display:none;">
                                <div class="form-group">
                                    <label>Numéro de la facture (*)</label>
                                    <input type="text" name="numero_facture" value="{{$facture_edit->numero_facture}}" required class="form-control " maxlength="30" onkeyup="this.value=this.value.toUpperCase()">
                                </div>
                            
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Date d'emission de la facture:</label>
                                    <input type="date" class="form-control  " required name="date_emission" value="{{$facture_edit->date_emission}}">
                                </div>
                                    
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Montant de la facture:</label>
                                    <input type="number" class="form-control  " value="{{$facture_edit->montant_facture}}" required name="montant_facture" maxlength="13">
                                </div>
                                    
                                <div class="form-group">
                                    <label>Prestation/Contrat:</label>
                                    <select class="form-control  " name="id_prestation" required>
                                            @php
                                                $contrats = $prestationcontroller->getAllNoReglee();
                                                
                                            @endphp
                                            
                                            <option value="{{$facture_edit->id_prestation}}"><b>Contrat:</b>{{$facture_edit->titre_contrat}}/<b>Date:</b>@php echo date('d/m/Y',strtotime($facture_edit->date_prestation));  @endphp</option>
                                            @foreach($contrats as $contrats)
                                                <option value="{{$contrats->id}}"><b>Contrat:</b>{{$contrats->titre_contrat}}/<b>Date:</b>@php echo date('d/m/Y',strtotime($facture_edit->date_prestation));  @endphp</option>
                                            @endforeach

                                    </select>
                                </div>

                                 <div class="form-group">
                                    <label>Fichier de la facture(PDF)</label>
                                    <input type="file" class="form-control" name="file" >
                                </div>
                        
                            
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer">
                            <button type="submit" class="btn btn-primary">VALIDER</button>
                            </div>
                        </form>
                    @endforeach
                 
                </div>
                <!-- /.box -->
            @else
           
                <!-- general form elements -->
                <div class="box box-aeneas">
                    <div class="box-header with-border">
                    <h3 class="box-title"> <b>ENREGISTRER UNE FACTURE</b></h3><br><b>(*) champ obligatoire</b>
                    </div>
                    
                    <!-- form start -->
                    <form role="form" method="post" action="add_facture" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label>Numéro de la facture (*)</label>
                                <input type="text" name="numero_facture" required value="{{old('numero_facture')}}" class="form-control " maxlength="30" onkeyup="this.value=this.value.toUpperCase()">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date d'emission de la facture(*):</label>
                                <input type="date" class="form-control  " value="{{old('date_emission')}}" required name="date_emission">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date de règlement:</label>
                                <input type="date" class="form-control  " name="date_reglement">
                            </div>
                                
                            <div class="form-group">
                                <label for="exampleInputEmail1">Montant de la facture(*):</label>
                                <input type="number" class="form-control  " required name="montant_facture" maxlength="13">
                            </div>
                                
                            <div class="form-group">
                                <label>Contrat(*):</label>
                            <select class="form-control  " name="id_contrat" required>
                                    @php
                                        $contrats = $contratcontroller->RetriveAll();
                                        
                                    @endphp
                                <option value="0">--Sélectionnez le contrat--</option>
                                    @foreach($contrats as $contrats)
                                        <option value="{{$contrats->id}}">--Date: @php 
                                            echo date('d/m/Y',strtotime($contrats->debut_contrat)) ;
                                            @endphp<b>--Contrat:</b>{{$contrats->titre_contrat}}/Client:{{$contrats->nom_entreprise}}</option>
                                    @endforeach

                            </select>
                            </div>

                            <div class="form-group">
                                <label>Fichier de la facture(PDF)</label>
                                <input type="file" class="form-control" name="file" >
                            </div>
                        
                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            @endif

        </div>
         

        <div class="col-md-3">
          

        </div>
    </div>
		
@endsection