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

            @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 3)
              <li><a href="entreprises"><i class="fa fa-circle-o"></i> Entreprises</a></li> 
              <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
              <li class="active"><a href="prospection"><i class="fa fa-circle-o"></i>Prospections</a></li>
              <li><a href="contrat"><i class="fa fa-circle-o"></i>Contrats</a></li>
              <li class="active"><a href="facture"><i class="fa fa-circle-o"></i>Factures</a></li>
              <li class="active"><a href="fiche"><i class="fa fa-circle-o"></i>Fiche clients</a></li>
              
            @endif

            @if(auth()->user()->id_role == 4 )
             <li><a href="entreprises"><i class="fa fa-circle-o"></i> Entreprises</a></li> 
              <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
              <li class="active"><a href="prospection"><i class="fa fa-circle-o"></i>Prospections</a></li>
              <li><a href="contrat"><i class="fa fa-circle-o"></i>Contrats</a></li>
              <li class="active"><a href="facture"><i class="fa fa-circle-o"></i>Factures</a></li>
              <li class="active"><a href="fiche"><i class="fa fa-circle-o"></i>Fiche clients</a></li>
            @endif

            @if(auth()->user()->id_role == null)

              <li class="active"><a href="prospection"><i class="fa fa-circle-o"></i>Prospections</a></li>
              <li class="active"><a href="fiche"><i class="fa fa-circle-o"></i>Fiche clients</a></li>

            @endif

            @if(auth()->user()->id_role == 5)
            
                @if(auth()->user()->id_departement == 1)
                  <li><a href="entreprises"><i class="fa fa-circle-o"></i>Entreprises</a></li>
                   <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
                   <li class="active"><a href="prospection"><i class="fa fa-circle-o"></i>Prospections</a></li>
                  <li class="active"><a href="fiche"><i class="fa fa-circle-o"></i>Fiche clients</a></li>
                  <li ><a href="prestation"><i class="fa fa-circle-o"></i>Prestations</a></li>
                   
                @endif

                @if(auth()->user()->id_departement == 4)
                 <li><a href="entreprises"><i class="fa fa-circle-o"></i>Entreprises</a></li>
                 <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
                  <li class="active"><a href="fiche"><i class="fa fa-circle-o"></i>Fiche clients</a></li>
                 
                   
                @endif

                @if(auth()->user()->id_departement == 5)
                  <li><a href="entreprises"><i class="fa fa-circle-o"></i>Entreprises</a></li>
                  <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
                    <li><a href="contrat"><i class="fa fa-circle-o"></i>Contrats</a></li>
                    <li class="active"><a href="facture"><i class="fa fa-circle-o"></i>Factures</a></li>
                    <li class="active"><a href="fiche"><i class="fa fa-circle-o"></i>Fiche clients</a></li>
                  
                @endif

                @if(auth()->user()->id_departement > 5)
                    <li><a href="entreprises"><i class="fa fa-circle-o"></i>Entreprises</a></li>
                     <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
                   
                    <li class="active"><a href="prospection"><i class="fa fa-circle-o"></i>Prospections</a></li>
                   
                  
                @endif
            @endif

             @if(auth()->user()->id_role == 2)
                
                @if(auth()->user()->id_departement == 5)
                    <li><a href="entreprises"><i class="fa fa-circle-o"></i>Entreprises</a></li>
                     <li><a href="interlocuteurs"><i class="fa fa-circle-o"></i>Interlocuteurs</a></li>
                    <li><a href="contrat"><i class="fa fa-circle-o"></i>Contrats</a></li>
                    <li class="active"><a href="facture"><i class="fa fa-circle-o"></i>Factures</a></li>
                   
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
            @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 3 OR auth()->user()->id_role == 2)
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
                    $get_guide = DB::table('docs')->where('id', 1)->get();
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