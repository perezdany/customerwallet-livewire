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