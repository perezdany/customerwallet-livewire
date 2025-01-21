@extends('layouts/base')

@php
   
    use App\Http\Controllers\ProspectionController;

    $prospectioncontroller = new ProspectionController();

    //LES DIFFERENTES REQUETES EN FONCTION DU DEPARTEMENT
    $my_own = $prospectioncontroller->MyOwnprospection(auth()->user()->id);

    $all = $prospectioncontroller->GetAll();
@endphp

@section('content')
      
    <div class="row">
        @can("edit")
            <div class="col-md-3">
                <a href="form_add_prospection"><button class="btn btn-primary"> <b><i class="fa fa-plus"></i> PROSPECTION</b></button></a>
            
            </div>
        @endcan
      
    </div>
    
    <div class="row">
                   
        <div class="col-md-12">
            <div class="box">
            
            <!--FAIRE UN ALGO POUR AFFICHER EN FONCTION DU DEPARTEMENT -->
        
                <div class="box-header">
                    <h3 class="box-title">Prospections réalisées</h3>
                </div>
                
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Entreprise</th>	
                            <th>prestation(s) proposée(s)</th>
                            @can("edit")
                                <th>Mod</th>
                            @endcan
                            
                            <th>Facture proforma:</th>
                            
                            <th>Compte Rendu</th>
                           
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all as $all)
                                <tr>
                                    <td>@php echo date('d/m/Y',strtotime($all->date_prospection)) @endphp</td>
                                    <td>
                                        <form method="post" action="display_about_prospect">
                                            @csrf
                                            <input type="text" value="{{$all->id_entreprise}}" style="display:none;" name="id_entreprise">
                                            <button class="btn btn-default"> <b>{{$all->nom_entreprise}}</b></button>
                                        </form>
                                    </td>
                                    <td>
                                        @php
                                            //On va écrire un code pour detecter tous les services offerts <a href="delete/{{$se_get->id}}"><button class="btn btn-danger"><i class="fa fa-times"></i></button></a>
                                            $se = DB::table('prospection_services')
                                            ->join('prospections', 'prospection_services.prospection_id', '=', 'prospections.id')
                                            ->join('services', 'prospection_services.service_id', '=', 'services.id') 
                                            ->where('prospection_id', $all->id)    
                                            ->get(['services.libele_service', 'prospection_services.*']);
                                        @endphp
                                        <ul>
                                        @foreach($se as $se_get)
                                            <li>{{$se_get->libele_service}}</li>
                                        @endforeach
                                        </ul>
                                    </td>
                                    
                                    @can('edit')
                                        <td>
                                            <form action="edit_prospect_form" method="post">
                                                @csrf
                                                <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                            </form>
                                           
                                        </td>
                                    @endcan
                                   
                                    <td>
                                        
                                        <form action="download_facture_proforma" method="post" enctype="multipart/form-data" target="blank">
                                            @csrf
                                            <label>Télécharger</label>
                                            <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                            <input type="text" class="form-control" name="file" value="{{$all->facture_path}}" style="display:none;">
                                            <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
                                        </form>
                                    </td>
                                    
                                
                                    
                                        @if(auth()->user()->id_role == 3)
                                        @else
                                            <td>

                                                <form action="download_prospect" method="post" enctype="multipart/form-data" target="blank">
                                                    @csrf
                                                    <label>Télécharger</label>
                                                    <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                                    <input type="text" class="form-control" name="file" value="{{$all->path_cr}}" style="display:none;">
                                                    <button type="submit" class="btn btn-warning"><i class="fa fa-download"></i></button>
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
	</div>
		
@endsection