@extends('layouts/base')

@php
   
    use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\PaiementController;
    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\Calculator;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    $prestationcontroller = new PrestationController();

    $calculator = new Calculator();
   
    $paiementcontroller = new PaiementController();
    $facturecontroller = new FactureController();


  //LES DIFFERENTES REQUETES EN FONCTION DU DEPARTEMENT
  //$my_own = $prestationcontroller->MyOwnPrestation(auth()->user()->id);

  $all = $prestationcontroller-> GetAll();


@endphp

@section('content')
    <!--FAIRE ALLERTE POUR AFFICHER LES FACTURES DONT LA DATE EST ECHUE ET AFFICHER EN HAUT ENSUITE METTRE LE TOTAL DES FACTURES NON PAYEES EN BAS -->
    <div class="row">
        <div class="col-md-3">
          <a href="form_add_facture"><button class="btn btn-success"> <b><i class="fa fa-plus"></i>FACTURE</b></button></a>
                
        </div>   

        <div class="col-md-3">
         
        </div>
      </div>
    <!--AFFICHAGE DES FACTURESE-->
    <div class="row">
        @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-green" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif

            @if(session('error'))
            <div class="col-md-12 box-header" style="font-size:13px;">
              <p class="bg-danger" >{{session('error')}}</p>
            </div>
        @endif

   
        @if(session('factures'))<!--ON EST DANS LA VARIABLE SESSION-->
            @php
                $factures = session('factures');
                $etat = session('etat');
                $id_entreprise = session('id_entreprise');
                $message_error = session('message_error');
                $message_success = session('message_succcess');
            @endphp
        @endif
        
        @if(isset($message_success))
            <div class="col-md-12 box-header" style="font-size:13px;">
            <p class="bg-green" >{{$message_success}}</p>
            </div>
        @endif

        @if(isset($message_error))
            <div class="col-md-12 box-header" style="font-size:13px;">
            <p class="bg-danger" >{{$message_error}}</p>
            </div>
        @endif

        @if(isset($factures))
            <div class="col-xs-12">
            
                <div class="box">
                    <div class="box-header">
                    <h3 class="box-title">Factures</h3>
                    
                        <form role="form" method="post" action="make_filter_facture">
                        @csrf
                        <a href="facture" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a>&emsp;&emsp;&emsp;&emsp;<label>Filtrer par:</label>
                        <div class="box-body">
                            <div class="row">
                            
                            <div class="col-xs-3">
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

                            <div class="col-xs-3">
                        
                                <select class="form-control" name="etat">
                                    @if($etat == "c")
                                    <option value="c">Etat</option>
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
                    <table id="example1" class="table table-bordered table-striped ">
                        <thead>
                        <tr>
                            <th>Facture N°</th>
                            <th>Client</th>
                            <th>Emise le:</th>
                            <th>Date de règlement</th>
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
                               
                                $date_aujourdhui = strtotime(Date('Y-m-d'));
                            @endphp
                            @foreach($factures as $factures)
                                @if($factures->reglee == 0)
                                    @php
                                        //LES FACTURE ECHUE DEPUIS UN MOMENT
                                        $total = $total + $factures->montant;
                                        $diff_in_days = floor(($date_aujourdhui - strtotime($factures->date_reglement)) / (60 * 60 * 24));//on obtient ca en jour
                                    @endphp

                                    @if($diff_in_days >= 60)
                                      
                                        <tr class="bg-red">
                                            <td class="bg-red">{{$factures->numero_facture}}</td>
                                            <td class="bg-red">{{$factures->nom_entreprise}}</td>
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($factures->date_emission)) @endphp</td>
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($factures->date_reglement)) @endphp</td>
                                            <td class="bg-red">
                                                @php
                                                    echo  number_format($factures->montant_facture, 2, ".", " ")." XOF";
                                                @endphp
                                            </td>
                                            <td class="bg-red">
                                                <form action="paiement_by_facture" method="post">
                                                        @csrf
                                                        <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                </form>
                                            </td>

                                            <td class="bg-red">
                                            
                                                <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                 
                                                    <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                    <input type="text" class="form-control" name="file" value="{{$factures->file_path}}" style="display:none;">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                </form>
                                            </td>
                                            @if(auth()->user()->id_role == 3)
                                            @else
                                                <td class="bg-red">

                                                    @if($factures->reglee == 0)
                                                        @if(auth()->user()->id_role == 2)
                                                        <form action="paiement_form" method="post">
                                                            @csrf
                                                            <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                                    <form action="edit_facture_form" method="post">
                                                        @csrf
                                                         <!--LES ELEMENTS DU FILTRE-->
                                                        <select class="form-control" name="entreprise" style="display:none">
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
                                                        <select class="form-control" name="etat"  style="display:none">
                                                            @if($etat == "c")
                                                            <option value="c">Etat</option>
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
                                                        <!--FIN ELEMENT FILTRE-->
                                                        <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                        
                                                        <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                    </form>
                                                </td>
                                                <td class="bg-red">
                                                    <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$factures->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                    </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$factures->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_facture" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer la facture {{$factures->numero_facture}}?</p>
                                                                @csrf
                                                                <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                            @endif
                                        
                                        </tr>
                                    @else
                                        @if($diff_in_days == 30)
                                            <tr class="bg-warning">
                                                <td class="bg-warning">{{$factures->numero_facture}}</td>
                                                <td class="bg-warning">{{$factures->nom_entreprise}}</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($factures->date_emission)) @endphp</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($factures->date_reglement)) @endphp</td>
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($factures->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post">
                                                            @csrf
                                                            <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                                
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                    
                                                        <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$factures->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($factures->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post">
                                                                @csrf
                                                                
                                                                <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                                        <form action="edit_facture_form" method="post">
                                                            @csrf
                                                            <!--LES ELEMENTS DU FILTRE-->
                                                            <select class="form-control" name="entreprise" style="display:none">
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
                                                            <select class="form-control" name="etat"  style="display:none">
                                                                @if($etat == "c")
                                                                <option value="c">Etat</option>
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
                                                            <!--FIN ELEMENT FILTRE-->
                                                            <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                            <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                        </form>
                                                    </td>
                                                    <td class="bg-warning">
                                                        <!--SUPPRESSION AVEC POPUP-->
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$factuees->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                        <div class="modal modal-danger fade" id="@php echo "".$factures->id.""; @endphp">
                                                            <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title">Supprimer </h4>
                                                                </div>
                                                                <form action="delete_facture" method="post">
                                                                <div class="modal-body">
                                                                    <p>Voulez-vous supprimer la facture {{$factures->numero_facture}}?</p>
                                                                    @csrf
                                                                    <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                                @endif
                                            
                                            </tr>
                                           
                                        @else
                                            <tr class="bg-warning">
                                                <td class="bg-warning">{{$factures->numero_facture}}</td>
                                                <td class="bg-warning">{{$factures->nom_entreprise}}</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($factures->date_emission)) @endphp</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($factures->date_reglement)) @endphp</td>
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($factures->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post">
                                                            @csrf
                                                            <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                                
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                    
                                                        <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$factures->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($factures->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post">
                                                                @csrf
                                                                
                                                                <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                                        <form action="edit_facture_form" method="post">
                                                            @csrf
                                                            <!--LES ELEMENTS DU FILTRE-->
                                                            <select class="form-control" name="entreprise" style="display:none">
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
                                                            <select class="form-control" name="etat"  style="display:none">
                                                                @if($etat == "c")
                                                                <option value="c">Etat</option>
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
                                                            <!--FIN ELEMENT FILTRE-->
                                                            <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                            <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                        </form>
                                                    </td>
                                                    <td class="bg-warning">
                                                        <!--SUPPRESSION AVEC POPUP-->
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$factures->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                        <div class="modal modal-danger fade" id="@php echo "".$factures->id.""; @endphp">
                                                            <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title">Supprimer </h4>
                                                                </div>
                                                                <form action="delete_facture" method="post">
                                                                <div class="modal-body">
                                                                    <p>Voulez-vous supprimer la facture {{$factures->numero_facture}}?</p>
                                                                    @csrf
                                                                    <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                                @endif
                                            
                                            </tr>
                                   
                                        @endif
                                    @endif
                                        
                                  
                                @else
                                    <tr>
                                        <td >{{$factures->numero_facture}}</td>
                                        <td>{{$factures->nom_entreprise}}</td>
                                        <td>@php echo date('d/m/Y',strtotime($factures->date_emission)) @endphp</td>
                                        <td>@php echo date('d/m/Y',strtotime($factures->date_reglement)) @endphp</td>
                                        <td>
                                            @php
                                                echo  number_format($factures->montant_facture, 2, ".", " ")." XOF";
                                            @endphp
                                        </td>
                                        <td>
                                            <form action="paiement_by_facture" method="post">
                                                    @csrf
                                                    <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                            </form>
                                        </td>
                                        
                                        <td>
                                        
                                            <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                @csrf
                                                
                                                <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                <input type="text" class="form-control" name="file" value="{{$factures->file_path}}" style="display:none;">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                            </form>
                                        </td>
                                        @if(auth()->user()->id_role == 3)
                                        @else
                                            <td >

                                                @if($factures->reglee == 0)
                                                    @if(auth()->user()->id_role == 2)
                                                    <form action="paiement_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                                <form action="edit_facture_form" method="post">
                                                    @csrf
                                                     <!--LES ELEMENTS DU FILTRE-->
                                                        <select class="form-control" name="entreprise" style="display:none">
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
                                                        <select class="form-control" name="etat"  style="display:none">
                                                            @if($etat == "c")
                                                            <option value="c">Etat</option>
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
                                                        <!--FIN ELEMENT FILTRE-->
                                                    <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
                                                    
                                                    <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                </form>
                                            </td>
                                            <td>
                                               <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$factuees->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                    </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$factures->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_facture" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer la facture {{$factures->numero_facture}}?</p>
                                                                @csrf
                                                                <input type="text" value={{$factures->id}} style="display:none;" name="id_facture">
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
                                        @endif
                                    
                                    </tr>
                                @endif
                                
                            @endforeach
                        </tbody>
                        
                    </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        @php
                            echo '<h3>TOTAL DES FACTURES ECHUES:'.$total." XOF</h3>";
                        @endphp
                    </div>
                </div>
                
                <!-- /.box -->
            
            </div>
        @else
            <div class="col-xs-12">
            
                <div class="box">
                    <div class="box-header">
                    <h3 class="box-title">Factures</h3>
                    
                        <form role="form" method="post" action="make_filter_facture">
                        @csrf
                        <a href="facture" style="color:blue"><u>Rétablir<i class="fa fa-refresh" aria-hidden="true"></i></u></a>&emsp;&emsp;&emsp;&emsp;<label>Filtrer par:</label>
                        <div class="box-body">
                            <div class="row">
                            
                            <div class="col-xs-3">
                                <select class="form-control" name="entreprise">
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
                        
                                <select class="form-control" name="etat">
                                
                                    <option value="c">Etat</option>
                                    <option value="1">Réglé</option>
                                    <option value="0">non-reglé</option>
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
                    <table id="example1" class="table table-bordered table-striped ">
                        <thead>
                        <tr>
                            <th>Facture N°</th>
                            <th>Client</th>
                            <th>Emise le:</th>
                            <th>Date de règlement</th>
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
                                $my_own = $facturecontroller->GetAll();
                                $date_aujourdhui = strtotime(date('Y-m-d'));
                            @endphp
                            @foreach($my_own as $my_own)
                                @if($my_own->reglee == 0)
                                    @php
                                    //dd('idi');
                                        //LES FACTURE ECHUE DEPUIS UN MOMENT
                                        $total = $total + $my_own->montant;
                                        //dd(gettype($date_aujourdhui));
                                        $diff_in_days = floor(($date_aujourdhui - strtotime($my_own->date_reglement)) / (60 * 60 * 24));//on obtient ca en jour
                                        //dd($diff_in_days);
                                    @endphp
                                    @if($diff_in_days >= 60)
                                    
                                        <tr class="bg-red">
                                            <td class="bg-red">{{$my_own->numero_facture}}</td>
                                            <td class="bg-red">{{$my_own->nom_entreprise}}</td>
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($my_own->date_emission)) @endphp</td>
                                            <td class="bg-red">@php echo date('d/m/Y',strtotime($my_own->date_reglement)) @endphp</td>
                                            <td class="bg-red">
                                                @php
                                                    echo  number_format($my_own->montant_facture, 2, ".", " ")." XOF";
                                                @endphp
                                            </td>
                                            <td class="bg-red">
                                                <form action="paiement_by_facture" method="post">
                                                        @csrf
                                                        <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                </form>
                                            </td>
                                            
                                        
                                            <td class="bg-red">
                                            
                                                <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                    @csrf
                                                   
                                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                    <input type="text" class="form-control" name="file" value="{{$my_own->file_path}}" style="display:none;">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                </form>
                                            </td>
                                            @if(auth()->user()->id_role == 3)
                                            @else
                                                <td class="bg-red">

                                                    @if($my_own->reglee == 0)
                                                        @if(auth()->user()->id_role == 2)
                                                        <form action="paiement_form" method="post">
                                                            @csrf
                                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                                    <form action="edit_facture_form" method="post">
                                                        @csrf
                                                        <!--LES ELEMENTS DU FILTRE-->
                                                            <select class="form-control" name="entreprise" style="display:none;">
                                                                <option value="all">Entreprises</option>
                                                                @php
                                                                    $get = (new EntrepriseController())->GetAll();
                                                                @endphp
                                                            
                                                                @foreach($get as $entreprise)
                                                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                                    
                                                                @endforeach
                                                                
                                                            </select>   
                                                           <select class="form-control" name="etat" style="display:none;">
                                                                <option value="c">Etat</option>
                                                                <option value="1">Réglé</option>
                                                                <option value="0">non-reglé</option>
                                                            </select> 
                                                            <!--FIN ELEMENT FILTRE-->
                                                        <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                        
                                                        <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                    </form>
                                                </td>

                                                <td class="bg-red">
                                                    <!--SUPPRESSION AVEC POPUP-->
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$my_own->id.""; @endphp">
                                                    <i class="fa fa-trash"></i>
                                                    </button>
                                                    <div class="modal modal-danger fade" id="@php echo "".$my_own->id.""; @endphp">
                                                        <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Supprimer </h4>
                                                            </div>
                                                            <form action="delete_facture" method="post">
                                                            <div class="modal-body">
                                                                <p>Voulez-vous supprimer la facture {{$my_own->numero_facture}}?</p>
                                                                @csrf
                                                                <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                            @endif
                                        
                                        </tr>
                                    @else
                                        @if($diff_in_days == 30)
                                        
                                            <tr class="bg-warning">
                                                <td class="bg-warning">{{$my_own->numero_facture}}</td>
                                                <td class="bg-warning">{{$my_own->nom_entreprise}}</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($my_own->date_emission)) @endphp</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($my_own->date_reglement)) @endphp</td>
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($my_own->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post">
                                                            @csrf
                                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        
                                                        <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$my_own->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($my_own->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post">
                                                                @csrf
                                                                <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                                        <form action="edit_facture_form" method="post">
                                                            @csrf
                                                              <!--LES ELEMENTS DU FILTRE-->
                                                            <select class="form-control" name="entreprise" style="display:none;">
                                                                <option value="all">Entreprises</option>
                                                                @php
                                                                    $get = (new EntrepriseController())->GetAll();
                                                                @endphp
                                                            
                                                                @foreach($get as $entreprise)
                                                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                                    
                                                                @endforeach
                                                                
                                                            </select>   
                                                           <select class="form-control" name="etat" style="display:none;">
                                                                <option value="c">Etat</option>
                                                                <option value="1">Réglé</option>
                                                                <option value="0">non-reglé</option>
                                                            </select> 
                                                            <!--FIN ELEMENT FILTRE-->
                                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                            
                                                            <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                        </form>
                                                    </td>

                                                    <td class="bg-warning">

                                                           <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$my_own->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                        <div class="modal modal-danger fade" id="@php echo "".$my_own->id.""; @endphp">
                                                            <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title">Supprimer </h4>
                                                                </div>
                                                                <form action="delete_facture" method="post">
                                                                <div class="modal-body">
                                                                    <p>Voulez-vous supprimer la facture {{$my_own->numero_facture}}?</p>
                                                                    @csrf
                                                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                                @endif
                                            
                                            </tr>
                                        @else
                                             <tr class="bg-warning">
                                                <td class="bg-warning">{{$my_own->numero_facture}}</td>
                                                <td class="bg-warning">{{$my_own->nom_entreprise}}</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($my_own->date_emission)) @endphp</td>
                                                <td class="bg-warning">@php echo date('d/m/Y',strtotime($my_own->date_reglement)) @endphp</td>
                                                <td class="bg-warning">
                                                    @php
                                                        echo  number_format($my_own->montant_facture, 2, ".", " ")." XOF";
                                                    @endphp
                                                </td>
                                                <td class="bg-warning">
                                                    <form action="paiement_by_facture" method="post">
                                                            @csrf
                                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                            <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                                    </form>
                                                </td>
                                            
                                                <td class="bg-warning">
                                                
                                                    <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                      
                                                        <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                        <input type="text" class="form-control" name="file" value="{{$my_own->file_path}}" style="display:none;">
                                                        <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                                    </form>
                                                </td>
                                                @if(auth()->user()->id_role == 3)
                                                @else
                                                    <td class="bg-warning">

                                                        @if($my_own->reglee == 0)
                                                            @if(auth()->user()->id_role == 2)
                                                            <form action="paiement_form" method="post">
                                                                @csrf
                                                                <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                                        <form action="edit_facture_form" method="post">
                                                            @csrf
                                                              <!--LES ELEMENTS DU FILTRE-->
                                                            <select class="form-control" name="entreprise" style="display:none;">
                                                                <option value="all">Entreprises</option>
                                                                @php
                                                                    $get = (new EntrepriseController())->GetAll();
                                                                @endphp
                                                            
                                                                @foreach($get as $entreprise)
                                                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                                    
                                                                @endforeach
                                                                
                                                            </select>   
                                                           <select class="form-control" name="etat" style="display:none;">
                                                                <option value="c">Etat</option>
                                                                <option value="1">Réglé</option>
                                                                <option value="0">non-reglé</option>
                                                            </select> 
                                                            <!--FIN ELEMENT FILTRE-->
                                                            <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                            
                                                            <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                        </form>
                                                    </td>

                                                    <td class="bg-warning">
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$my_own->id.""; @endphp">
                                                        <i class="fa fa-trash"></i>
                                                        </button>
                                                        <div class="modal modal-danger fade" id="@php echo "".$my_own->id.""; @endphp">
                                                            <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span></button>
                                                                <h4 class="modal-title">Supprimer </h4>
                                                                </div>
                                                                <form action="delete_facture" method="post">
                                                                <div class="modal-body">
                                                                    <p>Voulez-vous supprimer la facture {{$my_own->numero_facture}}?</p>
                                                                    @csrf
                                                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                                @endif
                                            
                                            </tr>
                                            
                                        @endif
                                    @endif
                                       

                                    
                                @else
                               
                                    <tr>
                                        <td >{{$my_own->numero_facture}}</td>
                                        <td>{{$my_own->nom_entreprise}}</td>
                                        <td>@php echo date('d/m/Y',strtotime($my_own->date_emission)) @endphp</td>
                                        <td>@php echo date('d/m/Y',strtotime($my_own->date_reglement)) @endphp</td>
                                        <td>
                                            @php
                                                echo  number_format($my_own->montant_facture, 2, ".", " ")." XOF";
                                            @endphp
                                        </td>
                                        <td>
                                            <form action="paiement_by_facture" method="post">
                                                    @csrf
                                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                    <button type="submit" class="btn btn-success"><i class="fa fa-money"></i>AFFICHER</button>
                                            </form>
                                        </td>
                                        
                                       
                                        <td>
                                        
                                            <form action="download_file_facture" method="post" enctype="multipart/form-data">
                                                @csrf
                                               
                                                <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                <input type="text" class="form-control" name="file" value="{{$my_own->file_path}}" style="display:none;">
                                                <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                            </form>
                                        </td>
                                        @if(auth()->user()->id_role == 3)
                                        @else
                                            <td class="bg-warning">

                                                @if($my_own->reglee == 0)
                                                    @if(auth()->user()->id_role == 2)
                                                    <form action="paiement_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                                <form action="edit_facture_form" method="post">
                                                    @csrf
                                                      <!--LES ELEMENTS DU FILTRE-->
                                                            <select class="form-control" name="entreprise" style="display:none;">
                                                                <option value="all">Entreprises</option>
                                                                @php
                                                                    $get = (new EntrepriseController())->GetAll();
                                                                @endphp
                                                            
                                                                @foreach($get as $entreprise)
                                                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                                                    
                                                                @endforeach
                                                                
                                                            </select>   
                                                           <select class="form-control" name="etat" style="display:none;">
                                                                <option value="c">Etat</option>
                                                                <option value="1">Réglé</option>
                                                                <option value="0">non-reglé</option>
                                                            </select> 
                                                            <!--FIN ELEMENT FILTRE-->
                                                    <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
                                                    
                                                    <button  type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                                </form>
                                            </td>

                                            <td >
                                                <button type="button" class="btn btn-danger" data-toggle="modal" data-target="@php echo "#".$my_own->id.""; @endphp">
                                                <i class="fa fa-trash"></i>
                                                </button>
                                                <div class="modal modal-danger fade" id="@php echo "".$my_own->id.""; @endphp">
                                                    <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Supprimer </h4>
                                                        </div>
                                                        <form action="delete_facture" method="post">
                                                        <div class="modal-body">
                                                            <p>Voulez-vous supprimer la facture {{$my_own->numero_facture}}?</p>
                                                            @csrf
                                                             <input type="text" value={{$my_own->id}} style="display:none;" name="id_facture">
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
                                        @endif
                                    
                                    </tr>
                                @endif
                                
                            @endforeach
                        </tbody>
                        
                    </table>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        @php
                            echo '<h3>TOTAL DES FACTURES ECHUES:'.$total." XOF</h3>";
                        @endphp
                    </div>
                </div>
                
                <!-- /.box -->
            
            </div>

        @endif
    </div>

  
		
@endsection