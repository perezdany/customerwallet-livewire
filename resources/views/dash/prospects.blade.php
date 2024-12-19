@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\StatutEntrepriseController;

    use App\Http\Controllers\PaysController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $all = $entreprisecontroller->DisplayProspects();

    $payscontroller = new PaysController();
     

    
@endphp

@section('content')

      @if(auth()->user()->id_role != NULL)
        <div class="row">
            <div class="col-md-3">
                <a href="form_add_prospection"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i>PROSPECTION</b></button></a>
                
            </div>
            <div class="col-md-3">
                <a href="form_add_prospect"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i> PROSPECT</b></button></a>
                
            </div>
        </div>           
      
      @endif
  
      <div class="row">
         @if(session('success'))
            <div class="col-xs-12 box-header">
              <p class="bg-green" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
            <div class="col-xs-6">
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Tableaux des prospects</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Chiffre d'Affaire</th>
                    <th>Nombre d'employés</th>
                    <th>Date d'ajout</th>
                    <th>Ajouté par</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($all as $all)
                    <tr>
                        <td>
                          <form method="post" action="display_about_prospect">
                            @csrf
                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                            <button class="btn btn-default"> <b>{{$all->nom_entreprise}}</b></button>
                          </form>
                        </td>

                        <td>
                            {{$all->chiffre_affaire}}
                        </td>
                        <td>
                            {{$all->nb_employes}}  
                        </td>

                        <td>
                            @php 
                            
                                echo "<b>".date('d/m/Y',strtotime($all->created_at))."</b> à <b>".date('H:i:s',strtotime($all->created_at))."</b>" ;
                         
                            @endphp
                        </td>
                        <td>{{$all->nom_prenoms}}</td>  

                        <td>
                          @if(auth()->user()->id_departement == 1)
                            <form action="display_prosp" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i></button>
                            </form>
                            @if(auth()->user()->id_role == 5)
                              <form action="edit_entreprise_prosp_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                              </form>
                              <!--SUPPRESSION AVEC POPUP-->
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$all->id.""; @endphp">
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
                                    <form action="delete_prospect" method="post">
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
                             
                            @endif

                             @if(auth()->user()->id_role == 3)
                              <form action="edit_entreprise_prosp_form" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                              </form>
                              
                            @endif
                            @if(auth()->user()->id_role == 1)

                              <form action="display_prosp" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i></button>
                              </form>

                              <form action="edit_entreprise_prosp_form" method="post">
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
                                    <form action="delete_prospect" method="post">
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
                              
                            @endif
                          @else
                             <form action="display_prosp" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i></button>
                            </form>
                            @if(auth()->user()->id_role == 5)
                              <form action="edit_entreprise_prosp_form" method="post">
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
                                    <form action="delete_prospect" method="post">
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
                            @endif

                            @if(auth()->user()->id_role == 4)
                              <form action="edit_entreprise_prosp_form" method="post">
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
                                    <form action="delete_prospect" method="post">
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
                            @endif

                            @if(auth()->user()->id_role == 3)
                              <form action="display_prosp" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i></button>
                              </form>
                              
                            @endif
                            @if(auth()->user()->id_role == 1)

                              <form action="display_prosp" method="post">
                                @csrf
                                <input type="text" value={{$all->id}} style="display:none;" name="id_entreprise">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-eye"></i></button>
                              </form>

                              <form action="edit_entreprise_prosp_form" method="post">
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
                                    <form action="delete_prospect" method="post">
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
                              
                            @endif
                          @endif
                            
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

              @if(auth()->user()->id_role != NULL)
                 <!-- general form elements -->
                <div class="col-xs-6">      
                      @if(isset($id_entreprise))
                        @php
                            $edit =  $entreprisecontroller->GetById($id_entreprise);
                        @endphp
                        @foreach($edit as $edit)
                            <div class="box box-aeneas">
                                <div class="box-header with-border">
                                  <h3 class="box-title">MODIFIER UN PROSPECT</h3><br>

                                  <div class="box-tools pull-right">
                                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                      </button>
                                      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                                  </div>
                                </div>
                            
                                <!-- form start -->
                                <form role="form" method="post" action="edit_prospect">
                                  <div class="box-body">
                                    @csrf
                                    <input type="text" name="id_entreprise" value="{{$edit->id}}" style="display:none;">
                                    <div class="box-body">
                                    
                                        <div class="form-group">
                                            <label>Désignation :</label>
                                            <input type="text" class="form-control input-lg" value="{{$edit->nom_entreprise}}" name="nom_entreprise" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                                        </div> 
                                      

                                        <div class="form-group">
                                            <label>Adresse :</label>
                                            <input type="text" class="form-control input-lg" value="{{$edit->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" />
                                        </div>


                                        <div class="form-group">
                                          <label >Téléphone (fixe/mobile):</label>
                                          <input type="text"  maxlength="18" class="form-control  input-lg" value="{{$edit->telephone}}" name="tel" placeholder="+225 27 47 54 45 68">
                                        </div>

                                        <div class="form-group">
                                          <label >Chiffre d'affaire (FCFA):</label>
                                          <input type="text" id="ca" value="{{$edit->chiffre_affaire}}"  maxlength="18" class="form-control  input-lg" name="chiffre" placeholder="1000000">
                                        </div>

                                        <div class="form-group">
                                          <label >Nombre d'employés:</label>
                                          <input type="text" id="ne" value="{{$edit->nb_employes}}" maxlength="18" class="form-control  input-lg" name="nb_emp" placeholder="5">
                                        </div>

                                        <div class="form-group">
                                          <label>Objet sociale/Activité:</label>
                                          <input type="text"  value="{{$edit->activite}}" maxlength="60" class="form-control  input-lg" 
                                          name="activite" onkeyup='this.value=this.value.toUpperCase()'>
                                        </div>

                                        <div class="form-group">
                                          <label>Email:</label>
                                          <input type="email"  maxlength="30" class="form-control  input-lg" value="{{$edit->activite}}" name="email">
                                        </div>

                                        <div class="form-group">
                                            <label>Pays :</label>
                                            <select class="form-control input-lg" name="pays">
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
                          
                          
                      @else
                    
                        <!-- AFFICHAGE RECAPITULATIF-->
                        @if(isset($display_recap))
                            @php
                                $edit =  $entreprisecontroller->GetById($display_recap);
                                
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
                                            <h4><label >Email:</label></h4>
                                            <p> {{$edit->adresse_email}} </p>
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
                            
                        
                        @else
                          
                          
                        
                        @endif 
                        
                    
                      @endif

                  </div>
                  <!--/.col (right) -->
                
              @endif
      </div>
       
   
    	
@endsection