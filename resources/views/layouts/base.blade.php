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

    /*thead{
    background-color: rgb(161, 157, 157);
    }

    tfoot{
      background-color: rgb(169, 164, 164);
      }*/

  </style>

   @livewireStyles
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

 <!--Header de la page-->
  @include('layouts.components.header-page')
  <!-- Left side column. contains the logo and sidebar -->
  @include('layouts.components.menu')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    

    <!-- Main content -->
    <section class="content">
      @if(session('success'))
      <div class="col-md-12 box-header">
          <p class="bg-green" style="font-size:13px;">{{session('success')}}</p>
      </div>
      @endif

      @if(session('error'))
      <div class="col-md-12 box-header">
          <p class="bg-red" style="font-size:13px;">{{session('error')}}</p>
      </div>
      @endif

      @if(isset($message_success))
      <div class="col-md-12 box-header">
          <p class="bg-success" style="font-size:13px;">{{$message_success}}</p>
      </div>
      @endif
      @if(isset($message_error))
      <div class="col-md-12 box-header">
          <p class="bg-red" style="font-size:13px;">{{$message_error}}</p>
      </div>
      @endif
    
        @yield('content')
       
      </div>
	   
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
@livewireScripts
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Select2 -->
<script src="bower_components/select2/dist/js/select2.full.min.js"></script>

<!-- SlimScroll -->
<script src="bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<!-- DataTables -->
<script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>

<!--sweet alert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	
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

    $(function () {
		$('#example3').DataTable()
		$('#example2').DataTable({
		  'paging'      : true,
		  'lengthChange': false,
		  'searching'   : false,
		  'ordering'    : true,
		  'info'        : true,
		  'autoWidth'   : false
		})
	  })

    $(function () {
		$('#example4').DataTable()
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

<script type="text/javascript">

  function togglePopup()
  {
     let popup = document.getElementById("popup");

     popup.classList.toggle("open");
  }

 
</script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

   
  })

   

</script>
 
 
 </body>
</html>
