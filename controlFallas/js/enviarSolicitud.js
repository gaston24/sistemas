const enviar = () => {

    let numSolicitud = document.querySelector("#numSolicitud").value;    
    let allTr = document.querySelectorAll("tbody tr");
    let data = []
    allTr.forEach(element => {

        data.push({
            id: element.querySelectorAll("td")[8].textContent,
            remito : element.querySelectorAll("td")[7].querySelector("select").value,
        })

    });

    Swal.fire({
        icon: 'info',
        title: 'Desea enviar la solicitud?',
        showDenyButton: true,
        confirmButtonText: 'Enviar',
        denyButtonText: 'No enviar',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
         
            $.ajax({
                url: "Controller/RecodificacionController.php?accion=enviar",
                type: "POST",
                data: {
                    data: data,
                    numSolicitud: numSolicitud
                },
                success: function (response) {
                    Swal.fire('La solicitud fue enviada!', '', 'success').then((result) => {

                        location.href = "seleccionDeSolicitudes.php";
                    })
                }
            })
       
        } else if (result.isDenied) {
        Swal.fire('La solicitud no fue enviada!', '', 'info')
        }
        })
        
        



}