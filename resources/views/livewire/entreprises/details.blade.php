<div class="modal modal-default fade" id="details" tabindex="-1" role="dialog" wire:ignore-self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Détails </h4>
            </div>

            <div class="modal-body">
                <div class="box-body form-group" style="text-align:center;"> 
                    
                    <h4><label>Dénomination:</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.nom_entreprise" disabled/>

                    <h4><label>Adresse :</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.adresse" disabled/>
                    <p>  </p>
                    <h4><label >Téléphone (fixe/mobile):</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.telephone" disabled/>
                    <h4><label >Email:</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.adresse_email" disabled/>
                   
                    <h4><label >Chiffre d'affaire (FCFA):</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.chiffre_affaire" disabled/>
                   
                    <h4><label >Nombre d'employés:</label></h4>
                    <input class="form-control" wire:model="entrepriseDetail.nb_employes" disabled/>
                   
                    <h4><label >Activités:</label></h4>
                     <input class="form-control" wire:model="entrepriseDetail.activite" disabled/>

                     <h4><label >Site web:</label></h4>
                     <input class="form-control" wire:model="entrepriseDetail.site_web" disabled/>

                     <h4><label >Date de création:</label></h4>
                     <input class="form-control" wire:model="entrepriseDetail.date_creation" disabled/>
                  
                    <h4><label>Pays :</label></h4>
       
                    <select class="form-control" id="id_pays" wire:model="entrepriseDetail.id_pays" disabled>
                        
                        @php
                            $pays = $payscontroller->DisplayAll();
                        @endphp
                        @foreach($pays as $pays)
                            <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                            
                        @endforeach
                        
                    </select>
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