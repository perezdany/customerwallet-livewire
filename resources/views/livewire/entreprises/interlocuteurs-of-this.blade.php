<div class="modal fade" id="@php echo "interlocuteurs".$entreprise->id.""; @endphp">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Interlocuteurs</h4>
        </div>
        
            <div class="modal-body">
                <div class="box-body">
                    <table class="table table-bordered table-striped table-hover table-responsive">
                        <thead>
                        <tr>
                            <th>Nom & Prénom(s)</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
                
                           
                        </tr>
                        </thead>
                        @php
                            $interloc = DB::table('interlocuteurs')->where('id_entreprise', $entreprise->id)
                            ->join('professions', 'interlocuteurs.fonction', '=', 'professions.id')
                            ->get(['interlocuteurs.*', 'professions.intitule']);
                        @endphp
                        <tbody>
                            @foreach($interloc as $interloc)
                                <tr>
                                    
                                    <td>{{$interloc->titre}} {{$interloc->nom}}</td>
                                    <td>{{$interloc->tel}}</td>
                                    <td>{{$interloc->email}}</td>
                                    <td>{{$interloc->intitule}}</td>

                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Nom & Prénom(s)</th>
                            <th>Téléphone</th>
                            <th>Email</th>
                            <th>Fonction</th>
                         
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.box-body -->
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn  btn-primary pull-left" data-dismiss="modal">Fermer</button>
                    
                </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>