@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    $contratcontroller = new ContratController();

    $entreprisecontroller = new EntrepriseController();

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
   
		<div class="row">
          <div class="col-md-3">
          </div>
      
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-aeneas">
              <div class="box-header with-border">
                <h3 class="box-title">MODIFIER LE CONTRAT</h3><br>(*) champ obligatoire
              </div>
                 @php
                    $contrat = $contratcontroller->GetById($id);
                @endphp

                @foreach($contrat as $contrat)
                    <!-- form start -->
                    <form role="form" method="post" action="edit_contrat_fiche" enctype="multipart/form-data">
                       
                        @csrf
                         <input type="text" value="{{$contrat->id}}" style="display:none" name="id_contrat">
                         <input type="text" value="{{$id_entreprise}}" style="display:none" name="id_entreprise">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Entreprise:</label>
                                <select class="form-control " name="entreprise">
                                    @php
                                        $nom = $entreprisecontroller->GetById($id_entreprise)
                                    @endphp
                                    @foreach($nom as $nom)
                                    
                                        <option value={{$nom->id}}>{{$nom->nom_entreprise}}</option>
                                        
                                    @endforeach
                                </select>

                            </div>      

                    
                            <div class="form-group">
                                <label>Titre</label>
                                <input type="text"  maxlength="100" value="{{$contrat->titre_contrat}}" class="form-control " name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
                            </div>
                        
                            <div class="form-group">
                                <label >Montant (XOF)</label>
                                <input type="number" class="form-control  " required name="montant"  value="{{$contrat->montant}}">
                            </div>
                        
                            <div class="form-group">
                                <label>Debut du contrat</label>
                                <input type="date" class="form-control  " required name="date_debut"  value="{{$contrat->debut_contrat}}">
                            </div>

                            <div class="form-group">
                                <label>Fichier du contrat(PDF)</label>
                                <input type="file" class="form-control" name="file">
                            </div>

                             <div class="form-group">
                                <label>Facture proforma</label>
                                <input type="file" class="form-control"  name="file_proforma">
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">VALIDER</button>
                        </div>
                    </form>
                @endforeach
             
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