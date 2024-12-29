
    <!--FAIRE ALLERTE POUR AFFICHER LES FACTURES DONT LA DATE EST ECHUE ET AFFICHER EN HAUT ENSUITE METTRE LE TOTAL DES FACTURES NON PAYEES EN BAS -->
    @php
        $total = 0;
        $date_aujourdhui = date('Y-m-d');
    @endphp
@if(isset($id_entreprise))

        
    @if($id_entreprise != "")
       
        <div class="col-xs-12">
                 <div class="box">
                <div class="box-header">
                  
                    <a href="facture" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a>&emsp;&emsp;&emsp;&emsp;
                    
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-striped ">
                        <thead>
                        <tr>
                            <th>Client</th>
                            <th>Facture N°</th>
                            <th>Emise le:</th>
                            <th>Date de règlement</th>
                            <!--<th>Contrat</th>-->
                            
                            <th>Montant</th>
                            <th>Afficher les paiements</th>
                            <th>Fichier</th>
                            @if(auth()->user()->id_role == 3)
                            @else
                            
                                <th>Modifier/Paiement</th>
                                <th>Supprimer</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                
                                $date_aujourdhui = strtotime(date('Y-m-d'));
                            @endphp
                        
                            @forelse($id_entreprise as $facture)
                              
                                @if($facture->reglee == 0)
                                    @php
                                    //dd('idi');
                                        //LES FACTURE ECHUE DEPUIS UN MOMENT
                                        $total = $total + $facture->montant;
                                        //dd(gettype($date_aujourdhui));
                                        $diff_in_days = floor(($date_aujourdhui - strtotime($facture->date_reglement)) / (60 * 60 * 24));//on obtient ca en jour
                                        //dd($diff_in_days);
                                    @endphp
                                    @if($diff_in_days >= 60)
                                    
                                        <tr class="bg-red">
                                            <td class="bg-red">{{$facture->nom_entreprise}}</td>
                                            <td class="bg-red">{{$facture->numero_facture}}</td>
                                            
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                            
                                            <td class="bg-red">
                                                @php
                                                    echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                                @endphp
                                            </td>
                                            <td class="bg-red">
                                                <form action="paiement_by_facture" method="post">
                                                        @csrf
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                </form>
                                            </td>
                                            
                                        
                                            <td class="bg-red">
                                            
                                                <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                    @csrf
                                                    
                                                    <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                    <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                </form>
                                            </td>
                                            @if(auth()->user()->id_role == 3)
                                            @else
                                                <td class="bg-red">

                                                    @if($facture->reglee == 0)
                                                        @if(auth()->user()->id_role == 2)
                                                        <form action="paiement_form" method="post">
                                                            @csrf
                                                            <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                        </form>
                                                        @endif
                                                    @else
                                                    
                                                    @endif

                                                    <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                    <i class="fa fa-edit"></i>
                                                    </button>
                                                    
                                                </td>

                                                <td class="bg-red">
                                                    <!--SUPPRESSION AVEC POPUP-->
                                                    <!--<button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$facture->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                    </button>-->
                                                
                                
                                                </td>
                                            @endif
                                        
                                        </tr>
                                    @else
                                        @if($diff_in_days == 30)
                                        
                                            <tr class="bg-warning">
                                            <td class="bg-red">{{$facture->nom_entreprise}}</td>
                                                <td class="bg-warning">{{$facture->numero_facture}}</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                        
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post" target="blank">
                                                            @csrf
                                                            <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                        @csrf
                                                        
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($facture->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post" target="blank">
                                                                @csrf
                                                                <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                            </form>
                                                            @endif
                                                        @else
                                                        
                                                        @endif
                                                        <div class="popup" id="popup">
                                                            
                                                            <div class="popup-contenu"><b>Cliquez et le formulaire s'affiche en dessous</b><br/>
                                                                <a href="#" id="popup-fermeture" onclick="togglePopup();">Fermer</a>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                        <i class="fa fa-edit"></i>
                                                        </button>
                                                    
                                                    </td>

                                                    <td class="bg-warning">

                                                        <!--<button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$facture->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>-->
                                                    </td>
                                                @endif
                                            
                                            </tr>
                                        @else
                                                <tr class="bg-warning">
                                                <td class="bg-warning">{{$facture->nom_entreprise}}</td>
                                                <td class="bg-warning">{{$facture->numero_facture}}</td>
                                                
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                                
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post">
                                                            @csrf
                                                            <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                        @csrf
                                                        
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($facture->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post">
                                                                @csrf
                                                                <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                            </form>
                                                            @endif
                                                        @else
                                                        
                                                        @endif
                                                        <div class="popup" id="popup">
                                                            
                                                            <div class="popup-contenu"><b>Cliquez et le formulaire s'affiche en dessous</b><br/>
                                                                <a href="#" id="popup-fermeture" onclick="togglePopup();">Fermer</a>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                        <i class="fa fa-edit"></i>
                                                        </button>
                                                        
                                                    </td>

                                                    <td class="bg-warning">
                                                        <!--<button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$facture->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>-->
                                                    

                                                    </td>
                                                @endif
                                            
                                            </tr>
                                            
                                        @endif
                                    @endif                              
                                @else
                                
                                    <tr>
                                        <td>{{$facture->nom_entreprise}}</td>
                                        <td >{{$facture->numero_facture}}</td>
                                        
                                        <td>@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                        <td>@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                        <td>
                                            @php
                                                echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                            @endphp
                                        </td>
                                        <td>
                                            <form action="paiement_by_facture" method="post">
                                                    @csrf
                                                    <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                            </form>
                                        </td>
                                        
                                        
                                        <td>
                                        
                                            <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                @csrf
                                                
                                                <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                            </form>
                                        </td>
                                        @if(auth()->user()->id_role == 3)
                                        @else
                                            <td>

                                                @if($facture->reglee == 0)
                                                    @if(auth()->user()->id_role == 2)
                                                    <form action="paiement_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                    </form>
                                                    @endif
                                                @else
                                                
                                                @endif
                                                <div class="popup" id="popup">
                                                    
                                                    <div class="popup-contenu"><b>Cliquez et le formulaire s'affiche en dessous</b><br/>
                                                        <a href="#" id="popup-fermeture" onclick="togglePopup();">Fermer</a>
                                                    </div>
                                                </div>
                                            
                                                <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                <i class="fa fa-edit"></i>
                                                </button>
                                            </td>

                                            <td >
                                                <!--<button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$facture->id.""; @endphp">
                                                <i class="fa fa-trash"></i>
                                                </button>-->
                                            
                                            </td>
                                        @endif
                                    
                                    </tr>
                                @endif
                                
                            @empty

                                <tr colspan="9">
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
                
                <div class="box-footer">
                    @php
                        //echo '<h3>TOTAL DES FACTURES ECHUES:'.$total." XOF</h3>";
                    @endphp
                </div>
            </div>
            
            <!-- /.box -->
        
        </div>
    @else

    @endif
        
        
@endif

<div class="col-xs-12">
            
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Listes des factures/<a href="cfactures">Liste des facutres par clients</a></h3>
                    <br><br>
                    <a href="facture" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a>&emsp;&emsp;&emsp;&emsp;<label>Filtrer par:</label>
                
                    <div class="row">
                    
                        <div class="col-xs-3">
                    
                            <select class="form-control" wire:model.change="etat">
                            
                                <option value="">Etat</option>
                                <option value="1">Réglé</option>
                                <option value="0">non-reglé</option>
                            </select>
                                                        
                        </div>

                        <div class="col-xs-3">
                    
                            <select class="form-control" wire:model.change="id_contrat" id="id_contrat">
                                @php
                                    $contrats = $contratcontroller->RetriveAll();
                                    
                                @endphp
                                
                                @foreach($contrats as $contrats)
                                    <option value="{{$contrats->id}}">{{$contrats->titre_contrat}}</option>
                                @endforeach
                            </select>
                                                        
                        </div>

                        <div class="col-xs-3">
                    
                            <select class="form-control" wire:model.change="annulee" id="annulee">
                                
                                <option value="">Annulée?</option>
                                <option value="1">OUI</option>
                                <option value="0">NON</option>
                            </select>
                                                        
                        </div>

                    </div>

                    <div class="box-tools">
                        <a href="form_add_facture" class="mr-4 d-block"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i> FACTURE</b></button></a><br>
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
               
                    <table class="table table-bordered table-striped ">
                        <thead>
                        <tr>
                            <th>Facture N°</th>
                            <th>Emise le:</th>
                            <th>Date de règlement</th>
                            <!--<th>Contrat</th>-->
                            <!--<th>Client</th>-->
                            <th>Montant</th>
                            <th>Afficher les paiements</th>
                            <th>Fichier</th>
                            <th>Modifier le Fichier</th>
                            @if(auth()->user()->id_role == 3)
                            @else
                            
                                <th>Modifier/Paiement</th>
                                <th>Supprimer</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                
                                $date_aujourdhui = strtotime(date('Y-m-d'));
                            @endphp
                        
                            @forelse($factures as $facture)
                                @if($facture->reglee == 0)
                                    @php
                                    //dd('idi');
                                        //LES FACTURE ECHUE DEPUIS UN MOMENT
                                        $total = $total + $facture->montant;
                                        //dd(gettype($date_aujourdhui));
                                        $diff_in_days = floor(($date_aujourdhui - strtotime($facture->date_reglement)) / (60 * 60 * 24));//on obtient ca en jour
                                        //dump($total);
                                       
                                    @endphp
                                    @if($diff_in_days >= 60)
                                    
                                        <tr class="bg-red">
                                            <td class="bg-red">{{$facture->numero_facture}}</td>
                                            
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                            
                                            <td class="bg-red">
                                                @php
                                                    echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                                @endphp
                                            </td>
                                            <td class="bg-red">
                                                <form action="paiement_by_facture" method="post">
                                                        @csrf
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                </form>
                                            </td>
                                            
                                        
                                            <td class="bg-red">
                                            
                                                <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                    @csrf
                                                    
                                                    <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                    <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                </form>
                                            </td>
                                            <td class="bg-red">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#add".$facture->id.""; @endphp">
                                                <i class="fa fa-upload"></i>
                                                </button>
                                                <div class="modal modal-default fade" id="@php echo "add".$facture->id.""; @endphp">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Fichiers de la facture {{$facture->numero_facture}}</h4>
                                                        </div>
                                                        <!-- form start -->
                                                        <form role="form" method="post" action="upload_file_facture" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <div class="box-body">
                                                                    @csrf
                                                                    <input type="text" name="id_facture" value="{{$facture->id}}" style="display:none;">
                                                                
                                                                    
                                                                        <div class="form-group">
                                                                            <label>Fichier de la facture(PDF)</label>
                                                                            <input type="file" class="form-control" name="file" >
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
                                            @if(auth()->user()->id_role == 3)
                                            @else
                                                <td class="bg-red">

                                                    @if($facture->reglee == 0)
                                                        @if(auth()->user()->id_role == 2)
                                                        <form action="paiement_form" method="post">
                                                            @csrf
                                                            <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                        </form>
                                                        @endif
                                                    @else
                                                    
                                                    @endif

                                                    <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                    <i class="fa fa-edit"></i>
                                                    </button>
                                                    
                                                </td>

                                                <td class="bg-red">
                                                   <button type="button" class="btn btn-danger"  wire:click="confirmDelete(' {{ $facture->numero_facture }} '
                                                        , {{ $facture->id }} )">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                
                                                </td>
                                            @endif
                                        
                                        </tr>
                                    @else
                                        @if($diff_in_days == 30)
                                        
                                            <tr class="bg-warning">
                                                <td class="bg-warning">{{$facture->numero_facture}}</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                        
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post" target="blank">
                                                            @csrf
                                                            <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                        @csrf
                                                        
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                 <td class="bg-warning">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#add".$facture->id.""; @endphp">
                                                    <i class="fa fa-upload"></i>
                                                    </button>
                                                    <div class="modal modal-default fade" id="@php echo "add".$facture->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Fichiers de la facture {{$facture->numero_facture}}</h4>
                                                            </div>
                                                            <!-- form start -->
                                                            <form role="form" method="post" action="upload_file_facture" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <div class="box-body">
                                                                        @csrf
                                                                        <input type="text" name="id_facture" value="{{$facture->id}}" style="display:none;">
                                                                    
                                                                        
                                                                            <div class="form-group">
                                                                                <label>Fichier de la facture(PDF)</label>
                                                                                <input type="file" class="form-control" name="file" >
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
                                                
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($facture->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post" target="blank">
                                                                @csrf
                                                                <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                            </form>
                                                            @endif
                                                        @else
                                                        
                                                        @endif
                                                        <div class="popup" id="popup">
                                                            
                                                            <div class="popup-contenu"><b>Cliquez et le formulaire s'affiche en dessous</b><br/>
                                                                <a href="#" id="popup-fermeture" onclick="togglePopup();">Fermer</a>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                        <i class="fa fa-edit"></i>
                                                        </button>
                                                    
                                                    </td>

                                                    <td class="bg-warning">

                                                      <button type="button" class="btn btn-danger"  wire:click="confirmDelete(' {{ $facture->numero_facture }} '
                                                        , {{ $facture->id }} )">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </td>
                                                @endif
                                            
                                            </tr>
                                        @else
                                                <tr class="bg-warning">
                                                <td class="bg-warning">{{$facture->numero_facture}}</td>
                                                
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                                
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post">
                                                            @csrf
                                                            <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                        @csrf
                                                        
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                <td class="bg-warning">
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#add".$facture->id.""; @endphp">
                                                    <i class="fa fa-upload"></i>
                                                    </button>
                                                    <div class="modal modal-default fade" id="@php echo "add".$facture->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Fichiers de la facture {{$facture->numero_facture}}</h4>
                                                            </div>
                                                            <!-- form start -->
                                                            <form role="form" method="post" action="upload_file_facture" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <div class="box-body">
                                                                        @csrf
                                                                        <input type="text" name="id_facture" value="{{$facture->id}}" style="display:none;">
                                                                    
                                                                        
                                                                            <div class="form-group">
                                                                                <label>Fichier de la facture(PDF)</label>
                                                                                <input type="file" class="form-control" name="file" >
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
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($facture->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post">
                                                                @csrf
                                                                <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                                <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                            </form>
                                                            @endif
                                                        @else
                                                        
                                                        @endif
                                                        <div class="popup" id="popup">
                                                            
                                                            <div class="popup-contenu"><b>Cliquez et le formulaire s'affiche en dessous</b><br/>
                                                                <a href="#" id="popup-fermeture" onclick="togglePopup();">Fermer</a>
                                                            </div>
                                                        </div>
                                                        <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                        <i class="fa fa-edit"></i>
                                                        </button>
                                                        
                                                    </td>

                                                    <td class="bg-warning">
                                                        <button type="button" class="btn btn-danger"  wire:click="confirmDelete(' {{ $facture->numero_facture }} '
                                                        , {{ $facture->id }} )">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    

                                                    </td>
                                                @endif
                                            
                                            </tr>
                                            
                                        @endif
                                    @endif                              
                                @else
                                
                                    <tr>
                                        <td >{{$facture->numero_facture}}</td>
                                        
                                        <td>@php echo date('d/m/Y',strtotime($facture->date_emission)) @endphp</td>
                                        <td>@php echo date('d/m/Y',strtotime($facture->date_reglement)) @endphp</td>
                                        <td>
                                            @php
                                                echo  number_format($facture->montant_facture, 2, ".", " ")." XOF";
                                            @endphp
                                        </td>
                                        <td>
                                            <form action="paiement_by_facture" method="post">
                                                    @csrf
                                                    <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                            </form>
                                        </td>
                                        
                                        
                                        <td>
                                        
                                            <form action="download_file_facture" method="post" enctype="multipart/form-data" target="blank">
                                                @csrf
                                                
                                                <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                <input type="text" class="form-control" name="file" value="{{$facture->file_path}}" style="display:none;">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                            </form>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="@php echo "#add".$facture->id.""; @endphp">
                                                    <i class="fa fa-upload"></i>
                                                    </button>
                                                    <div class="modal modal-default fade" id="@php echo "add".$facture->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Fichiers de la facture {{$facture->numero_facture}}</h4>
                                                            </div>
                                                            <!-- form start -->
                                                            <form role="form" method="post" action="upload_file_facture" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <div class="box-body">
                                                                        @csrf
                                                                        <input type="text" name="id_facture" value="{{$facture->id}}" style="display:none;">
                                                                    
                                                                        
                                                                            <div class="form-group">
                                                                                <label>Fichier de la facture(PDF)</label>
                                                                                <input type="file" class="form-control" name="file" >
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
                                        @if(auth()->user()->id_role == 3)
                                        @else
                                            <td>

                                                @if($facture->reglee == 0)
                                                    @if(auth()->user()->id_role == 2)
                                                    <form action="paiement_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$facture->id}} style="display:none;" name="id_facture">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-money"></i></button>
                                                    </form>
                                                    @endif
                                                @else
                                                
                                                @endif
                                                <div class="popup" id="popup">
                                                    
                                                    <div class="popup-contenu"><b>Cliquez et le formulaire s'affiche en dessous</b><br/>
                                                        <a href="#" id="popup-fermeture" onclick="togglePopup();">Fermer</a>
                                                    </div>
                                                </div>
                                            
                                                <button type="button" class="btn btn-primary" wire:click="EditFacture('{{$facture->id}}')">
                                                <i class="fa fa-edit"></i>
                                                </button>
                                            </td>

                                            <td >
                                              <button type="button" class="btn btn-danger"  wire:click="confirmDelete(' {{ $facture->numero_facture }} '
                                                        , {{ $facture->id }} )">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                            
                                            </td>
                                        @endif
                                    
                                    </tr>
                                @endif
                                
                            @empty

                                <tr colspan="9">
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
                    
                            {{$factures->links()}}
                    
                    
                    </ul>
                        
                </div>
                <div class="box-footer">
                  
                    @php
                        //echo '<h3>TOTAL DES FACTURES ECHUES:'.$total." XOF</h3>";
                    @endphp
                </div>
            </div>
            
            <!-- /.box -->
        
        </div>

      
     


   
     
