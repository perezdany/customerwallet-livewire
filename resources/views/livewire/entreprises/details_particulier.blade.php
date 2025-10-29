<div class="modal modal-default fade" id="detailsParticulier" tabindex="-1" role="dialog" wire:ignore-self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Détails</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        
            <div class="modal-body">
                <div class="box-body form-group" style="text-align:center;"> 
                    
                    <label>Nom & Prénom(s):</label>
                    <input class="form-control" wire:model="entrepriseDetail.nom_entreprise" disabled/>

                    <label>Adresse (géographique):</label>
                    <input class="form-control" wire:model="entrepriseDetail.adresse" disabled/>
                    <p>  </p>
                    <label >Téléphone (fixe/mobile):</label>
                    <input class="form-control" wire:model="entrepriseDetail.telephone" disabled/>
                    <label >Téléphone mobile:</label>
                    <input class="form-control" wire:model="entrepriseDetail.mobile" disabled/>
                    <label >Email:</label>
                    <input class="form-control" wire:model="entrepriseDetail.adresse_email" disabled/>
                      
                    <label >Activités:</label>
                    <input class="form-control" wire:model="entrepriseDetail.activite" disabled/>

                    <label >Site web:</label>
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