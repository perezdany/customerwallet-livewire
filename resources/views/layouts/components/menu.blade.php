 <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="welcome" class="brand-link">
       <img src="dist/img/logo-1.jpg" alt="AeneasLogo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">ÆNEAS WEST AFRICA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user-icon.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->nom_prenoms}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item"><a href="welcome" class="nav-link"><i class="nav-icon far fa-circle"></i>
                <p>
                  <i class="fa fa-circle-o"></i>Accueil
                </p>
              </a>
            </li>
            @can("admin")
              <li class="nav-item"><a href="entreprises" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Entreprises
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="interlocuteurs" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Interlocuteurs
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="contrat" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Contrats
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="facture" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Factures
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="prospection" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Prospections
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="fiche" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Fiche de suivi clients
                  </p>
                </a>
              </li>
         
            @endcan

            @can("manager")
              <li class="nav-item"><a href="entreprises" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Entreprises
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="interlocuteurs" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Interlocuteurs
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="contrat" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Contrats
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="facture" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Factures
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="prospection" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Prospections
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="fiche" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Fiche de suivi clients
                  </p>
                </a>
              </li>
         
            @endcan

            @can("commercial")
              <li class="nav-item"><a href="entreprises" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Entreprises
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="interlocuteurs" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Interlocuteurs
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="fiche" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Fiche de suivi clients
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="prospection" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Prospections
                  </p>
                </a>
              </li>
              
            @endcan

            @can("manager-commercial")
              <li class="nav-item"><a href="entreprises" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Entreprises
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="interlocuteurs" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Interlocuteurs
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="fiche" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Fiche de suivi clients
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="prospection" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Prospections
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="facture" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Factures
                  </p>
                </a>
              </li>
               
            @endcan

            @can("comptable")
              <li class="nav-item"><a href="entreprises" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Entreprises
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="interlocuteurs" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Interlocuteurs
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="contrat" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Contrats
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="facture" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Factures
                  </p>
                </a>
              </li>
        
            @endcan

            @can("employe")
              <li class="nav-item"><a href="entreprises" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Entreprises
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="interlocuteurs" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Interlocuteurs
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="fiche" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Fiche de suivi clients
                  </p>
                </a>
              </li>
            @endcan   

            
            @can("standard")
              <li class="nav-item"><a href="entreprises" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Entreprises
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="interlocuteurs" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Interlocuteurs
                  </p>
                </a>
              </li>
              <li class="nav-item"><a href="fiche" class="nav-link"><i class="nav-icon far fa-circle"></i>
                  <p>
                    <i class="fa fa-circle-o"></i>Fiche de suivi clients
                  </p>
                </a>
              </li>
             
            @endcan  
         
          
          
          @can("admin").
            <li class="nav-item">
            
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-edit"></i>
                <p>
                  Administrations
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="utilisateurs" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Utilisateurs</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="roles" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Roles</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="departements" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Departements</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="services" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nos services</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="entreprises" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Entreprises</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="type_prestation" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Type de prestations</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="interlocuteurs" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Interlocuteurs</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
            
              <a href="#" class="nav-link ">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                  Graphs
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="monthly" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Chiffre d'affaire mensuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="yearly" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Chiffre d'affaire annuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomery" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Annuel)</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomerm" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Mensuel)</p>
                  </a>
                </li>
              
              </ul>
            </li>

          @endcan

          @can("manager")
            <li class="nav-item menu">
            
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                  Graphs
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="monthly" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Chiffre d'affaire mensuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="yearly" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Chiffre d'affaire annuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomery" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Annuel)</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomerm" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Mensuel)</p>
                  </a>
                </li>
              
              </ul>
            </li>
          @endcan

          @can("comptable")
            <li class="nav-item">
            
              <a href="#" class="nav-link active">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                  Graphs
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="monthly" class="nav-link active">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Chiffre d'affaire mensuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="yearly" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Chiffre d'affaire annuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomery" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Annuel)</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomerm" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Mensuel)</p>
                  </a>
                </li>
              
              </ul>
            </li>
          @endcan
          @can("manager-commercial")
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>
                  Graphs
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="monthly" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>GChiffre d'affaire mensuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="yearly" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Chiffre d'affaire annuel</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomery" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Annuel)</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="newcustomerm" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Nouveaux Clients(Mensuel)</p>
                  </a>
                </li>
              </ul>
            </li>
          @endcan

         
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<!--
  <form action="download_guide" method="post" enctype="multipart/form-data">
      @csrf
      @php
        $get_guide = DB::table('docs')->where('id', 1)->get();
      @endphp
      @foreach($get_guide as $all)
        <input type="text" value={{$all->id}} style="display:none;" name="id_doc">
        <input type="text" class="form-control" name="file" value="{{$all->	path_doc}}" style="display:none;">
        <button type="submit" class="btn btn-default "><i class="fa fa-files-o"></i>Guide Utilisateur</button>
      @endforeach
      
  </form>-->
