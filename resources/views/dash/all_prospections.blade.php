@extends('layouts/base')

@php
   
    use App\Http\Controllers\ProspectionController;

    $prospectioncontroller = new ProspectionController();

    $all = $prospectioncontroller-> GetAll();
@endphp

@section('content')
     <div class="row">
      
        

            <div class="row">
                @if(session('success'))
                    <div class="col-md-12 card-header">
                    <p class="bg-success" style="font-size:13px;">{{session('success')}}</p>
                    </div>
                @endif
                <div class="col-md-3">
                    <a href="form_add_prospection"><button class="btn btn-primary"> <b>AJOUTER UNE PROSPECTION</b></button></a>
                
                </div>
            </div>
        
			<div class="col-md-12">
                <div class="card">
                     <div class="card-header">
                        <h3 class="card-title">Prospections réalisées</h3>
                    </div>
                    
                    <!-- /.card-header -->
                    <div class="card-body">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Entreprise</th>	
                            <th>prestation proposée</th>
                           
                            <th>Référant/Fonction</th>
                        
                            <th>Suivi effectués</th>
                       
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($all as $all)
                                <tr>
                                    <td>@php echo date('d/m/Y',strtotime($all->date_prospection)) @endphp</td>
                                    <td>{{$all->nom_entreprise}}</td>
                                     <td>{{$all->libele_service}}</td>
                                   
                                    <td>{{$all->tel}}/{{$all->fonction}}</td>
                                    <td><form action="display_suivi" method="post">
                                            @csrf
                                            <input type="text" value={{$all->id}} style="display:none;" name="id_prospection">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-eye"></i></button>
                                        </form>
                                        </td>
                                   
                                    <td>
                                    
                                        
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                    </div>
                    <!-- /.card-body -->
                </div>
			  <!-- /.card -->
			</div>
			<!-- /.col -->
		  </div>
		
@endsection