<div class="modal modal-default fade" id="add">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ajouter un interlocuteur </h4>
            </div>
            
            <div class="modal-body">
                <!-- form start -->
                <form role="form" action="add_referant" method="post">
                    @csrf
                    <div class="box-body">

                        <div class="form-group">
                            
                            <select class="form-control " name="entreprise">
                                @php
                                    $get = $entreprisecontroller->GetAll();
                                @endphp
                                <option value="0">--Selectionnez Une entreprise--</option>
                                @foreach($get as $entreprise)
                                    <option value={{$entreprise->id}}>{{$entreprise->nom_entreprise}}</option>
                                    
                                @endforeach
                                
                            </select>
                            
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="exampleInputFile">Titre :</label>
                            <select class="form-control " name="titre">
                                <option value="M">M</option>
                                <option value="Mme">Mme</option>
                                <option value="Mlle">Mlle</option>
                            </select>
                            
                        </div>
                        <div class="form-group">
                                <label >Nom & Prénom(s)</label>
                                <input type="text"  maxlength="100" class="form-control" required name="nom" onkeyup="this.value=this.value.toUpperCase()">
                        </div>

                        <div class="form-group">
                                <label>Email</label>
                                <input type="email"  maxlength="30" class="form-control" name="email" >
                            </div>

                        <div class="form-group">
                                <label>Téléphone (*)</label>
                                <input type="text"  maxlength="30"   class="form-control " required name="tel" placeholder="(+225)0214578931" >
                            </div>

                        <div class="form-group">
                            <label>Fonction (Choisir "Autre" si inexistant)</label>
                                <select class="form-control"  onchange="newFonction();" name="fonction" id="grise4" required>
                                @php
                                    $f = DB::table('professions')->orderBy('id', 'asc')->get();
                                @endphp
                                @foreach($f as $f)
                                    <option value="{{$f->id}}">{{$f->intitule}}</option>
                                @endforeach
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="form-group">
                                <label>Fonction:(*)</label>
                                <input type="text" disabled="disabled" required id="newf" maxlength="60"   class="form-control " name="new_fonction" onkeyup="this.value=this.value.toUpperCase()" >
                        </div>  
                        <script>
                            function newFonction()
                            {
                                
                                var f = document.getElementById("grise4").value;
                                //alert(f);
                                if(f == 'autre')
                                {
                                    document.getElementById("newf").removeAttribute("disabled");
                                }
                                else{
                                    document.getElementById("newf").setAttribute("disabled", "disabled");
                                }
                            }
                        </script>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn  pull-left" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Valider</button>
                    </div>
                </form>
                                
            
            </div>
        
            
        </div>
        <!-- /.modal-content -->
    </div>
</div> 
<!-- /.modal-dialog -->