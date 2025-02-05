@extends('layouts/base')

@php

    use App\Http\Controllers\PaysController;

     use App\Http\Controllers\CibleController;
     use App\Http\Controllers\EntrepriseController;

    $entreprisecontroller = new EntrepriseController();

    $ciblecontroller = new CibleController();

    $payscontroller = new PaysController();

    $all = $ciblecontroller->GetAll();

   
@endphp

@section('content')
      
    
  <!-- /.row -->
    <div class="row"></div>
    <div class="row">
   
        <div class="col-md-6">
          <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">AJOUTER UNE ENTREPRISE CIBLE</h3><br>
                  <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                
              </div>
           
              <!-- form start -->
              <form role="form" method="post" action="add_entreprise_cible">
                @csrf
                <div class="box-body">
                    <div class="form-group">  
                      <select class="form-control " name="particulier" id="particulier" onchange="EnableFields();" style="display:none;">
                            <option value="0">NON</option>
                      </select>
                    </div>  
                    <div class="form-group">
                        <label>Dénomination:</label>
                        <input type="text"  maxlength="50" class="form-control "  placeholder="COMPAGNIE IVOIRIENNE D'ELECTRICITE (CIE)" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                    </div> 
                    <div class="form-group">
                        <label>Nom du dirigeant(DG):</label>
                        <input type="text" maxlength="150" class="form-control " placeholder="EX: M. KOFFI KOFFI" name="dirigeant" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                    </div> 
                   
                     <div class="form-group">
                      <label >Adresse (géographique/siège social):</label>
                      <input type="text" maxlength="60" class="form-control  " name="adresse" placeholder="COCODY" onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                     <div class="form-group">
                      <label >Téléphone fixe:</label>
                      <input type="text"  maxlength="60" class="form-control  " name="tel" placeholder="+225 27 47 54 45 68">
                    </div>

                     <div class="form-group">
                      <label >Téléphone mobile:</label>
                      <input type="text"  maxlength="60" class="form-control  " name="mobile" placeholder="+225 07 47 54 45 68">
                    </div>

                    <div class="form-group">
                      <label >Chiffre d'affaire (FCFA):</label>
                      <input type="tex"  maxlength="18" class="form-control" name="chiffre" id="ca" placeholder="1000000">
                    </div>
                    <!--<div class="form-group">
                      <label>Activité:</label>
                      <input type="text" maxlength="100" class="form-control  " name="activite" id="activite"
                      placeholder="TRANSIT" onkeyup="this.value=this.value.toUpperCase()">
                    </div>-->
                    <div class="form-group">
                      <label>Année de création:</label>
                      <select class="" id="date_creation" name="date_creation">
                          <option value="">Choisir</option>
                          @php
                              $annee_fin = "2060";
                              for($annee="1980"; $annee<=$annee_fin; $annee++)
                              {
                                  echo'<option value='.$annee.'>'.$annee.'</option>';
                              }
                          @endphp
                          
                      </select>   
                    </div>
                    <div class="form-group">
                      <label >Nombre d'employés:</label>
                      <input type="text"  maxlength="18" class="form-control  " name="nb_emp" id="ne" placeholder="5">
                    </div>

                     <div class="form-group">
                      <label >Objet Sociale(Activités/Profession):</label>
                      <input type="text" maxlength="100" class="form-control  " name="activite" id="activite"
                      placeholder="TRANSIT" onkeyup="this.value=this.value.toUpperCase()">
                    </div>

                    <div class="form-group">
                      <label>Email:</label>
                      <input type="email"  maxlength="30" class="form-control  " name="email">
                    </div>
                    <div class="form-group">
                      <label >Site web:</label>
                      <input type="text" id="site_web" maxlength="60" class="form-control  " name="site_web" placeholder="COCODY DANGA" onkeyup="this.value=this.value.toUpperCase()">
                    </div> 
                    <div class="form-group">
                        <label>Pays :</label>
                        <select class="form-control " name="pays" id="pays">
                            @php
                                $pays = $payscontroller->DisplayAll();
                            @endphp
                            @foreach($pays as $pays)
                                <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                
                            @endforeach
                            
                        </select>
                    </div>
                    <script>
                      //CODE POUR ACTIVER CERTAINS CHAMPS SI C'EST UN PARTICULIER
                      function EnableFields()
                      {
                          var particulier = document.getElementById("particulier").value;
                          if( particulier == '1')
                          {
                          document.getElementById("ca").setAttribute("disabled", "disabled");
                          document.getElementById("ne").setAttribute("disabled", "disabled");
                          document.getElementById("date_creation").setAttribute("disabled", "disabled");
                          document.getElementById("pays").setAttribute("disabled", "disabled");
                          }
                          else{
                              document.getElementById("ca").removeAttribute("disabled");
                              document.getElementById("ne").removeAttribute("disabled");
                              document.getElementById("date_creation").removeAttribute("disabled");
                              document.getElementById("pays").removeAttribute("disabled");
                          
                          }
                      }
                    </script>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">ENREGISTRER</button>
                    </div>
                </div>
                <!-- /.box-body -->
              </form>
              
             
            </div>
            <!-- /.box -->
        </div>
        <!--/.col (left) -->
        

        <!-- right column -->
        <div class="col-md-6">
           <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">AJOUTER UN(E) PARTICULIER(E)</h3><br>
                  <div class="box-tools pull-right">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                
              </div>
           
              <!-- form start -->
              <form role="form" method="post" action="add_entreprise_cible">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <select class="form-control " name="particulier" id="particulier" onchange="EnableFields();" style="display:none;">
                          <option value="1">OUI</option>
                        </select>
                    </div>  
                    <div class="form-group">
                        <label>Nom & prénoms:</label>
                        <input type="text"  maxlength="50" class="form-control "  placeholder="M.KONAN KOFFI" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                    </div> 
                   
                     <div class="form-group">
                      <label >Adresse (géographique):</label>
                      <input type="text" maxlength="60" class="form-control  " name="adresse" placeholder="COCODY" onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                    <div class="form-group">
                      <label >Téléphone (fixe/mobile):</label>
                      <input type="text"  maxlength="60" class="form-control  " name="tel" placeholder="+225 27 47 54 45 68">
                    </div>

                    <div class="form-group">
                      <label >Téléphone mobile:</label>
                      <input type="text"  maxlength="60" class="form-control  " name="tel" placeholder="+225 07 47 54 45 68">
                    </div>

                    <div class="form-group">
                      <label >Profession:</label>
                      <input type="text" maxlength="100" class="form-control  " name="activite" id="activite"
                      placeholder="TRANSIT" onkeyup="this.value=this.value.toUpperCase()">
                    </div>

                    <div class="form-group">
                      <label>Email:</label>
                      <input type="email"  maxlength="30" class="form-control  " name="email">
                    </div>
                    <div class="form-group">
                      <label >Site web:</label>
                      <input type="text" id="site_web" maxlength="60" class="form-control  " name="site_web" placeholder="COCODY DANGA" onkeyup="this.value=this.value.toUpperCase()">
                    </div> 
                    <div class="form-group">
                        <label>Nationnalité:</label>
                        <select class="form-control " name="pays" id="pays">
                            @php
                                $pays = $payscontroller->DisplayAll();
                            @endphp
                            @foreach($pays as $pays)
                                <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                
                            @endforeach
                            
                        </select>
                    </div>
                    <script>
                      //CODE POUR ACTIVER CERTAINS CHAMPS SI C'EST UN PARTICULIER
                      function EnableFields()
                      {
                          var particulier = document.getElementById("particulier").value;
                          if( particulier == '1')
                          {
                          document.getElementById("ca").setAttribute("disabled", "disabled");
                          document.getElementById("ne").setAttribute("disabled", "disabled");
                          document.getElementById("date_creation").setAttribute("disabled", "disabled");
                          document.getElementById("pays").setAttribute("disabled", "disabled");
                          }
                          else{
                              document.getElementById("ca").removeAttribute("disabled");
                              document.getElementById("ne").removeAttribute("disabled");
                              document.getElementById("date_creation").removeAttribute("disabled");
                              document.getElementById("pays").removeAttribute("disabled");
                          
                          }
                      }
                    </script>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">ENREGISTRER</button>
                    </div>
                </div>
                <!-- /.box-body -->
              </form>
              
             
            </div>
            <!-- /.box -->
        </div>
          <!-- /.box -->
		  
    </div>
    <!--/.col (right) -->
      <!--/.col (right) -->
@endsection