
@can("manager")
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
                                <th>Client</th>
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
                                    <td>{{$my_own->nom_entreprise}}</td>
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
@endcan

@can("admin")
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
                                <th>Client</th>
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
                                    <td>{{$my_own->nom_entreprise}}</td>
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
@endcan

@can("comptable")
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
                                <th>Client</th>
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
                                    <td>{{$my_own->nom_entreprise}}</td>
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
@endcan