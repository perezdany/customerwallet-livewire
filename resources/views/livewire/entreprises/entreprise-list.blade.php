    @php
    //dd($entreprises);
    @endphp
    
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title flex-grow-1">Base des Entreprises/Prospects/Particuliers/Cibles</h3><br>
        
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
                    <label>Afficher par date d'ajout:</label>
                    <div class="row">
                        <div class="col-xs-3 form-group">
                            <div class="col-xs-3">
                                <select class="" id="compare" wire:model.debounce.250ms="compare">
                                    <option value="">Choisir</option>
                                    <option value="<"><</option> 
                                    <option value=">">></option>
                                    <option value="=">=</option>                              
                                </select>   
                            </div>
                             <div class="col-xs-3">
                                <select class="" id="anne_depuis" wire:model.debounce.250ms="annee_depuis">
                                    <option value="">Choisir</option>
                                    @php
                                        $annee_fin = "2030";
                                        for($annee="2014"; $annee<=$annee_fin; $annee++)
                                        {
                                            echo'<option value='.$annee.'>'.$annee.'</option>';
                                        }
                                    @endphp
                                    
                                </select>   
                            </div>
                        </div>    
                    </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <table id="" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th wire:click="setOrderField('nom_entreprise')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Nom</th>
                        <th wire:click="setOrderField('adresse')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Adresse</th>
                        <th wire:click="setOrderField('etat')"><i class="fa fa-sort-amount-desc" aria-hidden="true"></i>Statut</th>
                        <th style="display:none">EtatEntreprise</th>
                    
                        <!--<th>Fiche Signalétique</th>-->
                        <th>Interlocuteurs:</th>
                        <th>Mod</th>
                        <th>Supp</th>
                        <th>Fiche Signalétique</th>
                    </tr>
                    </thead>
                    <tbody>

                        @forelse($entreprises as $entreprise)
        
                            <tr  wire:key="{{ $entreprise->id }}">
                            <td>
                                
                                @if($entreprise->id_statutentreprise == 2)
                                
                                    <form method="get" action="display_fiche_customer" target="blank">
                                        @csrf
                                        <input type="text" value="{{$entreprise->id}}" style="display:none;" name="id_entreprise">
                                        <button class="btn btn-default"> <b>{{$entreprise->nom_entreprise}}</b></button>
                                    </form>
                                @else
                                    @if($entreprise->id_statutentreprise == 1)
                                    
                                    <form method="post" action="display_about_prospect">
                                        @csrf
                                        <input type="text" value="{{$entreprise->id}}" style="display:none;" name="id_entreprise">
                                        <button class="btn btn-default"> <b>{{$entreprise->nom_entreprise}}</b></button>
                                    </form>
                                    @endif
                                    @if($entreprise->id_statutentreprise == 3)
                                        <form method="post" action="display_about_prospect">
                                            @csrf
                                            <input type="text" value="{{$entreprise->id}}" style="display:none;" name="id_entreprise">
                                            <button class="btn btn-default"> <b>{{$entreprise->nom_entreprise}}</b></button>
                                        </form>
                                    @endif
                             
                                @endif
                            </td>
                            
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
                                @can("edit")
                                <button type="button" class="btn btn-primary"  wire:click="EditEntreprise(' {{ $entreprise->id }} ')">
                                    <i class="fa fa-edit"></i>
                                </button>
                                @endcan
                                
                            </td>
                            <td>
                                <!--SUPPRESSION AVEC POPUP-->
                                @can("delete")
                                <button type="button" class="btn btn-danger"  wire:click="confirmDelete(' {{ $entreprise->nom_entreprise }} '
                                , {{ $entreprise->id }} )">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endcan

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