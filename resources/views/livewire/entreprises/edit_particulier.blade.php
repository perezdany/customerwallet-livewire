<div class="modal fade"  id="editModalParticulier"  role="dialog" wire:ignore.self>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">   
                    Edition
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- form start -->
            <form wire:submit.prevent="updateEntreprise">
                <div class="modal-body">
                    @csrf
                    <div class="box-body">   
                         <div class="row form-group text-center">
                        <div class="col-sm-4"><label><!--Particulier:--></label></div>
                          
                        <div class="col-sm-8">
                            <select class="form-control" id="id_statutentreprise" wire:model="editEntreprise.particulier" style="display:none;">
                               <option value="0">NON</option>
                               <option value="1">OUI</option>
                            </select>
                        </div>
                        </div><br>
                        <div class="row text-center">
                        <div class="col-sm-4"><label> <label>Nom & prénoms :</label></div>

                        <div class="col-sm-8"><input type="text" wire:model="editEntreprise.nom_entreprise" class="form-control" 
                        onkeyup='this.value=this.value.toUpperCase()' id="nom_entreprise" placeholder="M. KONAN KOFFI" reuqired/>
                        @error('editEntreprise.nom_entreprise') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        </div> <br>

                        <div class="row form-group text-center">
                            <div class="col-sm-4"><label>Client Depuis le :</label></div>
                            <div class="col-sm-8"><input type="date" class="form-control" 
                            id="depuis" wire:model="editEntreprise.client_depuis" /></div>
                        </div><br>

                        <div class="row form-group text-center">
                            <div class="col-sm-4"><label>Adresse(géographique) :</label></div>
                            <div class="col-sm-8"><input type="text" id="adresse" wire:model="editEntreprise.adresse"  class="form-control"  onkeyup='this.value=this.value.toUpperCase()' name="adresse" /></div>
                            @error('editEntreprise.adresse') <span class="error">{{ $message }}</span> @enderror
                        </div><br>
                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label>Statut:</label></div>
                            @php
                                $statut = $statutentreprisecontroller->GetAll();
                            @endphp
                        <div class="col-sm-8">
                            <select class="form-control" id="id_statutentreprise" wire:model="editEntreprise.id_statutentreprise" reuqired>
                                @foreach($statut as $statut)
                                    <option value={{$statut->id}}>{{$statut->libele_statut}}</option>
                                @endforeach
                            </select>

                            @error('editEntreprise.id_statutentreprise') <span class="error">{{ $message }}</span> @enderror
                        </div>
                        </div><br>

                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label >Téléphone (fixe/mobile):</label></div>
                        <div class="col-sm-8"><input type="text" id="telephone" maxlength="18" class="form-control"  wire:model="editEntreprise.telephone" ></div>
                        @error('editEntreprise.tel') <span class="error">{{ $message }}</span> @enderror
                        </div><br>
                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label >Téléphone mobile:</label></div>
                        <div class="col-sm-8"><input type="text" id="mobile" maxlength="18" class="form-control"  wire:model="editEntreprise.mobile" ></div>
                        @error('editEntreprise.tel') <span class="error">{{ $message }}</span> @enderror
                        </div><br>
                        <div class="row form-group text-center">
                            <div class="col-sm-4"><label>Email:</label></div>
                            <div class="col-sm-8"><input type="email" wire:model="editEntreprise.adresse_email"  maxlength="30" class="form-control" 
                            id="email"></div>
                        </div><br>
                        <div class="row form-group text-center">
                            <label>Etat du client</label>
                            <div class="radio col-md-12">
                            
                                <label>
                                
                                <input type="radio" wire:model="editEntreprise.etat" id="optionsRadios1" value="1" >
                                Actif
                                </label><br><br>
                                    <label>
                                <input type="radio" id="optionsRadios2" value="0" wire:model="editEntreprise.etat" checked>
                                Inactif
                                </label>
                            </div>
                        </div>
                      
                        <div class="modal-footer">
                        
                            <button type="button" class="btn btn-danger pull-left" wire:click="closeEditModal">Fermer</button>
                         
                            @if($editHasChanged)

                                <button type="submit" class="btn btn-success">Valider la modification</button>
                                    
                            @endif
                            
                        </div>
                        
                    </div>
             
                </div>
            </form>
           
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

