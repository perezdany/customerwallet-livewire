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

            $prospections = $prospectioncontroller->GetProspectionByIdEntr($id_entreprise);

            $contrats = $contratcontroller->GetContratByIdEntr($id_entreprise);

            $count_contrat = $contrats->count();

        @endphp


        <div class="row">
          
            <div class="col-md-2"></div>
            <!-- left column -->
            <div class="col-md-8" >
                <!-- Horizontal Form -->
                <div class="box "  style="text-align:center">
                    <div class="box-header">
                        <h3 class="box-title"><b>Contrats</b></h3>
                    </div>
                    @if($count_contrat == 0)
                        <div class="box-header" style="text-align:center">
                            <h3 class="box-title"><b>Pas de Contrat</b></h3>
                            <hr>
                        </div> 
                    @endif
                  
                    <!--Contrats-->
                    <div class="no-padding"  style="text-align:center">
                        <table class="table table-hover box-body">
                        
                            <tr>
                                <th>Titre de contrat</th>
                                <th>Début du contrat</th>
                                <th>Fin du contrat</th>
                                <th>Montant</th>	
                                
                            </tr>
                            <!--LES FICHIERS ET LES FACTURES-->
                            @foreach($contrats as $contrats)
                                <tr>
                                    <td> {{$contrats->titre_contrat}}  </td>
                                    <td>
                                        @php 
                                            echo date('d/m/Y',strtotime($contrats->debut_contrat));
                                        @endphp
                                        </td>
                                    <td>
                                        @php 
                                        echo date('d/m/Y',strtotime($contrats->fin_contrat)) ;
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                            echo  number_format($contrats->montant, 2, ".", " ")." XOF";
                                        @endphp
                                    
                                    </td>
                                
                                
                                </tr>
                            @endforeach
                
                        </table>
                    </div>

                
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
                                <td>{{$interlocuteurs->fonction}}</td>
                               
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
