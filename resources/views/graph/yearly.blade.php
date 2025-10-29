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
                                echo 'Chiffre d\'affaire Année: '. date('Y'). '<br>';
                            @endphp
                           
                        </h3><br>
                        @php
                            echo  number_format($total_annuel, 2, ".", " ")." XOF";
                        @endphp
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
                                    label: 'Chiffre d\'affaire Annuel '+dateperso,
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
                        <h3 class="card-title"> 
                            @php
                                echo 'Pourcentage (%) par client en '.date('Y');

                            @endphp
                        </h3><br>
                        
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
                                    label: "Répartition",
                                    data: @json($percent),
                                    backgroundColor: @json($colors),
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
        <!--POURCENTAGE PAR SERVICE-->
        <div class="col-md-4">
             
            <div class="card">
                <div class="card-body">
                    <div class="card-header with-border">
                        <h3 class="card-title"> 
                            @php
                                echo 'Graphe des prestations en '.date('Y');

                            @endphp
                        </h3><br>
                        
                    </div>
                    <!--my chart-->
                    <canvas id="servpercentchart" aria-label="chart" style="height:580px;"></canvas>
                    
                    <script>
                
                        const ctx3 = document.getElementById('servpercentchart').getContext('2d');
        
                        const piechart2 = new Chart(ctx3, {
                            type : "pie",
                            data : {

                                //LE LABELS POUR LES ABSCISSES DU GRAPHE
                                labels: @json($serv),
                                datasets: [{
                                    label: "Répartition",
                                    data: @json($data_serv),
                                    backgroundColor: ["#A09ABC", "#173753", "#1B4353", "#F6BD60", "#7E5109",
                                    "#D0D3D4", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#A57548", "#7E5109", "#1D8348", 
                                    "#A93226", "#F4D03F", "#1A5276", "#82DDF0", "#040F0F",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#7E5109", "#2BA84A",
                                     "#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA", "#F6DDCC",
                                      "#979A9A", "#FCFFFC", "#696D7D", "#138A36", "#D4DF9E",
                                      "#34403A", "#12100E", "#4A4B2F", "#FA198B", "#256EFF",
                                      "#FF495C", "#46237A", "#EC7505", "#5B5B5B", "#FCB0B3"],
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
                    <form role="form" action="search_yearly_chart" method="post">
                        @csrf
                        
                        <div class="card-body">
                           
                            <div class="form-group">
                                    <label >Année:</label>
                                    <input type="date" class="form-control input-lg" name="year" required>
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
     
    
   