<div class="modal modal-default fade" id="details" tabindex="-1" role="dialog" wire:ignore-self>
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
                    
                    <label>Dénomination:</label>
                    <input class="form-control" wire:model="entrepriseDetail.nom_entreprise" disabled/>

                    <label>Adresse :</label>
                    <input class="form-control" wire:model="entrepriseDetail.adresse" disabled/>
                    <p>  </p>
                    <label >Téléphone fixe:</label>
                    <input class="form-control" wire:model="entrepriseDetail.telephone" disabled/>
                     <label >Téléphone mobile:</label>
                    <input class="form-control" wire:model="entrepriseDetail.mobile" disabled/>
                    <label >Email:</label>
                    <input class="form-control" wire:model="entrepriseDetail.adresse_email" disabled/>
                   
                    <label >Chiffre d'affaire (FCFA):</label>
                    <input class="form-control" wire:model="entrepriseDetail.chiffre_affaire" disabled/>
                   
                    <label >Dirigeant:</label>
                    <input class="form-control" wire:model="entrepriseDetail.dirigeant" disabled/>
                   
                    <label >Nombre d'employés:</label>
                    <input class="form-control" wire:model="entrepriseDetail.nb_employes" disabled/>
                   
                    <label >Activités:</label>
                    <input class="form-control" wire:model="entrepriseDetail.activite" disabled/>

                    <label >Site web:</label>
                    <input class="form-control" wire:model="entrepriseDetail.site_web" disabled/>

                    <label >Date de création:</label>
                    <input class="form-control" wire:model="entrepriseDetail.date_creation" disabled/>
                  
                    <label>Pays :</label>
       
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