@extends('layouts/base')

@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;

    use App\Http\Controllers\PrestationController;

    use App\Http\Controllers\ProspectionController;

    $contratcontroller = new ContratController();
    $entreprisecontroller = new EntrepriseController();
    $prestationcontroller = new PrestationController();
    $prospectioncontroller = new ProspectionController();

    $my_own =  $contratcontroller->MyOwnContrat(auth()->user()->id);

    $all = $contratcontroller->RetriveAll();
@endphp

@section('content')
    @if(isset($id_entreprise))
        
        @php
       
            $contrats = $contratcontroller->GetContratByIdEntr($id_entreprise);
           
            $prestations = $prestationcontroller->GetPrestationByIdEntr($id_entreprise);

            $prospections = $prospectioncontroller->GetProspectionByIdEntr($id_entreprise);
        @endphp
        <div class="row">
            @if(session('success'))
                <div class="col-md-12 box-header">
                <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
                </div>
            @endif

             <!-- left column -->
            <div class="col-md-12">
                  <div class="box">
                    <div class="box-header">
                    <h3 class="box-title">Prestations réalisées</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                       
                         <th>Sercice proposé</th>
                          <th>Date</th>
                        <th>Type de prestation</th>

                        @if(auth()->user()->id_role == 3)
                        @else
                            <th>Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($prestations as $all)
                            <tr>

                                <td>{{$all->libele_service}}</td>
                                 <td>@php echo date('d/m/Y',strtotime($all->date_prestation)) @endphp</td>
                                <td>{{$all->libele}}</td>
                                
                                @if(auth()->user()->id_role == 3)
                                    
                                @else
                                    <td>
                                    @if(auth()->user()->id_role == 2)
                                        <form action="display_facture" method="post">
                                            @csrf
                                            <input type="text" value={{$all->id}} style="display:none;" name="id_prestation">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-ticket"></i></button>
                                        </form>
                                    @else

                                    @endif

                                    
                                    <form action="edit_prestation_form" method="post">
                                        @csrf
                                        <input type="text" value={{$all->id}} style="display:none;" name="id_prestation">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-edit"></i></button>
                                    </form>

                                    </td>
                                @endif

                                     
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Sercice proposé</th>
                        <th>Date</th>
                       	<th>Type de prestation</th>
                    
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
            <!-- /.row -->
        <div class="row">
          
           <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Contrats</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Numéro de contrat</th>
                        
                        <th>Début du contrat</th>
                        <th>Fin du contrat</th>
                        <th>Montant</th>	
                    
                        @if(auth()->user()->id_role == 3)
                        @else
                            <th>Action</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($contrats as $all)
                            <tr>
                            <td>{{$all->titre_contrat}}</td>
                            
                            <td>@php echo date('d/m/Y',strtotime($all->debut_contrat)) @endphp</td>
                            <td>@php echo date('d/m/Y',strtotime($all->fin_contrat)) @endphp</td>
                            <td>
                                @php
                                echo  number_format($all->montant, 2, ".", " ")." XOF";
                                @endphp
                            
                            </td>  
                            @if(auth()->user()->id_role == 3)
                            @else
                                <td>
                                <form action="edit_contrat_form" method="post">
                                    @csrf
                                    <input type="text" value={{$all->id}} style="display:none;" name="id_contrat">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-edit"></i></button>
                                </form>
                                </td>
                            @endif  
                            
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Numéro de contrat</th>
                        <th>Début du contrat</th>
                        <th>Fin du contrat</th>
                        <th>Montant</th>	
                    
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
                <!-- /.col -->

        </div>
        <!--/.col (right) -->

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
                                <th>Date</th>
                                <th>Date de fin de prospection</th>
                                <th>Contact/Fonction</th>
                                <th>Ajouté par:</th>
                                <th>Suivi effectués</th>
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
                                      
                                        <td>@php echo date('d/m/Y',strtotime($all->date_fin)) @endphp</td>
                                        <td>{{$all->tel}}/{{$all->fonction}}</td>
                                        <td>{{$all->nom_prenoms}}</td>
                                        <td><form action="display_suivi" method="post">
                                                @csrf
                                                <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                                <button type="submit" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                                            </form>
                                            </td>
                                    
                                       
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
                            <th>Date</th>
                            
                            
                            <th>Date de fin de prospection</th>
                            <th>Contact/Fonction</th>
                            <th>Ajouté par:</th>
                            <th>Suivi effectués</th>
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