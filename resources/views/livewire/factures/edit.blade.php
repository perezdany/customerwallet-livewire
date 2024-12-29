<div class="modal fade" id="editModal" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Modifier la Facture</h4>
        </div>
            <form wire:submit.prevent="updateFacture" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf

                    <div class="box-body">
                        
                        <div class="form-group">
                            <label>Numéro de la facture (*)</label>
                            <input type="text" wire:model="editFacture.numero_facture" class="form-control" maxlength="30" >
                        </div>
                    
                        
                        <div class="form-group">
                            <label for="exampleInputEmail1">Date d'emission de la facture:</label>
                            <input type="date" class="form-control" wire:model="editFacture.date_emission">
                        </div>
                            
                        <div class="form-group">
                            <label for="exampleInputEmail1">Montant de la facture:</label>
                            <input type="number" class="form-control" wire:model="editFacture.montant_facture" maxlength="13">
                        </div>
                            
                        <div class="form-group">
                            <label>Prestation/Contrat:</label>
                            <select class="form-control" wire:model="editFacture.id_contrat" required>
                                    @php
                                        $contrats = $prestationcontroller->getAllNoReglee();
                                        
                                    @endphp
                                    
                                    @foreach($contrats as $contrats)
                                        <option value="{{$contrats->id}}">Contrat:{{$contrats->titre_contrat}}/Date:@php echo date('d/m/Y',strtotime($contrats->date_prestation));  @endphp</option>
                                    @endforeach
                            </select>
                        </div>

                        <!--<div class="form-group">
                            <label>Fichier de la facture(PDF)</label>
                            <input type="file" class="form-control" wire:model="editFacture.file_path">
                        </div>-->

                        <div class="form-group">
                            <label>Annulée:</label>
                            <select class="form-control" wire:model="editFacture.annulee" required>
                                    @php
                                        $contrats = $prestationcontroller->getAllNoReglee();
                                        
                                    @endphp
                                    
                                    <option value="0">NON</option>
                                    <option value="1">OUI</option>
                                    
                            </select>
                        </div>
                
                    
                    </div>
                            <!-- /.box-body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Fermer</button>
                           @if($editHasChanged)

                                <button type="submit" class="btn btn-success">Valider la modification</button>
                                    
                            @endif
                    </div>
                </div>
            </form>
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>    
<!-- /.modal -->