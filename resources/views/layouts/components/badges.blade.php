  <!--RESTRICTIONS POUR LES INFOS BOX -->

    @can("admin")

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
                @php
                  $count = $calculator->CountCustomer();
                  $countactif = $calculator-> CountActif();
                  $countinactif = $calculator-> CountInactif();
                  //dd($count);
                @endphp
              <form method="get" action="clients">
                @csrf
            
                <button class="btn bg-aqua"><p><h3>{{$count}}</h3> </p></button>  
              </form>
              
              <form method="get" action="clients">
                @csrf
              
                <button class="btn bg-aqua"> <p>CLIENTS</p></button>  
              </form>
              
            

              <form method="get" action="actifs">
                @csrf
                
                <button class="btn bg-aqua"><p>Actifs : {{$countactif}}  </p></button>  
              </form>
              <form method="get" action="inactifs">
                @csrf
                
                <button class="btn bg-aqua"><p style="color:red">Inactifs : {{$countinactif}}  </p></button>  
              </form>
            
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a href="customers" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            
              <div class="inner">
                @php
                  $count = $calculator->CountContrat();
                  $encours = $calculator->CountContratEncours();
                  $end = $calculator->CountContratEnd();
                @endphp
                <h3><a href="contrat" style="color:#fff;"> {{$count}} </a></h3>

                  <a href="contrat" style="color:#fff;"> <p>CONTRATS</p>  </a><br>
                    <form method="get" action="encours">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="0" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>En cours : {{$encours}}  </p></button>  
                      </form>
                      <form method="get" action="end">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="1" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>Terminés : {{$end}}  </p></button>  
                      </form>
            
                  
              </div>
          
            <div class="icon"><!--ion ion-person-add-->
              <i class="fa fa-book"></i>
            </div>
            <a href="contrat" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
              @php
                    $count = $calculator->CountFacture();
                      $countno = $calculator-> CountFactureNoReglee();
                $countreglee = $calculator-> CountFactureReglee();
                    //dd($count);
                  @endphp
                <h3> <a href="facture" style="color:#fff;">{{$count}}</a></h3>
                
                <a href="facture" style="color:#fff;"><p>FACTURE</p></a><br>

              <form method="get" action="no_reglee">
                @csrf
                
                <button class="btn bg-purple"><p>Non réglées : {{$countno}} </p></button>  
              </form>
              <form method="get" action="reglee">
                @csrf
                  
                <button class="btn bg-purple"><p>Réglées : {{$countreglee}}  </p></button>  
              </form> 
              
              </div>
              <div class="icon">
                <i class="fa fa-money"></i>
              </div>
              <a href="facture" class="small-box-footer">
                Plus d'infos <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
                @php
                  $count = $calculator->CountProspect();
                @endphp
              <h3><a href="prospects" style="color:#fff">{{$count}}</a></h3>
                    <a href="prospects" style="color:#fff"><p>PROSPECTS</p></a><br>
                    <p> </p>
                    <p></p>  <br><br>
                      <p></p>  
                      <br>
                      <p></p>  
            
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="prospects" class="small-box-footer">
              Plus d'infos<i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
          
      
      </div>
      <!-- /.row -->

      <div class="row">
              <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
                @php
                  $count = $calculator->CountCible();
                @endphp
              <h3><a href="cibles" style="color:#fff">{{$count}}</a></h3>
                    <a href="cibles" style="color:#fff"><p>CIBLES</p></a><br>
                    <p> </p>
                    <p></p>  <br><br>
                      <p></p>  
                      <br>
                      <p></p>  
            
            </div>
            <div class="icon">
              <i class="fa fa-bullseye"></i>
            </div>
            <a href="cibles" class="small-box-footer">
              Plus d'infos<i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
          
      </div>
      <!-- /.row -->
    
    @endcan

    @can("manager")

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
                @php
                  $count = $calculator->CountCustomer();
                  $countactif = $calculator-> CountActif();
                  $countinactif = $calculator-> CountInactif();
                  //dd($count);
                @endphp
              <form method="get" action="clients">
                @csrf
            
                <button class="btn bg-aqua"><p><h3>{{$count}}</h3> </p></button>  
              </form>
              
              <form method="get" action="clients">
                @csrf
              
                <button class="btn bg-aqua"> <p>CLIENTS</p></button>  
              </form>
              
            

              <form method="get" action="actifs">
                @csrf
                
                <button class="btn bg-aqua"><p>Actifs : {{$countactif}}  </p></button>  
              </form>
              <form method="get" action="inactifs">
                @csrf
                
                <button class="btn bg-aqua"><p style="color:red">Inactifs : {{$countinactif}}  </p></button>  
              </form>
            
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a href="customers" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            
              <div class="inner">
                @php
                  $count = $calculator->CountContrat();
                  $encours = $calculator->CountContratEncours();
                  $end = $calculator->CountContratEnd();
                @endphp
                <h3><a href="contrat" style="color:#fff;"> {{$count}} </a></h3>

                  <a href="contrat" style="color:#fff;"> <p>CONTRATS</p>  </a><br>
                    <form method="get" action="encours">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="0" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>En cours : {{$encours}}  </p></button>  
                      </form>
                      <form method="get" action="end">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="1" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>Terminés : {{$end}}  </p></button>  
                      </form>
            
                  
              </div>
          
            <div class="icon"><!--ion ion-person-add-->
              <i class="fa fa-book"></i>
            </div>
            <a href="contrat" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
              @php
                    $count = $calculator->CountFacture();
                      $countno = $calculator-> CountFactureNoReglee();
                $countreglee = $calculator-> CountFactureReglee();
                    //dd($count);
                  @endphp
                <h3> <a href="facture" style="color:#fff;">{{$count}}</a></h3>
                
                <a href="facture" style="color:#fff;"><p>FACTURE</p></a><br>

              <form method="get" action="no_reglee">
                @csrf
                
                <button class="btn bg-purple"><p>Non réglées : {{$countno}} </p></button>  
              </form>
              <form method="get" action="reglee">
                @csrf
                  
                <button class="btn bg-purple"><p>Réglées : {{$countreglee}}  </p></button>  
              </form> 
              
              </div>
              <div class="icon">
                <i class="fa fa-money"></i>
              </div>
              <a href="facture" class="small-box-footer">
                Plus d'infos <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
                @php
                  $count = $calculator->CountProspect();
                @endphp
              <h3><a href="prospects" style="color:#fff">{{$count}}</a></h3>
                    <a href="prospects" style="color:#fff"><p>PROSPECTS</p></a><br>
                    <p> </p>
                    <p></p>  <br><br>
                      <p></p>  
                      <br>
                      <p></p>  
            
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="prospects" class="small-box-footer">
              Plus d'infos<i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
          
      
      </div>
      <!-- /.row -->

      <div class="row">
              <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
                @php
                  $count = $calculator->CountCible();
                @endphp
              <h3><a href="cibles" style="color:#fff">{{$count}}</a></h3>
                    <a href="cibles" style="color:#fff"><p>CIBLES</p></a><br>
                    <p> </p>
                    <p></p>  <br><br>
                      <p></p>  
                      <br>
                      <p></p>  
            
            </div>
            <div class="icon">
              <i class="fa fa-bullseye"></i>
            </div>
            <a href="cibles" class="small-box-footer">
              Plus d'infos<i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
          
      </div>
      <!-- /.row -->
    
    @endcan

    @can("comptable")

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
                @php
                  $count = $calculator->CountCustomer();
                  $countactif = $calculator-> CountActif();
                  $countinactif = $calculator-> CountInactif();
                  //dd($count);
                @endphp
              <form method="get" action="clients">
                @csrf
            
                <button class="btn bg-aqua"><p><h3>{{$count}}</h3> </p></button>  
              </form>
              
              <form method="get" action="clients">
                @csrf
              
                <button class="btn bg-aqua"> <p>CLIENTS</p></button>  
              </form>
              
            

              <form method="get" action="actifs">
                @csrf
                
                <button class="btn bg-aqua"><p>Actifs : {{$countactif}}  </p></button>  
              </form>
              <form method="get" action="inactifs">
                @csrf
                
                <button class="btn bg-aqua"><p style="color:red">Inactifs : {{$countinactif}}  </p></button>  
              </form>
            
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a href="customers" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            
              <div class="inner">
                @php
                  $count = $calculator->CountContrat();
                  $encours = $calculator->CountContratEncours();
                  $end = $calculator->CountContratEnd();
                @endphp
                <h3><a href="contrat" style="color:#fff;"> {{$count}} </a></h3>

                  <a href="contrat" style="color:#fff;"> <p>CONTRATS</p>  </a><br>
                    <form method="get" action="encours">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="0" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>En cours : {{$encours}}  </p></button>  
                      </form>
                      <form method="get" action="end">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="1" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>Terminés : {{$end}}  </p></button>  
                      </form>
            
                  
              </div>
          
            <div class="icon"><!--ion ion-person-add-->
              <i class="fa fa-book"></i>
            </div>
            <a href="contrat" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->

          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
              @php
                    $count = $calculator->CountFacture();
                      $countno = $calculator-> CountFactureNoReglee();
                $countreglee = $calculator-> CountFactureReglee();
                    //dd($count);
                  @endphp
                <h3> <a href="facture" style="color:#fff;">{{$count}}</a></h3>
                
                <a href="facture" style="color:#fff;"><p>FACTURE</p></a><br>

              <form method="get" action="no_reglee">
                @csrf
                
                <button class="btn bg-purple"><p>Non réglées : {{$countno}} </p></button>  
              </form>
              <form method="get" action="reglee">
                @csrf
                  
                <button class="btn bg-purple"><p>Réglées : {{$countreglee}}  </p></button>  
              </form> 
              
              </div>
              <div class="icon">
                <i class="fa fa-money"></i>
              </div>
              <a href="facture" class="small-box-footer">
                Plus d'infos <i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>

      
          
      
      </div>
      <!-- /.row -->

      <div class="row">
          
          
      </div>
      <!-- /.row -->
    
    @endcan

    @can("manager-commercial")

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
                @php
                  $count = $calculator->CountCustomer();
                  $countactif = $calculator-> CountActif();
                  $countinactif = $calculator-> CountInactif();
                  //dd($count);
                @endphp
              <form method="get" action="clients">
                @csrf
            
                <button class="btn bg-aqua"><p><h3>{{$count}}</h3> </p></button>  
              </form>
              
              <form method="get" action="clients">
                @csrf
              
                <button class="btn bg-aqua"> <p>CLIENTS</p></button>  
              </form>
              
            

              <form method="get" action="actifs">
                @csrf
                
                <button class="btn bg-aqua"><p>Actifs : {{$countactif}}  </p></button>  
              </form>
              <form method="get" action="inactifs">
                @csrf
                
                <button class="btn bg-aqua"><p style="color:red">Inactifs : {{$countinactif}}  </p></button>  
              </form>
            
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a href="customers" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            
              <div class="inner">
                @php
                  $count = $calculator->CountContrat();
                  $encours = $calculator->CountContratEncours();
                  $end = $calculator->CountContratEnd();
                @endphp
                <h3><a href="contrat" style="color:#fff;"> {{$count}} </a></h3>

                  <a href="contrat" style="color:#fff;"> <p>CONTRATS</p>  </a><br>
                    <form method="get" action="encours">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="0" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>En cours : {{$encours}}  </p></button>  
                      </form>
                      <form method="get" action="end">
                      @csrf
                      <!--<input type="text" value="" name="entreprise" style="display:none;">
                      <input type="text" value="" name="reconduction" style="display:none;">
                      <input type="text" value="1" name="etat_contrat" style="display:none;">
                      <input type="text" value="" name="service" style="display:none;">-->
                      <button class="btn  bg-yellow"><p>Terminés : {{$end}}  </p></button>  
                      </form>
            
                  
              </div>
          
            <div class="icon"><!--ion ion-person-add-->
              <i class="fa fa-book"></i>
            </div>
            <a href="contrat" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
                @php
                  $count = $calculator->CountProspect();
                @endphp
              <h3><a href="prospects" style="color:#fff">{{$count}}</a></h3>
                    <a href="prospects" style="color:#fff"><p>PROSPECTS</p></a><br>
                    <p> </p>
                    <p></p>  <br><br>
                      <p></p>  
                      <br>
                      <p></p>  
            
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="prospects" class="small-box-footer">
              Plus d'infos<i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      
        <div class="row">
              <div class="col-lg-3 col-xs-6">
          <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                  @php
                    $count = $calculator->CountCible();
                  @endphp
                <h3><a href="cibles" style="color:#fff">{{$count}}</a></h3>
                      <a href="cibles" style="color:#fff"><p>CIBLES</p></a><br>
                      <p> </p>
                      <p></p>  <br><br>
                        <p></p>  
                        <br>
                        <p></p>  
              
              </div>
              <div class="icon">
                <i class="fa fa-bullseye"></i>
              </div>
              <a href="cibles" class="small-box-footer">
                Plus d'infos<i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
            
        </div>
        <!-- /.row -->
      
      </div>
      <!-- /.row -->

      <div class="row">
        
          
      </div>
      <!-- /.row -->
    
    @endcan

    @can("commercial")

      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
                @php
                  $count = $calculator->CountCustomer();
                  $countactif = $calculator-> CountActif();
                  $countinactif = $calculator-> CountInactif();
                  //dd($count);
                @endphp
              <form method="get" action="clients">
                @csrf
            
                <button class="btn bg-aqua"><p><h3>{{$count}}</h3> </p></button>  
              </form>
              
              <form method="get" action="clients">
                @csrf
              
                <button class="btn bg-aqua"> <p>CLIENTS</p></button>  
              </form>
              
            

              <form method="get" action="actifs">
                @csrf
                
                <button class="btn bg-aqua"><p>Actifs : {{$countactif}}  </p></button>  
              </form>
              <form method="get" action="inactifs">
                @csrf
                
                <button class="btn bg-aqua"><p style="color:red">Inactifs : {{$countinactif}}  </p></button>  
              </form>
            
            </div>
            <div class="icon">
              <i class="fa fa-building"></i>
            </div>
            <a href="customers" class="small-box-footer">
              Plus d'infos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
      
       

        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
                @php
                  $count = $calculator->CountProspect();
                @endphp
              <h3><a href="prospects" style="color:#fff">{{$count}}</a></h3>
                    <a href="prospects" style="color:#fff"><p>PROSPECTS</p></a><br>
                    <p> </p>
                    <p></p>  <br><br>
                      <p></p>  
                      <br>
                      <p></p>  
            
            </div>
            <div class="icon">
              <i class="ion ion-person-add"></i>
            </div>
            <a href="prospects" class="small-box-footer">
              Plus d'infos<i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      
        <div class="row">
              <div class="col-lg-3 col-xs-6">
          <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                  @php
                    $count = $calculator->CountCible();
                  @endphp
                <h3><a href="cibles" style="color:#fff">{{$count}}</a></h3>
                      <a href="cibles" style="color:#fff"><p>CIBLES</p></a><br>
                      <p> </p>
                      <p></p>  <br><br>
                        <p></p>  
                        <br>
                        <p></p>  
              
              </div>
              <div class="icon">
                <i class="fa fa-bullseye"></i>
              </div>
              <a href="cibles" class="small-box-footer">
                Plus d'infos<i class="fa fa-arrow-circle-right"></i>
              </a>
            </div>
          </div>
          <!-- ./col -->
            
        </div>
        <!-- /.row -->
      
      </div>
      <!-- /.row -->

      <div class="row">
        
          
      </div>
      <!-- /.row -->
    
    @endcan
      
    
