@php
  //header("Refresh:0");
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\ContratController;
    use App\Http\Controllers\StatutEntrepriseController;
    
    use App\Http\Controllers\TypePrestationController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\CategorieController;

    $statutentreprisecontroller = new StatutEntrepriseController();

    $contratcontroller = new ContratController();

    $categoriecontroller = new CategorieController();

    $servicecontroller = new ServiceController();

    $entreprisecontroller = new EntrepriseController();
    
    $typeprestationcontroller = new TypePrestationController();

    $all = $contratcontroller->RetriveAll();


    /*IMPORTANT ! ECRIRE UN CODE ICI POUR SI A CETTE DATE LE CONTRAT DOIT ETRE RECONDUIT ON ACTUALISE LA DATE DE FIN */
@endphp

<div class="row">
    @include('livewire.contrats.edit')

    @include('livewire.contrats.contrats-list')


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
