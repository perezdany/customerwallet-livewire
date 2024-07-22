@extends('layouts/dash')
@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

     use App\Http\Controllers\ContratController;

     use App\Http\Controllers\EntrepriseController;

     use App\Http\Controllers\TypePrestationController;

     use App\Http\Controllers\InterlocuteurController;

     $servicecontroller = new ServiceController();

     $typeprestationcontroller = new TypePrestationController();

     $contratcontroller = new ContratController();

     $entreprisecontroller = new EntrepriseController();

     $interlocuteurcontroller = new InterlocuteurController();

     
@endphp

@section('content')
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
                                <label>Entreprise: Ou choisir Autre</label>
                                <select class="form-control input-lg" name="entreprise">
                                    @php
                                        $get = (new EntrepriseController())->GetAll();
                                    @endphp
                                    
                                    @foreach($get as $entreprise)
                                        <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                        
                                    @endforeach
                                    <option value="autre">Autre</option>
                                </select>
                                    
                            </div>   
                            <div class="form-group">
                                <label>Renseigner le nom de l'entreprise</label>
                                <input type="text"  maxlength="50" onkeyup='this.value=this.value.toUpperCase()' class="form-control input-lg" name="entreprise_name" placeholder="Ex:BICICI"/>
                            </div>

                        <div class="form-group">
                            <label>Titre</label>
                            <input type="text"  maxlength="100" class="form-control input-lg" name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
                        </div>
                    
                        <div class="form-group">
                            <label >Montant (XOF)</label>
                            <input type="number" maxlength="13"  
                            class="form-control  input-lg" required name="montant">
                        </div>

                        <!--SCRIPT POUR FORCER LA SAISIE DE NOMBRE-->
                        <script type="text/javascript">
                            /*function restrictAlphabets(e)
                            {
                                var x = e.wich || e.keycode;
                                if(x >= 48 && x <= 57)
                                {
                                    return true;
                                }
                                else
                                {
                                    return false;
                                }
                            }*/
                        </script>
                    
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
                                            $contrat = $contratcontroller->GetAll();
                                            
                                        @endphp
                                        
                                        @foreach($contrat as $contrat)
                                            <option value={{$contrat->id}}>{{$contrat->titre_contrat}}</option>
                                            
                                        @endforeach
                                    </select>
                                </div>
                                    
                                <div class="form-group">
                                    <label>Adresse </label>
                                    <input type="text"  maxlength="100" class="form-control input-lg" name="localisation" placeholder="Ex: Cocody Angré Cocovico">
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
                                    <label>Service Proposé (*)</label>
                                    <select class="form-control input-lg" name="service_propose" required>
                                        <!--liste des services a choisir -->
                                        @php
                                            $get = $servicecontroller->GetAll();
                                        @endphp
                                        
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
                                        
                                        @foreach($get as $entreprise)
                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                            
                                        @endforeach
                                        <option value="autre">Autre</option>
                                    </select>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Saisir le nom de l'entreprise</label>
                                <input type="text"  maxlength="50" class="form-control input-lg" name="entreprise_name" onkeyup="this.value=this.value.toUpperCase()">
                                    
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
              @if(auth()->user()->id_role == 2)
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
                                            
                                            @foreach($get as $entreprise)
                                                <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                
                                            @endforeach
                                            <option value="autre">Autre</option>
                                        </select>
                                        
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputFile">Saisir le nom de l'entreprise</label>
                                    <input type="text"  maxlength="30" class="form-control input-lg" name="entreprise_name" onkeyup="this.value=this.value.toUpperCase()">
                                        
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
                                        
                                        @foreach($get as $entreprise)
                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                            
                                        @endforeach
                                        <option value="autre">Autre</option>
                                    </select>
                                        
                                </div>   
                                <div class="form-group">
                                    <label>Renseigner le nom de l'entreprise</label>
                                    <input type="text"  maxlength="30" onkeyup='this.value=this.value.toUpperCase()'
                                     class="form-control input-lg" name="entreprise_name" placeholder="Ex:BICICI"/>
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
                                                $contrat = $contratcontroller->GetAll();
                                                
                                            @endphp
                                            
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
     
    
   