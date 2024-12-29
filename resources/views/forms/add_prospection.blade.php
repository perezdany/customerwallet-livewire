@extends('layouts/base')
@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

     use App\Http\Controllers\ContratController;

     use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\TypePrestationController;

     use App\Http\Controllers\InterlocuteurController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\CategorieController;

    use App\Http\Controllers\Calculator;

    use App\Http\Controllers\PaysController;

    $calculator = new Calculator();

    $facturecontroller = new FactureController();

    $categoriecontroller = new CategorieController();

    $servicecontroller = new ServiceController();

    $typeprestationcontroller = new TypePrestationController();

    $contratcontroller = new ContratController();

    $entreprisecontroller = new EntrepriseController();

    $interlocuteurcontroller = new InterlocuteurController();

    $my_own =  $facturecontroller->FactureDateDepassee();
    $count_non_reglee = $calculator->CountFactureNonRegleDepasse();
    $payscontroller = new PaysController();
     
@endphp

@section('content')
   
    <div class="row">


        <div class="row">
             <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="box box-aeneas">
                      
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>ENREGISTRER UNE PROSPECTION</b> </h3><br>
                        <b>(*)champ obligatoire</b>
                    </div>
                
                    <!-- form start -->
                    <form role="form" action="add_prospection" method="post" enctype="multipart/form-data">
                        
                        @csrf
                        <div class="box-body">
                            <div class="col-md-6">
                            
                                <div class="form-group">
                                    <label >Date de la prospection (*)</label>
                                    <input type="date" class="form-control  " name="date_prospect" required>
                                </div>
                                <div class="form-group">
                                    <label>Services Proposés (*)</label>
                                    <select class="form-control  select2" data-placeholder ="--Selctionner un service--" multiple="multiple" name="service_propose[]" required>
                                    
                                        <!--liste des services a choisir -->
                                    
                                        @php
                                            $get = $servicecontroller->GetAllNoSusp();
                                            $categorie = $categoriecontroller->DisplayAll();
                                        @endphp
                                        @foreach( $categorie as $categorie)
                                            
                                            <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                            @php
                                                $get = $servicecontroller->GetByCategorieNoSusp($categorie->id);
                                                
                                            @endphp
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        @endforeach
                                    
                                    </select>
                            
                                
                                </div>
                            
                                <!--<div class="form-group">
                                    <label for="exampleInputFile">Durée de la prospection (Jr)</label>
                                    <input type="number" max="31" min="1" class="form-control " name="duree" required>
                                </div>-->
                                    <!--CALCULER LA DATE DE FIN DE LA PROSPECTION ET AJOUTER-->
                                <div class="box-header">
                                    <b><h3 class="box-title">L'ENTREPRISE</h3></b>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Choisissez l'entreprise si existant ou sélectionnez Autre:</label>
                                    <select class="form-control " id="mySelectEnt" name="entreprise" onchange="griseFunction1()">
                                        @php
                                            $get = $entreprisecontroller->GetAll();
                                        @endphp
                                        <option value="0">--Selectionnez Une entreprise--</option>
                                        @foreach($get as $entreprise)
                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                            
                                        @endforeach
                                        <option value="autre">Autre</option>
                                    </select>
                                    
                                </div>

                                <div class="form-group">
                                        <label >Nom :</label>
                                        <input type="text" id="ent" required disabled="disabled" maxlength="50" class="form-control  " name="nom_entreprise" onkeyup="this.value=this.value.toUpperCase()">
                                </div>

                                <div class="form-group">
                                        <label >Chiffre d'affaire (FCFA):</label>
                                        <input type="text" id="ca" disabled="disabled" maxlength="18" class="form-control  " name="chiffre" placeholder="1000000">
                                </div>

                                <div class="form-group">
                                        <label >Nombre d'employés:</label>
                                        <input type="text" id="ne" disabled="disabled" maxlength="18" class="form-control  " name="nb_emp" placeholder="5">
                                </div>

                                <div class="form-group">
                                        <label >Adresse (géographique):</label>
                                        <input type="text" id="adresse" disabled="disabled" maxlength="60" class="form-control  " name="adresse" placeholder="COCODY DANGA" onkeyup="this.value=this.value.toUpperCase()">
                                </div>

                                 <div class="form-group">
                                        <label >Activité :</label>
                                        <input type="text" id="activite" disabled="disabled" maxlength="60" class="form-control  " name="activite" placeholder="TRANSIT" onkeyup="this.value=this.value.toUpperCase()">
                                </div>
                                <div class="form-group">
                                    <label>Pays :</label>
                                    <select class="form-control " name="pays" id="pays" disabled>
                                        @php
                                            $pays = $payscontroller->DisplayAll();
                                        @endphp
                                        @foreach($pays as $pays)
                                            <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                            
                                        @endforeach
                                        
                                    </select>
                                </div>

                                <script>
                                    function griseFunction1() {
                                        /* ce script permet d'activer les champ si l'utilisateur choisit autre*/
                                        var val = document.getElementById("mySelectEnt").value;
                                        
                                        if( val == 'autre')
                                        {
                                            document.getElementById("ent").removeAttribute("disabled");
                                            document.getElementById("ca").removeAttribute("disabled");
                                            document.getElementById("ne").removeAttribute("disabled");
                                            document.getElementById("adresse").removeAttribute("disabled");
                                            document.getElementById("activite").removeAttribute("disabled");
                                            document.getElementById("pays").removeAttribute("disabled");
                                        }
                                        else{
                                            document.getElementById("ent").setAttribute("disabled", "disabled");
                                            document.getElementById("ca").setAttribute("disabled", "disabled");
                                            document.getElementById("ne").setAttribute("disabled", "disabled");
                                            document.getElementById("adresse").setAttribute("disabled", "disabled");
                                            document.getElementById("activite").setAttribute("disabled", "disabled");
                                            document.getElementById("pays").setAttribute("disabled", "disabled");
                                        }
                                    
                                    }
                                </script>
                        
                            </div>

                            <div class="col-md-6">
                                <!--DEUXIEMEN PARTIE DU FORMULAIRE-->
                            
                                    <div class="box-header">
                                        <b><h3 class="box-title">INFORMATIONS DE L'INTERLOCUTEUR </h3></b>
                                    </div>
                            
                                    <div class="form-group">
                                        <label for="exampleInputFile">Choisissez l'interlocuteur si existant ou sélectionnez Autre:</label>
                                        <select class="form-control " name="interlocuteur" id="mySelect" onchange="griseFunction()" onfocus="griseFunction()">
                                            @php
                                                $interlocuteur = $interlocuteurcontroller->GetAll();
                                                
                                            @endphp
                                            
                                            @foreach($interlocuteur as $interlocuteur)
                                                <option value={{$interlocuteur->id}}>{{$interlocuteur->nom}}/{{$interlocuteur->fonction}}/{{$interlocuteur->tel}}</option>
                                                
                                            @endforeach

                                            <option value="autre">Autre</option>

                                        </select>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputFile">Titre :</label>
                                        <select class="form-control " name="titre" id="grise1" disabled="disabled">
                                            <option value="M">M</option>
                                            <option value="Mme">Mme</option>
                                            <option value="Mlle">Mlle</option>
                                        </select>
                                        
                                    </div>
                                    <div class="form-group">
                                            <label >Nom & Prénom(s)</label>
                                            <input type="text" disabled="disabled" maxlength="100" required id="grise2" class="form-control  " name="nom" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>

                                    <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" disabled="disabled" id="grise5" maxlength="30" class="form-control " name="email" >
                                    </div>

                                    <div class="form-group">
                                            <label>Téléphone (*)</label>
                                            <input type="text" disabled="disabled" required id="grise3" maxlength="30"   class="form-control " name="tel" placeholder="(+225)0214578931" >
                                        </div>

                                    <div class="form-group">
                                            <label>Fonction</label>
                                            <input disabled="disabled" required type="text" class="form-control "  id="grise4" maxlength="60" name="fonction" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>  
                            
                            </div>

                            <script>
                                function griseFunction() {
                                    /* ce script permet d'activer les champ si l'utilisateur choisit autre*/
                                    var x = document.getElementById("mySelect").value;
                                    
                                    if( x == 'autre')
                                    {
                                        document.getElementById("grise1").removeAttribute("disabled");
                                        document.getElementById("grise2").removeAttribute("disabled");
                                        document.getElementById("grise3").removeAttribute("disabled");
                                        document.getElementById("grise4").removeAttribute("disabled");
                                        document.getElementById("grise5").removeAttribute("disabled");

                                    }
                                    else{
                                        document.getElementById("grise1").setAttribute("disabled", "disabled");
                                        document.getElementById("grise2").setAttribute("disabled", "disabled");
                                        document.getElementById("grise3").setAttribute("disabled", "disabled");
                                        document.getElementById("grise4").setAttribute("disabled", "disabled");
                                        document.getElementById("grise5").setAttribute("disabled", "disabled");
                                    }
                                
                                }
                            </script>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Facture Proforma</label>
                                    
                                    <input type="file" class="form-control" name="fileproforma" >
                                
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label>Compte Rendu</label>
                                    
                                    <input type="file" class="form-control" name="file" >
                                </div>
                                    
                            </div>
                            <!-- /.row-->
                        </div>
                        <!-- /.box-body -->

                        
                    
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">VALIDER</button>
                        </div>
                    
                    </form>   

                </div>	
            </div>
        
             <div class="col-md-2"></div>
        </div>
       

           
    </div>
    <!-- Main row -->  

@endsection
     
    
   