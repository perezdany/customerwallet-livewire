@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\ProspectionController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\InterlocuteurController;

    use App\Http\Controllers\DocController;

    use App\Http\Controllers\CategorieController;

    $contratcontroller = new ContratController();
    $entreprisecontroller = new EntrepriseController();
    $prestationcontroller = new PrestationController();
    $prospectioncontroller = new ProspectionController();
    $facturecontroller = new FactureController();
    $interlocuterController = new InterlocuteurController();
    $documentController = new DocController();
    $categoriecontroller = new CategorieController();
    $servicecontroller = new ServiceController();

@endphp

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CustoWallet</title>

  <link rel="icon" type="image/png" href="dist/img/icon.jpg">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="bower_components/Ionicons/css/ionicons.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/AdminLTE.css">
  
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- Select2 -->
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">

  <style type="text/css">
      .defilement {
      height: 3000px;
    }

    .popup {
      display: none;
    }

    #popup.open {
      display: flex !important;
    }
    .popup-encart {
      position: fixed;
      left: 90%;
      background: rgba( 0, 0, 0, .25 )
    }
    .popup-contenu {
      position: fixed;
      left: 90%;
      padding: 25px;
      background: #fff;
      /*transform: translate(-50%, -50%)*/
      max-width: 250 px
    }

    #popup-fermeture{
      color: #138AED;
      position:absolute;
      right:0;
      bottom:-3px
    }

    thead{
    background-color: rgb(161, 157, 157);
    }

    tfoot{
      background-color: rgb(169, 164, 164);
      }

  </style>


</head>

<body onload="window.print();">
    <div class="wrapper">
    <!-- Main content -->
    <!-- title row -->
        <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
            <i class="fa fa-globe"></i> ÆNEAS WEST AFRICA.
            
            </h2>
        </div>
        <!-- /.col -->
        </div>
    <section class="invoice">
        @if(isset($id_entreprise))
            
            @php
                $nom = $entreprisecontroller->GetById($id_entreprise);

                $prospections = $prospectioncontroller->GetProspectionByIdEntr($id_entreprise);

                $contrats = $contratcontroller->GetContratByIdEntr($id_entreprise);

                $count_contrat = $contrats->count();

                $prestations = $prestationcontroller->GetPrestationByIdEntr($id_entreprise);

            @endphp


            <div class="row">
            
                <div class="col-md-2"></div>
                <!-- left column -->
                <div class="col-md-8">
                    <!-- Horizontal Form -->
                    @foreach($nom as $nom)
                            <h3 class="box-title"><b>{{$nom->nom_entreprise}}</b>
                                @if($nom->etat == 0)
                                    <span class="bg-red">INACTIF</span>
                                @else
                                @endif
                            </h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> <b>CLIENT DEPUIS LE :</b></label>
                                
                                    <div class="col-sm-6">
                                        <input type="text" value="@php echo date('d/m/Y', strtotime($nom->client_depuis)) @endphp" class="form-control" disabled>
                                    </div><br><br><br>
                                    
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> <b>ADRESSE GEROGRAPHIQUE DE L'ENTREPRISE :</b></label>
                                    <div class="col-sm-6">
                                        <input type="text" value="{{$nom->adresse}}" class="form-control" disabled>
                                    </div><br><br><br>
                                    
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> <b>TELEPHONE :</b></label>
                                    <div class="col-sm-6">
                                        <input type="text" value="{{$nom->telephone}}" class="form-control" disabled>
                                    </div><br><br><br>
                                    
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> <b>ADRESSE EMAIL:</b></label>
                                
                                    <div class="col-sm-6">
                                        <input type="text" value="{{$nom->adresse_email}}" class="form-control" disabled>
                                    </div><br><br>
                                    
                                </div>
                                @if($nom->particulier == 0)
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>CHIFFRE D'AFFAIRE:</b></label>
                                    
                                        <div class="col-sm-6">
                                            <input type="text" value="{{$nom->chiffre_affaire}}" class="form-control" disabled>
                                        </div><br><br>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>NOMBRE D'ENPLOYES:</b></label>
                                    
                                        <div class="col-sm-6">
                                            <input type="text" value="{{$nom->nb_employes}}" class="form-control" disabled>
                                        </div><br><br>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>DATE DE CREATION:</b></label>
                                    
                                        <div class="col-sm-6">
                                            <input type="text" value="{{$nom->date_creation}}" class="form-control" disabled>
                                        </div><br><br>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-6 control-label"> <b>PAYS:</b></label>
                                    
                                        <div class="col-sm-6">
                                            <input type="text" value="{{$nom->nom_pays}}" class="form-control" disabled>
                                        </div><br><br>
                                        
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> <b>SITE WEB:</b></label>
                                
                                    <div class="col-sm-6">
                                        <input type="text" value="{{$nom->site_web}}" class="form-control" disabled>
                                    </div><br><br>
                                        
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"> <b>ACTIVITE/PROFESSION:</b></label>
                                
                                    <div class="col-sm-6">
                                        <input type="text" value="{{$nom->activite}}" class="form-control" disabled>
                                    </div><br><br>
                                        
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-6 control-label"><b>AJOUTE PAR :</b></label>
                                
                                    <div class="col-sm-6">
                                        <input class="form-control" disabled type="text" value="{{$nom->nom_prenoms}}">
                                    </div><br><br>
                                
                                </div>
                            
                            </div>

                        @endforeach
                    <div class="box ">
                        
                        @php 
                            $interlocuteurs =  $interlocuterController->InterlocuteurWithIdEntreprise($id_entreprise);
                        @endphp
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Interlocuteur(s)</b></h3>
                        </div>

                        <table class="table table-hover box-body">
                                        
                            <tr>
                        
                                <th>Nom</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Fonction</th>
                            
                                
                            </tr>
                            <!--LES FICHIERS ET LES FACTURES-->
                            
                            @foreach($interlocuteurs as $interlocuteurs)
                                <tr>
                                    <td> {{$interlocuteurs->titre}} {{$interlocuteurs->nom}}</td>
                                
                                    <td>
                                        {{$interlocuteurs->tel}}
                                    </td>
                                    <td>{{$interlocuteurs->email}}</td>
                                    <td>{{$interlocuteurs->intitule}}</td>
                                
                                </tr>
                            
                            @endforeach
                        
                        </table>
        
                        <hr>
                    
                    </div>
                
                    <!-- /.box -->
                
            
                </div>
                
                <div class="col-md-2"></div>
            </div>
            <!--/.col (right) -->
        @endif

    
    

        

    
    </section>
    <!-- /.content -->
    </div>
<!-- ./wrapper -->
</body>
</html>
