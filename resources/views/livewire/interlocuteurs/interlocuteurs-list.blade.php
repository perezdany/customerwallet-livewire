<div class="col-md-12">
    <!--AJOUT AVEC POPUP-->       
    &emsp;	&emsp;<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add">
    <i class="fa fa-plus">INTERLOCUTEUR</i>
    </button>


    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Interlocuteurs</h3>
            <br>    
            <a href="interlocuteurs" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp;&emsp;&emsp; <label>Filtrer par:</label>
            <div class="box-body">
                <div class="row">
                
                    <div class="col-xs-3">
                        <select class="form-control" wire:model.change="entreprise">
                            <option value="">Entreprises</option>
                            @php
                                $get = $entreprisecontroller->GetAll();
                            @endphp
                        
                            @foreach($get as $entreprise)
                                <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                
                            @endforeach
                            
                        </select>   
                    </div>    
                    <div class="col-xs-3">
                        <select class="form-control" wire:model.change="fonction">
                            <option value="">Fonctions</option>
                            @php
                                $get = DB::table('professions')->get();
                            @endphp
                        
                            @foreach($get as $f)
                                <option value="{{$f->id}}">{{$f->intitule}}</option>
                                
                            @endforeach
                            
                        </select>   
                    </div>    
                </div>
            </div>
            <!-- /.box-body -->

            <div class="box-tools">
               
                <br><div class="input-group input-group-sm" style="width: 300px;">
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
                    <th wire:click="setOrderField('nom')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Nom & Prénom(s)</th>
                    <th wire:click="setOrderField('tel')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Téléphone</th>
                    <th wire:click="setOrderField('email')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Email</th>
                    <th wire:click="setOrderField('fonction')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Fonction</th>
                    <th wire:click="setOrderField('nom_entreprise')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Entreprise</th>
                
                    <th wire:click="setOrderField('nom_prenoms')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Ajouté par</th>
                        
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                    @forelse($interlocuteurs as $interlocuteur)
                        <tr>
                            
                            <td>{{$interlocuteur->titre}} {{$interlocuteur->nom}}</td>
                            <td>{{$interlocuteur->tel}}</td>
                            <td>{{$interlocuteur->email}}</td>
                            <td>{{$interlocuteur->intitule}}</td>
                            <td>
                               {{$interlocuteur->nom_entreprise}}
                            </td>
                            <td>
                               {{$interlocuteur->nom_prenoms}}
                            </td>
                            <td>
                                <div class="col-xs-6 no-padding">
                                    @can("delete")
                                        
                                        <!--SUPPRESSION AVEC POPUP-->
                                        
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$interlocuteur->id.""; @endphp">
                                            <i class="fa fa-trash"></i>
                                            </button>
                                        <div class="modal modal-danger fade" id="@php echo "".$interlocuteur->id.""; @endphp">
                                            <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Supprimer </h4>
                                                </div>
                                                <form action="delete_interlocuteur" method="post">
                                                <div class="modal-body">
                                                    <p>Voulez-vous supprimer {{$interlocuteur->nom}}?</p>
                                                    @csrf
                                                    <input type="text" value="{{$interlocuteur->id}}" style="display:none;" name="id_interlocuteur">
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
                                </div>

                                <div class="col-xs-6 no-padding">
                                    @can("edit")
                                        <form action="edit_interlocuteur_form" method="post">
                                            @csrf
                                            <input type="text" value={{$interlocuteur->id}} style="display:none;" name="id_interlocuteur">
                                            <button type="submit" class="btn btn-primary"><i class ="fa fa-edit"></i></button>
                                        </form>
                                    @endcan
                                </div>
                                
                            </td>
                        </tr>
                    @empty
                        <tr colspan="7">
                            <div class="alert alert-info alert-dismissible">
                                
                                <h4><i class="icon fa fa-ban"></i> Oups!</h4>
                                Aucune donnée trouvée
                            </div>
                        </tr>
                    @endforelse
                </tbody>
                
            </table>
        </div>
        <!-- /.box-body -->
        <div clas="box-footer clearfix">
            <ul class="pagination pagination-sm no-margin pull-right">
            
                {{$interlocuteurs->links()}}
            
            </ul>
                
        </div>
    </div>
    <!-- /.box -->
   

</div>