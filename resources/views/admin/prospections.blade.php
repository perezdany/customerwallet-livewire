@extends('layouts/base')

@php
   
    use App\Http\Controllers\ProspectionController;

    $prospectioncontroller = new ProspectionController();

    //LES DIFFERENTES REQUETES EN FONCTION DU DEPARTEMENT
    $my_own = $prospectioncontroller->MyOwnprospection(auth()->user()->id);

    $all = $prospectioncontroller-> GetAll();
@endphp

@section('content')
      
     <div class="row">
                <div class="col-md-3">
                    <a href="form_add_prospection"><button class="btn btn-primary"> <b>AJOUTER UNE PROSPECTION</b></button></a>
                
                </div>

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
                            
                            <th>Contact(interlocuteur)</th>
                            
                            <th>Suivi effectués/Modifier la prospection</th>
                            <th>Facture proforma:</th>
                            
                            @if(auth()->user()->id_role == 3)
                            @else
                                <th>Compte Rendu</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all as $all)
                                <tr>
                                    <td>@php echo date('d/m/Y',strtotime($all->date_prospection)) @endphp</td>
                                    <td>{{$all->nom_entreprise}}</td>
                                    <td>
                                    @php
                                        //On va écrire un code pour detecter tous les services offerts
                                        $se = DB::table('prospection_service')
                                        ->join('prospections', 'prospection_service.prospection_id', '=', 'prospections.id')
                                        ->join('services', 'prospection_service.service_id', '=', 'services.id') 
                                        ->where('prospection_id', $all->id)    
                                        ->get(['services.libele_service', 'prospection_service.*']);
                                    @endphp
                                    <ul>
                                    @foreach($se as $se_get)
                                        <li>{{$se_get->libele_service}}<a href="delete/{{$se_get->id}}"><button class="btn btn-danger"><i class="fa fa-times"></i></button></a></li>
                                    @endforeach
                                    </ul>
                                    </td>
                                    
                                    <td>{{$all->nom}}/{{$all->tel}}</td>
                                    

                                        <td>
                                        <form action="display_suivi" method="post">
                                            @csrf
                                            <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                                        </form>
                                        @if(auth()->user()->id_role == 3)
                                        @else
                                            <form action="edit_prospect_form" method="post">
                                                @csrf
                                                <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                            </form>
                                        @endif
                                        
                                    </td>
                                    <td>
                                        

                                        <form action="download_facture_proforma" method="post" enctype="multipart/form-data">
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
                                                

                                                    

                                                <form action="download_prospect" method="post" enctype="multipart/form-data">
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
                        <tfoot>
                        <tr>
                        <th>Date</th>
                        <th>Entreprise</th>	
                        <th>prestation proposée</th>
                    
                        <th>Contact/Fonction</th>
                        
                        <th>Suivi effectués/Modifier la prospection</th>
                        <th>Facture proforma:</th>
                        
                        @if(auth()->user()->id_role == 3)
                        @else
                            <th>Compte Rendu</th>
                        @endif
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->

            
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
	</div>
		
@endsection