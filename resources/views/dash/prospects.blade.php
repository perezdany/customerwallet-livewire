@extends('layouts/base')

@php
    
    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\StatutEntrepriseController;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $all = $entreprisecontroller->DisplayProspects();

    
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
                <div class="box-header">
                  <h3 class="box-title">Tableaux des prospects</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead>
                  <tr>
                    <th>Nom</th>
                    
                    <th>Date d'ajout</th>
                    <th>Ajouté par</th>
                    
                  </tr>
                  </thead>
                  <tbody>
                    @foreach($all as $all)
                    <tr>
                        <td>
                          <form method="post" action="display_about_prospect">
                            @csrf
                            <input type="text" value="{{$all->id}}" style="display:none;" name="id_entreprise">
                            <button class="btn btn-default"> <b>{{$all->nom_entreprise}}</b></button>
                          </form>
                        </td>

                        <td>
                            @php 
                            
                                echo "<b>".date('d/m/Y',strtotime($all->created_at))."</b> à <b>".date('H:i:s',strtotime($all->created_at))."</b>" ;
                         
                            @endphp
                        </td>
                        <td>{{$all->nom_prenoms}}</td>  
                    
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                  <tr>
                   <th>Nom</th>
                   
                    <th>Client depuis le:</th>
                    <th>Ajouté par</th>
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
       
    </div>
    <!--/.col (right) -->
@endsection