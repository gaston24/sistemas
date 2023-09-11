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

            let error = false;

            document.querySelectorAll("#selectRemito").forEach(element => {

                if(element.getAttribute("remitoAprobado") == "false"){
                    Swal.fire('Debe seleccionar un remito valido para cada articulo', '', 'error')
                    error = true
                }
            });

            if(error) return 1;



            $.ajax({
                url: "Controller/RecodificacionController.php?accion=enviar",
                type: "POST",
                data: {
                    data: data,
                    numSolicitud: numSolicitud
                },
                success: function (response) {
                    $.ajax({
                        url: "Controller/SendEmailController.php?accion=enviarSolicitud",
                        type: "POST",
                        data: {
                            numSolicitud: numSolicitud
                        },
                        success: function (response) {
                    
                            Swal.fire('La solicitud fue enviada!', '', 'success').then((result) => {
        
                                location.href = "seleccionDeSolicitudes.php";
                            })

                        }
                    })
                    
                }
            })
       
        } else if (result.isDenied) {
        Swal.fire('La solicitud no fue enviada!', '', 'info')
        }
        })
        
        



}

const comprobarArticuloEnRemito = (div) => {
    let nComp = div.value
    let articulo = div.parentElement.parentElement.querySelectorAll("td")[0].textContent

    $.ajax({
        url: "Controller/RecodificacionController.php?accion=comprobarArticuloEnRemito",
        type: "POST",
        data: {
            nComp: nComp,
            articulo: articulo
        },
        success: function (response) {

            if(response == false){

                Swal.fire('El articulo no se encuentra en el remito seleccionado', '', 'error')
                div.parentElement.querySelector("span").querySelector(".selection").querySelector("span").querySelector("span").style="color:red"
                div.setAttribute("remitoAprobado", false)

            }else{

                div.parentElement.querySelector("span").querySelector(".selection").querySelector("span").querySelector("span").style="color:black"
                div.setAttribute("remitoAprobado", true)


            }
        }
    });

}