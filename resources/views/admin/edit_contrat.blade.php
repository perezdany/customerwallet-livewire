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
                    <form role="form" method="post" action="edit_contrat" enctype="multipart/form-data">
                       
                        @csrf
                         <input type="text" value="{{$contrat->id}}" style="display:none" name="id_contrat">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Entreprise:</label>
                                <select class="form-control input-lg" name="entreprise">
                                    @php
                                        $get = (new EntrepriseController())->GetAll();
                                    @endphp
                                    <option value="{{$contrat->id_entreprise}}">{{$contrat->nom_entreprise}}</option>
                                    @foreach($get as $entreprise)
                                        <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                        
                                    @endforeach
                                </select>

                            </div>      

                    
                            <div class="form-group">
                                <label>Réfrence du contrat:</label>
                                <input type="text"  maxlength="100" value="{{$contrat->titre_contrat}}" class="form-control input-lg" name="titre" placeholder="Ex: Contrat de sureté BICICI"/>
                            </div>

                            <!--ICI IL FAUT DONNER LA POSSIBILITE DE CHOISIR SI C'EST UN AVENANT-->
                            <div class="form-group">
                            <label>Avenant ?</label>
                            <select class="form-control input-lg" name="avenant" id="mySelectAvenant" onchange="griseFunction1()" >
                                @if($contrat->avenant == 0)
                                    <option value="{{$contrat->avenant}}">NON</option>
                                    <option value="0">NON</option>
                                    <option value="1">TACITE</option>
                                    <option value="1">ACCORD PARTIES</option>
                                @else
                                    @if($contrat->avenant == 1)
                                        <option value="{{$contrat->avenant}}">TACITE</option>
                                        <option value="0">NON</option>
                                        <option value="1">TACITE</option>
                                        <option value="1">ACCORD PARTIES</option>
                                    @endif
                                    @if($contrat->avenant == 2)
                                        <option value="{{$contrat->avenant}}">ACCORD PARTIES</option>
                                        <option value="0">NON</option>
                                        <option value="1">TACITE</option>
                                        <option value="1">ACCORD PARTIES</option>
                                    @endif
                                   
                                @endif
                               
                                
                            </select>
                                
                            </div> 

                            <div class="form-group">
                                <label>Contrat Parent:</label>
                                <select class="form-control input-lg" name="contrat_parent" id="contratparent" disabled required>
                                    @php
                                        $getcontrat =  DB::table('contrats')
                                        ->join('entreprises', 'contrats.id_entreprise', '=', 'entreprises.id')
                                        ->join('utilisateurs', 'contrats.created_by', '=', 'utilisateurs.id')
                                        ->orderBy('entreprises.nom_entreprise', 'asc')
                                        ->where('contrats.id', $contrat->id_contrat_parent)
                                        ->get(['contrats.*', 'utilisateurs.nom_prenoms', 'entreprises.nom_entreprise', ]);
                                    @endphp
                            
                                    @foreach($getcontrat as $getcontrat)
                                        <option value={{$getcontrat->id}}>{{$getcontrat->titre_contrat}}/{{$getcontrat->nom_entreprise}}</option>
                                        
                                    @endforeach

                                    @php
                                        $getparent = ($contratcontroller)->GetContratParent();
                                    @endphp
                                    <option value="0">--Choisir une entreprise--</option>
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
                                <input type="number" class="form-control  input-lg" required name="montant"  value="{{$contrat->montant}}">
                            </div>
                        
                            <div class="form-group">
                                <label>Debut du contrat</label>
                                <input type="date" class="form-control  input-lg" required name="date_debut"  value="{{$contrat->debut_contrat}}">
                            </div>

                          

                            <div class="form-group">
                                <label>Fichier du contrat(PDF)</label>
                                <input type="file" class="form-control" name="file">
                            </div>

                             <div class="form-group">
                                <label>Facture proforma</label>
                                <input type="file" class="form-control" name="file_proforma">
                            </div>

                            <div class="form-group">
                                <label>Bon de commande(PDF)</label>
                                <input type="file" class="form-control" name="bon_commande" >
                            </div>


                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                        <button type="submit" class="btn btn-primary">MODIFIER</button>
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