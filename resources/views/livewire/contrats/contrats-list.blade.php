
<div class="col-xs-12">
    <div class="box ">
        
        <div class="box-header">
        <h3 class="box-title">Bases de données des contrats</h3><br><br>
            <a href="contrat" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp;&emsp;&emsp; <label>Filtrer par:</label>
        
            <div class="row">
                <div class="col-md-2 form-group">
                    <select class="form-control"  wire:model.debounce.250ms="id_entreprise" id="id_entreprise">
                        <option value="">Entreprises</option>
                        @php
                            $get =  $entreprisecontroller->GetAll();
                        @endphp
                        
                        @foreach($get as $entreprise)
                            <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                            
                        @endforeach
                        
                    </select>   
                </div>    

                <div class="col-md-2 form-group">
            
                    <select class="form-control" wire:model.change="reconduction" id="reconduction">
                    
                        <option value="">Renouvellement</option>
                        <option value="0">Non</option>
                        <option value="1">Tacite</option>
                        <option value="2">Accord parties</option>
                    </select>
                                            
                </div>

                <div class="col-md-2 form-group">
            
                    <select class="form-control" id="etat_contrat"  wire:model.change="etat_contrat">
                        <option value="">Etat</option>
                        <option value="1">En cours</option>
                        <option value="0">Terminé</option>
                    </select>
                                            
                </div>

                <div class="col-md-4 form-group">
                    <select class="form-control" id="service" wire:model.debounce.250ms="service">
                        <!--liste des services a choisir -->
                        <option value="service">Service</option>
                        @php
                            $get = $servicecontroller->GetAll();
                            $categorie = $categoriecontroller->DisplayAll();
                        @endphp
                        @foreach( $categorie as $categorie)
                            
                            <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                            @php
                                $get = $servicecontroller->GetByCategorie($categorie->id);
                                
                            @endphp
                            @foreach($get as $service)
                                <option value={{$service->id}}>{{$service->libele_service}}</option>
                                
                            @endforeach
                        @endforeach
                            
                    </select>
                </div>

            
                
            </div>

    
            <div class="box-tools">
                <a href="form_add_contrat" class="mr-4 d-block"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i> CONTRAT</b></button></a><br>
                <div class="input-group input-group-sm" style="width: 300px;">
                    <input type="text" id="search" wire:model.debounce.250ms="search" class="form-control pull-right" placeholder="Rechercher">

                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped table-hover">
            <thead>
            <tr>
                <th>Titre de contrat</th>
                <th>Entreprise</th>
                <th>Début du contrat</th>
                <th>Fin du contrat</th>
                <th>Montant</th>	
                <th>Services</th><!--LA LISTE DES SERVICES -->
                <th>Ajouter un service</th><!--LA LISTE DES SERVICES -->
                <th>Fichier du contrat</th>
                <th>Bond de commande</th>
                <th>Ajouter un Fichier</th>
                <th>Modifier</th>
                
            
            </tr>
            </thead>
            <tbody>
                @if(isset($prestations))
                    @php
                        //dd($prestations);
                    @endphp

                    @forelse($prestations as $prestation)

                        <tr>
                        
                            <td>{{$prestation->titre_contrat}}</td>
                            <td>
                                    @php
                                    $ent = $entreprisecontroller->GetById($prestation->id_entreprise)
                                @endphp
                                @foreach($ent as $ent)
                                    {{$ent->nom_entreprise}}
                                @endforeach
                                    
                            </td>
                            <td>@php echo date('d/m/Y',strtotime($prestation->debut_contrat)) @endphp</td>
                            <td>@php echo date('d/m/Y',strtotime($prestation->fin_contrat)) @endphp</td>
                            <td>
                                @php
                                echo  number_format($prestation->montant, 2, ".", " ")." XOF";
                                @endphp
                            
                            </td>  
                        
                            <td>
                            
                                @php
                                
                                    //On va écrire un code pour detecter tous les services offerts
                                    $se = DB::table('prestation_services')
                                    ->join('contrats', 'prestation_services.contrat_id', '=', 'contrats.id')
                                    ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                                    
                                    ->where('contrats.id', $prestation->id)
                                    ->get(['services.libele_service']);
                                        //dd($se);
                                @endphp
                                <ul>
                                @foreach($se as $se_get)
                                    <li>{{$se_get->libele_service}}</li>
                                @endforeach
                                </ul>
                            </td>
                             <td>
                                @can("edit")
                                    <button type="button" class="table-row" data-toggle="modal" data-target="@php echo "#addprest".$prestation->id; @endphp">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                @endcan
                                <div class="modal modal-default fade" id="@php echo "addprest".$prestation->id; @endphp">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Services</h4>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <!-- form start -->
                                                <form role="form" action="edit_the_serv" method="post">
                                                    @csrf
                                                    <input type="text" value="{{$prestation->id}}" style="display:none"; name="id_contrat">
                                                    <!-- /.box-body -->
                                                   
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label>Service (*)</label>
                                                            <select class="form-control select2" multiple="multiple" name="service[]"
                                                                style="width: 100%;" data-placeholder="--Selectionnez le service--" required>
                                                                <!--liste des services a choisir -->
                                                                
                                                                @php
                                                                    $get = $servicecontroller->GetAllNoSusp();
                                                                    $categorie = $categoriecontroller->DisplayAll();
                                                                @endphp
                                                                @foreach( $categorie as $categorie)
                                                                    
                                                                    <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                                                    @php
                                                                        $get = $servicecontroller->GetByCategorie($categorie->id);
                                                                        
                                                                    @endphp
                                                                    @foreach($get as $service)
                                                                        
                                                                        <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                                        
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Valider</button>
                                                    </div>
                                                </form>
                                                                
                                            
                                            </div>
                                        
                                            
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                </div> 
                                <!-- /.modal-dialog -->
                            </td>
                            <td>
                                <form action="download" method="post" enctype="multipart/form-data" target="blank">
                                @csrf
                                
                                    <input type="text" value={{$prestation->id}} style="display:none;" name="id_prestation">
                                <input type="text" class="form-control" name="file" value="{{$prestation->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                </form>
                                
                            </td>
                        
                            <td>
                                <form action="view_bon_commande" method="post" enctype="multipart/form-data" target="blank">
                                    @csrf
                                    
                                    <input type="text" value={{$prestation->id}} style="display:none;" name="id_prestation">
                                    <input type="text" class="form-control" name="file_bon" value="{{$prestation->bon_commande}}" style="display:none;">
                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                </form>
                            
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#add".$prestation->id.""; @endphp">
                                <i class="fa fa-upload"></i>
                                </button>
                                <div class="modal modal-default fade" id="@php echo "add".$prestation->id.""; @endphp">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Fichiers du contrat {{$prestation->titre_contrat}}</h4>
                                        </div>
                                        <!-- form start -->
                                        <form role="form" method="post" action="add_files_contrat" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="box-body">
                                                    @csrf
                                                    <input type="text" name="id_contrat" value="{{$prestation->id}}" style="display:none;">
                                                
                                                    
                                                        <div class="row">
                                                            <div class="col-sm-6"><label>Fichier du contrat(PDF)</label></div>
                                                            <div class="col-sm-6"> <input type="file" class="form-control" name="path"></div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-sm-6"><label>Facture proforma :</label></div>
                                                            <div class="col-sm-6"><input type="file" class="form-control" name="proforma_file"></div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-sm-6"><label>Bon de commande(PDF) :</label></div>
                                                            <div class="col-sm-6"><input type="file" class="form-control" name="bon_commande" ></div>
                                                        </div><br>
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
                                <!--MODIFICATION AVEC POPUP-->
                                @can("edit")
                                    <button type="button" class="btn btn-primary" wire:click="EditContrat('{{$prestation->id}}')">
                                    <i class="fa fa-edit"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @empty
                    <tr colspan="9">
                        <div class="alert alert-info alert-dismissible">
                            
                            <h4><i class="icon fa fa-ban"></i> Oups!</h4>
                            Aucune donnée trouvée
                        </div>
                    </tr>
                        @endforelse

                @else
                    @forelse($contrats as $contrat)
                        <tr>
                        
                            <td>{{$contrat->titre_contrat}} </td>
                            <td>
                                @php
                                    $ent = $entreprisecontroller->GetById($contrat->id_entreprise)
                                @endphp
                                @foreach($ent as $ent)
                                    {{$ent->nom_entreprise}}
                                @endforeach

                            </td>
                            <td>@php echo date('d/m/Y',strtotime($contrat->debut_contrat)) @endphp</td>
                            <td>@php echo date('d/m/Y',strtotime($contrat->fin_contrat)) @endphp</td>
                            <td>
                                @php
                                echo  number_format($contrat->montant, 2, ".", " ")." XOF";
                                @endphp
                            
                            </td>  
                        
                            <td>
                            
                                @php
                                
                                    //On va écrire un code pour detecter tous les services offerts
                                    $se = DB::table('prestation_services')
                                    ->join('contrats', 'prestation_services.contrat_id', '=', 'contrats.id')
                                    ->join('services', 'prestation_services.service_id', '=', 'services.id') 
                                    
                                    ->where('contrats.id', $contrat->id)
                                    ->get(['prestation_services.*', 'services.libele_service']);
                                        //dd($se);
                                @endphp
                                <ul>
                                @foreach($se as $se_get)
                                    <li class="no-padding">{{$se_get->libele_service}} @can("delete") <a href="delprest/{{$se_get->id}}"><i class="fa fa-trash"></i></a>@endcan</li>
                                @endforeach
                                </ul>
                            </td>
                            <td>
                                @can("edit")
                                    <button type="button" class="table-row" data-toggle="modal" data-target="@php echo "#addprest".$contrat->id; @endphp">
                                        <i class="fa fa-plus"></i>
                                    </button>
                                @endcan
                                <div class="modal modal-default fade" id="@php echo "addprest".$contrat->id; @endphp">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Services</h4>
                                            </div>
                                            
                                            <div class="modal-body">
                                                <!-- form start -->
                                                <form role="form" action="edit_the_serv" method="post">
                                                    @csrf
                                                    <input type="text" value="{{$contrat->id}}" style="display:none"; name="id_contrat">
                                                    <!-- /.box-body -->
                                                   
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label>Service (*)</label>
                                                            <select class="form-control  select2" multiple="multiple" name="service[]"
                                                                style="width: 100%;" data-placeholder="--Selectionnez le service--" required>
                                                                <!--liste des services a choisir -->
                                                                
                                                                @php
                                                                    $get = $servicecontroller->GetAllNoSusp();
                                                                    $categorie = $categoriecontroller->DisplayAll();
                                                                @endphp
                                                                @foreach( $categorie as $categorie)
                                                                    
                                                                    <optgroup label="{{$categorie->libele_categorie}}">{{$categorie->libele_categorie}}</optgroup>
                                                                    @php
                                                                        $get = $servicecontroller->GetByCategorie($categorie->id);
                                                                        
                                                                    @endphp
                                                                    @foreach($get as $service)
                                                                        
                                                                        <option value={{$service->id}}>{{$service->libele_service}}</option>
                                                                        
                                                                    @endforeach
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                                                        <button type="submit" class="btn btn-primary">Valider</button>
                                                    </div>
                                                </form>
                                                                
                                            
                                            </div>
                                        
                                            
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                                </div> 
                                <!-- /.modal-dialog -->
                            </td>
                            <td>
                                <form action="download" method="post" enctype="multipart/form-data" target="blank">
                                @csrf
                                
                                    <input type="text" value={{$contrat->id}} style="display:none;" name="id_contrat">
                                <input type="text" class="form-control" name="file" value="{{$contrat->path}}" style="display:none;">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                </form>
                                
                            </td>
                        
                            <td>
                                <form action="view_bon_commande" method="post" enctype="multipart/form-data" target="blank">
                                    @csrf
                                    
                                    <input type="text" value={{$contrat->id}} style="display:none;" name="id_contrat">
                                    <input type="text" class="form-control" name="file_bon" value="{{$contrat->bon_commande}}" style="display:none;">
                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                </form>
                            
                            </td>

                            <td>
                                @can("edit")
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#add".$contrat->id.""; @endphp">
                                <i class="fa fa-upload"></i>
                                </button>
                                @endcan
                                <div class="modal modal-default fade" id="@php echo "add".$contrat->id.""; @endphp">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Fichiers du contrat {{$contrat->titre_contrat}}</h4>
                                        </div>
                                        <!-- form start -->
                                        <form role="form" method="post" action="add_files_contrat" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="box-body">
                                                    @csrf
                                                    <input type="text" name="id_contrat" value="{{$contrat->id}}" style="display:none;">
                                                
                                                    
                                                        <div class="row">
                                                            <div class="col-sm-6"><label>Fichier du contrat(PDF)</label></div>
                                                            <div class="col-sm-6"> <input type="file" class="form-control" name="file"></div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-sm-6"><label>Facture proforma :</label></div>
                                                            <div class="col-sm-6"><input type="file" class="form-control" name="proforma_file"></div>
                                                        </div><br>
                                                        <div class="row">
                                                            <div class="col-sm-6"><label>Bon de commande(PDF) :</label></div>
                                                            <div class="col-sm-6"><input type="file" class="form-control" name="bon_commande" ></div>
                                                        </div><br>
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
                                <!--MODIFICATION AVEC POPUP-->
                                @can("edit")
                                <button type="button" class="btn btn-primary" wire:click="EditContrat('{{$contrat->id}}')">
                                <i class="fa fa-edit"></i>
                                </button>
                                @endcan

                            </td>       
                        </tr>
                    @empty

                    <tr colspan="9">
                        <div class="alert alert-info alert-dismissible">
                            
                            <h4><i class="icon fa fa-ban"></i> Oups!</h4>
                            Aucune donnée trouvée
                        </div>
                    </tr>
                    @endforelse
                @endif
            </tbody>
            
            </table>
        </div>
        <!-- /.box-body -->

        <div clas="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
                @if(isset($prestations))
                    {{$prestations->links()}}
                @else
                    {{$contrats->links()}}
                @endif
                
            </ul>
                
        </div>
    </div>
    <!-- /.box -->
</div>
