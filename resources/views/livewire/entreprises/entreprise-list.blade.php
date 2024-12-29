    @php
    //dd($entreprises);
    @endphp
    
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title flex-grow-1">Base des Entreprise</h3><br>
        
                        <!--<a href="form_add_entreprise"><button class="btn btn-success"> <b><i class="fa fa-plus"></i> ENTREPRISE</b></button></a><br><br>-->
                    <a href="entreprises" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a> &emsp;&emsp; <label>Filtrer par:</label>
                    <div class="row">
                    
                        <div class="col-xs-3 form-group">
                            <select class="form-control" id="categorie" wire:model.debounce.250ms="categorie">
                                <option value="">Catégorie</option>
                                @php
                                    $categorie = ($statutentreprisecontroller)->GetAll();
                                @endphp
                                
                                @foreach($categorie as $categorie)
                                    <option value={{$categorie->id}}>{{$categorie->libele_statut}}</option>
                                    
                                @endforeach
                                
                            </select>   
                        </div>    

                        <div class="col-xs-3 form-group">
                    
                        <select class="form-control" id="etat" wire:model.debounce.250ms="etat">
                        
                            <option value="">Statut</option>
                            <option value="0">Inactif</option>
                            <option value="1">Actif</option>
                        </select>
                                                    
                        </div>

                    </div>
                    
                <div class="box-tools d-flex aglin-items-center">
                    <a href="form_add_entreprise" class="mr-4 d-block"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i> ENTREPRISE</b></button></a> <br><br>
                    <div class="input-group input-group-sm" style="width: 350px;">
                        <input type="text" id="search" wire:model.debounce.250ms="search" class="form-control pull-right" placeholder="Rechercher">

                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Adresse</th>
                        <th>Statut</th>
                        <th style="display:none">EatEntreprise</th>
                    
                        <th>Fiche</th>
                        <th>Interlocuteurs: </th>
                        <th>Modifier</th>
                        <th>Supprimer</th>
                        <th>Détails</th>
                    </tr>
                    </thead>
                    <tbody>

                        @forelse($entreprises as $entreprise)
                            
                            <tr  wire:key="{{ $entreprise->id }}">
                            <td>{{$entreprise->nom_entreprise}}</td>
                            
                            <td>
                            {{$entreprise->adresse}}
                            </td>
                            <th>
                                @if($entreprise->etat == 0)
                                    <p class="bg-red">Inactif</p>
                                @else
                                    <p class="bg-green">Actif</p>
                                @endif
                            
                            </th>
                            <th style="display:none">{{$entreprise->etat}}</th>
                            <td>
                                @if($entreprise->id_statutentreprise == 2)
                                <form method="get" action="display_fiche_customer">
                                    @csrf
                                    <input type="text" value="{{$entreprise->id}}" style="display:none;" name="id_entreprise">
                                    <button class="btn btn-default"> <b>Fiche</b></button>
                                </form>
                                @endif
                            </td>
                            <td>
                                @include('livewire.entreprises.interlocuteurs-of-this')
                                <!--AFFICHAGE DES INTERLOCUTEURS AVEC POPUP-->
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="@php echo "#interlocuteurs".$entreprise->id.""; @endphp">
                                <i class="fa fa-eye"></i>
                                </button>
                                <!-- <button type="button" class="btn btn-success"  wire:click="showInterlocuteurs(' {{ $entreprise->id }} ')">
                                    <i class="fa fa-eye"></i>
                                </button>-->
                                

                            </td>
                            <td>
                                <!--MODIFICATION AVEC POPUP-->
                                
                                <!--<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#edit{{ $entreprise->id }}">
                                    <i class="fa fa-edit"></i>
                                </button>-->
                                <button type="button" class="btn btn-primary"  wire:click="EditEntreprise(' {{ $entreprise->id }} ')">
                                    <i class="fa fa-edit"></i>
                                </button>
                                
                            </td>
                            <td>
                                <!--SUPPRESSION AVEC POPUP-->
                                <button type="button" class="btn btn-danger"  wire:click="confirmDelete(' {{ $entreprise->nom_entreprise }} '
                                , {{ $entreprise->id }} )">
                                    <i class="fa fa-trash"></i>
                                </button>
                                

                            </td>
                            <td>
                                <!--DETAILS AVEC POPUP-->

                                <button type="button" class="btn btn-warning"  wire:click="Detail(' {{ $entreprise->id }} ')">
                                    <i class="fa fa-eye"></i>
                                </button>

                            
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
            <div class="box-footer clearfix">
                <ul class="pagination pagination-sm no-margin pull-right">
                    {{$entreprises->links()}}
                </ul>
                    
            </div>
        </div>
        <!-- /.box -->
    </div>
   






