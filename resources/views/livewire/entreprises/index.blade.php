@php
    
    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\StatutEntrepriseController;

    use App\Http\Controllers\PaysController;

    use App\Models\Interlcotueur;

    $entreprisecontroller = new EntrepriseController();

    $statutentreprisecontroller = new StatutEntrepriseController();

    $payscontroller = new PaysController();

    //$entreprise = $entreprisecontroller->Getentreprise();

    $statut = $statutentreprisecontroller->GetAll();

    //dd($statut);
    
@endphp

<div class="row">
   
    @include('livewire.entreprises.edit')
    @include('livewire.entreprises.details')
    @include("livewire.entreprises.entreprise-list")

    <script>
        window.addEventListener("showEditModal",  event=>{
            $("#editModal").modal(
                {
                    "show" : true, 
                    "backup": "static"
                }
            )
        })

        //POPUP AFFICHER LES INTERLOCUTEURS

        window.addEventListener("showInterlocuteursModal",  event=>{
            $("#interlocuteurs").modal(
                {
                    "show" : true, 
                    "backup": "static"
                }
            )
        })

        //FERMER LE POPUP DE MODIF
        window.addEventListener("closeEditModal",  event=>{
            $("#editModal").modal("hide")
        })

        //POPUP MODIF
        window.addEventListener("showConfirmMessage",  event=>{
        Swal.fire({
            title: event.detail.message.title,
            text: event.detail.message.text,
            icon: event.detail.message.type,
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Continuer!",
            cancelButtonText: "Annuler!",
            }).then((result) => {
            if (result.isConfirmed) {
            @this.deleteEntreprise(event.detail.message.data.id_entreprise)
            }
            });


            //Message de succès
            window.addEventListener("showSuccessMessage",  event=>{
            Swal.fire({
                position: 'top-end',
                icon: "success",
                toast: true,
                title: event.detail.message || "Opération effectuée avec succès!",
                showCancelButton: false,
                timer: 3000,
                })
            })

            //Message d'erreur
            window.addEventListener("showErrorMessage",  event=>{
        Swal.fire({
                icon: "error",
                title: "Oops...",
                background: "#ff3362" ,
                color: "#fff",
                position: "top",
                text: event.detail.message,
            
                });
            })
        })


        window.addEventListener("showDetail",  event=>{
            $("#details").modal(
                {
                    "show" : true, 
                    "backup": "static"
                }
            )
        })


        window.addEventListener("showSuccessMessage",  event=>{
            Swal.fire({
                position: 'top-end',
                icon: "success",
                toast: true,
                title: event.detail.message || "Opération effectuée avec succès!",
                showCancelButton: false,
                timer: 3000,
                })
            })

        
    </script>   

    

</div>

