@php
  use App\Http\Controllers\Calculator;
  use App\Http\Controllers\EntrepriseController;


  $calculator = new Calculator();
  $entreprisecontroller = new EntrepriseController();
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

  <!-- Select2 -->
  <link rel="stylesheet" href="bower_components/select2/dist/css/select2.min.css">
  
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">


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
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b>WA</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>ÆNEAS</b>WA</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav" style="font-size: 23px;">
          <!--CODE POUR LES ALERTES DES NOUVEAUX STATUTS DES CLIENTS-->

       	  <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell"></i>
              <span class="label label-danger">{{$calculator->CountNewCustomer()}}</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">Vous avez {{$calculator->CountNewCustomer()}} notifications</li>
              <li>
                <!-- inner menu: contains the actual data -->
                @php
                  $today = strtotime(date('Y-m-d'));
                 
                  $tableau_couleurs = ['fa fa-users text-aqua', 'fa fa-users text-red', 'fa fa-users text-green', 'fa fa-users text-yellow'];

                  $new_customer = $entreprisecontroller->DetectNewCustomer();

                  $limiteur = 0;
                @endphp
                <ul class="menu">
                  @foreach($new_customer as $new_customer)
                    
                      @php
                        $date_start = strtotime($new_customer->client_depuis);
                        $diff_in_days = floor(($today - $date_start) / (60 * 60 * 24));
                        //SI C'EST INFERIEUR OU EGAL A 7, ON PEUT DIRE C'EST UN NOUVEAU CLIENT 
                        $colors = rand(0,3);
                      @endphp

                      @if($diff_in_days <= 7)
                        @php
                          $limiteur = $limiteur + 1;
                        @endphp
                        <li>
                          <a href="#">
                            <i class="{{$tableau_couleurs[$colors]}}"></i> <b>{{$new_customer->nom_entreprise}}</b> est désormais un client de ÆNEAS WA
                          </a>
                        </li>
                        
                      @endif

                      @if($limiteur == 5)
                        @break
                      @endif
                    
                  @endforeach
                 
                </ul>
              </li>
              @if($limiteur !==0)
                
                  <li class="footer"><a href="customers">Voir tout</a></li>
                
              @endif
              
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="dist/img/user-icon.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs">{{auth()->user()->nom_prenoms}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="dist/img/user-icon.jpg" class="img-circle" alt="User Image">

                <p>
                  {{auth()->user()->nom_prenoms}} <br> {{auth()->user()->poste}}
                 
                </p>
              </li>
             
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <!-- A PART LES ADMINS, PERSONNE NE PEUT VOIR CA-->
                  @if(auth()->user()->id_role == 4)
                    <form action="profile" method="post">
                      @csrf
                      <input type="text" value="{{auth()->user()->id}}" name="id_user" style="display:none;">
                      <button class="btn btn-primary btn-flat">Profile</button>
                    </form>
                    
                  @endif
                  
                </div>
                <div class="pull-right">
                  <form action="logout" method="post">
                    @csrf
                    <input type="text" value="{{auth()->user()->id}}" name="id" style="display:none;">
                    <button class="btn btn-primary btn-flat">Déconnexion</button>
                  </form>
                  
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>

    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
     
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">NAVIGATION</li>

        <!--ON FAIT DES RESTRICTIONS POUR CHAQUE TYPE D'UTILISATEURS-->
        <li class="active treeview menu-open">
          <a href="#" style="background-color: #FFFFFF">
            <i class="fa fa-dashboard"></i> <span>Tableau de bord</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="welcome"><i class="fa fa-circle-o"></i>Accueil</a></li>

            @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 3 OR auth()->user()->id_role == 4 OR auth()->user()->id_role == 2)
              <li class="active"><a href="prestation"><i class="fa fa-circle-o"></i>Prestations</a></li>
              <li class="active"><a href="prospection"><i class="fa fa-circle-o"></i>Prospections</a></li>
              <li class="active"><a href="suivi"><i class="fa fa-circle-o"></i>Suivis</a></li>
              <li><a href="contrat"><i class="fa fa-circle-o"></i>Contrats</a></li>
              <li class="active"><a href="facture"><i class="fa fa-circle-o"></i>Factures</a></li>
              
            @else
              @if(auth()->user()->id_role == 5)
                @if(auth()->user()->id_departement == 1)
                    <li class="active"><a href="prospection"><i class="fa fa-circle-o"></i>Prospections</a></li>
                    <li class="active"><a href="suivi"><i class="fa fa-circle-o"></i>Suivis</a></li>
                   
                @endif

                @if(auth()->user()->id_departement == 5)
                    <li class="active"><a href="prestation"><i class="fa fa-circle-o"></i>Prestations</a></li>
                    <li><a href="contrat"><i class="fa fa-circle-o"></i>Contrats</a></li>
                    <li><a href="entreprises"><i class="fa fa-circle-o"></i> Clients/Prospects</a></li>
                @endif
              @endif
            @endif
           
          </ul>
        </li>

        @if(auth()->user()->id_role == 4)

          <li class="treeview">
            <a href="#" style="background-color: #FFFFFF">
              <i class="fa fa-files-o"></i>
              <span>Administrations</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="utilisateurs"><i class="fa fa-circle-o"></i>Utilisateurs</a></li>
              <li><a href="roles"><i class="fa fa-circle-o"></i>Rôles</a></li>
              <li><a href="departements"><i class="fa fa-circle-o"></i>Départements</a></li>
              <li><a href="services"><i class="fa fa-circle-o"></i>Nos services</a></li>
              <li><a href="entreprises"><i class="fa fa-circle-o"></i> Entreprises</a></li>
              <li><a href="type_prestation"><i class="fa fa-circle-o"></i>Type de prestations</a></li>
              <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
               <li><a href="entreprises"><i class="fa fa-circle-o"></i> Clients/Prospects</a></li>
        
            </ul>
          </li>

          <li class="treeview">
            <a href="#" style="background-color: #FFFFFF">
              <i class="fa fa-pie-chart"></i>
              <span>Graphs</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li><a href="monthly"><i class="fa fa-circle-o"></i> Chiffre d'affaire mensuel</a></li>
              <li><a href="yearly"><i class="fa fa-circle-o"></i> Chiffre d'affaire annuel</a></li>
              
            </ul>
          </li>
        @else
            @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 3)
              <li class="treeview">
                <a href="#" style="background-color: #FFFFFF">
                  <i class="fa fa-pie-chart"></i>
                  <span>Graphs</span>
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu">
                  <li><a href="monthly"><i class="fa fa-circle-o"></i> Chiffre d'affaire mensuel</a></li>
                  <li><a href="yearly"><i class="fa fa-circle-o"></i> Chiffre d'affaire annuel</a></li>
                  
                </ul>
              </li>
            @endif

          

        @endif
        
         <li class="">
            <a href="#" style="background-color: #FFFFFF">
              
              
              <form action="download_guide" method="post" enctype="multipart/form-data">
                  @csrf
                  @php
                    $get_guide = DB::table('docs')->get();
                  @endphp
                  @foreach($get_guide as $all)
                    <input type="text" value={{$all->id}} style="display:none;" name="id_doc">
                    <input type="text" class="form-control" name="file" value="{{$all->	path_doc}}" style="display:none;">
                    <button type="submit" class="btn btn-default "><i class="fa fa-files-o"></i>Guide Utilisateur</button>
                  @endforeach
                  
              </form>
            </a>
           
          </li>

        
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    

    <!-- Main content -->
    <section class="content">
      

      <!--RESTRICTIONS POUR LES INFOS BOX -->

      @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 3 OR auth()->user()->id_role == 4)

        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
              @php
                    $count = $calculator->CountCustomer();
                    //dd($count);
                  @endphp
                <h3>{{$count}}</h3>
                
                <p>CLIENTS</p>
              </div>
              <div class="icon">
                <i class="fa fa-shopping-cart"></i>
              </div>
              <a href="customers" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
        
          <!-- ./col -->
          <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
              @php
                  $count = $calculator->CountContratEncours();
                @endphp
                <h3>{{$count}}</h3>

                <p>CONTRATS EN COURS</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="contrat" class="small-box-footer">
                Plus d'infos <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-4 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                  @php
                    $count = $calculator->CountProspect();
                  @endphp
                <h3>{{$count}}</h3>
                <p>PROSPECTS</p>
              
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="prospects" class="small-box-footer">
                Plus d'info<i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
       
      
      @else
        @if(auth()->user()->id_role == 5 )
          @if(auth()->user()->id_departement == 1)
            <!-- Small boxes (Stat box) -->
              <div class="row">
                <div class="col-lg-4 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-aqua">
                    <div class="inner">
                    @php
                          $count = $calculator->CountCustomer();
                          //dd($count);
                        @endphp
                      <h3>{{$count}}</h3>
                      
                      <p>CLIENTS</p>
                    </div>
                    <div class="icon">
                      <i class="fa fa-shopping-cart"></i>
                    </div>
                    <a href="customers" class="small-box-footer">
                      More info <i class="fa fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
              
               
                <!-- ./col -->
                <div class="col-lg-4 col-xs-6">
                  <!-- small box -->
                  <div class="small-box bg-red">
                    <div class="inner">
                        @php
                          $count = $calculator->CountProspect();
                        @endphp
                      <h3>{{$count}}</h3>
                      <p>PROSPECTS</p>
                    
                    </div>
                    <div class="icon">
                      <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="prospects" class="small-box-footer">
                      Plus d'info<i class="fa fa-arrow-circle-right"></i>
                    </a>
                  </div>
                </div>
                <!-- ./col -->
              </div>
            <!-- /.row -->
          @endif

          @if(auth()->user()->id_departement == 5)
              
          @endif
        @endif

        @if(auth()->user()->id_role == 2 )
          
          @if(auth()->user()->id_departement == 5)
              
          @endif
        @endif
       
      @endif

    
	    @yield('content')
      <!-- Main row -->  
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0
    </div>
    <strong>Copyright &copy; 2024</strong> AENEAS WEST AFRICA
  </footer>


</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="plugins/input-mask/jquery.inputmask.js"></script>
<script src="plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- DataTables -->
<script src="bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<!-- bootstrap datepicker -->
<script src="bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- Page script -->
	
<!-- page script -->
	<script>
	  $(function () {
		$('#example1').DataTable()
		$('#example2').DataTable({
		  'paging'      : true,
		  'lengthChange': false,
		  'searching'   : false,
		  'ordering'    : true,
		  'info'        : true,
		  'autoWidth'   : false
		})
	  })
	</script>

  <script>
    $(function () {
      //Initialize Select2 Elements
      $('.select2').select2()

     
    })
  </script>
</body>
</html>
