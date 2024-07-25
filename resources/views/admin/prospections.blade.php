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
      
         @if(session('success'))
            <div class="col-md-12 box-header">
              <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
            </div>
          @endif
        
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
                            <th>prestation proposée</th>
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
                                @foreach($all as $all)
                                    <tr>
                                        <td>@php echo date('d/m/Y',strtotime($all->date_prospection)) @endphp</td>
                                        <td>{{$all->nom_entreprise}}</td>
                                        <td>{{$all->libele_service}}</td>
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
                            <th>Entreprise</th>	
                            <th>prestation proposée</th>
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
			<!-- /.col -->
		  </div>
		
@endsection