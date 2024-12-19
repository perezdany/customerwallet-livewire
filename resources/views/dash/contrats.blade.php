@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    $contratcontroller = new ContratController();

    $all = $contratcontroller->RetriveAll();

    /*IMPORTANT ! ECRIRE UN CODE ICI POUR SI A CETTE DATE LE CONTRAT DOIT ETRE RECONDUIT ON ACTUALISE LA DATE DE FIN */
@endphp

@section('content')
      <div class="row">
        <div class="col-md-3">
          <a href="form_add_contrat"><button class="btn btn-success"> <b>ENREGISTRER UN CONTRAT</b></button></a>
                
        </div>   

        <div class="col-md-3">
         
        </div>
      </div>
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
        @if(isset($contrats))
          
          <div class="col-md-12">
              <div class="box">
                  
                  <div class="box-header">
                    <h3 class="box-title">Bases de données des contrats</h3>
                    <a href="contrat" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a>
                    <form role="form" method="post" action="make_filter_contrat">
                      @csrf
                      
                      <div class="box-body">
                        <div class="row">
                        
                          <div class="col-md-3">
                            <select class="form-control" name="entreprise">
                                @if($id_entreprise == "all")
                                  <option value="all">Entreprises</option>
                                @else

                                  @php
                                      $le_nom_entreprise = (new EntrepriseController())->GetById($id_entreprise);
                                  @endphp
                                  
                                  @foreach($le_nom_entreprise as $le_nom_entreprise)
                                      <option value={{$le_nom_entreprise->id}}>{{$le_nom_entreprise->nom_entreprise}}</option>
                                     
                                  @endforeach
                                  <option value="all">Entreprises</option>
                                
                                @endif
                                
                                @php
                                  
                                  $get = (new EntrepriseController())->GetAll();
                                  
                                @endphp
                            
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>   
                          </div>    

                          <div class="col-md-3">
                      
                            <select class="form-control" name="reconduction">
                                @if($etat == "c")
                                  <option value="c">--Etat:choisir--</option>
                                  <option value="0">Non réglé</option>
                                  <option value="1">Réglé</option>

                                @else
                                  @if($etat == 0)
                                  <option value="0">Non réglé</option>
                                  <option value="1">Réglé</option>
                                  <option value="c">--Rétablir--</option>
                                  @else
                                    <option value="1">Réglé</option>
                                    <option value="0">Non réglé</option>
                                    <option value="c">--Rétablir--</option>
                                  @endif
                                @endif
                                
                            </select>
                                                        
                          </div>

                          <div class="col-md-3">
                      
                            <select class="form-control" name="etat_contrat">
                                @if($etat == "c")
                                  <option value="c">Etat</option>
                                  <option value="0">En cours</option>
                                  <option value="1">Terminé</option>
                                @else 
                                  @if($etat == 0)
                                      <option value="0">En cours</option>
                                      <option value="1">Terminé</option> 
                                      <option value="c">--Rétablir--</option>
                                    @else
                                      <option value="1">Terminé</option>
                                      <option value="0">En cours</option>
                                      <option value="c">--Rétablir--</option>
                                    @endif 
                                @endif
                                
                              
                              
                              
                            </select>
                                                        
                          </div>


                          <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                          </div>
                          
                        </div>
                        
                      </div>
                      <!-- /.box-body -->
                    </form>
                  

                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                      <th>Titre de contrat</th>
                      <th>Entreprise</th>
                    
                      
                      <th>Début du contrat</th>
                      <th>Fin du contrat</th>
                      <th>Montant</th>	
              
                      <th>Etat</th><!--VERIFIER SI LE CONTRAT EST EN COURS-->
                      <th>Reconduction</th><!--VERIFIER SI LE CONTRAT EST RECONDUIT-->
                      <th>Fichier du contrat</th>
                      <th>Proforma</th>
                      @if(auth()->user()->id_role == 3)
                      @else
                        <th>Modifier</th>
                      @endif
                    
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($contrats as $contrats)
                          <tr>
                            <td>{{$contrats->titre_contrat}}</td>
                            <td>{{$contrats->nom_entreprise}}</td>
                  
                            
                            <td>@php echo date('d/m/Y',strtotime($contrats->debut_contrat)) @endphp</td>
                            <td>@php echo date('d/m/Y',strtotime($contrats->fin_contrat)) @endphp</td>
                            <td>
                              @php
                                echo  number_format($contrats->montant, 2, ".", " ")." XOF";
                              @endphp
                            
                            </td>  
                            <td>
                              
                              @php 
                              if($contrats->fin_contrat > date('Y-m-d'))
                                {
                                  echo '<p class="bg-success">EN COURS</p>';
                                }
                                else
                                {
                                  echo '<p class="bg-error">TERMINE</p>';
                                }
                              @endphp
                            

                            </td>
                            <td>
                              @php 
                                if($contrats->reconduction == 0)
                                {
                                  echo '<p class="bg-warning">NON</p>';
                                }
                                else
                                {
                                  echo '<p class="bg-success">OUI</p>';
                                }
                              @endphp
                            </td>
                            <td>
                              
                              
                              <form action="download" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                  <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file" value="{{$contrats->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>
                            <td>
                              
                              
                              <form action="view_contrat_proforma" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                  <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="proforma_file" value="{{$contrats->proforma_file}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>
                            @if(auth()->user()->id_role == 3)
                            @else
                            

                              <td>
                                  <form action="edit_contrat_form" method="post">
                                      @csrf
                                      <input type="text" value={{$contrats->id}} style="display:none;" name="id_contrat">
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
        @else
            <div class="col-md-12">
              <div class="box">
                  
                  <div class="box-header">
                    <h3 class="box-title">Bases de données des contrats</h3>
                    <a href="contrat" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a>
                    <form role="form" method="post" action="make_filter_contrat">
                      @csrf
                      
                      <div class="box-body">
                        <div class="row">
                        
                          <div class="col-md-3">
                            <select class="form-control" name="entreprise">
                                <option value="all"}>Toutes les entreprises</option>
                                @php
                                    $get = (new EntrepriseController())->GetAll();
                                @endphp
                              
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>   
                          </div>    

                          <div class="col-md-3">
                      
                            <select class="form-control" name="reconduction">
                            
                                <option value="c">Renouvellement</option>
                                <option value="0">Non reconduit</option>
                                <option value="1">Reconduit</option>
                            </select>
                                                        
                          </div>

                          <div class="col-md-3">
                      
                            <select class="form-control" name="etat_contrat">
                            
                              <option value="c">Etat</option>
                              <option value="0">En cours</option>
                              <option value="1">Terminé</option>
                            </select>
                                                        
                          </div>


                          <div class="col-md-3">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i></button>
                          </div>
                          
                        </div>
                        
                          
                          
                      
                      </div>
                      <!-- /.box-body -->
                    </form>
                  

                  </div>
                  <!-- /.box-header -->
                  <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                      <th>Titre de contrat</th>
                      <th>Entreprise</th>
                    
                      
                      <th>Début du contrat</th>
                      <th>Fin du contrat</th>
                      <th>Montant</th>	
              
                      <th>Etat</th><!--VERIFIER SI LE CONTRAT EST EN COURS-->
                      <th>Reconduction</th><!--VERIFIER SI LE CONTRAT EST RECONDUIT-->
                      <th>Fichier du contrat</th>
                      <th>Proforma</th>
                      @if(auth()->user()->id_role == 3)
                      @else
                        <th>Modifier</th>
                      @endif
                    
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($all as $all)
                          <tr>
                            <td>{{$all->titre_contrat}}</td>
                            <td>{{$all->nom_entreprise}}</td>
                  
                            
                            <td>@php echo date('d/m/Y',strtotime($all->debut_contrat)) @endphp</td>
                            <td>@php echo date('d/m/Y',strtotime($all->fin_contrat)) @endphp</td>
                            <td>
                              @php
                                echo  number_format($all->montant, 2, ".", " ")." XOF";
                              @endphp
                            
                            </td>  
                            <td>
                              
                              @php 
                              if($all->fin_contrat > date('Y-m-d'))
                                {
                                  echo '<p class="bg-success">EN COURS</p>';
                                }
                                else
                                {
                                  echo '<p class="bg-error">TERMINE</p>';
                                }
                              @endphp
                            

                            </td>
                            <td>
                              @php 
                                if($all->reconduction == 0)
                                {
                                  echo '<p class="bg-warning">NON</p>';
                                }
                                else
                                {
                                  echo '<p class="bg-success">OUI</p>';
                                }
                              @endphp
                            </td>
                            <td>
                              
                              
                              <form action="download" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                  <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file" value="{{$all->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>
                            <td>
                              
                              
                              <form action="view_contrat_proforma" method="post" enctype="multipart/form-data">
                                @csrf
                                
                                  <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="proforma_file" value="{{$all->proforma_file}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                              </form>
                              
                            </td>
                            @if(auth()->user()->id_role == 3)
                            @else
                            

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
        @endif
        
    </div>
        
@endsection