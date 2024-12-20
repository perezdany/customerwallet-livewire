@extends('layouts/base')

@php

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\StatutEntrepriseController;

    use App\Http\Controllers\PaysController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $payscontroller = new PaysController();

    $all = $entreprisecontroller->GetActifs();
    //dd($all);
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
                  <h3 class="box-title">Clients actifs</h3>
                </div>
                <!-- /.box-header -->
                 <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    
                    <th>Interlocuteurs: </th>

                    @if(auth()->user()->id_role != NULL)
                      <th>Action</th>
                    @endif
                  </tr>
                  </thead>
                  <tbody>
                      @foreach($all as $all)
                        <tr>
                          <td>
                           <form method="post" action="display_fiche_customer">
                                @csrf
                                <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                                <button class="btn btn-default"> <b>{{$all->nom_entreprise}}</b></button>
                            </form>
                          
                          <td>{{$all->adresse}}</td>
                          
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
                          @if(auth()->user()->id_role != NULL)
                            <td>
                              <!--POPUP-->
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
                                        <form role="form" method="post" action="edit_entreprise_actif">
                                        
                                        @csrf
                                          <input type="text" name="id_entreprise" value="{{$all->id}}" style="display:none;">
                                            <div class="row">
                                              <div class="col-sm-6">  <label>Raison sociale :</label></div>
                                              <div class="col-md-6"><input type="text" class="form-control" value="{{$all->nom_entreprise}}"
                                               name="nom_entreprise" onkeyup='this.value=this.value.toUpperCase()'/></div>
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

            <div class="col-md-6">

             
           
            </div>
      </div>
          <!-- /.row -->
    <div class="row"></div>
    <div class="row">
     
        <!--/.col (left) -->
        

        <!-- right column -->
        <div class="col-md-6">
        </div>
          <!-- /.box -->
		  
    </div>
    <!--/.col (right) -->
      <!--/.col (right) -->
@endsection