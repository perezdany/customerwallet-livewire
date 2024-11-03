@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ProspectionController;

    $entreprisecontroller = new EntrepriseController();

    $prospectioncontroller = new ProspectionController();


@endphp

@section('content')
    @if(isset($id_entreprise))
        
        @php
            $prospections = $prospectioncontroller->GetProspectionByIdEntr($id_entreprise);
        @endphp
         
        <div class="row">
            <div class="col-md-3">
                <a href="form_add_prospection"><button class="btn btn-primary"> <b>AJOUTER UNE PROSPECTION</b></button></a>
            
            </div>
        </div>
        <div class="row">
          
        
            <!-- left column -->
            <div class="col-md-12">
                  <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Prospections</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                            <tr>
                                <th>Date de la prospection</th>
                                
                                <th>Contact(interlocuteur)</th>
                                <th>Ajouté par:</th>
                                <th>Suivi effectués</th>
                   
                                 <th>Facture proforma:</th>
                                  @if(auth()->user()->id_role == 3)
                                  @else
                                        <th>Compte Rendu</th>
                                  @endif
                                
                                @if(auth()->user()->id_role == 3)
                                @else
                                    <th>Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($prospections as $all)
                                    <tr>
                                        <td>@php echo date('d/m/Y',strtotime($all->date_prospection)) @endphp</td>
                                      
                                       
                                        <td>{{$all->nom}}/tel:{{$all->tel}}/<b>fonction:{{$all->fonction}}</b></td>
                                        <td>{{$all->nom_prenoms}}</td>
                                        <td><form action="display_suivi" method="post">
                                                @csrf
                                                <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                                            </form>
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
                                       
                                            @if(auth()->user()->id_role == 3)
                                            @else
                                                <td>
                                                    <form action="edit_prospect_form" method="post">
                                                        @csrf
                                                        <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                                        <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                                    </form>
                                                </td>

                                            @endif
                                           
                                            
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Date de la prospection</th>
                    
                                <th>Contact(interlocuteur)</th>
                                <th>Ajouté par:</th>
                                <th>Suivi effectués</th>
                                <th>Facture proforma:</th>
                                @if(auth()->user()->id_role == 3)
                                @else
                                    <th>Compte Rendu</th>
                                @endif
                                @if(auth()->user()->id_role == 3)
                                @else
                                    <th>Action</th>
                                @endif
                            </tr>
                            </tfoot>
                    </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            
            
        </div>
        <!--/.col (right) -->
    @endif
    
@endsection