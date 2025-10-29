<div class="modal fade" id="editModal" wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modification</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="updateInterlocuteur">
                <div class="modal-body">
                    @csrf

                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="exampleInputFile">Titre :</label>
                            <select class="form-control " wire:model="editInterlocuteur.titre">
                            
                                <option value="M">M</option>
                                <option value="Mme">Mme</option>
                                <option value="Mlle">Mlle</option>
                            </select>
                            
                        </div>
                        <div class="form-group">
                            <label>Nom & Prénom(s)</label>
                            <input type="text" maxlength="60" class="form-control" wire:model="editInterlocuteur.nom" onkeyup="this.value=this.value.toUpperCase()">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" class="form-control " wire:model="editInterlocuteur.email">
                        </div>

                        <div class="form-group">
                            <label>Téléphone (*)</label>
                            <input type="text" maxlength="30" class="form-control" wire:model="editInterlocuteur.tel" placeholder="(+225)02 14 57 89 31"  >
                        </div>

                        <div class="form-group">
                            <label>Fonction</label>
                            <select class="form-control"  maxlength="60" wire:model="editInterlocuteur.fonction" required>
                                @php
                                    $f = DB::table('professions')->orderBy('id', 'asc')->get();
                                @endphp
                                @foreach($f as $f)
                                    <option value="{{$f->id}}">{{$f->intitule}}</option>
                                @endforeach
                            </select>
                          
                        </div>

                        <div class="form-group">
                            <label>Choisissez l'entreprise :</label>
                            <select class="form-control" wire:model="editInterlocuteur.id_entreprise">
                                @php
                                    $get = $entreprisecontroller->GetAll();
                                @endphp
                                
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>
                        
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="modal-footer justify-content-beetwen">
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Fermer</button>
                        @if($editHasChanged)
                            <button type="submit" class="btn pullu-right btn-success">Valider la modification</button>
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