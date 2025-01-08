@php
   
use App\Http\Controllers\InterlocuteurController;

$interlocuteurcontroller = new InterlocuteurController();

use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\UserController;

$entreprisecontroller = new EntrepriseController();
$usercontroller = new UserController();

$all =  $interlocuteurcontroller-> GetAll();

  //dd($all);
@endphp
<div class="row">
    @include('livewire.interlocuteurs.add')
    @include('livewire.interlocuteurs.interlocuteurs-list')


    <script>
        window.addEventListener("showEditModal",  event=>{
            $("#editModal").modal(
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

        //POPUP DELETE
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
            @this.deleteFacture(event.detail.message.data.id_facture)
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
            

        
    </script>   
</div>