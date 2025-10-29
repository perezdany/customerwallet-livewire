<div class="modal fade"  id="editModal"  role="dialog" wire:ignore.self>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">   
                    Edition d'une entreprise
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
                              
                            </select>
                        </div>
                        </div><br>
                        <div class="row text-center">
                        <div class="col-sm-4"><label> <label>Dénomination(Ou nom & prénoms) :</label></div>

                        <div class="col-sm-8"><input type="text" wire:model="editEntreprise.nom_entreprise" class="form-control" 
                        onkeyup='this.value=this.value.toUpperCase()' id="nom_entreprise" reuqired/>
                        @error('editEntreprise.nom_entreprise') <span class="error">{{ $message }}</span> @enderror
                        </div>

                        </div> <br>

                        <div class="row form-group text-center">
                            <div class="col-sm-4"><label>Client Depuis le :</label></div>
                            <div class="col-sm-8"><input type="date" class="form-control" 
                            id="depuis" wire:model="editEntreprise.client_depuis" /></div>
                        </div><br>

                        <div class="row form-group text-center">
                            <div class="col-sm-4"><label>Adresse :</label></div>
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

                        <div class="row form-gorup text-center">
                        <div class="col-sm-4"> <label >Chiffre d'affaire (FCFA):</label></div>
                        <div class="col-sm-8"><input type="text" id="chiffre_affaire" wire:model="editEntreprise.chiffre_affaire"  maxlength="18" class="form-control" name="chiffre" placeholder="1000000"></div>
                        </div><br>

                         <div class="row form-gorup text-center">
                        <div class="col-sm-4"> <label >Dirigeant:</label></div>
                        <div class="col-sm-8"><input type="text" wire:model="editEntreprise.dirigeant"  maxlength="60" class="form-control" name="chiffre" placeholder="M. ARTHUR VILBRUN"></div>
                        </div><br>

                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label >Nombre d'employés:</label></div>
                        <div class="col-sm-8"><input type="text" id="nb_emp" wire:model="editEntreprise.nb_employes" maxlength="18" class="form-control" ></div>
                        </div><br>

                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label >Téléphone fixe:</label></div>
                        <div class="col-sm-8"><input type="text" id="telephone" maxlength="18" class="form-control"  wire:model="editEntreprise.telephone" ></div>
                        @error('editEntreprise.tel') <span class="error">{{ $message }}</span> @enderror
                        </div><br>

                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label >Téléphone mobile</label></div>
                        <div class="col-sm-8"><input type="text" id="mobile" maxlength="18" class="form-control"  wire:model="editEntreprise.mobile" ></div>
                        @error('editEntreprise.tel') <span class="error">{{ $message }}</span> @enderror
                        </div><br>

                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label>Pays :</label></div>
                            <div class="col-sm-8">
                            <select class="form-control" id="id_pays" wire:model="editEntreprise.id_pays">
                                @php
                                    $pays = $payscontroller->DisplayAll();
                                @endphp
                                @foreach($pays as $pays)
                                    <option value={{$pays->id}}>{{$pays->nom_pays}}</option>
                                    
                                @endforeach   
                            </select>
                            </div>
                        </div><br>
                        <div class="row form-group text-center">
                            <div class="col-sm-4"><label >Activité:</label></div>
                            <div class="col-sm-8"><input type="text" maxlength="60" class="form-control" 
                            id="activite" wire:model="editEntreprise.activite" onkeyup='this.value=this.value.toUpperCase()'></div>
                        </div><br>
                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label>Email:</label></div>
                        <div class="col-sm-8"><input type="email" wire:model="editEntreprise.adresse_email"  maxlength="60" class="form-control" 
                        id="email"></div>
                        </div><br>
                        <div class="row form-group text-center">
                        <div class="col-sm-4"><label>Site web:</label></div>
                        <div class="col-sm-8"><input type="text" wire:model="editEntreprise.site_web"  maxlength="60" class="form-control" 
                        id="site_web"></div>
                        </div><br>

                         <div class="row form-group text-center">
                            <div class="col-sm-4"><label>Année de création:</label></div>
                            <div class="col-sm-8">
                                 <select class="form-control" id="id_pays" wire:model="editEntreprise.date_creation">
                                <option value="">Choisir</option>
                                    @php
                                        $annee_fin = "2060";
                                        for($annee="1980"; $annee<=$annee_fin; $annee++)
                                        {
                                            echo'<option value='.$annee.'>'.$annee.'</option>';
                                        }
                                    @endphp
                                </select>
                             
                            </div>
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

