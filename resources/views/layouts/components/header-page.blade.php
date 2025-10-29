<nav class="main-header navbar navbar-expand navbar-golden navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="/" class="nav-link">Accueil</a>
    </li>
    
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-danger navbar-badge">{{$calculator->CountNewCustomer()}}</span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">{{$calculator->CountNewCustomer()}} notification</span>
        <div class="dropdown-divider"></div>
        @php
          $today = strtotime(date('Y-m-d'));
          
          $tableau_couleurs = ['fa fa-users text-aqua', 'fa fa-users text-red', 'fa fa-users text-green', 'fa fa-users text-yellow'];

          $new_customer = $entreprisecontroller->DetectNewCustomer();

          $limiteur = 0;
        @endphp
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
                <a href="#" class="dropdown-item">
                  <i class="fas fa-info mr-2"></i> <b>{{$new_customer->nom_entreprise}}</b> est désormais un client de ÆNEAS WA
                  <!--<span class="float-right text-muted text-sm">3 mins</span>-->
                </a>
                <div class="dropdown-divider"></div>
            @endif

            @if($limiteur == 5)
              @break
            @endif
          
        @endforeach
        <a href="entreprises" class="dropdown-item dropdown-footer">Voir tout</a>
        
       
        
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="#" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="" data-slide="true" href="logout" role="button">
        <i class="fa fa-power-off"></i>
      </a>
    </li>
  </ul>
</nav>

