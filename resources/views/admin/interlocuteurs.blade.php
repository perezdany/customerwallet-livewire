@extends('layouts/base')

@php
   
use App\Http\Controllers\InterlocuteurController;

$interlocuteurcontroller = new InterlocuteurController();

use App\Http\Controllers\EntrepriseController;

$entreprisecontroller = new EntrepriseController();

$all =  $interlocuteurcontroller-> GetAll();

  //dd($all);
@endphp

@section('content')
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
          
                <div class="col-md-12">
                <!--AJOUT AVEC POPUP-->
                            
                    	&emsp;	&emsp;<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add">
                    <i class="fa fa-plus">INTERLOCUTEUR</i>
                    </button>
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
                                    <form role="form" action="add_referant" method="post">
                                        @csrf
                                        <div class="box-body">
                                            @if(isset($intel))
                                                <div class="col-xs-3">
                                                    <select class="form-control" name="entreprise" style="display:none;">
                                                        @if($id_entreprise == "all")

                                                        <option value="all">Entreprises</option>
                                                        @else

                                                        @php
                                                            $le_nom_entreprise = (new EntrepriseController())->GetById($id_entreprise);
                                                            
                                                        @endphp
                                                        
                                                        @foreach($le_nom_entreprise as $le_nom_entreprise)

                                                            <option value={{$le_nom_entreprise->id}}>{{$le_nom_entreprise->nom_entreprise}}</option>
                                                            
                                                        @endforeach
                                                        <option value="all">Toutes les Entreprises</option>
                                                        
                                                        @endif
                                                        
                                                        @php
                                                        
                                                        $get = (new EntrepriseController())->GetAll();
                                                        
                                                        @endphp
                                                    
                                                        @foreach($get as $entreprise)
                                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                            
                                                        @endforeach
                                                    
                                                    </select>   
                                                </div>    
                                                <div class="col-xs-3">
                                                    <select class="form-control" name="fonction" style="display:none;">
                                                    @if($id_entreprise == "all")

                                                    <option value="all">Fonctions</option>
                                                    @else
                            
                                                    @php
                                                        $le_nom= DB::table('professions')->where('intitule', $fonction)->get();
                                                        
                                                    @endphp
                                                    
                                                    @foreach($le_nom as $le_nom)

                                                        <option value={{$le_nom->id}}>{{$le_nom->nom_entreprise}}</option>
                                                        
                                                    @endforeach
                                                    @endif
                                                    
                                                        <option value="all">Fonctions</option>
                                                        @php
                                                            $get = DB::table('professions')->get();
                                                        @endphp
                                                    
                                                        @foreach($get as $f)
                                                            <option value={{$f->intitule}}>{{$f->intitule}}</option>
                                                            
                                                        @endforeach
                                                    
                                                </select>   
                                                </div>    
                        
                                            @else
                                                <div class="col-xs-3">
                                                    <select class="form-control" name="entreprise" style="display:none;">
                                                        <option value="all">Entreprises</option>
                                                        @php
                                                            $get = (new EntrepriseController())->GetAll();
                                                        @endphp
                                                    
                                                        @foreach($get as $entreprise)
                                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                            
                                                        @endforeach
                                                        
                                                    </select>   
                                                </div>    
                                                <div class="col-xs-3">
                                                    <select class="form-control" name="fonction" style="display:none;">
                                                        <option value="all">Fonctions</option>
                                                        @php
                                                            $get = DB::table('professions')->get();
                                                        @endphp
                                                    
                                                        @foreach($get as $f)
                                                            <option value={{$f->intitule}}>{{$f->intitule}}</option>
                                                            
                                                        @endforeach
                                                        
                                                    </select>   
                                                </div>    

                                            @endif
                                         
                                            <div class="form-group">
                                               
                                                <select class="form-control " name="entreprise">
                                                    @php
                                                        $get = $entreprisecontroller->GetAll();
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
                                                <select class="form-control " name="titre">
                                                    <option value="M">M</option>
                                                    <option value="Mme">Mme</option>
                                                    <option value="Mlle">Mlle</option>
                                                </select>
                                                
                                            </div>
                                            <div class="form-group">
                                                    <label >Nom & Prénom(s)</label>
                                                    <input type="text"  maxlength="100" class="form-control  " name="nom" onkeyup="this.value=this.value.toUpperCase()">
                                            </div>

                                            <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email"  maxlength="30" class="form-control " name="email" >
                                                </div>

                                            <div class="form-group">
                                                    <label>Téléphone (*)</label>
                                                    <input type="text"  maxlength="30"   class="form-control " name="tel" placeholder="(+225)0214578931" >
                                                </div>

                                            <div class="form-group">
                                                <label>Fonction</label>
                                                    <select class="form-control select2"  maxlength="60" name="fonction" required>
                                                    @php
                                                        $f = DB::table('professions')->orderBy('id', 'asc')->get();
                                                    @endphp
                                                    @foreach($f as $f)
                                                        <option value="{{$f->intitule}}">{{$f->intitule}}</option>
                                                    @endforeach
                                                </select>
                                            </div>  
                                        </div>

                                        <div class="modal-footer">
                                                            <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-primary">Valider</button>
                                    </form>
                                                    
                                
                                </div>
                            
                                
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div> 
                    <!-- /.modal-dialog -->
        
                </div>
           
        @if(isset($intel))
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                    <h3 class="box-title">Interlocuteurs</h3>
                      
                    <form role="form" method="post" action="make_filter_interlocuteur">
                      @csrf
                       <a href="interlocuteurs" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp;&emsp;&emsp; <label>Filtrer par:</label>
                      <div class="box-body">
                        <div class="row">
                        
                            <div class="col-xs-3">
                               <select class="form-control select2" name="entreprise">
                                    @if($id_entreprise == "all")

                                    <option value="all">Entreprises</option>
                                    @else

                                    @php
                                        $le_nom_entreprise = (new EntrepriseController())->GetById($id_entreprise);
                                        
                                    @endphp
                                    
                                    @foreach($le_nom_entreprise as $le_nom_entreprise)

                                        <option value={{$le_nom_entreprise->id}}>{{$le_nom_entreprise->nom_entreprise}}</option>
                                        
                                    @endforeach
                                    <option value="all">Toutes les Entreprises</option>
                                    
                                    @endif
                                    
                                    @php
                                    
                                    $get = (new EntrepriseController())->GetAll();
                                    
                                    @endphp
                                
                                    @foreach($get as $entreprise)
                                        <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                        
                                    @endforeach
                                
                                </select>   
                            </div>    
    

                            <div class="col-xs-3">
                                  <select class="form-control select2" name="fonction">
                                @if($id_entreprise == "all")

                                  <option value="all">Fonctions</option>
                                @else
        
                                  @php
                                      $le_nom= DB::table('professions')->where('intitule', $fonction)->get();
                                     
                                  @endphp
                                  
                                  @foreach($le_nom as $le_nom)

                                    <option value={{$le_nom->id}}>{{$le_nom->nom_entreprise}}</option>
                                     
                                  @endforeach
                                @endif
                                
                                    <option value="all">Fonctions</option>
                                    @php
                                        $get = DB::table('professions')->get();
                                    @endphp
                                
                                    @foreach($get as $f)
                                        <option value={{$f->intitule}}>{{$f->intitule}}</option>
                                        
                                    @endforeach
                                
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
                        <table id="example1" class="table table-bordered table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Nom & Prénom(s)</th>
                                <th>Téléphone</th>
                                <th>Email</th>
                                <th>Fonction</th>
                                <th>Entreprise</th>
                            
                                <th>Ajouté par</th>
                                    
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($intel as $intel)
                                    <tr>
                                        
                                        <td>{{$intel->titre}} {{$intel->nom}}</td>
                                        <td>{{$intel->tel}}</td>
                                        <td>{{$intel->email}}</td>
                                        <td>{{$intel->fonction}}</td>
                                        <td>{{$intel->nom_entreprise}}</td>
                                        <td>{{$intel->nom_prenoms}}</td>
                                        <td>
                                            @if(auth()->user()->id_departement == 1)
                                               
                                                <form action="edit_interlocuteur_form" method="post">
                                                    @csrf
                                                    <input type="text" value={{$intel->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                </form>
                                                @if(auth()->user()->id_role == 5)
                                                    
                                                    <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$intel->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$intel->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_interlocuteur" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer {{$intel->nom}}?</p>
                                                                @csrf
                                                                <input type="text" value="{{$intel->id}}" style="display:none;" name="id_interlocuteur">
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
                                                <!--AJOUT AVEC POPUP-->
                                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#edit".$intel->id."" @endphp">
                                                    <i class="fa fa-edit"></i>
                                                    </button>
                                                    <div class="modal modal-default fade" id="@php echo "edit".$intel->id."" @endphp">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                    <h4 class="modal-title">Modifier un interlocuteur</h4>
                                                                </div>
                                                                
                                                                <div class="modal-body">
                                                                    <!-- form start -->
                                                                    <form role="form" action="edit_interlocuteur" method="post">
                                                                        @csrf
                                                                        <div class="box-body">
                                                                            @if(isset($intel))
                                                                                <div class="col-xs-3">
                                                                                    <select class="form-control" name="entreprise" style="display:none;">
                                                                                        @if($id_entreprise == "all")

                                                                                        <option value="all">Entreprises</option>
                                                                                        @else

                                                                                        @php
                                                                                            $le_nom_entreprise = (new EntrepriseController())->GetById($id_entreprise);
                                                                                            
                                                                                        @endphp
                                                                                        
                                                                                        @foreach($le_nom_entreprise as $le_nom_entreprise)

                                                                                            <option value={{$le_nom_entreprise->id}}>{{$le_nom_entreprise->nom_entreprise}}</option>
                                                                                            
                                                                                        @endforeach
                                                                                        <option value="all">Toutes les Entreprises</option>
                                                                                        
                                                                                        @endif
                                                                                        
                                                                                        @php
                                                                                        
                                                                                        $get = (new EntrepriseController())->GetAll();
                                                                                        
                                                                                        @endphp
                                                                                    
                                                                                        @foreach($get as $entreprise)
                                                                                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                                                            
                                                                                        @endforeach
                                                                                    
                                                                                    </select>   
                                                                                </div>    
                                                                                <div class="col-xs-3">
                                                                                    <select class="form-control" name="fonction" style="display:none;">
                                                                                    @if($id_entreprise == "all")

                                                                                    <option value="all">Fonctions</option>
                                                                                    @else
                                                            
                                                                                    @php
                                                                                        $le_nom= DB::table('professions')->where('intitule', $fonction)->get();
                                                                                        
                                                                                    @endphp
                                                                                    
                                                                                    @foreach($le_nom as $le_nom)

                                                                                        <option value={{$le_nom->id}}>{{$le_nom->nom_entreprise}}</option>
                                                                                        
                                                                                    @endforeach
                                                                                    @endif
                                                                                    
                                                                                        <option value="all">Fonctions</option>
                                                                                        @php
                                                                                            $get = DB::table('professions')->get();
                                                                                        @endphp
                                                                                    
                                                                                        @foreach($get as $f)
                                                                                            <option value={{$f->intitule}}>{{$f->intitule}}</option>
                                                                                            
                                                                                        @endforeach
                                                                                    
                                                                                </select>   
                                                                                </div>    
                                                        
                                                                            @else
                                                                            

                                                                            @endif
                                                                        
                                                                            <div class="form-group">
                                                                            
                                                                                <select class="form-control " name="entreprise">
                                                                                    @php
                                                                                        $get = $entreprisecontroller->GetAll();
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
                                                                                <select class="form-control " name="titre">
                                                                                    <option value="{{$intel->titre}}">{{$intel->titre}}</option>
                                                                                    <option value="M">M</option>
                                                                                    <option value="Mme">Mme</option>
                                                                                    <option value="Mlle">Mlle</option>
                                                                                </select>
                                                                                
                                                                            </div>
                                                                            <div class="form-group">
                                                                                    <label >Nom & Prénom(s)</label>
                                                                                    <input type="text" value="{{$intel->nom}}"   maxlength="100" class="form-control  " name="nom" onkeyup="this.value=this.value.toUpperCase()">
                                                                            </div>

                                                                            <div class="form-group">
                                                                                <label>Email</label>
                                                                                <input type="email" value="{{$intel->email}}"  maxlength="30" class="form-control " name="email" >
                                                                            </div>

                                                                            <div class="form-group">
                                                                                    <label>Téléphone (*)</label>
                                                                                    <input type="text"  maxlength="30"  value="{{$intel->tel}}"  class="form-control " name="tel" placeholder="(+225)0214578931" >
                                                                                </div>

                                                                            <div class="form-group">
                                                                                <label>Fonction</label>
                                                                                    <select class="form-control select2"  maxlength="60" name="fonction" required>
                                                                                    <option value="{{$intel->fonction}}">{{$intel->fonction}}</option>
                                                                                    @php
                                                                                        $f = DB::table('professions')->get();
                                                                                    @endphp
                                                                                    @foreach($f as $f)
                                                                                        <option value="{{$f->intitule}}">{{$f->intitule}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>  
                                                                        </div>

                                                                        <div class="modal-footer">
                                                                        <button type="button" class="btn pull-left" data-dismiss="modal">Fermer</button>
                                                                        <button type="submit" class="btn btn-primary">Valider</button>
                                                                    </form>
                                                                                    
                                                                
                                                                </div>
                                                            
                                                                
                                                            </div>
                                                            <!-- /.modal-content -->
                                                        </div>
                                                    </div> 
                                                    <!-- /.modal-dialog -->
                                        
                                                    <form action="edit_interlocuteur_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$intel->id}} style="display:none;" name="id_interlocuteur">
                                                        <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                    </form>
                                                    
                                                    <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$intel->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$intel->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_interlocuteur" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer {{$intel->nom}}?</p>
                                                                @csrf
                                                                <input type="text" value="{{$intel->id}}" style="display:none;" name="id_interlocuteur">
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
                                                
                                                    
                                                    <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$intel->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$intel->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_interlocuteur" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer {{$intel->nom}}?</p>
                                                                @csrf
                                                                <input type="text" value="{{$intel->id}}" style="display:none;" name="id_interlocuteur">
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
                                                 
                                                    <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$intel->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$intel->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_interlocuteur" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer {{$intel->nom}}?</p>
                                                                @csrf
                                                                <input type="text" value="{{$intel->id}}" style="display:none;" name="id_interlocuteur">
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
                                                   <form action="edit_interlocuteur_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$intel->id}} style="display:none;" name="id_interlocuteur">
                                                        <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                    </form>
                                                @endif

                                                @if(auth()->user()->id_role == 3)
                                            
                                                
                                                @endif
                                                @if(auth()->user()->id_role == 1)
                                                      <form action="edit_interlocuteur_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$intel->id}} style="display:none;" name="id_interlocuteur">
                                                        <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                                    </form>
                                                   
                                                   <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$intel->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$intel->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_interlocuteur" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer {{$intel->nom}}?</p>
                                                                @csrf
                                                                <input type="text" value="{{$intel->id}}" style="display:none;" name="id_interlocuteur">
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
        @else
            <div class="col-md-12">
            <div class="box">
               <div class="box-header">
                    <h3 class="box-title">Interlocuteurs</h3>
                      
                    <form role="form" method="post" action="make_filter_interlocuteur">
                      @csrf
                       <a href="interlocuteurs" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp;&emsp;&emsp; <label>Filtrer par:</label>
                      <div class="box-body">
                        <div class="row">
                        
                            <div class="col-xs-3">
                                <select class="form-control select2" name="entreprise">
                                    <option value="all">Entreprises</option>
                                    @php
                                        $get = (new EntrepriseController())->GetAll();
                                    @endphp
                                
                                    @foreach($get as $entreprise)
                                        <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                        
                                    @endforeach
                                    
                                </select>   
                            </div>    
                            <div class="col-xs-3">
                                <select class="form-control select2" name="fonction">
                                    <option value="all">Fonctions</option>
                                    @php
                                        $get = DB::table('professions')->get();
                                    @endphp
                                
                                    @foreach($get as $f)
                                        <option value={{$f->intitule}}>{{$f->intitule}}</option>
                                        
                                    @endforeach
                                    
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
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Nom & Prénom(s)</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
                            <th>Entreprise</th>
                        
                            <th>Ajouté par</th>
                                
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all as $all)
                                <tr>
                                    
                                    <td>{{$all->titre}} {{$all->nom}}</td>
                                    <td>{{$all->tel}}</td>
                                    <td>{{$all->email}}</td>
                                    <td>{{$all->fonction}}</td>
                                    <td>{{$all->nom_entreprise}}</td>
                                    <td>{{$all->nom_prenoms}}</td>
                                    <td>
                                        @if(auth()->user()->id_departement == 1)
                                            <form action="edit_interlocuteur_form" method="post">
                                                @csrf
                                                <input type="text" value={{$all->id}} style="display:none;" name="id_interlocuteur">
                                                <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                            </form>
                                            @if(auth()->user()->id_role == 5)
                                                
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
                                                        <form action="delete_interlocuteur" method="post">
                                                        <div class="modal-body">
                                                            <p>Voulez-vous supprimer {{$all->nom}}?</p>
                                                            @csrf
                                                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_interlocuteur">
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
                                                    <input type="text" value={{$all->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
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
                                                        <form action="delete_interlocuteur" method="post">
                                                        <div class="modal-body">
                                                            <p>Voulez-vous supprimer {{$all->nom}}?</p>
                                                            @csrf
                                                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_interlocuteur">
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
                                                        <form action="delete_interlocuteur" method="post">
                                                        <div class="modal-body">
                                                            <p>Voulez-vous supprimer {{$all->nom}}?</p>
                                                            @csrf
                                                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_interlocuteur">
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
                                                    <input type="text" value={{$all->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
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
                                                        <form action="delete_interlocuteur" method="post">
                                                        <div class="modal-body">
                                                            <p>Voulez-vous supprimer {{$all->nom}}?</p>
                                                            @csrf
                                                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_interlocuteur">
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
                                                    <input type="text" value={{$all->id}} style="display:none;" name="id_interlocuteur">
                                                    <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
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
                                                        <form action="delete_interlocuteur" method="post">
                                                        <div class="modal-body">
                                                            <p>Voulez-vous supprimer {{$all->nom}}?</p>
                                                            @csrf
                                                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_interlocuteur">
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
        @endif
      
        <!-- /.col -->

        
	</div>
		
@endsection