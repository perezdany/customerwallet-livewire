@extends('layouts/dash')
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
    @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 4 OR auth()->user()->id_role == 2)
        <div class="row">
            <div class="col-md-8">
            <!-- TABLE: LATEST ORDERS LES FACTURES QUI N'ONT PAS ETE REGLEES ET LADATE EST DEPASS2E-->
            @if($count_non_reglee != 0)
                    <div class="box box-info">
                        <div class="box-header with-border">
                        <h3 class="box-title">Attention! Ces factures ne sont pas réglées et la date de règlement est dépassée</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                        <div class="table-responsive">
                            <table class="table no-margin">
                            <thead>
                                <tr>
                                    <th>Facture N°</th>

                                
                                    <th>Date de règlement</th>
                                    <th>Montant</th>
                                    <th>Afficher les paiements</th>
                                    <th>Contrat</th>
                                    <th>Etat facture</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($my_own as $my_own)
                                        <tr>
                                        <td>{{$my_own->numero_facture}}</td>
                                        
                                        <td>@php echo date('d/m/Y',strtotime($my_own->date_reglement)) @endphp</td>
                                        <td>
                                            @php
                                                echo  number_format($my_own->montant_facture, 2, ".", " ")." XOF";
                                            @endphp
                                        </td>
                                        <td>
                                            <form action="paiement_by_facture" method="post">
                                                    @csrf
                                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                            </form>
                                        </td>
                                        <td>{{$my_own->titre_contrat}}</td>
                                            <td>
                                            @if($my_own->reglee == 0)
                                                <p class="bg-warning">
                                                <b>Facture non réglée</b>
                                                </p>
                                            @endif
                                            @if($my_own->reglee == 1)
                                                <p class="bg-success">
                                                <b>Facture réglée</b>
                                                </p>
                                            @endif
                                            
                                            </td>
                                        <td>

                                            @if($my_own->reglee == 0)
                                                @if(auth()->user()->id_role != 2)
                                                <form action="paiement_form" method="post">
                                                    @csrf
                                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                </form>
                                                @endif
                                            @else
                                            
                                            @endif
                                           
                                        </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                    <th>Facture N°</th>

                                    
                                        <th>Date de règlement</th>
                                        <th>Montant</th>
                                        <th>Afficher les paiements</th>
                                        <th>Contrat</th>
                                        <th>Etat facture</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer clearfix">
             
                            <a href="facture" class="btn btn-sm btn-primary btn-flat pull-right">Voir tout</a>
                        </div>
                    </div>
                    <!-- /.box -->
            @endif
            
            </div>
        </div>
    @endif
    <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-success" >{{session('success')}}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-danger" >{{session('error')}}</p>
            </div>
        @endif
        <!-- CODE POUR LES RESTRICTIONS-->

        
            @if(auth()->user()->id_role == 1 OR auth()->user()->id_role == 3 OR auth()->user()->id_role == 4)
               <!-- left column -->
                <div class="col-md-4">
                    <div class="box box-aeneas">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>ENREGISTRER UN CONTRAT</b></h3><br>(*) <b>champ obligatoire
                    </div>
                    
                    <!-- form start -->
                    <form role="form" method="post" action="add_contrat">
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label>Entreprise (*):</label>
                           
                                <select class="form-control input-lg" name="entreprise" required>
                                    @php
                                        $get = (new EntrepriseController())->GetAll();
                                    @endphp
                                    <option value="0">--Selectionnez Une entreprise--</option>
                                    @foreach($get as $entreprise)
                                        <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                        
                                    @endforeach

                                </select>
                                    
                            </div>   

                        <div class="form-group">
                            <label>Numéro de contrat</label>
                            <input type="text"  maxlength="100" required class="form-control input-lg" name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
                        </div>
                    
                        <div class="form-group">
                            <label >Montant (XOF)</label>
                            <input type="number" maxlength="13"  
                            class="form-control  input-lg" required name="montant">
                        </div>
                    
                        <div class="form-group">
                            <label>Debut du contrat</label>
                            <input type="date" class="form-control  input-lg" required name="date_debut">
                        </div>

                        <div class="form-group">
                            <label>Date de solde</label>
                            <input type="date" class="form-control  input-lg" required name="date_solde">
                        </div>

                            <div class="form-group">
                                <label>Durée du contrat</label>
                                <!--FAIRE DES CALCULS POUR DETERMINER LA FIN DU CONTRAT-->
                                <div class="row">
                                    <div class="col-md-4">
                                    <input type="number" class="form-control" placeholder="jours" min="1" max="31" name="jour" >
                                    </div>
                                    <div class="col-md-4">
                                    <input type="number" class="form-control" placeholder="mois" min="1" max="12" name="mois">
                                    </div>
                                    <div class="col-md-4">
                                    <input type="number" class="form-control" placeholder="année" min="1" max="10" name="annee">
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
                <!--/.col (left) -->

                <!-- right column -->
                <div class="col-md-4">
                    <!-- general form elements -->
                    <div class="box box-aeneas">
                        <div class="box-header with-border">
                        <h3 class="box-title"> <b>ENREGISTRER UNE PRESTATION</b></h3><br><b>(*) champ obligatoire</b>
                        </div>
                        
                        <!-- form start -->
                        <form role="form" method="post" action="add_prestation">
                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Service (*)</label>
                                    <select class="form-control input-lg" name="service" required>
                                       <!--liste des services a choisir -->
                                        <option value="0">--Selectionnez le service--</option>
                                        @php
                                            $get = $servicecontroller->GetAll();
                                            $categorie = $categoriecontroller->DisplayAll();
                                        @endphp
                                         @foreach( $categorie as $categorie)
                                            
                                            <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                            @php
                                                $get = $servicecontroller->GetByCategorie($categorie->id);
                                                
                                            @endphp
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="form-group">
                                    <label>Type de prestation (*)</label>
                                    <select class="form-control input-lg" name="type" required>
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
                                    <label for="exampleInputEmail1">Date d'exécution(*)</label>
                                    <input type="date" class="form-control  input-lg" required name="date_execute">
                                </div>
                                    
                                <div class="form-group">
                                    <label>Choisissez le contrat(*) </label>
                                    <!--Afficher les contrats que l'utilisateur a créé-->
                                    <select class="form-control input-lg" name="contrat" required>
                                        @php
                                            $contrat = $contratcontroller->GetAllNoSolde();
                                            
                                        @endphp
                                        <option value="0">--Choisir le contrat--</option>
                                        @foreach($contrat as $contrat)
                                            <option value={{$contrat->id}}>{{$contrat->titre_contrat}}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                                    
                                <div class="form-group">
                                    <label>Adresse </label>
                                    <input type="text" required maxlength="100" class="form-control input-lg" 
                                    name="localisation" placeholder="Ex: Cocody Angré Cocovico">
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

                <!--/.col (right) -->
                <div class="col-md-4">

                    <div class="box box-aeneas">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>ENREGISTRER UNE PROSPECTION</b> </h3><br>
                            <b>(*)champ obligatoire</b>
                        </div>
                    
                        <!-- form start -->
                        <form role="form" action="add_prospection" method="post">
                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label >Date de la prospection (*)</label>
                                    <input type="date" class="form-control  input-lg" name="date_prospect" required>
                                </div>
                                <div class="form-group">
                                    <label>Service Proposé (*)</label>
                                    <select class="form-control input-lg" name="service_propose" required>
                                    
                                        <!--liste des services a choisir -->
                                        <option value="0">--Selectionnez le service--</option>
                                        @php
                                            $get = $servicecontroller->GetAll();
                                            $categorie = $categoriecontroller->DisplayAll();
                                        @endphp
                                         @foreach( $categorie as $categorie)
                                            
                                            <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                            @php
                                                $get = $servicecontroller->GetByCategorie($categorie->id);
                                                
                                            @endphp
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        @endforeach
                                       
                                    </select>
                            
                                
                                </div>
                               
                                <div class="form-group">
                                    <label for="exampleInputFile">Durée de la prospection (Jr)</label>
                                    <input type="number" max="31" min="1" class="form-control input-lg" name="duree">
                                </div>
                                    <!--CALCULER LA DATE DE FIN DE LA PROSPECTION ET AJOUTER-->
                                <div class="box-header">
                                    <b><h3 class="box-title">L'ENTREPRISE</h3></b>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Choisissez l'entreprise si existant ou sélectionnez Autre:</label>
                                    <select class="form-control input-lg" name="entreprise">
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
                                        <label >Nom de l'entreprise:</label>
                                        <input type="text"  maxlength="50" class="form-control  input-lg" name="entreprise_name" onkeyup="this.value=this.value.toUpperCase()">
                                </div>
                               
                            </div>
                            <!-- /.box-body -->

                            <div class="box-header">
                                <b><h3 class="box-title">INFORMATIONS DE L'INTERLOCUTEUR </h3></b>
                            </div>
                            <div class="box-body">
                                <div class="form-group">
                                    <label for="exampleInputFile">Choisissez l'interlocuteur si existant ou sélectionnez Autre:</label>
                                    <select class="form-control input-lg" name="interlocuteur">
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
                                    <select class="form-control input-lg" name="titre">
                                        <option value="M">M</option>
                                        <option value="Mme">Mme</option>
                                        <option value="Mlle">Mlle</option>
                                    </select>
                                    
                                </div>
                                <div class="form-group">
                                        <label >Nom & Prénom(s)</label>
                                        <input type="text"  maxlength="100" class="form-control  input-lg" name="nom" onkeyup="this.value=this.value.toUpperCase()">
                                </div>

                                <div class="form-group">
                                        <label>Email</label>
                                        <input type="email"  maxlength="30" class="form-control input-lg" name="email" >
                                    </div>

                                <div class="form-group">
                                        <label>Téléphone (*)</label>
                                        <input type="text"  maxlength="30"   class="form-control input-lg" name="tel" placeholder="(+225)0214578931" >
                                    </div>

                                <div class="form-group">
                                        <label>Fonction</label>
                                        <input type="text" class="form-control input-lg"  maxlength="60" name="fonction" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>  
                            </div>

                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">VALIDER</button>
                            </div>
                        </form>
                    </div>		
                </div>
            @else
              @if(auth()->user()->id_role == 5 )
                @if(auth()->user()->id_departement == 1)
                      <!-- left column -->
                    <div class="col-md-3">
                    
                        <!-- /.box -->
                    </div>
                    <!--/.col (left) -->

                    <!-- right column -->
                    <div class="col-md-6">
                        <div class="box box-aeneas">
                            <div class="box-header with-border">
                                <h3 class="box-title"><b>ENREGISTRER UNE PROSPECTION</b> </h3><br>
                                <b>(*)champ obligatoire</b>
                            </div>
                        
                            <!-- form start -->
                            <form role="form" action="add_prospection" method="post">
                                @csrf
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Service Proposé (*)</label>
                                        <select class="form-control input-lg" name="service_propose" required>
                                            <!--liste des services a choisir -->
                                            @php
                                                $get = $servicecontroller->GetAll();
                                            @endphp
                                            <option value="0">--Choisisez le service--</option>
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        </select>
                                
                                    
                                    </div>
                                    <div class="form-group">
                                        <label >Date de la prospection (*)</label>
                                        <input type="date" class="form-control  input-lg" name="date_prospect" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Durée de la prospection (Jr)</label>
                                        <input type="number" max="31" min="1" class="form-control input-lg" name="duree">
                                    </div>
                                        <!--CALCULER LA DATE DE FIN DE LA PROSPECTION ET AJOUTER-->
                                    <div class="box-header">
                                        <b><h3 class="box-title">L'ENTREPRISE</h3></b>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Choisissez l'entreprise si existant ou sélectionnez Autre:</label>
                                        <select class="form-control input-lg" name="entreprise">
                                            @php
                                                $get = $entreprisecontroller->GetAll();
                                            @endphp
                                            <option value="0">Choissez l'entreprise--</option>
                                            @foreach($get as $entreprise)
                                                <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                
                                            @endforeach
                                            <option value="autre">Autre</option>
                                        </select>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label >Nom de l'entreprise:</label>
                                        <input type="text"  maxlength="50" class="form-control  input-lg" name="entreprise_name" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>
                                </div>
                                <!-- /.box-body -->

                                <div class="box-header">
                                    <b><h3 class="box-title">INFORMATIONS DE L'INTERLOCUTEUR </h3></b>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="exampleInputFile">Choisissez l'interlocuteur si existant ou sélectionnez Autre:</label>
                                        <select class="form-control input-lg" name="interlocuteur">
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
                                        <select class="form-control input-lg" name="titre">
                                            <option value="M">M</option>
                                            <option value="Mme">Mme</option>
                                            <option value="Mlle">Mlle</option>
                                        </select>
                                        
                                    </div>
                                    <div class="form-group">
                                            <label >Nom & Prénom(s)</label>
                                            <input type="text"   maxlength="60"  class="form-control  input-lg" name="nom" onkeyup="this.value=this.value.toUpperCase()">
                                    </div>

                                    <div class="form-group">
                                            <label>Email</label>
                                            <input type="email"  maxlength="30" class="form-control input-lg" name="email" >
                                        </div>

                                    <div class="form-group">
                                            <label>Téléphone (*)</label>
                                            <input type="text"  maxlength="30" class="form-control input-lg" name="tel" placeholder="(+225)0214578931" >
                                        </div>

                                    <div class="form-group">
                                            <label>Fonction</label>
                                            <input type="text"  maxlength="60" class="form-control input-lg" name="fonction" onkeyup="this.value=this.value.toUpperCase()">
                                        </div>  
                                </div>

                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">VALIDER</button>
                                </div>
                            </form>
                        </div>		
                    </div>

                    <!--/.col (right) -->
                    <div class="col-md-3">
                    </div>
                @endif

                @if(auth()->user()->id_departement == 5)
                     <!-- left column -->
                    <div class="col-md-4">
                        <div class="box box-aeneas">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>ENREGISTRER UN CONTRAT</b></h3><br>(*) <b>champ obligatoire
                        </div>
                        
                        <!-- form start -->
                        <form role="form" method="post" action="add_contrat">
                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Entreprise: Ou choisir Autre</label>
                                    <select class="form-control input-lg" name="entreprise">
                                        @php
                                            $get = (new EntrepriseController())->GetAll();
                                        @endphp
                                         <option  value="0">--Selectionnez Une entreprise--</option>
                                        @foreach($get as $entreprise)
                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                            
                                        @endforeach
                                        
                                    </select>
                                        
                                </div>   
                               

                            <div class="form-group">
                                <label>Titre</label>
                                <input type="text" class="form-control input-lg"  maxlength="100"  
                                 name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
                            </div>
                        
                            <div class="form-group">
                                <label >Montant (XOF)</label>
                                <input type="number" class="form-control  input-lg" required name="montant">
                            </div>
                        
                            <div class="form-group">
                                <label>Debut du contrat</label>
                                <input type="date" class="form-control  input-lg" required name="date_debut">
                            </div>

                            <div class="form-group">
                                <label>Date de solde</label>
                                <input type="date" class="form-control  input-lg" required name="date_solde">
                            </div>

                                <div class="form-group">
                                    <label>Durée du contrat</label>
                                    <!--FAIRE DES CALCULS POUR DETERMINER LA FIN DU CONTRAT-->
                                    <div class="row">
                                        <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="jours" min="1" max="31" name="jour" >
                                        </div>
                                        <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="mois" min="1" max="12" name="mois">
                                        </div>
                                        <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="année" min="1" max="10" name="annee">
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
                    <!--/.col (left) -->

                    <!-- right column -->
                    <div class="col-md-4">
                        <!-- general form elements -->
                        <div class="box box-aeneas">
                            <div class="box-header with-border">
                            <h3 class="box-title"> <b>ENREGISTRER UNE PRESTATION</b></h3><br><b>(*) champ obligatoire</b>
                            </div>
                            
                            <!-- form start -->
                            <form role="form" method="post" action="add_prestation">
                                @csrf
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Service (*)</label>
                                        <select class="form-control input-lg" name="service" required>
                                            <!--liste des services a choisir -->
                                            @php
                                                $get = $servicecontroller->GetAll();
                                            @endphp
                                            <option  value="0">--Selectionnez le service--</option>
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    <div class="form-group">
                                        <label>Type de prestation (*)</label>
                                        <select class="form-control input-lg" name="type" required>
                                            <!--liste des services a choisir -->
                                            @php
                                                $get = $typeprestationcontroller->GetAll();
                                            @endphp
                                             <option  value="0">--Selectionnez le type--</option>
                                            @foreach($get as $type)
                                                <option value={{$type->id}}>{{$type->libele}}</option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Date d'exécution(*)</label>
                                        <input type="date" class="form-control  input-lg" required name="date_execute">
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Choisissez le contrat(*) </label>
                                        <!--Afficher les contrats que l'utilisateur a créé-->
                                        <select class="form-control input-lg" name="contrat" required>
                                            @php
                                                $contrat = $contratcontroller->GetAllNoSolde();
                                                
                                            @endphp
                                             <option value="0">--Selectionnez le contrat--</option>
                                            @foreach($contrat as $contrat)
                                                <option value={{$contrat->id}}>{{$contrat->titre_contrat}}</option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Adresse </label>
                                        <input type="text" class="form-control input-lg"  maxlength="100"  
                                        name="localisation" placeholder="Ex: Cocody Angré Cocovico" required>
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
                @endif
              @endif

               @if(auth()->user()->id_role == 2 )
               
                @if(auth()->user()->id_departement == 5)
                     <!-- left column -->
                    <div class="col-md-4">
                        <div class="box box-aeneas">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>ENREGISTRER UN CONTRAT</b></h3><br>(*) <b>champ obligatoire
                        </div>
                        
                        <!-- form start -->
                        <form role="form" method="post" action="add_contrat">
                            @csrf
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Entreprise: Ou choisir Autre</label>
                                    <select class="form-control input-lg" name="entreprise">
                                        @php
                                            $get = (new EntrepriseController())->GetAll();
                                        @endphp
                                         <option  value="0">--Selectionnez Une entreprise--</option>
                                        @foreach($get as $entreprise)
                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                            
                                        @endforeach
                                        
                                    </select>
                                        
                                </div>   
                               

                            <div class="form-group">
                                <label>Titre</label>
                                <input type="text" class="form-control input-lg"  maxlength="100"  
                                 name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
                            </div>
                        
                            <div class="form-group">
                                <label >Montant (XOF)</label>
                                <input type="number" class="form-control  input-lg" required name="montant">
                            </div>
                        
                            <div class="form-group">
                                <label>Debut du contrat</label>
                                <input type="date" class="form-control  input-lg" required name="date_debut">
                            </div>

                            <div class="form-group">
                                <label>Date de solde</label>
                                <input type="date" class="form-control  input-lg" required name="date_solde">
                            </div>

                                <div class="form-group">
                                    <label>Durée du contrat</label>
                                    <!--FAIRE DES CALCULS POUR DETERMINER LA FIN DU CONTRAT-->
                                    <div class="row">
                                        <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="jours" min="1" max="31" name="jour" >
                                        </div>
                                        <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="mois" min="1" max="12" name="mois">
                                        </div>
                                        <div class="col-md-4">
                                        <input type="number" class="form-control" placeholder="année" min="1" max="10" name="annee">
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
                    <!--/.col (left) -->

                    <!-- right column -->
                    <div class="col-md-4">
                        <!-- general form elements -->
                        <div class="box box-aeneas">
                            <div class="box-header with-border">
                            <h3 class="box-title"> <b>ENREGISTRER UNE PRESTATION</b></h3><br><b>(*) champ obligatoire</b>
                            </div>
                            
                            <!-- form start -->
                            <form role="form" method="post" action="add_prestation">
                                @csrf
                                <div class="box-body">
                                    <div class="form-group">
                                        <label>Service (*)</label>
                                        <select class="form-control input-lg" name="service" required>
                                            <!--liste des services a choisir -->
                                            @php
                                                $get = $servicecontroller->GetAll();
                                            @endphp
                                            <option  value="0">--Selectionnez le service--</option>
                                            @foreach($get as $service)
                                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                
                                    <div class="form-group">
                                        <label>Type de prestation (*)</label>
                                        <select class="form-control input-lg" name="type" required>
                                            <!--liste des services a choisir -->
                                            @php
                                                $get = $typeprestationcontroller->GetAll();
                                            @endphp
                                             <option  value="0">--Selectionnez le type--</option>
                                            @foreach($get as $type)
                                                <option value={{$type->id}}>{{$type->libele}}</option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Date d'exécution(*)</label>
                                        <input type="date" class="form-control  input-lg" required name="date_execute">
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Choisissez le contrat(*) </label>
                                        <!--Afficher les contrats que l'utilisateur a créé-->
                                        <select class="form-control input-lg" name="contrat" required>
                                            @php
                                                $contrat = $contratcontroller->GetAllNoSolde();
                                                
                                            @endphp
                                             <option value="0">--Selectionnez le contrat--</option>
                                            @foreach($contrat as $contrat)
                                                <option value={{$contrat->id}}>{{$contrat->titre_contrat}}</option>
                                                
                                            @endforeach
                                        </select>
                                    </div>
                                        
                                    <div class="form-group">
                                        <label>Adresse </label>
                                        <input type="text" class="form-control input-lg"  maxlength="100"  
                                        name="localisation" placeholder="Ex: Cocody Angré Cocovico">
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
                @endif
              @endif
            @endif

           
    </div>
    <!-- Main row -->  

@endsection
     
    
   