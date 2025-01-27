<div class="modal modal-default fade" id="detailsParticulier" tabindex="-1" role="dialog" wire:ignore-self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Détails </h4>
            </div>

            <div class="modal-body">
                <div class="box-body form-group" style="text-align:center;"> 
                    
                    <h4><label>Nom & Prénom(s):</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.nom_entreprise" disabled/>

                    <h4><label>Adresse (géographique):</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.adresse" disabled/>
                    <p>  </p>
                    <h4><label >Téléphone (fixe/mobile):</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.telephone" disabled/>
                    <h4><label >Téléphone mobile:</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.mobile" disabled/>
                    <h4><label >Email:</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.adresse_email" disabled/>
                      
                    <h4><label >Activités:</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.activite" disabled/>

                    <h4><label >Site web:</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.site_web" disabled/>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left" data-dismiss="modal">Fermer</button>

                    </div>

                </div>

            </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>