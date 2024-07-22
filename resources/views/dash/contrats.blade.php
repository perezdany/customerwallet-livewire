@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    $contratcontroller = new ContratController();

    $my_own =  $contratcontroller->MyOwnContrat(auth()->user()->id);

    $all = $contratcontroller->RetriveAll();
@endphp

@section('content')
     <div class="row">
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
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
                    <th>Titre du contrat</th>
                    <th>Montant</th>
                    <th>Reste à payer</th>
                    <th>Début du contrat</th>
                    <th>Fin du contrat</th>
                    <th>Entreprise</th>	
                    <th>Date de solde</th>
                    <th>Enregistré par:</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>{{$all->titre_contrat}}</td>
                          <td>{{$all->montant}}</td>
                          <td>{{$all->reste_a_payer}}</td>
                          <td>@php echo date('d/m/Y',strtotime($all->debut_contrat)) @endphp</td>
                          <td>@php echo date('d/m/Y',strtotime($all->fin_contrat)) @endphp</td>
                          <td>{{$all->nom_entreprise}}</td>
                          <td>
                            @php 
                              if($all->statut_solde == 0)
                              {
                                echo date('d/m/Y',strtotime($all->date_solde)) ;
                              }
                              else
                              {
                                echo '<p class="bg-success">Soldé</p>';
                              }
                            @endphp
                          </td>
                          <td>{{$all->nom_prenoms}}</td>
                          <td>
                            <form action="edit_contrat_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                  <th>Titre du contrat</th>
                    <th>Montant</th>
                    <th>Reste à payer</th>
                    <th>Début du contrat</th>
                    <th>Fin du contrat</th>
                    <th>Entreprise</th>	
                    <th>Date de solde</th>
                    <th>Enregistré par</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
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
          <div class="col-md-3">
          </div>
      
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">ENREGISTRER UN CONTRAT</h3><br>(*) champ obligatoire
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
                        <option value="autre">Autre<option>
                    </select>
                      
                  </div>    

                  <div class="form-group">
                      <label>Renseigner le nom de l'entreprise</label>
                      <input type="text" onkeyup='this.value=this.value.toUpperCase()' class="form-control input-lg" name="entreprise_name" placeholder="Ex:BICICI"/>
                  </div>  
                  <div class="form-group">
                    <label>Titre</label>
                    <input type="text"  class="form-control input-lg" name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
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
          <div class="col-md-3">
		  		</div>
    </div>
    <!--/.col (right) -->
@endsection