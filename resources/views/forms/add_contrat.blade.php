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

        <div class="row">
            <div class="col-md-3"></div>

            <div class="col-md-6">
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
                            <label>Numéro de contrat (Titre du contrat)</label>
                            <input type="text"  maxlength="100" required class="form-control input-lg" name="titre" placeholder="Ex: 20231214/SUPPORT/TT/01"/>
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

                        <div class="form-group">
                            <label>Fichier du contrat(PDF)</label>
                              <input type="file" class="form-control" name="file">
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

            <div class="col-md-3"></div>
        </div>
       

           
    </div>
    <!-- Main row -->  

@endsection
     
    
   