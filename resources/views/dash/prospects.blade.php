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

      @can("edit")
        <div class="row">
            <div class="col-md-3">
                <a href="form_add_prospection"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i>PROSPECTION</b></button></a>
                
            </div>
            <div class="col-md-3">
                <a href="form_add_prospect"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i> PROSPECT</b></button></a>
                
            </div>
        </div>           
      
      @endcan
  
      <div class="row">
      
            
            <div class="col-xs-12">
              <div class="box table-responsive">
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
                    <th>Modifier</th>
                    <th>Supprimer</th>
                    <th>Détails</th>
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
                         
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#edit".$all->id.""; @endphp">
                                  <i class="fa fa-edit"></i>
                          </button>
                          <div class="modal modal-default fade" id="@php echo "edit".$all->id.""; @endphp">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title">Modification</h4>
                                </div>
                                
                                  
                                <!-- form start -->
                                <form role="form" method="post" action="edit_prospect">
                                  <div class="modal-body">
                                      <div class="box-body">
                                          @csrf
                                          <input type="text" name="id_entreprise" value="{{$all->id}}" style="display:none;">
                                      
                                        
                                            <div class="form-group">
                                                <label>Désignation :</label>
                                                <input type="text" class="form-control " value="{{$all->nom_entreprise}}" name="nom_entreprise" onkeyup='this.value=this.value.toUpperCase()'  reuqired />
                                            </div> <br><br>
                                          

                                            <div class="form-group">
                                                <label>Adresse :</label>
                                                <input type="text" class="form-control " value="{{$all->adresse}}"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" />
                                            </div><br><br>


                                            <div class="form-group">
                                              <label >Téléphone (fixe/mobile):</label>
                                              <input type="text"  maxlength="18" class="form-control  " value="{{$all->telephone}}" name="tel" placeholder="+225 27 47 54 45 68">
                                            </div><br><br>

                                            <div class="form-group">
                                              <label >Chiffre d'affaire (FCFA):</label>
                                              <input type="text" id="ca" value="{{$all->chiffre_affaire}}"  maxlength="18" class="form-control  " name="chiffre" placeholder="1000000">
                                            </div><br><br>

                                            <div class="form-group">
                                              <label >Nombre d'employés:</label>
                                              <input type="text" id="ne" value="{{$all->nb_employes}}" maxlength="18" class="form-control  " name="nb_emp" placeholder="5">
                                            </div><br><br>

                                            <div class="form-group">
                                              <label>Objet sociale/Activité:</label>
                                              <input type="text"  value="{{$all->activite}}" maxlength="60" class="form-control  " 
                                              name="activite" onkeyup='this.value=this.value.toUpperCase()'>
                                            </div><br><br>

                                            <div class="form-group">
                                              <label>Email:</label>
                                              <input type="email"  maxlength="30" class="form-control  " value="{{$all->adresse_email}}" name="email">
                                            </div><br><br>

                                            <div class="form-group">
                                                <label>Pays :</label>
                                                <select class="form-control " name="pays">
                                                <option value={{$all->id_pays}}>{{$all->nom_pays}}</option>
                                                    @php
                                                        $pays = $payscontroller->DisplayAll();
                                                    @endphp
                                                    @foreach($pays as $pays)
                                                        <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                                        
                                                    @endforeach
                                                    
                                                </select>
                                            </div>

                                          <div class="modal-footer">
                        
                                            <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Fermer</button>
                                        
                                            <button type="submit" class="btn btn-success">Valider la modification</button>
                                            
                                          </div> 
                                       
                                         
                                      </div>
                                    
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
                            @can("admin")
                               <!--SUPPRESSION AVEC POPUP-->
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$all->id.""; @endphp">
                                  <i class="fa fa-trash"></i>
                                </button>
                            @endcan
                            @can("commercial")
                               <!--SUPPRESSION AVEC POPUP-->
                              <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$all->id.""; @endphp">
                                  <i class="fa fa-trash"></i>
                                </button>
                            @endcan
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
                        </td>

                        <td>
                          <button type="button" class="btn btn-warning" data-toggle="modal" data-target="@php echo "#detail".$all->id.""; @endphp">
                                  <i class="fa fa-eye"></i>
                          </button>
                          <div class="modal modal-default fade" id="@php echo "detail".$all->id.""; @endphp">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title">Infos</h4>
                                </div>
                                
                                  
                                <!-- form start -->
                               
                                  <div class="modal-body">
                                       <div class="box-body" >
                                        @csrf
                                       
                                        <div class="box-body" style="text-align: center;">
                                        
                                          <div class="form-group">
                                              <h4><label>Raison sociale:</label></h4>
                                              <p> {{$all->nom_entreprise}} </p>
                                          </div><br>
                                          <div class="form-group">
                                              <h4><label>Adresse :</label></h4>
                                              <p> {{$all->adresse}} </p>
                                          </div><br>

                                          <div class="form-group">
                                            <h4><label >Téléphone (fixe/mobile):</label></h4>
                                            <p> {{$all->telephone}} </p>
                                          </div><br>

                                          <div class="form-group">
                                            <h4><label >Email:</label></h4>
                                            <p> {{$all->adresse_email}} </p>
                                          </div><br>
        
                                          <div class="form-group">
                                            <h4><label >Chiffre d'affaire (FCFA):</label></h4>
                                            <p> {{$all->chiffre_affaire}} </p>
                                          </div><br>

                                          <div class="form-group">
                                            <h4><label >Nombre d'employés:</label></h4>
                                            <p> {{$all->nb_employes}} </p>
                                          </div><br>

                                          <div class="form-group">
                                            <h4><label >Activités:</label></h4>
                                            <p>{{$all->activite}} </p>
                                          </div><br>

                                          <div class="form-group">
                                              <h4><label>Pays :</label></h4>
                                              <p> {{$all->nom_pays}}</p>
                                                
                                          </div><br>
                                          
                                        </div>
                                      </div>  <!-- /.box-body -->
                                      
                                       <div class="modal-footer">
                        
                                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Fermer</button>
                                      
                                    
                                      </div>
                                  </div>
                                
                             
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
                <!-- /.box-body -->
              </div>
              <!-- /.box -->
            </div>
            <!-- /.col -->
              
      </div>
       
   
    	
@endsection