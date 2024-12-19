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
      <div class="row">
          <div class="col-md-3">
              <a href="form_add_cible"><button class="btn btn-success"> <b><i class="fa fa-plus"></i> CIBLE</b></button></a>
                    
            </div>   
      </div>
      <div class="row">
          @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-green" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
          @if(session('error'))
            <div class="col-md-12 box-header">
              <p class="bg-warning" style="font-size:13px;">{{session('error')}}</p>
            </div>
          @endif
      
            <div class="col-md-6">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Entreprise Cibles</h3>
                </div>
                <!-- /.box-header -->
                 <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Raison sociale</th>
                    <th>Activité</th>
                    <th>Adresse</th>
                    <th>Chiffre d'Affaire</th>
                    <th>Interlocuteurs</th>
                   
                      <th>Action</th>
       
                    
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
                                <button type="submit" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                            </form>
                          </td>
                          <td>
                            @if(auth()->user()->id_role != NULL)
                              
                            @endif
                              <form action="edit_cible_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                              </form>
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
                            
                              <form action="display_info" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i></button>
                              </form>
                           
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

            <div class="col-md-6">
              <!-- LES INTERLOCUTUEURS -->
              <!-- Afficher les interlocuteurs de l'entreprise sélectionnée -->
              @if(isset($interloc))
                  
                <div class="box">
                    <div class="box-header with-border">
                    <h3 class="box-title">INTERLOCUTEURS</h3><br>

                      <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                        </div>
                </div>
                
                      <!-- /.box-header -->
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
                                                 <!--SUPPRESSION AVEC POPUP-->
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interloc->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                  </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$interloc->id.""; @endphp">
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
                                            @endif

                                            @if(auth()->user()->id_role == 3)
                                            
                                            
                                            @endif
                                            @if(auth()->user()->id_role == 1)

                                                <form action="edit_interlocuteur_form" method="post">
                                                    @csrf
                                                    <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                </form>
                                                 <!--SUPPRESSION AVEC POPUP-->
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interloc->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                  </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$interloc->id.""; @endphp">
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
                                              
                                            @endif
                                        @else
                                            
                                            @if(auth()->user()->id_role == 5)
                                                <form action="edit_interlocuteur_form" method="post">
                                                    @csrf
                                                    <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                </form>
                                                  <!--SUPPRESSION AVEC POPUP-->
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interloc->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                  </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$interloc->id.""; @endphp">
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
                                            @endif

                                            @if(auth()->user()->id_role == 4)
                                                <form action="edit_interlocuteur_form" method="post">
                                                    @csrf
                                                    <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                </form>
                                                 <!--SUPPRESSION AVEC POPUP-->
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interloc->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                  </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$interloc->id.""; @endphp">
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
                                            @endif

                                            @if(auth()->user()->id_role == 3)
                                            
                                            
                                            @endif
                                            @if(auth()->user()->id_role == 1)

                                                <form action="edit_interlocuteur_form" method="post">
                                                    @csrf
                                                    <input type="text" value={{$interloc->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                </form>
                                                 <!--SUPPRESSION AVEC POPUP-->
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#interloc".$interloc->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                  </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$interloc->id.""; @endphp">
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
                  
              @endif

              <!--FORMULAIRE DE MODIF -->
              <!-- general form elements -->
              @if(isset($id_entreprise))
                  @php
                      $edit =  $ciblecontroller->GetById($id_entreprise);
                      
                  @endphp
                  @foreach($edit as $edit)
                      <div class="box box-aeneas">
                          <div class="box-header with-border">
                            <h3 class="box-title">MODIFIER UNE ENTREPRISE CIBLE</h3><br>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                          </div>
                      
                          <!-- form start -->
                          <form role="form" method="post" action="edit_entreprise_cible">
                            <div class="box-body">
                              @csrf
                              <input type="text" name="id_entreprise" value="{{$edit->id}}" style="display:none;">
                              <div class="box-body">
                              
                                <div class="form-group">
                                    <label>Identité :</label>
                                    <input type="text" maxlength="50" class="form-control input-lg" value="{{$edit->nom_entreprise}}" name="nom" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                                </div> 
                                

                                <div class="form-group">
                                    <label>Adresse :</label>
                                    <input type="text" class="form-control input-lg" value="{{$edit->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" />
                                </div>

                            
                                <div class="form-group">
                                  <label >Téléphone (fixe/mobile):</label>
                                  <input type="text"  maxlength="60" class="form-control  input-lg" value="{{$edit->telephone}}" name="tel" placeholder="+225 27 47 54 45 68">
                                </div>
                      
                                <div class="form-group">
                                  <label >Chiffre d'affaire (FCFA):</label>
                                  <input type="tex" id="ca" value="{{$edit->chiffre_affaire}}"  maxlength="18" class="form-control  input-lg" name="chiffre" placeholder="1000000">
                                </div>

                                <div class="form-group">
                                  <label >Nombre d'employés:</label>
                                  <input type="text" id="ne" value="{{$edit->nb_employes}}" maxlength="18" class="form-control  input-lg" name="nb_emp" placeholder="5">
                                </div>

                                <div class="form-group">
                                  <label >Objet Social/Activités:</label>
                                  <input type="text" value="{{$edit->activite}}" maxlength="100" class="form-control  input-lg" name="activite" 
                                  placeholder="TRANSIT" onkeyup="this.value=this.value.toUpperCase()">
                                </div>

                                  <div class="form-group">
                                    <label>Email:</label>
                                    <input type="email"  maxlength="30" class="form-control  input-lg" value="{{$edit->adresse_email}}" name="email">
                                  </div>

                                <div class="form-group">
                                    <label>Pays :</label>
                                    <select class="form-control input-lg" name="pays" required>
                                      <option value={{$edit->id_pays}}>{{$edit->nom_pays}}</option>
                                        @php
                                            $pays = $payscontroller->DisplayAll();
                                        @endphp
                                        @foreach($pays as $pays)
                                            <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                            
                                        @endforeach
                                        
                                    </select>
                                </div>

                                  <div class="box-footer">
                                      <button type="submit" class="btn btn-primary">VALIDER</button>
                                  </div>
                              </div>
                            </div>  <!-- /.box-body -->
                            
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
                      <div class="box box-aeneas">
                          <div class="box-header with-border">
                            <h3 class="box-title">INFO</h3><br>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                          </div>
                      
                          <!-- form start -->
                          <form role="form" >
                            <div class="box-body" >
                              @csrf
                              <input type="text" name="id_entreprise" value="{{$edit->id}}" style="display:none;">
                              <div class="box-body" style="text-align: center;">
                              
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
                            </div>  <!-- /.box-body -->
                            
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
        <div class="col-md-6">
            <div class="box box-aeneas">
                <div class="box-header with-border">
                    <h3 class="box-title"><b>ENREGISTRER UN INTERLOCUTEUR</b> </h3><br>
                    <b>(*)champ obligatoire</b>
                </div>
            
                <!-- form start -->
                <form role="form" action="add_referant_cible" method="post">
                    @csrf
                    <div class="box-body">
                        
                        <div class="box-header">
                            <b><h3 class="box-title">L'ENTREPRISE</h3></b>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">Choisissez l'entreprise :</label>
                            <select class="form-control input-lg" name="entreprise">
                                @php
                                    $get = $ciblecontroller->GetAll();
                                @endphp
                                <option value="0">--Selectionnez Une entreprise--</option>
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>
                            
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        
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
          <!-- /.box -->
        </div>
          <!-- /.box -->
		  
    </div>
    <!--/.col (right) -->
      <!--/.col (right) -->
@endsection