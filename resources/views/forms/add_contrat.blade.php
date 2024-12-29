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

     
@endphp

@section('content')
   
    <div class="row">
       
        <!-- CODE POUR LES RESTRICTIONS-->

        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-8">
                <div class="box box-aeneas">
                  <div class="box-header with-border">
                      <h3 class="box-title"><b>ENREGISTRER UN CONTRAT</b></h3><br>(*) <b>champ obligatoire
                  </div>
                
                  <!-- form start -->
                  <form role="form" method="post" action="add_contrat_with_prest" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                      <div class="col-md-6">
                          
                        <div class="box-body">
                          <div class="form-group">
                            <label>Entreprise:</label>
                            <select class="form-control " name="entreprise" required>
                              @php
                                    $get = (new EntrepriseController())->GetAll();
                                @endphp
                                <option value="0">--Choisir une entreprise--</option>
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>
                              
                          </div>    

                          <div class="form-group">
                            <label>Référence du contrat:</label>
                            <input type="text"  class="form-control " name="titre"  required placeholder="Ex: 202317854/SUPPORT/TTR/01"/>
                          </div>

                          <!--ICI IL FAUT DONNER LA POSSIBILITE DE CHOISIR SI C'EST UN AVENANT-->
                          <div class="form-group">
                            <label>Avenant ?</label>
                            <select class="form-control " name="avenant" id="mySelectAvenant" onchange="griseFunction1()" >
                              <option value="0">NON</option>
                              <option value="1">OUI</option>
                                
                            </select>
                              
                          </div> 

                          <div class="form-group">
                            <label>Contrat Parent:</label>
                            <select class="form-control " name="contrat_parent" id="contratparent" disabled required>
                                @php
                                    $getparent = ($contratcontroller)->GetContratParent();
                                @endphp
                                <option value="0">--Choisir le contrat</option>
                                @foreach($getparent as $getparent)
                                    <option value={{$getparent->id}}>{{$getparent->titre_contrat}}/{{$getparent->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>
                              
                          </div>    
                          <script>
                            function griseFunction1() {
                                /* ce script permet d'activer les champ si l'utilisateur choisit autre*/
                                var val = document.getElementById("mySelectAvenant").value;
                                
                                if( val == '1')
                                {
                                document.getElementById("contratparent").removeAttribute("disabled");
                                
                                }
                                else
                                {
                                document.getElementById("contratparent").setAttribute("disabled", "disabled");
                                
                                }
                            
                            }
                          </script>   
                          <div class="form-group">
                            <label >Montant (XOF)</label>
                            <input type="text" class="form-control  " required name="montant">
                          </div>
                    
                          <div class="form-group">
                            <label>Debut du contrat</label>
                            <input type="date" class="form-control  " required name="date_debut">
                          </div>
                          <div class="form-group">
                            <label>Durée du contrat</label>
                            <!--FAIRE DES CALCULS POUR DETERMINER LA FIN DU CONTRAT-->
                              <div class="row">
                                <div class="col-md-3">
                                  <input type="number" class="form-control" placeholder="jours" min="1" max="365" name="jour" >
                                </div>
                                <div class="col-md-4">
                                  <input type="number" class="form-control" placeholder="mois" min="1" max="12" name="mois">
                                </div>
                                <div class="col-md-5">
                                  <input type="number" class="form-control" placeholder="année" min="1" max="10" name="annee">
                                </div>
                              </div>
                          </div>

                        </div>
                      </div>
                      <div class="col-md-6">

                        <div class="box-body">
                        
                          <div class="form-group">
                              <label>Service (*)</label>
                              <select class="form-control  select2" multiple="multiple" name="service[]"
                                  style="width: 100%;" data-placeholder="--Selectionnez le service--" required>
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
                    
                          <div class="form-group">
                              <label>Type de facturation (*)</label>
                              <select class="form-control " name="type" required>
                                  <!--liste des services a choisir -->
                                  @php
                                      $get = $typeprestationcontroller->GetAll();
                                  @endphp
                                  <option value="0">--Choisir le type--</option>
                                  @foreach($get as $type)
                                      <option value={{$type->id}}>{{$type->libele}}</option>
                                      
                                  @endforeach
                              </select>
                          </div>

                          <div class="form-group">
                            <label>Reconduction:</label>
                            <select class="form-control " name="reconduction" required>
                                
                                  <option value="0">NON</option>
                                  <option value="1">TACITE</option>
                                  <option value="2">ACCORD PARTIES</option>
                            </select>
                          </div>

                          <div class="form-group">
                              <label>Fichier du contrat(PDF)</label>
                                <input type="file" class="form-control" name="file" required>
                          </div>

                          <div class="form-group">
                              <label>Facture Proforma(PDF)</label>
                                <input type="file" class="form-control" name="file_proforma" >
                          </div>

                          <div class="form-group">
                              <label>Bon de commande(PDF)</label>
                                <input type="file" class="form-control" name="bon_commande" >
                          </div>

                        </div>


                      </div>

                    </div>
                    
                    <!-- /.box-body -->

                    <div class="box-footer">
                      <button type="submit" class="btn btn-primary">VALIDER</button>
                    </div>
                  </form>
                </div>
                <!-- /.box -->
            </div>

            <div class="col-md-2"></div>
        </div>
       

           
    </div>
    <!-- Main row -->  

@endsection
     
    
   