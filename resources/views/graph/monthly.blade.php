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
    
        <!-- left column -->
        <div class="col-md-12">

            <div class="box">
                <div class="box-body">
                    <div class="box-header with-border">
                        <b>
                        <h3 class="box-title"> 
                            @php
                                echo 'Chiffre d\'affaire au mois de ';
                                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                 echo utf8_encode(strftime( '%B ')). '<br>';
                            @endphp
                        </h3><br>
                        
                    </div>
                    <!--my chart-->
                    <canvas id="mychart" aria-label="chart" style="height:580px;"></canvas>

                    <!-- my own chart import-->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    
                    <script>
                        //FONCTION POUR RECUPERER LE NOMBRE DE JOURS DU MOIS
                        function NonbreJourMois(mois, annee)
                        {
                            var nbreJour = 0;
                            
                            if (mois <= 6)
                            {
                                if (mois%2 == 0)
                                {
                                    nbreJour = 31;
                                }
                                else
                                {
                                    nbreJour = 30;
                                }
                            }
                            
                            else
                            {
                                if (mois%2 == 1)
                                {
                                    nbreJour = 30;
                                }
                                else
                                {
                                    nbreJour = 31;
                                }
                            }
                            if (mois == 1)
                            {
                            if(annee%4==0)
                            {
                            if(annee%100==0)
                                {
                                if(annee%400==0)
                                {
                                    nbreJour = 29;
                                }
                                else
                                {
                                    nbreJour = 28;
                                }

                                }
                                else
                                {
                                nbreJour = 29;
                                }
                            }
                            else
                            {
                            nbreJour = 28;
                            }

                            }
                            
                            return nbreJour;
                            
                        }

                        let thedate = new Date();
                        
                        //Récuper le mois et l'année
                        let mois = thedate.getMonth();
                        let annee = thedate.getFullYear();

                        //Récupérer le nombre de jours du mois en cours
                        const nb_jour = NonbreJourMois(mois, annee);

                        //Créer un tableau pour récuperer tous les numéros des jours du mois 
                        var tableau = ["1", "2", "3"];
                        for(let i = 4; i <= nb_jour; i++)
                        {
                            tableau.push(''+i+'');
                        }

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
                                labels: tableau,
                                datasets: [{
                                    label: 'Chiffre d\'affaire mensuel au '+dateperso,
                                    data: @json($data),
                                    backgroundColor: ["#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA",
                                    "#D0D3D4", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#7E5109", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#7E5109",
                                     "#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA", "#F6DDCC", "#979A9A"],
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
             <!--ON VA ESSAYER D' AFFICHER LES POURCENTAGE DE CHAQUE ENTREPRISE-->
            <div class="box">
                <div class="box-body">
                    <div class="box-header with-border">
                        <b>
                        <h3 class="box-title"> 
                            @php
                                echo 'Pourcentage (%) par client du mois de ';
                                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                 echo utf8_encode(strftime( '%B ')). '<br>';
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
                                    label: 'Pourcentage',
                                    data: @json($percent),
                                    backgroundColor: ["#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA", "#7E5109",
                                    "#D0D3D4", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#7E5109", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#7E5109",
                                     "#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA", "#F6DDCC", "#979A9A"],
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
            
            <div class="box">
                <div class="box-body">
                    <div class="box-header with-border">
                        <b>
                        <h3 class="box-title"> 
                            @php
                                echo 'Graphe des prestations du mois de ';
                                setlocale(LC_TIME, ['fr', 'fra', 'fr_FR']);
                                 echo utf8_encode(strftime( '%B ')). '<br>';
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
                                    label: 'P ',
                                    data: @json($data_serv),
                                    backgroundColor: ["#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA", "#7E5109",
                                    "#D0D3D4", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#7E5109", "#1D8348", "#A93226", "#F4D03F", "#1A5276",
                                    "#9B59B6", "#F6DDCC", "#979A9A", "#7E5109",
                                     "#A55A5A", "#47C526", "#A9CCE3 ", "#BFC9CA", "#F6DDCC", "#979A9A"],
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
             <!--ON VA ESSAYER D' AFFICHER LES POURCENTAGE DE CHAQUE ENTREPRISE-->
            <div class="box">
                <div class="box-body">
                    <div class="box-header with-border">
                        <b><h3 class="box-title"> RECHERCHER UN MOIS</h3><br>
                    </div>

                    <!-- form start -->
                    <form role="form" action="search_monthly_chart" method="post">
                        @csrf
                        
                        <div class="box-body">
                           
                            <div class="form-group">
                                    <label >Mois:</label>
                                    <input type="month" class="form-control input-lg" name="month" required>
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">RECHERCHER</button>
                            </div>
                        </div>
                        <!-- /.box-body -->

                        
                    </form>
               
                </div>
            </div>
        </div>
      
    </div>
    <!-- Main row -->  

@endsection
     
    
   