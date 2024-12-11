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
  //$my_own = $prestationcontroller->MyOwnPrestation(auth()->user()->id);

  $all = $prestationcontroller-> GetAll();


@endphp

@section('content')
        
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
        <div class="col-md-12">
          

            <div class="box">
                <div class="box-header">
                <h3 class="box-title">Factures</h3>
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
                        <th>Afficher les paiements</th>
                        <th>Contrat</th>
                        <th>Etat facture</th>
                        <th>Fichier</th>
                        @if(auth()->user()->id_role == 3)
                        @else
                            <th>Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                        @php
            
                            if(isset($id_entreprise))
                            {
                                $my_own = $facturecontroller->GetByIdEntreprise($id_entreprise);
                            }
                            
                        @endphp
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
                            <td>
                                <form action="paiement_by_facture" method="post">
                                        @csrf
                                        <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                </form>
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
                                <form action="upload_file_facture" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <label>Fichier scanné(PDF)</label>
                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                    <input type="file" class="form-control" name="file">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                                </form>

                                <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <label>Télécharger</label>
                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                    <input type="text" class="form-control" name="file" value="{{$my_own->file_path}}" style="display:none;">
                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                </form>
                            </td>
                            @if(auth()->user()->id_role == 3)
                            @else
                                <td>

                                    @if($my_own->reglee == 0)
                                        @if(auth()->user()->id_role == 2)
                                        <form action="paiement_form" method="post">
                                            @csrf
                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                        </form>
                                        @endif
                                    @else
                                    
                                    @endif
                                    <div class="popup" id="popup">
                                        
                                        <div class="popup-contenu"><b>Cliquez et le formulaire s'affiche en dessous</b><br/>
                                            <a href="#" id="popup-fermeture" onclick="togglePopup();">Fermer</a>
                                        </div>
                                    </div>
                                    <form action="edit_facture_form" method="post">
                                        @csrf
                                        <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                        <input type="text" value={{$id_entreprise}} style="display:none;" name="id_entreprise">
                                        <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                    </form>
                                </td>
                            @endif
                            
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
           
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">

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
                            <input type="text" name="numero_facture" required value="{{old('numero_facture')}}" class="form-control input-lg" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">
                        </div>

                         <div class="form-group">
                            <label for="exampleInputEmail1">Date d'emission de la facture:</label>
                            <input type="date" class="form-control  input-lg" value="{{old('date_emission')}}" required name="date_emission">
                        </div>

                        <div class="form-group">
                            <label>A régler d'ici le: (*)</label>
                            <input type="date" name="date_reglement" class="form-control input-lg" value="{{old('date_reglement')}}" required>
                        </div>
                            
                        <div class="form-group">
                            <label for="exampleInputEmail1">Montant de la facture:</label>
                            <input type="number" class="form-control  input-lg" required name="montant_facture" maxlength="13">
                        </div>
                            
                        <div class="form-group">
                            <label>Prestation/Contrat:</label>
                           <select class="form-control  input-lg" name="id_prestation" required>
                                @php
                                    $contrats = $prestationcontroller->getAllNoReglee();
                                    
                                @endphp
                               <option value="0">--Sélectionnez la prestation--</option>
                                @foreach($contrats as $contrats)
                                    <option value="{{$contrats->id}}">--Date:{{$contrats->date_prestation}}<b>--Contrat:</b>{{$contrats->titre_contrat}}--Lieu:</b>{{$contrats->localisation}}</option>
                                @endforeach

                           </select>
                        </div>

                        <div class="form-group">
                            <label>Fichier de la facture(PDF)</label>
                              <input type="file" class="form-control" name="file" required>
                        </div>
                    
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer">
                    <button type="submit" class="btn btn-primary">VALIDER</button>
                    </div>
                </form>
            </div>
            <!-- /.box -->

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
                        <form role="form" method="post" action="edit_facture">
                            @csrf
                            <div class="box-body">
                                <input type="text", value="{{$facture_edit->id}}" name="id_facture" style="display:none;">
                                <div class="form-group">
                                    <label>Numéro de la facture (*)</label>
                                    <input type="text" name="numero_facture" value="{{$facture_edit->numero_facture}}" required class="form-control input-lg" maxlength="30" onkeyup="this.value=this.value.toUpperCase()">
                                </div>
                            
                                <div class="form-group">
                                    <label>A régler d'ici le: (*)</label>
                                    <input type="date" name="date_reglement" value="{{$facture_edit->date_reglement}}" class="form-control input-lg" required>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Date d'emission de la facture:</label>
                                    <input type="date" class="form-control  input-lg" required name="date_emission" value="{{$facture_edit->date_emission}}">
                                </div>
                                    
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Montant de la facture:</label>
                                    <input type="number" class="form-control  input-lg" value="{{$facture_edit->montant_facture}}" required name="montant_facture" maxlength="13">
                                </div>
                                    
                                <div class="form-group">
                                    <label>Prestation/Contrat:</label>
                                <select class="form-control  input-lg" name="id_prestation" required>
                                        @php
                                            $contrats = $prestationcontroller->getAllNoReglee();
                                            
                                        @endphp
                                        
                                        <option value="{{$facture_edit->id_prestation}}"><b>Contrat:</b>{{$facture_edit->titre_contrat}}/<b>Date de la Prestation:</b>@php echo date('d/m/Y',strtotime($facture_edit->date_prestation));  @endphp</option>
                                        @foreach($contrats as $contrats)
                                            <option value="{{$contrats->id}}"><b>Contrat:</b>{{$contrats->titre_contrat}}/<b>Date de la Prestation:</b>@php echo date('d/m/Y',strtotime($facture_edit->date_prestation));  @endphp</option>
                                        @endforeach

                                </select>
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
            @endif
           

        </div>
    </div>
		
@endsection