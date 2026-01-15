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
           
            <div class="card">
                <div class="card-body">
                    <div class="card-header with-border">
                        <b>
                        <h3 class="card-title"> 
                            @if(isset($year))
                                @php
                                    echo 'Chiffre d\'affaire vendu par client : ';
                        
                                    echo $year. '<br>';
                                @endphp
                            @else
                                @php
                                    echo 'Chiffre d\'affaire vendu par client : ';
                        
                                    echo date('Y'). '<br>';
                                @endphp
                            @endif
                           
                        </h3><br>
                          @php
                            //echo  number_format($total, 2, ".", " ")." XOF";
                        @endphp
                    </div>
                    <!--my chart-->
                    <canvas id="mychart" aria-label="chart" style="height:580px;"></canvas>

                            <!-- my own chart import-->
                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                   
                    <script>
                        //FONCTION POUR RECUPERER LE NOMBRE DE JOURS DU MOIS
                       
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
                                labels: @json($entreprises),
                                datasets: [{
                                    label: 'Entreprises',
                                    data: @json($montant_clients),
                                    backgroundColor: ["#9B59B6", "#F6DDCC", "#A57548", "#7E5109", "#1D8348", 
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

    </div>
    <div class="row">
        <div class="col-md-8">
           
            <div class="card">
                <div class="card-header border-transparent">
                    <h3 class="card-title">Tableau récapitulatif</h3>
                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                </div>
              <!-- /.card-header -->
                <div class="card-body p-0 table-responsive">
                  

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Clients</th>
                                <th>Montants</th>
                                <!--<th>Services</th>-->
                            
                            </tr>
                            </thead>
                            <tbody>
                                @php
                                    $col = ['bg-success', 'bg-warning', 'bg-info']
                                @endphp
                                @for($i=0; $i < count($entreprises); $i++)
                                    @php
                                        $col_couleur = $col[rand(0,2)];
                                    @endphp
                                    <tr>
                                        <td>{{$entreprises[$i]}}</td>
                                        <td class="{{$col_couleur}}">
                                            <i>@php echo number_format($montant_clients[$i], 2, ".", " "); @endphp 
                                            X0F</i>
                                        </td>
                                        
                                    </tr>
                                   
                                @endfor
                            </tbody>
                            
                        </table>
                 
                <!-- /.table-responsive -->
               </div>
              <!-- /.card-body -->
              
              <!-- /.card-footer -->
            </div>
           
        </div>
        <div class="col-md-4">
              <div class="card">
                <div class="card-body">
                    <div class="card-header with-border">
                        <b><h3 class="card-title"> RECHERCHER UNE ANNEE</h3><br>
                    </div>

                    <!-- form start -->
                    <form role="form" action="search_oth_year_by_client" method="post">
                        @csrf
                        
                        <div class="card-body">
                           
                            <div class="form-group">
                                    <label >Mois+Année:</label>
                                    <input type="month" class="form-control input-lg" name="year" required>
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
    <!-- Main row -->  

@endsection
