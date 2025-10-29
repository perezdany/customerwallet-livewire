@extends('layouts/base')

@php

  use App\Http\Controllers\PaysController;

    use App\Http\Controllers\CibleController;
    use App\Http\Controllers\EntrepriseController;

  $entreprisecontroller = new EntrepriseController();

  $ciblecontroller = new CibleController();

  $payscontroller = new PaysController();

  $all = $ciblecontroller->GetAll();

  
@endphp

@section('content')
  <div class="modal modal-default fade" id="add">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Ajouter un interlocuteur </h4>
        </div>
        
        <div class="modal-body">
            <!-- form start -->
            <form action="add_referant_in_fiche_customer" method="post">  
                @csrf
            
                <!-- /.card-body -->
                <div class="card-body">
                @csrf
                    <div class="card-header">
                        <h3 class="card-title"><b>AJOUTER UN INTERLOCUTEUR </b></h3>
                    </div> 

                    <div class="form-group">
                      <select class="form-control " name="entreprise">
                          @php
                              $get = $ciblecontroller->GetAll();
                          @endphp
                          <option value="0">--Selectionnez Une entreprise--</option>
                          @foreach($get as $entreprise)
                              <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                              
                          @endforeach
                          
                      </select>
                    </div>        

                    <div class="form-group">
                        <label for="exampleInputFile">Titre :</label>
                        <select class="form-control " name="titre" id="grise1" >
                            <option value="M">M</option>
                            <option value="Mme">Mme</option>
                            <option value="Mlle">Mlle</option>
                        </select>
                        
                    </div>
                    <div class="form-group">
                            <label>Nom & Prénom(s)</label>
                            <input type="text" maxlength="100" required id="grise2" class="form-control  " name="nom" onkeyup="this.value=this.value.toUpperCase()">
                    </div>

                    <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="grise5" maxlength="30" class="form-control " name="email" >
                    </div>

                    <div class="form-group">
                            <label>Téléphone (*)</label>
                            <input type="text" required id="grise3" maxlength="30"   class="form-control " name="tel" placeholder="(+225)0214578931" >
                        </div>

                        <div class="form-group">
                            <label>Fonction (Choisir "Autre" si inexistant)</label>
                                <select class="form-control"  onchange="newFonction();" name="fonction" id="grise4" required>
                                @php
                                    $f = DB::table('professions')->orderBy('id', 'asc')->get();
                                @endphp
                                @foreach($f as $f)
                                    <option value="{{$f->id}}">{{$f->intitule}}</option>
                                @endforeach
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="form-group">
                                <label>Fonction:(*)</label>
                                <input type="text" disabled="disabled" required id="newf" maxlength="60"   class="form-control " name="new_fonction" onkeyup="this.value=this.value.toUpperCase()" >
                        </div>  
                    </div>
                        <script>
                            function newFonction()
                            {
                                
                                var f = document.getElementById("grise4").value;
                                //alert(f);
                                if(f == 'autre')
                                {
                                    document.getElementById("newf").removeAttribute("disabled");
                                }
                                else{
                                    document.getElementById("newf").setAttribute("disabled", "disabled");
                                }
                            }
                        </script>

                

                <div class="modal-footer">
                    <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
                            
        
        </div>
      
      </div>
      <!-- /.modal-content -->
    </div>
  </div> 
  <!-- /.modal-dialog -->
  <div class="row">
    <div class="col-md-3">
      <a href="form_add_cible"><button class="btn btn-success"> <b><i class="fa fa-plus"></i> CIBLE</b></button></a>   
    </div>  

    @can("edit")
      <div class="col-md-3">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add"><b><i class="fa fa-plus"></i>INTERLOCUTEUR</b></button>
      </div>
    @endcan 
  </div>
  
  <div class="row">
    
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Les Cibles</h3>
            </div>
            <!-- /.card-header -->
              <div class="card-body table-responsive">
              <table id="example1" class="table table-bordered table-striped table-hover">
              <thead>
              <tr>
                <th>Nom</th>
                <th>Activité</th>
                <th>Adresse</th>
                <th>Chiffre d'Affaire</th>
                <th>Interlocuteurs</th>
                <th>Details</th>
                <th>Mod</th>
                <th>Sup</th>
              </tr>
              </thead>
              <tbody>
                  @foreach($all as $all)
                    <tr>
                      <td>{{$all->nom_entreprise}}</td>
                      <td>{{$all->activite}}</td>
                      <td>{{$all->adresse}}</td>
                      <td>{{$all->chiffre_affaire}}</td>
                      <td>
                        <form action="cible_display_by_id_entreprise" method="post">
                            @csrf
                            <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                            <button type="submit" class="btn btn-success"><i class="fa fa-eye"></i></button>
                        </form>
                        
                      </td>
                      <td>
                        @php
                          $edit =  $ciblecontroller->GetById($all->id);  
                        @endphp
                        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="@php echo "#detail".$all->id.""; @endphp">
                          <i class="fa fa-eye"></i>
                        </button>
                        <div class="modal modal-default fade" id="@php echo "detail".$all->id.""; @endphp">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Details de {{$all->nom_entreprise}} </h4>
                              </div>
                              <div class="modal-body">
                                <div class="card-body">
                                  @foreach($edit as $edit)
                                    <div class="form-group">
                                        <h4><label>Nom:</label></h4>
                                        <p> {{$edit->nom_entreprise}} </p>
                                    </div> <br>
                                    
                                    <div class="form-group">
                                        <h4><label>Adresse :</label></h4>
                                        <p> {{$edit->adresse}} </p>
                                    </div><br>

                                    <div class="form-group">
                                      <h4><label >Téléphone fixe:</label></h4>
                                      <p> {{$edit->telephone}} </p>
                                    </div><br>
                                    <div class="form-group">
                                      <h4><label >Téléphone mobile:</label></h4>
                                      <p> {{$edit->mobile}} </p>
                                    </div><br>
                                    @if($edit->particulier == 0)
                                      <div class="form-group">
                                        <h4><label >Dirigeant:</label></h4>
                                        <p> {{$edit->dirigeant}} </p>
                                      </div><br>
                                      <div class="form-group">
                                        <h4><label >Chiffre d'affaire (FCFA):</label></h4>
                                        <p> {{$edit->chiffre_affaire}} </p>
                                      </div><br>

                                      <div class="form-group">
                                        <h4><label >Nombre d'employés:</label></h4>
                                        <p> {{$edit->nb_employes}} </p>
                                      </div><br>

                                      <div class="form-group">
                                        <h4><label >Activité:</label></h4>
                                        <p>{{$edit->activite}} </p>
                                      </div><br>
                                      <div class="form-group">
                                        <h4><label>Pays :</label></h4>
                                        <p> {{$edit->nom_pays}}</p>
                                            
                                      </div><br>
                                    @else
                                      
                                       <div class="form-group">
                                        <h4><label >Profession:</label></h4>
                                        <p>{{$edit->activite}} </p>
                                      </div><br>
                                      <div class="form-group">
                                        <h4><label>Nationnalité:</label></h4>
                                        <p> {{$edit->nom_pays}}</p>
                                            
                                      </div><br>
                                    @endif
                                    
                                  @endforeach
                                </div>
                                
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Fermer</button>
                              </div>
                            </div>
                            <!-- /.modal-content -->
                          
                          </div>
                          <!-- /.modal-dialog -->
                          
                        </div>
                        <!-- /.modal -->
                      </td>
                      <td>
                        @can("edit")
                          <form action="edit_cible_form" method="post">
                            @csrf
                            <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                          </form>
                        @endcan 
                        
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
                              <form action="delete_cible" method="post">
                                <div class="modal-body">
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
                    </tr>
                  @endforeach
              </tbody>
              
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col --> 

        <div class="col-md-6">
          <!-- LES INTERLOCUTUEURS -->
          <!-- Afficher les interlocuteurs de l'entreprise sélectionnée -->
          @if(isset($interloc))
              
            <div class="card">
                <div class="card-header with-border">
                <h3 class="card-title">INTERLOCUTEURS</h3><br>

                  <div class="card-tools pull-right">
                        <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-card-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
            </div>
            
                  <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Nom & Prénom(s)</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Fonction</th>
                        <th>Mod</th>
                        <th>Supp</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($interloc as $interloc)
                        <tr>
                            
                          <td>{{$interloc->titre}} {{$interloc->nom}}</td>
                          <td>{{$interloc->tel}}</td>
                          <td>{{$interloc->email}}</td>
                          <td>{{$interloc->intitule}}</td>
                          
                          <td>
                            @can("edit")

                                <form action="edit_interlocuteur_form" method="post">
                                    @csrf
                                    <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                </form>                          
                            @endcan
                          
                          </td>
                          <td>
                                
                            @can("delete")
                              <!--SUPPRESSION AVEC POPUP-->
                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interloc->id.""; @endphp">
                                    <i class="fa fa-trash"></i>
                                  </button>
                                <div class="modal modal-danger fade" id="@php echo "interloc".$interloc->id.""; @endphp">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Supprimer </h4>
                                      </div>
                                        <form action="delete_interlocuteur" method="post">
                                        <div class="modal-body">
                                          <p>Voulez-vous supprimer {{$interloc->nom}}?</p>
                                          @csrf
                                          <input type="text" value="{{$interloc->id}}" style="display:none;" name="id_entreprise">
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
                            @endcan
                          </td>

                        </tr>
                      @endforeach
                    </tbody>
                    
                </table>
            </div>
            <!-- /.card-body -->
              
          @endif

          <!--FORMULAIRE DE MODIF -->
          <!-- general form elements -->
          @if(isset($id_entreprise))
              @php
                  $edit =  $ciblecontroller->GetById($id_entreprise);
                  
              @endphp
              @foreach($edit as $edit)
                  <div class="card card-aeneas">
                      <div class="card-header with-border">
                        <h3 class="card-title">MODIFIER UNE CIBLE</h3><br>

                        <div class="card-tools pull-right">
                            <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-card-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                  
                      <!-- form start -->
                      <form role="form" method="post" action="edit_entreprise_cible">
                        <div class="card-body">
                          @csrf
                          <input type="text" name="id_entreprise" value="{{$edit->id}}" style="display:none;">
                          
                          <div class="card-body">
                          
                            <div class="form-group">
                                <label>Nom :</label>
                                <input type="text" maxlength="50" class="form-control " value="{{$edit->nom_entreprise}}" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                            </div> 
                            
                            <div class="form-group">
                                <label>Adresse :</label>
                                <input type="text" class="form-control " value="{{$edit->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" />
                            </div>

                            <div class="form-group">
                                <label>Année de création:</label>
                           
                                <select class="" id="date_creation" name="date_creation">
                                    <option value="">Choisir</option>
                                     <option value="$edit->date_creation">{{$edit->date_creation}}</option>
                                    @php
                                        $annee_fin = "2060";
                                        for($annee="1980"; $annee<=$annee_fin; $annee++)
                                        {
                                            echo'<option value='.$annee.'>'.$annee.'</option>';
                                        }
                                    @endphp
                                    
                                </select>   
                            </div>
                            <div class="form-group">
                              <label >Téléphone fixe:</label>
                              <input type="text"  maxlength="60" class="form-control  " value="{{$edit->telephone}}" name="tel" placeholder="+225 27 47 54 45 68">
                            </div>

                             <div class="form-group">
                              <label >Téléphone mobile:</label>
                              <input type="text"  maxlength="60" class="form-control  " value="{{$edit->telephone}}" name="mobile" placeholder="+225 27 47 54 45 68">
                            </div>

                            <div class="form-group">
                              <label>Email:</label>
                              <input type="email"  maxlength="30" class="form-control  " value="{{$edit->adresse_email}}" name="email">
                            </div>
                         
                            @if($edit->particulier == "0")
                              <div class="form-group">
                                <label >Nom du dirigeant:</label>
                                <input type="tex" id="dirigeant" value="{{$edit->dirigeant}}"  maxlength="150" class="form-control  " name="dirigeant" onkeyup='this.value=this.value.toUpperCase()'>
                              </div>
                              <div class="form-group">
                              <select class="form-control " name="particulier" id="particulier" onchange="EnableFields();" style="display:none;">
                                    <option value="0">OUI</option>
                                  </select>
                              </div>  
                              <div class="form-group">
                                <label >Chiffre d'affaire (FCFA):</label>
                                <input type="tex" id="ca" value="{{$edit->chiffre_affaire}}"  maxlength="18" class="form-control  " name="chiffre" placeholder="1000000">
                              </div>

                              <div class="form-group">
                                <label >Nombre d'employés:</label>
                                <input type="text" id="ne" value="{{$edit->nb_employes}}" maxlength="18" class="form-control  " name="nb_emp" placeholder="5">
                              </div>

                              <div class="form-group">
                                <label >Objet Social/Activités:</label>
                                <input type="text" value="{{$edit->activite}}" maxlength="100" class="form-control  " name="activite" 
                                placeholder="TRANSIT" onkeyup="this.value=this.value.toUpperCase()">
                              </div>

                              <div class="form-group">
                                  <label>Pays :</label>
                                  <select class="form-control " name="pays" required>
                                    <option value={{$edit->id_pays}}>{{$edit->nom_pays}}</option>
                                      @php
                                          $pays = $payscontroller->DisplayAll();
                                      @endphp
                                      @foreach($pays as $pays)
                                          <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                          
                                      @endforeach
                                      
                                  </select>
                              </div>
                            @else
                              <div class="form-group">
                                  <select class="form-control " name="particulier" id="particulier" onchange="EnableFields();" style="display:none;">
                                    <option value="1">OUI</option>
                                  </select>
                              </div>  
                              <div class="form-group">
                                <label >Profession:</label>
                                <input type="text" value="{{$edit->activite}}" maxlength="100" class="form-control  " name="activite" 
                                placeholder="TRANSIT" onkeyup="this.value=this.value.toUpperCase()">
                              </div>
                              <div class="form-group">
                                  <label>Nationnalité :</label>
                                  <select class="form-control " name="pays" required>
                                    <option value={{$edit->id_pays}}>{{$edit->nom_pays}}</option>
                                      @php
                                          $pays = $payscontroller->DisplayAll();
                                      @endphp
                                      @foreach($pays as $pays)
                                          <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                          
                                      @endforeach
                                      
                                  </select>
                              </div>
                            @endif

                              <div class="card-footer">
                                  <button type="submit" class="btn btn-primary">VALIDER</button>
                              </div>
                          </div>
                        </div>  <!-- /.card-body -->
                        
                      </form>
                  </div>
              @endforeach
              
          @endif


          <!-- AFFICHAGE RECAPITULATIF-->
          @if(isset($display_entreprise))
              @php
                $edit =  $ciblecontroller->GetById($display_entreprise);  
              @endphp
              @foreach($edit as $edit)
                  <div class="card card-aeneas">
                      <div class="card-header with-border">
                        <h3 class="card-title">INFO</h3><br>

                        <div class="card-tools pull-right">
                            <button type="button" class="btn btn-card-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-card-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                      </div>
                  
                      <!-- form start -->
                      <form role="form" >
                        <div class="card-body" >
                          @csrf
                          <input type="text" name="id_entreprise" value="{{$edit->id}}" style="display:none;">
                          <div class="card-body" style="text-align: center;">
                          
                            <div class="form-group">
                                <h4><label>Raison sociale:</label></h4>
                                <p> {{$edit->nom_entreprise}} </p>
                            </div> 
                            

                            <div class="form-group">
                                <h4><label>Adresse :</label></h4>
                                <p> {{$edit->adresse}} </p>
                            </div>

                        
                            <div class="form-group">
                              <h4><label >Téléphone (fixe/mobile):</label></h4>
                              <p> {{$edit->telephone}} </p>
                            </div>
                  
                            <div class="form-group">
                              <h4><label >Chiffre d'affaire (FCFA):</label></h4>
                              <p> {{$edit->chiffre_affaire}} </p>
                            </div>

                            <div class="form-group">
                              <h4><label >Nombre d'employés:</label></h4>
                              <p> {{$edit->nb_employes}} </p>
                            </div>

                            <div class="form-group">
                              <h4><label >Activités:</label></h4>
                              <p>{{$edit->activite}} </p>
                            </div>

                            <div class="form-group">
                                <h4><label>Pays :</label></h4>
                                <p> {{$edit->nom_pays}}</p>
                                    
                            </div>

                              
                          </div>
                        </div>  <!-- /.card-body -->
                        
                      </form>
                  </div>
              @endforeach
              
          @endif

        </div>
  </div>
  <!-- /.row -->
  <div class="row"></div>
  <div class="row">
      <!-- right column -->
     
        <!-- /.card -->
    
  </div>
  <!--/.col (right) -->
  <!--/.col (right) -->
@endsection