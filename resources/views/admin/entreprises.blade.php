@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\StatutEntrepriseController;

    use App\Http\Controllers\PaysController;

    use App\Models\Interlcotueur;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $payscontroller = new PaysController();

    $all = $entreprisecontroller->GetAll();

    $statut = $statutentreprisecontroller->GetAll();

   
    
@endphp

@section('content')
      <div class="row">
        <div class="col-md-3">
          <a href="form_add_entreprise"><button class="btn btn-success"> <b><i class="fa fa-plus"></i> ENTREPRISE</b></button></a>
                
        </div>   

        <div class="col-md-3">
        
        </div>
      </div>
      <div class="row">
          @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-green" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif

           @if(isset($message_success))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{$message_success}}</p>
            </div>
          @endif
           @if(isset($message_error))
            <div class="col-md-12 box-header">
              <p class="bg-red" style="font-size:13px;">{{$message_error}}</p>
            </div>
          @endif
          @php
            //dd($entreprises);
          @endphp


          @if(isset($entreprises))
              @php
                //dd($entreprises)
              @endphp
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Base des Entreprise</h3>
                    
                      <form role="form" method="post" action="make_filter_entreprise">
                        @csrf
                        <a href="entreprises" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp;&emsp;&emsp;<label>Filtrer par:</label>
                        <div class="box-body">
                          <div class="row">
                          
                            <div class="col-xs-3">
                              <select class="form-control" name="categorie">
                              
                                @if($categorie == "c")
                                  <option value="c">Catégorie</option>
                                  @php
                                      $get = ($statutentreprisecontroller)->GetAll();
                                  @endphp
                                
                                  @foreach($get as $statut)
                                    <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                      
                                  @endforeach
                                @else
                                   @php
                                    $le_statut_choisi = ($statutentreprisecontroller)->GetById($categorie);
                                    
                                  @endphp
                                  
                                  @foreach($le_statut_choisi as $le_statut_choisi)
                                      <option value={{$le_statut_choisi->id}}><b>{{$le_statut_choisi->libele_statut}}</b></option>
                                  @endforeach
                                  <option value="c">Catégorie</option>
                                  @php
                                      $get = ($statutentreprisecontroller)->GetAll();
                                  @endphp
                                
                                  @foreach($get as $statut)
                                      <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                  @endforeach

                                 
                                @endif
                                
    
                                  
                              </select>   
                            </div>    
                            <div class="col-xs-3">
                              <select class="form-control" name="etat">
                                @if($etat == "c")
                                  <option value="c">Statut</option>
                                  <option value="0">Inactif</option>
                                  <option value="1">Actif</option>

                                @else
                                  @if($etat == "0")
                                
                                    <option value="0"><b>Inactif</b></option>
                                   <option value="1">Actif</option>
                                   
                                  <option value="c">Statut</option>
                                  @else
                                    <option value="1"><b>Actif</b></option>
                            
                                     <option value="0">Inactif</option>
                                    <option value="c">Statut</option>
                                  @endif
                                @endif
                              
                                 
                              </select>                         
                            </div>
                            <div class="col-xs-3">
                              <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                            </div>
                            
                          </div>
                          
                            
                            
                        
                        </div>
                        <!-- /.box-body -->
                      </form>
                  </div>
                  
                  <!-- /.box-header -->
                  <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover table-responsive">
                    <thead>
                    <tr>
                      <th>Nom</th>
                      <th>Adresse</th>
                      <th>Fiche</th>
                      <th>Interlocuteurs: </th>
                      <th>Modifier</th>
                      <th>Supprimer</th>
                      <th>Détails</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($entreprises as $entreprises)
                       
                          <tr>
                            <td>{{$entreprises->nom_entreprise}}</td>

                            <td>
                              {{$entreprises->adresse}}
                            </td>
                            <td>
                             @if($entreprises->id_statutentreprise == 2)
                              <form method="post" action="display_fiche_customer">
                                  @csrf
                                  <input type="text" value="{{$entreprises->id}}" style="display:none;" name="id_entreprise">
                                  <button class="btn btn-default"> <b>Fiche</b></button>
                              </form>
                             @endif
                            </td>
                            <td>
                              
                              <!--AFFICHAGE DES INTERLOCUTEURS AVEC POPUP-->
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#interlocuteurs".$entreprises->id.""; @endphp">
                              <i class="fa fa-eye"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "interlocuteurs".$entreprises->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Interlocuteurs</h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <div class="box-body">
                                            <table id="example1" class="table table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Nom & Prénom(s)</th>
                                                    <th>Téléphone</th>
                                                    <th>Email</th>
                                                    <th>Fonction</th>
                                      
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                @php
                                                   $interloc = DB::table('interlocuteurs')->where('id_entreprise', $entreprises->id)->get();
                                                @endphp
                                                <tbody>
                                                    @foreach($interloc as $interloc)
                                                        <tr>
                                                            
                                                            <td>{{$interloc->titre}} {{$interloc->nom}}</td>
                                                            <td>{{$interloc->tel}}</td>
                                                            <td>{{$interloc->email}}</td>
                                                            <td>{{$interloc->fonction}}</td>
                                                            
                                                          <td>
                                                                @if(auth()->user()->id_departement == 1)
                                                                    <form action="edit_interlocuteur_form" method="post">
                                                                        @csrf
                                                                        <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                        <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                    </form>
                                                                    @if(auth()->user()->id_role == 5)
                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                          <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                    @endif

                                                                    @if(auth()->user()->id_role == 3)
                                                                    
                                                                    
                                                                    @endif
                                                                    @if(auth()->user()->id_role == 1)

                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                  
                                                                    
                                                                    @endif
                                                                @else
                                                                  
                                                                    @if(auth()->user()->id_role == 5)
                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                    @endif

                                                                    @if(auth()->user()->id_role == 4)
                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                    @endif

                                                                    @if(auth()->user()->id_role == 3)
                                                                  
                                                                    
                                                                    @endif
                                                                    @if(auth()->user()->id_role == 1)

                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>

                                                                  
                                                                    
                                                                    @endif
                                                                @endif
                                                    
                                                              
                                                                
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>Nom & Prénom(s)</th>
                                                    <th>Téléphone</th>
                                                    <th>Email</th>
                                                    <th>Fonction</th>
                                                    <th>Action</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.box-body -->
                                      </div>
                                    
                                      <div class="modal-footer">
                                          <button type="button" class="btn  btn-primary pull-left" data-dismiss="modal">Fermer</button>
                                          
                                        </div>
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>
                              
                          
                            </td>
                            <td>
                               <!--MODIFICATION AVEC POPUP-->
                           
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#edit".$entreprises->id.""; @endphp">
                              <i class="fa fa-edit"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "edit".$entreprises->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Modifier </h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <form action="edit_entreprise_with_filter" method="post">
                                        @csrf
                                        <input type="text" name="id_entreprise" value="{{$entreprises->id}}" style="display:none;">
                           
                                            <!--LES VALEURS DU FILTRE ACTUELLE -->
                                            <div class="form-group">
                                              <select class="form-control" name="categorie" style="display:none;">
                              
                                                @if($categorie == "c")
                                                  <option value="c">Catégorie</option>
                                                  @php
                                                      $get = ($statutentreprisecontroller)->GetAll();
                                                  @endphp
                                                
                                                  @foreach($get as $statut)
                                                    <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                                      
                                                  @endforeach
                                                @else
                                                  @php
                                                    $le_statut_choisi = ($statutentreprisecontroller)->GetById($categorie);
                                                    
                                                  @endphp
                                                  
                                                  @foreach($le_statut_choisi as $le_statut_choisi)
                                                      <option value={{$le_statut_choisi->id}}><b>{{$le_statut_choisi->libele_statut}}</b></option>
                                                  @endforeach
                                                  <option value="c">Catégorie</option>
                                                  @php
                                                      $get = ($statutentreprisecontroller)->GetAll();
                                                  @endphp
                                                
                                                  @foreach($get as $statut)
                                                      <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                                  @endforeach

                                                
                                                @endif
                                              </select>   
                                            </div><br>

                                            <div class="form-group">
                                              <select class="form-control" name="etat" style="display:none;">
                                                @if($etat == "c")
                                                  <option value="c">Statut</option>
                                                  <option value="0">Inactif</option>
                                                  <option value="1">Actif</option>

                                                @else
                                                  @if($etat == 0)
                                                    <option value="0"><b>Inactif</b></option>
                                                  <option value="1">Actif</option>
                                                  
                                                  <option value="c">Statut</option>
                                                  @else
                                                    <option value="1"><b>Actif</b></option>
                                            
                                                    <option value="0">Inactif</option>
                                                    <option value="c">Statut</option>
                                                  @endif
                                                @endif
                                              
                                                
                                              </select>       
                                            </div> <br>
                                            <!--FIN FILTRE ACTUEL-->

                                            <div class="row">
                                              <div class="col-sm-6">  <label>Raison sociale :</label></div>
                                              <div class="col-md-6"><input type="text" class="form-control" value="{{$entreprises->nom_entreprise}}" name="nom_entreprise" onkeyup='this.value=this.value.toUpperCase()'  reuqired /></div>
                                            </div> <br>
                                            <div class=" row">
                                                 <div class="col-sm-6"><label>Statut:</label></div>
                                                @php

                                                    $statut = $statutentreprisecontroller->GetAll();
                        
                                                @endphp
                                                 <div class="col-sm-6">
                                                  <select class="form-control" name="statut" reuqired>
                                                      
                                                      <option value={{$entreprises->id_statutentreprise}}>{{$entreprises->libele_statut}}</option>
                                                      @foreach($statut as $statut)
                                                          <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                                          
                                                      @endforeach
                                                      
                                                  </select></div>
                                            </div>  <br>

                                            <div class=" row">
                                                 <div class="col-sm-6"><label>Adresse :</label></div>
                                                 <div class="col-sm-6"><input type="text" class="form-control" value="{{$entreprises->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" /></div>
                                            </div><br>
                                            <div class=" row">
                                              <div class="col-sm-6"><label >Téléphone (fixe/mobile):</label></div>
                                               <div class="col-sm-6"><input type="text"  maxlength="18" class="form-control" value="{{$entreprises->telephone}}" name="tel" placeholder="+225 27 47 54 45 68"></div>
                                            </div><br>
                                            <div class="row">
                                              <div class="col-sm-6"> <label >Chiffre d'affaire (FCFA):</label></div>
                                               <div class="col-sm-6"><input type="text" id="ca" value="{{$entreprises->chiffre_affaire}}"  maxlength="18" class="form-control" name="chiffre" placeholder="1000000"></div>
                                            </div><br>
                                            <div class="row">
                                               <div class="col-sm-6"><label >Nombre d'employés:</label></div>
                                               <div class="col-sm-6"><input type="text" id="ne" value="{{$entreprises->nb_employes}}" maxlength="18" class="form-control" name="nb_emp" placeholder="5"></div>
                                            </div><br>
                                            <div class=" row">
                                               <div class="col-sm-6"><label >Activité:</label></div>
                                               <div class="col-sm-6"><input type="text"  value="{{$entreprises->activite}}" maxlength="60" class="form-control" 
                                              name="activite" onkeyup='this.value=this.value.toUpperCase()'></div>
                                            </div><br>
                                            <div class=" row">
                                               <div class="col-sm-6"><label>Email:</label></div>
                                               <div class="col-sm-6"><input type="email" value="{{$entreprises->adresse_email}}"  maxlength="30" class="form-control" 
                                              name="email"></div>
                                            </div><br>
                                            <div class=" row">
                                                <div class="col-sm-6"><label>Pays :</label></div>
                                                 <div class="col-sm-6"><select class="form-control" name="pays">
                                                  <option value={{$entreprises->id_pays}}>{{$entreprises->nom_pays}}</option>
                                                    @php
                                                        $pays = $payscontroller->DisplayAll();
                                                    @endphp
                                                    @foreach($pays as $pays)
                                                        <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                                        
                                                    @endforeach
                                                    
                                                </select></div>
                                            </div><br>
                                            <div class=" row">
                                                 <div class="col-sm-6"><label>Client Depuis le :</label></div>
                                                 <div class="col-sm-6"><input type="date" class="form-control" 
                                                 value="{{$entreprises->client_depuis}}" name="depuis" /></div>
                                            </div><br>

                                            <div class="row">
                                              &ensp;&ensp;<label>Etat du client:</label>&ensp;&ensp;&ensp;
                                                  @if($entreprises->etat == 0)

                                                    <div class="radio">
                                                      <label>
                                                        <input type="radio" name="optionsradios" id="optionsRadios1" value="1" >
                                                        Actif
                                                      </label>
                                                    </div>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                                                    <div class="radio">
                                                      <label>
                                                        <input type="radio" name="optionsradios" id="optionsRadios2" value="0" checked>
                                                        Inactif
                                                      </label>
                                                    </div>
                                                  @else
                                               
                                                      <div class="radio">
                                                        <label>
                                                          <input type="radio" name="optionsradios" id="optionsRadios1" value="1" checked>
                                                          Actif
                                                        </label>
                                                      </div>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                                                      <div class="radio">
                                                        <label>
                                                          <input type="radio" name="optionsradios" id="optionsRadios2" value="0" >
                                                          Inactif
                                                        </label>
                                                      </div>
                                                  @endif
                                                  
                                            
                                            </div>
                                          <div class="modal-footer">
                                          <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                          <button type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                        </form>
                                      
                                      </div>
                                    
                                      
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>
                              <!-- /.modal -->
                          
                            </td>
                            <td>
                              <!--SUPPRESSION AVEC POPUP-->
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$entreprises->id.""; @endphp">
                                  <i class="fa fa-trash"></i>
                                </button>
                              <div class="modal modal-danger fade" id="@php echo "".$entreprises->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Supprimer </h4>
                                    </div>
                                    <form action="delete_entreprise" method="post">
                                      <div class="modal-body">
                                        <div class="">
                                          <select class="form-control" name="categorie" style="display:none;">
                                          
                                            @if($categorie == "c")
                                              <option value="c">Catégorie</option>
                                              @php
                                                  $get = ($statutentreprisecontroller)->GetAll();
                                              @endphp
                                            
                                              @foreach($get as $statut)
                                                <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                                  
                                              @endforeach
                                            @else
                                              @php
                                                $le_statut_choisi = ($statutentreprisecontroller)->GetById($categorie);
                                                
                                              @endphp
                                              
                                              @foreach($le_statut_choisi as $le_statut_choisi)
                                                  <option value={{$le_statut_choisi->id}}><b>{{$le_statut_choisi->libele_statut}}</b></option>
                                              @endforeach
                                              <option value="c">Catégorie</option>
                                              @php
                                                  $get = ($statutentreprisecontroller)->GetAll();
                                              @endphp
                                            
                                              @foreach($get as $statut)
                                                  <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                              @endforeach

                                            
                                            @endif
                                            
                
                                              
                                          </select>   
                                        </div>    
                                        <div class="">
                                          <select class="form-control" style="display:none;" name="etat">
                                            @if($etat == "c")
                                              <option value="c">Statut</option>
                                              <option value="0">Inactif</option>
                                              <option value="1">Actif</option>

                                            @else
                                              @if($etat == "0")
                                            
                                                <option value="0"><b>Inactif</b></option>
                                              <option value="1">Actif</option>
                                              
                                              <option value="c">Statut</option>
                                              @else
                                                <option value="1"><b>Actif</b></option>
                                        
                                                <option value="0">Inactif</option>
                                                <option value="c">Statut</option>
                                              @endif
                                            @endif
                                          
                                            
                                          </select>                         
                                        </div>
                                        TTTTT
                                        <p>Voulez-vous supprimer {{$entreprises->nom_entreprise}}?</p>
                                        @csrf
                                        <input type="text" value="{{$entreprises->id}}" style="display:none;" name="id_entreprise">
                                      </div>
                                    
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-outline">Supprimer</button>
                                      </div>
                                    </form>
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>
                              <!-- /.modal -->
                              
                            </td>
                            <td>

                               <!--DETAILS AVEC POPUP-->

                              <button type="button" class="btn btn-warning" data-toggle="modal" data-target="@php echo "#details".$entreprises->id.""; @endphp">
                              <i class="fa fa-eye"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "details".$entreprises->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Détails </h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <div class="box-body" style="text-align: center;">             
                                            <h4><label>Raison sociale:</label></h4>
                                            <p> {{$entreprises->nom_entreprise}} </p>

                                            <h4><label>Adresse :</label></h4>
                                            <p> {{$entreprises->adresse}} </p>
                                            <h4><label >Téléphone (fixe/mobile):</label></h4>
                                            <p> {{$entreprises->telephone}} </p>
                                            <h4><label >Email:</label></h4>
                                            <p> {{$entreprises->adresse_email}} </p>
                                            <h4><label >Chiffre d'affaire (FCFA):</label></h4>
                                            <p> {{$entreprises->chiffre_affaire}} </p>
                                            <h4><label >Nombre d'employés:</label></h4>
                                            <p> {{$entreprises->nb_employes}} </p>
                                            <h4><label >Activités:</label></h4>
                                            <p>{{$entreprises->activite}} </p>
                                            <h4><label>Pays :</label></h4>
                                            <p> {{$entreprises->nom_pays}}</p>

                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Fermer</button>
                                            
                                          </div>
                                            
                                        </div>
                                      
                                      </div>
                                    
                                      
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>                            
                            </td>
                          </tr>
                        @endforeach
                    </tbody>
                    
                    </table>
                  </div>
                  <!-- /.box-body -->
                </div>
                <!-- /.box -->
              </div>
          @else
              <div class="col-xs-12">
                <div class="box">
                  <div class="box-header">
                    <h3 class="box-title">Base des Entreprise</h3>
                   
                    <form role="form" method="post" action="make_filter_entreprise">
                      @csrf
                       <a href="entreprises" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp;&emsp;&emsp; <label>Filtrer par:</label>
                      <div class="box-body">
                        <div class="row">
                        
                          <div class="col-xs-3">
                            <select class="form-control" name="categorie">
                                <option value="c">Catégorie</option>
                                @php
                                    $get = ($statutentreprisecontroller)->GetAll();
                                @endphp
                              
                                @foreach($get as $statut)
                                    <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                    
                                @endforeach
                                
                            </select>   
                          </div>    

                          <div class="col-xs-3">
                      
                            <select class="form-control" name="etat">
                            
                                <option value="c">Statut</option>
                                <option value="0">Inactif</option>
                                <option value="1">Actif</option>
                            </select>
                                                        
                          </div>

                          <div class="col-xs-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                          </div>
                          
                        </div>
                        
                          
                          
                      
                      </div>
                      <!-- /.box-body -->
                    </form>
                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Nom</th>
                      <th>Adresse</th>
                      <th>Fiche</th>
                      <th>Interlocuteurs: </th>
                      <th>Modifier</th>
                      <th>Supprimer</th>
                      <th>Détails</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($all as $all)
                          <tr>
                            <td>{{$all->nom_entreprise}}</td>
                            
                
                            <td>
                            {{$all->adresse}}
                            </td>
                            <td>
                              @if($all->id_statutentreprise == 2)
                                <form method="post" action="display_fiche_customer">
                                    @csrf
                                    <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                                    <button class="btn btn-default"> <b>Fiche</b></button>
                                </form>
                              @endif
                            </td>
                            <td>

                              <!--AFFICHAGE DES INTERLOCUTEURS AVEC POPUP-->
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#interlocuteurs".$all->id.""; @endphp">
                              <i class="fa fa-eye"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "interlocuteurs".$all->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Interlocuteurs</h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <div class="box-body">
                                            <table id="example1" class="table table-bordered table-striped table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Nom & Prénom(s)</th>
                                                    <th>Téléphone</th>
                                                    <th>Email</th>
                                                    <th>Fonction</th>
                                      
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                @php
                                                   $interloc = DB::table('interlocuteurs')->where('id_entreprise', $all->id)->get();
                                                @endphp
                                                <tbody>
                                                    @foreach($interloc as $interloc)
                                                        <tr>
                                                            
                                                            <td>{{$interloc->titre}} {{$interloc->nom}}</td>
                                                            <td>{{$interloc->tel}}</td>
                                                            <td>{{$interloc->email}}</td>
                                                            <td>{{$interloc->fonction}}</td>
                                                            
                                                          <td>
                                                                @if(auth()->user()->id_departement == 1)
                                                                    <form action="edit_interlocuteur_form" method="post">
                                                                        @csrf
                                                                        <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                        <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                    </form>
                                                                    @if(auth()->user()->id_role == 5)
                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                          <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                    @endif

                                                                    @if(auth()->user()->id_role == 3)
                                                                    
                                                                    
                                                                    @endif
                                                                    @if(auth()->user()->id_role == 1)

                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                  
                                                                    
                                                                    @endif
                                                                @else
                                                                  
                                                                    @if(auth()->user()->id_role == 5)
                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                    @endif

                                                                    @if(auth()->user()->id_role == 4)
                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>
                                                                    @endif

                                                                    @if(auth()->user()->id_role == 3)
                                                                  
                                                                    
                                                                    @endif
                                                                    @if(auth()->user()->id_role == 1)

                                                                        <form action="edit_interlocuteur_form" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                                        </form>
                                                                        <form action="delete_interlocuteur" method="post">
                                                                            @csrf
                                                                            <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                                            <button type="submit" class="btn btn-danger"><i class ="fa fa-trash"></i></button>
                                                                        </form>

                                                                  
                                                                    
                                                                    @endif
                                                                @endif
                                                    
                                                              
                                                                
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                <tr>
                                                    <th>Nom & Prénom(s)</th>
                                                    <th>Téléphone</th>
                                                    <th>Email</th>
                                                    <th>Fonction</th>
                                                    <th>Action</th>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <!-- /.box-body -->
                                      </div>
                                    
                                      <div class="modal-footer">
                                          <button type="button" class="btn  btn-primary pull-left" data-dismiss="modal">Fermer</button>
                                          
                                        </div>
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>

                            </td>
                            <td>
                              <!--MODIFICATION AVEC POPUP-->
                           
                              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#edit".$all->id.""; @endphp">
                              <i class="fa fa-edit"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "edit".$all->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Modifier </h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <form action="edit_entreprise_with_filter" method="post">
                                        @csrf
                                        <input type="text" name="id_entreprise" value="{{$all->id}}" style="display:none;">
                           
                                            <!--LES VALEURS DU FILTRE ACTUELLE -->
                                            <div class="form-group">
                                               <select class="form-control" name="categorie" style="display:none;">
                                                  <option value="c">Catégorie</option>
                                                  @php
                                                      $get = ($statutentreprisecontroller)->GetAll();
                                                  @endphp
                                                
                                                  @foreach($get as $statut)
                                                      <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                                      
                                                  @endforeach
                                                  
                                              </select>   
                                            </div><br>

                                            <div class="form-group">
                                                <select class="form-control" name="etat" style="display:none;">
                            
                                                    <option value="c">Statut</option>
                                                    <option value="0">Inactif</option>
                                                    <option value="1">Actif</option>
                                                </select>
                                            </div> <br>
                                            <!--FIN FILTRE ACTUEL-->

                                            <div class="row">
                                              <div class="col-sm-6">  <label>Raison sociale :</label></div>
                                              <div class="col-md-6"><input type="text" class="form-control" value="{{$all->nom_entreprise}}" name="nom_entreprise" onkeyup='this.value=this.value.toUpperCase()'  reuqired /></div>
                                            </div> <br>
                                            <div class=" row">
                                                 <div class="col-sm-6"><label>Statut:</label></div>
                                                @php

                                                    $statut = $statutentreprisecontroller->GetAll();
                        
                                                @endphp
                                                 <div class="col-sm-6">
                                                  <select class="form-control" name="statut" reuqired>
                                                      
                                                      <option value={{$all->id_statutentreprise}}>{{$all->libele_statut}}</option>
                                                      @foreach($statut as $statut)
                                                          <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                                          
                                                      @endforeach
                                                      
                                                  </select></div>
                                            </div>  <br>

                                            <div class=" row">
                                                 <div class="col-sm-6"><label>Adresse :</label></div>
                                                 <div class="col-sm-6"><input type="text" class="form-control" value="{{$all->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" /></div>
                                            </div><br>
                                            <div class=" row">
                                              <div class="col-sm-6"><label >Téléphone (fixe/mobile):</label></div>
                                               <div class="col-sm-6"><input type="text"  maxlength="18" class="form-control" value="{{$all->telephone}}" name="tel" placeholder="+225 27 47 54 45 68"></div>
                                            </div><br>
                                            <div class="row">
                                              <div class="col-sm-6"> <label >Chiffre d'affaire (FCFA):</label></div>
                                               <div class="col-sm-6"><input type="text" id="ca" value="{{$all->chiffre_affaire}}"  maxlength="18" class="form-control" name="chiffre" placeholder="1000000"></div>
                                            </div><br>
                                            <div class="row">
                                               <div class="col-sm-6"><label >Nombre d'employés:</label></div>
                                               <div class="col-sm-6"><input type="text" id="ne" value="{{$all->nb_employes}}" maxlength="18" class="form-control" name="nb_emp" placeholder="5"></div>
                                            </div><br>
                                            <div class=" row">
                                               <div class="col-sm-6"><label >Activité:</label></div>
                                               <div class="col-sm-6"><input type="text"  value="{{$all->activite}}" maxlength="60" class="form-control" 
                                              name="activite" onkeyup='this.value=this.value.toUpperCase()'></div>
                                            </div><br>
                                            <div class=" row">
                                               <div class="col-sm-6"><label>Email:</label></div>
                                               <div class="col-sm-6"><input type="email" value="{{$all->adresse_email}}"  maxlength="30" class="form-control" 
                                              name="email"></div>
                                            </div><br>
                                            <div class=" row">
                                                <div class="col-sm-6"><label>Pays :</label></div>
                                                 <div class="col-sm-6"><select class="form-control" name="pays">
                                                  <option value={{$all->id_pays}}>{{$all->nom_pays}}</option>
                                                    @php
                                                        $pays = $payscontroller->DisplayAll();
                                                    @endphp
                                                    @foreach($pays as $pays)
                                                        <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                                        
                                                    @endforeach
                                                    
                                                </select></div>
                                            </div><br>
                                            <div class=" row">
                                                 <div class="col-sm-6"><label>Client Depuis le :</label></div>
                                                 <div class="col-sm-6"><input type="date" class="form-control" 
                                                 value="{{$all->client_depuis}}" name="depuis" /></div>
                                            </div><br>

                                            <div class="row">
                                              &ensp;&ensp;<label>Etat du client:</label>&ensp;&ensp;&ensp;
                                                  @if($all->etat == 0)

                                                    <div class="radio">
                                                      <label>
                                                        <input type="radio" name="optionsradios" id="optionsRadios1" value="1" >
                                                        Actif
                                                      </label>
                                                    </div>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                                                    <div class="radio">
                                                      <label>
                                                        <input type="radio" name="optionsradios" id="optionsRadios2" value="0" checked>
                                                        Inactif
                                                      </label>
                                                    </div>
                                                  @else
                                               
                                                      <div class="radio">
                                                        <label>
                                                          <input type="radio" name="optionsradios" id="optionsRadios1" value="1" checked>
                                                          Actif
                                                        </label>
                                                      </div>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;
                                                      <div class="radio">
                                                        <label>
                                                          <input type="radio" name="optionsradios" id="optionsRadios2" value="0" >
                                                          Inactif
                                                        </label>
                                                      </div>
                                                  @endif
                                                  
                                            
                                            </div>
                                          <div class="modal-footer">
                                          <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                          <button type="submit" class="btn btn-primary">Modifier</button>
                                        </div>
                                        </form>
                                      
                                      </div>
                                    
                                      
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>
                              <!-- /.modal -->
                            </td>
                            <td>
                              <!--SUPPRESSION AVEC POPUP-->
                               <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$all->id.""; @endphp">
                                  <i class="fa fa-trash"></i>
                                </button>
                              <div class="modal modal-danger fade" id="@php echo "".$all->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Supprimer </h4>
                                    </div>
                                    <form action="delete_entreprise" method="post">
                                      <div class="modal-body">
                                              <div class="">
                                              <select class="form-control" style="display:none;" name="categorie">
                                                  <option value="c">Catégorie</option>
                                                  @php
                                                      $get = ($statutentreprisecontroller)->GetAll();
                                                  @endphp
                                                
                                                  @foreach($get as $statut)
                                                      <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                                      
                                                  @endforeach
                                                  
                                              </select>   
                                            </div>    

                                            <div class="">
                                        
                                              <select class="form-control" name="etat" style="display:none;">
                                              
                                                  <option value="c">Statut</option>
                                                  <option value="0">Inactif</option>
                                                  <option value="1">Actif</option>
                                              </select>
                                                                          
                                            </div>

                                        <p>Voulez-vous supprimer {{$all->nom_entreprise}}?</p>
                                        @csrf
                                        <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                                      </div>
                                    
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fermer</button>
                                        <button type="submit" class="btn btn-outline">Supprimer</button>
                                      </div>
                                    </form>
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>
                              <!-- /.modal -->
                              
                              
                            </td>
                            <td>
                              <!--DETAILS AVEC POPUP-->

                              <button type="button" class="btn btn-warning" data-toggle="modal" data-target="@php echo "#details".$all->id.""; @endphp">
                              <i class="fa fa-eye"></i>
                              </button>
                              <div class="modal modal-default fade" id="@php echo "details".$all->id.""; @endphp">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title">Détails </h4>
                                    </div>
                                    
                                      <div class="modal-body">
                                        <div class="box-body" style="text-align: center;">             
                                            <h4><label>Raison sociale:</label></h4>
                                            <p> {{$all->nom_entreprise}} </p>

                                            <h4><label>Adresse :</label></h4>
                                            <p> {{$all->adresse}} </p>
                                            <h4><label >Téléphone (fixe/mobile):</label></h4>
                                            <p> {{$all->telephone}} </p>
                                            <h4><label >Email:</label></h4>
                                            <p> {{$all->adresse_email}} </p>
                                            <h4><label >Chiffre d'affaire (FCFA):</label></h4>
                                            <p> {{$all->chiffre_affaire}} </p>
                                            <h4><label >Nombre d'employés:</label></h4>
                                            <p> {{$all->nb_employes}} </p>
                                            <h4><label >Activités:</label></h4>
                                            <p>{{$all->activite}} </p>
                                            <h4><label>Pays :</label></h4>
                                            <p> {{$all->nom_pays}}</p>

                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Fermer</button>
                                            
                                          </div>
                                            
                                        </div>
                                      
                                      </div>
                                    
                                      
                                  </div>
                                  <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                              </div>

                          
                            </td>
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
          @endif
             
           
      </div>
      <!-- /.row -->
      
   
		<div class="row">
      
        <div class="col-md-6">
       
        </div>
        <!--/.col (left) -->
        

        <!-- right column -->
        <div class="col-md-6">
        </div>
          <!-- /.box -->
		  
    </div>
    <!--/.col (right) -->



        

@endsection