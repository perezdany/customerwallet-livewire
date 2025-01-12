@php
    //dd($editHasChanged)
@endphp
<div class="modal fade" id="editModal" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Modifier le contrat</h4>
        </div>
            <form wire:submit.prevent="updateContrat" enctype="multipart/form-data">
                <div class="modal-body">
                     @csrf
                    <div class="row">
                        <div class="col-sm-6">  <label>Réfrence du contrat:</label></div>
                        <div class="col-md-6"><input type="text"  maxlength="100" wire:model="editContrat.titre_contrat" class="form-control "/></div>
                    </div> <br>

                     <div class="row">
                        <div class="col-sm-6"><label >Montant (XOF):</label></div>
                        <div class="col-sm-6"><input type="number" class="form-control " wire:model="editContrat.montant" /></div>
                    </div><br><br>

                     <div class="row"> 
                        <div class="col-sm-6"><label >Debut du contrat:</label></div>
                        <div class="col-sm-6">
                        <input type="date" class="form-control " wire:model="editContrat.debut_contrat">
                        </div>
                    </div><br>

                     <div class="row"> 
                        <div class="col-sm-6"><label >Fin du contrat:</label></div>
                        <div class="col-sm-6">
                        <input type="date" class="form-control " wire:model="editContrat.fin_contrat">
                        </div>
                    </div><br>
                    
                     <div class="row"> 
                        <div class="col-sm-6"><label >Type de Facturation</label></div>
                        <div class="col-sm-6">
                         <select class="form-control " name="type" wire:model="editContrat.id_type_prestation">
                                  <!--liste des services a choisir -->
                                  @php
                                      $get = $typeprestationcontroller->GetAll();
                                  @endphp
                                 
                                  @foreach($get as $type)
                                      <option value={{$type->id}}>{{$type->libele}}</option>
                                      
                                  @endforeach
                              </select>
                        </div>
                       
                    </div><br>

                      <!--<div class="form-group">
                            <label>Durée du contrat</label>
                          
                              <div class="row">
                                <div class="col-md-3">
                                  <input type="number" class="form-control" placeholder="jours" min="1" max="365" wire.model="jours" >
                                </div>
                                <div class="col-md-4">
                                  <input type="number" class="form-control" placeholder="mois" min="1" max="12" wire.model="mois">
                                </div>
                                <div class="col-md-5">
                                  <input type="number" class="form-control" placeholder="année" min="1" max="10" wire.model="annee">
                                </div>
                              </div>
                          </div>-->

                    <div class=" row">
                        <div class="col-sm-6"><label>Entreprise:</label></div>
                        @php
                            $get = $entreprisecontroller->GetAll();
                        @endphp
                        <div class="col-sm-6">
                        <select class="form-control select2" wire:model="editContrat.id_entreprise">
                           
                            @foreach($get as $entreprise)
                                <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                
                            @endforeach
                        </select></div>
                    </div>  <br>

                    <!--<div class="row">
                        <div class="col-sm-6"><label>Fichier du contrat(PDF)</label></div>
                        <div class="col-sm-6"> <input type="file" class="form-control" wire:model="editContrat.path"></div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm-6"><label>Facture proforma :</label></div>
                        <div class="col-sm-6"><input type="file" class="form-control" wire:model="editContrat.proforma_file"></div>
                    </div><br>
                    <div class="row">
                        <div class="col-sm-6"><label>Bon de commande(PDF) :</label></div>
                        <div class="col-sm-6"><input type="file" class="form-control" wire:model="editContrat.bon_commande" ></div>
                    </div><br>-->
                    
                     <div class=" row">
                        <div class="col-sm-6"><label >Reconduction:</label></div>
                        <div class="col-sm-6"> 
                            <select class="form-control" wire:model="editContrat.reconduction">
                            
                                    <option value="1">TACITE</option>
                                    <option value="0">NON</option>
                                    
                                    <option value="2">ACCORD PARTIES</option>
                            
                                
                            </select>
                        </div>
                    </div><br>
                      <div class=" row">
                        <div class="col-sm-6"><label >Etat:</label></div>
                        <div class="col-sm-6"> 
                            <select class="form-control" wire:model="editContrat.etat">
                            
                                    <option value="0">TERMINE</option>
                                    <option value="1">EN COURS</option>
                                    
                            </select>
                        </div>
                    </div><br>
                    <div class=" row">
                        <div class="col-sm-6"><label>Avenant ?</label></div>
                        <div class="col-sm-6">
                            <select class="form-control" wire:model="editContrat.avenant" id="mySelectAvenant" onchange="griseFunction1()" >
                        
                                <option value="0">NON</option>
                            
                                <option value="1">OUI</option>
                                
                            </select>
                        </div>
                    </div><br>
                   
                    <div class="row">
                        <div class="col-sm-6"> <label >Contrat Parent:</label></div>
                        <div class="col-sm-6">
                        <select class="form-control select4" wire:model="editContrat.id_contrat_parent" id="id_contrat_parent" disabled required>
                            

                            @php
                                $getparent = $contratcontroller->GetContratParent();
                            @endphp
                            <option value="0">--Choisir le contrat--</option>
                            @foreach($getparent as $getparent)
                                <option value={{$getparent->id}}>{{$getparent->titre_contrat}}/{{$getparent->nom_entreprise}}</option>
                                
                            @endforeach
                            
                        </select></div>
                    </div><br>
                    <script>
                        function griseFunction1() {
                            /* ce script permet d'activer les champ si l'utilisateur choisit autre*/
                            var val = document.getElementById("mySelectAvenant").value;
                            
                            if( val == '1')
                            {
                            document.getElementById("id_contrat_parent").removeAttribute("disabled");
                            
                            }
                            else
                            {
                            document.getElementById("id_contrat_parent").setAttribute("disabled", "disabled");
                            
                            }
                        
                        }
                    </script>   

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