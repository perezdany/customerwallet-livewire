@extends('layouts/base')

@php

    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    use App\Http\Controllers\CategorieController;

     use App\Http\Controllers\TypePrestationController;

       $typeprestationcontroller = new TypePrestationController();

    $contratcontroller = new ContratController();

    $categoriecontroller = new CategorieController();

     $servicecontroller = new ServiceController();
    $my_own =  $contratcontroller->MyOwnContrat(auth()->user()->id);

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

          <div class="col-md-12">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Contrats</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Titre de contrat</th>
                    <th>Entreprise</th>
                    <th>Type de contrat</th>
                   
                   <th>Début du contrat</th>
                   <th>Fin du contrat</th>
                    <th>Montant</th>	
                   
                    @if(auth()->user()->id_role == 3)
                    @else
                      <th>Fichier du contrat</th>
                        <th>Action</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                         @php
            
                            if(isset($id_entreprise))
                            {
                                $all = $contratcontroller->GetAll($id_entreprise);
                            }
                            
                        @endphp
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->titre_contrat}}</td>
                          <td>{{$all->nom_entreprise}}</td>
                          <td>
                            <!--ECRIRE UN CODE POUR DETECTER LE TYPE DE PRESTATION-->
                            @php
                                $g = DB::table('prestations')
                                      ->join('typeprestations', 'prestations.id_type_prestation', '=', 'typeprestations.id')
                                      ->where('id_contrat', $all->id)
                                      ->get('typeprestations.libele');
                            @endphp
                            @foreach($g as $type)
                                {{$type->libele}}
                            @endforeach
                          
                          </td>
                        
                          <td>@php echo date('d/m/Y',strtotime($all->debut_contrat)) @endphp</td>
                           <td>@php echo date('d/m/Y',strtotime($all->fin_contrat)) @endphp</td>
                          <td>
                            @php
                              echo  number_format($all->montant, 2, ".", " ")." XOF";
                            @endphp
                           
                          </td>  
                          @if(auth()->user()->id_role == 3)
                          @else
                            <td>
                              
                              <form action="upload" method="post" enctype="multipart/form-data">
                                @csrf
                                <label>Fichier scanné(PDF)</label>
                                 <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="file" class="form-control" name="file">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i></button>
                              </form>

                              <form action="download" method="post" enctype="multipart/form-data">
                                @csrf
                                <label>Télécharger</label>
                                 <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file" value="{{$all->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                            </td>

                            <td>
                                <form action="edit_contrat_form" method="post">
                                    @csrf
                                    <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                </form>

                            </td>
                          @endif  
                          
                        </tr>
                      @endforeach
                  </tbody>
                 
                  </table>
                </div>
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col --> 
      </div>
          <!-- /.row -->
      <div class="row">
          <!-- left column -->
            <div class="col-md-2">
              
            </div>
            <!--/.col (left) -->
            <div class="col-md-8">

              <!-- general form elements -->
              <div class="box box-aeneas">
               
              
                <!-- form start -->
                <form role="form" method="post" action="add_contrat_with_prest" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                    <div class="col-md-6">
                        <div class="box-header with-border">
                          <h3 class="box-title"><b>ENREGISTRER UN CONTRAT</h3><br>(*) champ obligatoire</b>
                        </div>
                      <div class="box-body">
                        <div class="form-group">
                          <label>Entreprise:</label>
                          <select class="form-control input-lg" name="entreprise">
                            @php
                                  $get = (new EntrepriseController())->GetAll();
                              @endphp
                              <option value="0">--Choisir une entreprise--</option>
                              @foreach($get as $entreprise)
                                  <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                  
                              @endforeach
                              <option value="autre">Autre<option>
                          </select>
                            
                        </div>    

                        <div class="form-group">
                          <label>Titre</label>
                          <input type="text"  class="form-control input-lg" name="titre" placeholder="Ex: 202317854/SUPPORT/TTR/01"/>
                        </div>
                  
                        <div class="form-group">
                          <label >Montant (XOF)</label>
                          <input type="text" class="form-control  input-lg" required name="montant">
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
                              <div class="col-md-3">
                                <input type="number" class="form-control" placeholder="jours" min="1" max="31" name="jour" required>
                              </div>
                              <div class="col-md-4">
                                <input type="number" class="form-control" placeholder="mois" min="1" max="12" name="mois">
                              </div>
                              <div class="col-md-5">
                                <input type="number" class="form-control" placeholder="année" min="1" max="10" name="annee">
                              </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Fichier du contrat(PDF)</label>
                              <input type="file" class="form-control" name="file">
                        </div>

                        <div class="form-group">
                            <label>Facture Proforma(PDF)</label>
                              <input type="file" class="form-control" name="file_proforma" required>
                        </div>

                        
                      
                      </div>
                    </div>
                    <div class="col-md-6">

                      <div class="box-body">
                        <div class="box-header with-border">
                          <h3 class="box-title"> <b>PRESTATION</b></h3><br>
                        </div>

                        <div class="form-group">
                            <label>Service (*)</label>
                            <select class="form-control input-lg select2" multiple="multiple" name="service[]"
                                style="width: 100%;" data-placeholder="--Selectionnez le service--" required>
                                <!--liste des services a choisir -->
                                
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
                            <label>Adresse </label>
                            <input type="text" required maxlength="100" class="form-control input-lg" 
                            name="localisation" placeholder="Ex: Cocody Angré Cocovico">
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
        
            <!-- left column -->
            <div class="col-md-2">
              
            </div>
            <!--/.col (left) -->
            
      </div>
      <!--/.col (right) -->
@endsection