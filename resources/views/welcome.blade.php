@extends('layouts/dash')
@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

     use App\Http\Controllers\ContratController;

     use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\TypePrestationController;

     use App\Http\Controllers\InterlocuteurController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\CategorieController;

    use App\Http\Controllers\Calculator;

    $calculator = new Calculator();

    $facturecontroller = new FactureController();

    $categoriecontroller = new CategorieController();

    $servicecontroller = new ServiceController();

    $typeprestationcontroller = new TypePrestationController();

    $contratcontroller = new ContratController();

    $entreprisecontroller = new EntrepriseController();

    $interlocuteurcontroller = new InterlocuteurController();

    $my_own =  $facturecontroller->FactureDateDepassee();
    $count_non_reglee = $calculator->CountFactureNonRegleDepasse();

     
@endphp

@section('content')
    @php
        /*CODE POUR METTRE A JOUR LES CONTRATS RECONDUITS*/
        $contrats = $contratcontroller->RetriveAll();
        foreach($contrats as $contrats)
        {
            //VERIFIER LA DATE DE FIN DE CONTRAT
            if($contrats->fin_contrat <= date('Y-m-d'))
            {
                //LE CONTRAT EST FINI, VERIFIONS SI IL EST RECONDUIT
                if($contrats->reconduction == 0)//Pas reconduit
                {

                }
                else
                {
                    $date_debut = strtotime($contrats->debut_contrat);
                    $date_fin = strtotime($contrats->fin_contrat);
                    $diff_in_days = floor(($date_fin - $date_debut ) / (60 * 60 * 24));//on obtient ca en jour

                    //ACTUALISATION DE LA DATE
                    $timestamp = strtotime($contrats->debut_contrat);
                    $departtime1 = strtotime('+'.$diff_in_days.' days', $timestamp);
    
                    $nouvelle_date_fin = date("Y-m-d", $departtime1);

                    //MISE A JOUR DE L'ENREGISTREMENT DANS LA TABLE
                    $affected = DB::table('contrats')
                    ->where('id', $contrats->id)
                    ->update([
                        'fin_contrat'=> $nouvelle_date_fin,  
                    ]);
                }
                
            }
        }
    @endphp
    @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 4 OR auth()->user()->id_role == 2)
        @if(auth()->user()->id_departement != 1)
            <div class="row">
                <div class="col-md-8">
                <!-- TABLE: LATEST ORDERS LES FACTURES QUI N'ONT PAS ETE REGLEES ET LADATE EST DEPASS2E-->
                @if($count_non_reglee != 0)
                        <div class="box box-info">
                            <div class="box-header with-border">
                            <h3 class="box-title">Attention! Ces factures ne sont pas réglées et la date de règlement est dépassée</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                <thead>
                                    <tr>
                                        <th>Facture N°</th>

                                    
                                        <th>Date de règlement</th>
                                        <th>Montant</th>
                                        @if(auth()->user()->id_role == 2)<th>Afficher les paiements</th>@endif
                                        <th>Contrat</th>
                                        <th>Etat facture</th>
                                        @if(auth()->user()->id_role == 2)Action</th>@endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($my_own as $my_own)
                                            <tr>
                                            <td>{{$my_own->numero_facture}}</td>
                                            
                                            <td>@php echo date('d/m/Y',strtotime($my_own->date_reglement)) @endphp</td>
                                            <td>
                                                @php
                                                    echo  number_format($my_own->montant_facture, 2, ".", " ")." XOF";
                                                @endphp
                                            </td>
                                            @if(auth()->user()->id_role == 2)
                                                <td>
                                                    <form action="paiement_by_facture" method="post">
                                                            @csrf
                                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                            @endif
                                        
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
                                            
                                            </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                        <th>Facture N°</th>

                                        
                                            <th>Date de règlement</th>
                                            <th>Montant</th>
                                            @if(auth()->user()->id_role == 2)<th>Afficher les paiements</th>@endif
                                            <th>Contrat</th>
                                            <th>Etat facture</th>
                                            @if(auth()->user()->id_role == 2)Action</th>@endif
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                            </div>
                            <!-- /.box-body -->

                            <div class="box-footer clearfix">
                
                                @if(auth()->user()->id_role == 2)<a href="facture" class="btn btn-sm btn-primary btn-flat pull-right">Voir tout</a>@endif
                            </div>
                        </div>
                        <!-- /.box -->
                @endif
                
                </div>
            </div>
        @endif
    @endif
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
       

           
    </div>
    <!-- Main row -->  

@endsection
     
    
   