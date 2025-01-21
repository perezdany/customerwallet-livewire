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
      
      <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
            @if(session('error'))
            <div class="col-md-12 box-header">
              <p class="bg-warning" style="font-size:13px;">{{session('error')}}</p>
            </div>
          @endif

      </div>
          <!-- /.row -->
    <div class="row"></div>
    <div class="row">
      <div class="col-md-3"></div>
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
                        <label>Particulier ?:</label>

                        <select class="form-control " name="particulier" id="particulier" onchange="EnableFields();">
                            <option value="0">NON</option>
                            <option value="1">OUI</option>
                        </select>
                    </div>  
                    <div class="form-group">
                        <label>Identité (Dénomination/nom&prénoms):</label>
                        <input type="text"  maxlength="50" class="form-control "  placeholder="CIE ou M.KONAN KOFFI" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                    </div> 
                   
                     <div class="form-group">
                      <label >Adresse (géographique/siège social):</label>
                      <input type="text" maxlength="60" class="form-control  " name="adresse" placeholder="COCODY" onkeyup="this.value=this.value.toUpperCase()">
                    </div>
                     <div class="form-group">
                      <label >Téléphone (fixe/mobile):</label>
                      <input type="text"  maxlength="60" class="form-control  " name="tel" placeholder="+225 27 47 54 45 68">
                    </div>

                    <div class="form-group">
                      <label >Chiffre d'affaire (FCFA):</label>
                      <input type="tex"  maxlength="18" class="form-control" name="chiffre" id="ca" placeholder="1000000">
                    </div>

                    <div class="form-group">
                      <label> Date de création:</label>
                      <input type="date" id="date_creation" maxlength="11" class="form-control  " name="date_creation">
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
        <div class="col-md-3"></div>
          <!-- /.box -->
		  
    </div>
    <!--/.col (right) -->
      <!--/.col (right) -->
@endsection