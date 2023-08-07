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


    $.ajax({
        url: "Controller/RecodificacionController.php?accion=enviar",
        type: "POST",
        data: {
            data: data,
            numSolicitud: numSolicitud
        },
        success: function (response) {
            swal.fire({
                title: "Solicitud enviada",
                icon: "success",
                confirmButtonText: "Aceptar",
            }).then((result) => {
                location.href = "seleccionDeSolicitudes.php";
            })

        }
    })

}