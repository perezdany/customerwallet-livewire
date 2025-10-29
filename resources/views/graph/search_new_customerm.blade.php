@extends('layouts/base')

@section('content')


@php
    use App\Http\Controllers\InterlocuteurController;
    use App\Http\Controllers\EntrepriseController;


    $interlocuteurcontroller = new InterlocuteurController();
    $entreprisecontroller = new EntrepriseController();
@endphp

@section('content')
    <div class="row">
        <p class="bg-primary">
            <ul>
                <li>Pour rechercher un mois, <b>Selectionnez le mois en question et un jour quelquonque de ce mois</b><br></li>
                <li>Pour rechercher une année, <b>Selectionnez l'année en question et un mois et un jours quelquonque de cette année</b><br></li>
            </ul>
        </p>
        <!-- left column -->
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    <div class="card-header with-border">
                        <b>
                        <h3 class="card-title"> 
                            @php
                                echo 'Statistiques Nouveaux clients :<b>'. $mois.' '.$year.'</b><br>';
                            @endphp
                           
                        </h3><br>
                       
                    </div>
                    <!--my chart-->
                    <canvas id="mychart" aria-label="chart" style="height:580px;"></canvas>

                    <!-- my own chart import-->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    
                    <script>
                        
                        const ctx = document.getElementById('mychart').getContext('2d');
                        let date1 = new Date();

                        let dateperso = date1.toLocaleString('fr-FR',{
                            weekday: 'short',
                            day: 'numeric',
                            month: 'short',
                            year: '2-digit',
                            hour: 'numeric',
                            minute: 'numeric',
                            second: 'numeric'});

                        const barchart = new Chart(ctx, {
                            type : "bar",
                            data : {

                                //LE LABELS POUR LES ABSCISSES DU GRAPHE
                                labels: @json($mois_francais),
                                datasets: [{
                                    label: 'Nombre de clients',
                                    data: @json($data),
                                    backgroundColor: ["#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA",
                                    "#D0D3D4", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", ],
                                }]
                            },
                            options: {
                                layout: {
                                    padding: 20
                                }
                            }
                              
                        })
                    </script>
                </div>
            </div>
           
        </div>
    </div>
    <!-- Main row -->  

    <div class="row">
        <div class="col-md-4">
             <!--ON VA ESSAYER D' AFFICHER LES POURCENTAGE DE CHAQUE ENTREPRISE-->
            <div class="card">
                <div class="card-body">
                    <div class="card-header with-border">
                        
                        <h3 class="card-title"> Noms des clients</h3><br>
                    </div>
                    <!--my chart-->
                    <canvas id="percentchart" aria-label="chart" style="height:580px;"></canvas>
                    
                    <script>
                
                        const ctx2 = document.getElementById('percentchart').getContext('2d');
        
                        const barchart2 = new Chart(ctx2, {
                            type : "pie",
                            data : {

                                //LE LABELS POUR LES ABSCISSES DU GRAPHE
                                labels: @json($company),
                                datasets: [{
                                    label: "Client",
                                    data: @json($customers),
                                    backgroundColor: ["#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA",
                                    "#D0D3D4", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#A93226", "#A55A5A", 
                                    "#A9CCE3 ", "#1D8348", "#A93226", "#F4D03F",  "#A93226",
                                    "#9B59B6", "#F6DDCC", "#979A9A",  "#A93226", "#9B59B6",
                                    "#A9CCE3 ", "#1D8348", "#9B59B6", "#F6DDCC", "#979A9A", 
                                    "#A9CCE3 ", "#1D8348", "#A93226", "#A55A5A", "#47C526", "#A9CCE3 ",],
                                }]
                            },
                            options: {
                                layout: {
                                    padding: 20
                                }
                            }
                              
                        })

                    </script>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            
            <div class="card">
                <div class="card-body">
                    <div class="card-header with-border">
                        <h3 class="card-title"> RECHERCHER</h3><br>
                    </div>

                    <!-- form start -->
                    <form role="form" action="search_monthly_customer" method="post">
                        @csrf
                        
                        <div class="card-body">
                           
                            <div class="form-group">
                                    <label >Année:</label>
                                    <input type="date" class="form-control input-lg" name="month" required>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">RECHERCHER</button>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
     
    
   