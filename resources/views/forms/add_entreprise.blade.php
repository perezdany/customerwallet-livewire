@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\StatutEntrepriseController;

    use App\Http\Controllers\PaysController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $payscontroller = new PaysController();

    $all = $entreprisecontroller->GetAll();

    $statut = $statutentreprisecontroller->GetAll();

   
    
@endphp

@section('content')
      <div class="row">
          @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif

            <div class="col-md-3">
       
            </div>
        
            <div class="col-md-6">
                <!-- general form elements -->
                <div class="box box-aeneas">
                    <div class="box-header with-border">
                        <h3 class="box-title">AJOUTER UNE ENTREPRISE/PARTICULIER</h3><br>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        
                    </div>
                
                    <!-- form start -->
                    <form role="form" method="post" action="add_entreprise">
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
                                <label>Désignation (Nom & Prénom(s)):</label>
                                <input type="text" class="form-control " name="nom_entreprise" onkeyup='this.value=this.value.toUpperCase()'  required />
                            </div> 
                            <div class="form-group">
                                <label>Statut:</label>

                                <select class="form-control " name="statut">
                                    @php
                                        $statut = $statutentreprisecontroller->GetAll();
                                    @endphp
                                    @foreach($statut as $statut)
                                        <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                        
                                    @endforeach
                                    
                                </select>
                            </div>  
                            <div class="form-group">
                            <label >Adresse:</label>
                            <input type="text" maxlength="60" class="form-control  " name="adresse" placeholder="COCODY" onkeyup="this.value=this.value.toUpperCase()">
                            </div>
                            <div class="form-group">
                            <label >Téléphone (fixe/mobile):</label>
                            <input type="text"  maxlength="60" class="form-control  " name="tel" placeholder="+225 27 47 54 45 68">
                            </div>
                            <div class="form-group">
                            <label >Chiffre d'affaire (FCFA): </label>
                            <input type="text" id="ca" maxlength="18" class="form-control  " name="chiffre" placeholder="1000000">
                            </div>

                            <div class="form-group">
                            <label >Nombre d'employés:</label>
                            <input type="text" id="ne" maxlength="11" class="form-control  " name="nb_emp" placeholder="5">
                            </div>

                            <div class="form-group">
                            <label> Date de création:</label>
                            <input type="date" id="date_creation" maxlength="11" class="form-control  " name="date_creation">
                            </div>

                            <div class="form-group">
                                <label>Activité (Ou profession):</label>
                                <input type="text"  maxlength="100" class="form-control  " 
                                name="activite" onkeyup='this.value=this.value.toUpperCase()'>
                            </div>

                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email"  maxlength="30" class="form-control  " 
                                name="email">
                            </div>
                            <div class="form-group">
                                <label >Site web:</label>
                                <input type="text" id="site_web" maxlength="60" class="form-control  " name="site_web" placeholder="www.site.com">
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

                            <div class="form-group">
                            <label>Etat du client:</label>
                            <div class="radio">
                                <label>
                                <input type="radio" name="optionsradios" id="optionsRadios1" value="1" checked>
                                Actif
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                <input type="radio" name="optionsradios" id="optionsRadios2" value="0">
                                Inactif
                                </label>
                            </div>
                            
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
                                <button type="submit" class="btn btn-primary">VALIDER</button>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </form>
                
                
                </div>
                <!-- /.box -->

            </div>

            <div class="col-md-3">
            </div>
      </div>
      <!-- /.row -->
   
@endsection