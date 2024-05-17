const activarRecodificacion  = (div) => {

    let allCheck = div.parentElement.parentElement.querySelectorAll("input[type='checkbox']")
    if(div.checked){
        allCheck.forEach((e,y)=> {
            if(y != 0){
                e.disabled = false;
            }
        });
        div.parentElement.parentElement.querySelectorAll("td")[12].querySelector("select").disabled = false;
        div.parentElement.parentElement.querySelectorAll("td")[12].querySelector("select").options[0].remove();

    }else{

        div.parentElement.parentElement.querySelectorAll("td")[12].querySelector("select").disabled = true;
        const nuevaOpcion = new Option("CENTRAL", "1");

        nuevaOpcion.setAttribute("selected", "selected");

        // Obtener el elemento select
        const select = div.parentElement.parentElement.querySelectorAll("td")[12].querySelector("select");
      
        // Insertar la nueva opción al principio del select
        select.insertBefore(nuevaOpcion, select.firstChild);

        allCheck.forEach((e ,y)=> {
            if(y != 0){
                e.disabled = true;
                e.checked = false;
                e.parentElement.parentElement.querySelectorAll("td")[11].textContent = "";
            }
        });
    }

}

const comprobarCheckbox = (div) => {

    let allCheck = div.parentElement.parentElement.querySelectorAll("input[type='checkbox']");

    allCheck.forEach((element,y) => {
    
        if(y != 0){
            
                   if(element.getAttribute("porcentaje") != div.getAttribute("porcentaje")){
                       element.checked = false;
                   }

        }

    });

    if(div.getAttribute("porcentaje") != "unico"){

        let codArticulo = div.parentElement.parentElement.querySelector("td").textContent;
        let valor = div.getAttribute("porcentaje");

        $.ajax({
            url: "Controller/RecodificacionController.php?accion=traerCodigoRecodificacion",
            type: "POST",
            data: { 
                codArticulo: codArticulo,
                valor: valor
            },
            success: function (response) {
 

                let data = JSON.parse(response)


                div.parentElement.parentElement.querySelectorAll("td")[11].textContent = data[0]['COD_OUTLET'];
                // div.parentElement.parentElement.querySelectorAll("td")[2].textContent ="$"+ parseNumber(data[0]['PRECIO_DESC_RED']);
            }   
        });

    }else{
       
        let codArticulo = div.parentElement.parentElement.querySelector("td").textContent;

        let newStr = codArticulo.slice(1)
        newStr = "O" + newStr;
        div.parentElement.parentElement.querySelectorAll("td")[11].textContent =newStr
        
    
    }

}


const autorizar = () => {



        
    let allTr = document.querySelectorAll("tbody tr");
    let numSolicitud = document.querySelector("#numSolicitud").value;
    let nombreSuc = document.querySelector("#nombreSuc").value;
    let data = [];
    let codigosOulet = []; 
    let outlet = document.querySelector("#outlet").textContent;

    allTr.forEach((element,y) => {
        data.push({
            ID: element.querySelectorAll("td")[14].textContent,
            COD_ARTICULO: element.querySelectorAll("td")[0].textContent,
            PRECIO: element.querySelectorAll("td")[2].textContent.replace(/[$.]/g, ""),
            NUEVO_CODIGO: element.querySelectorAll("td")[11].textContent,
            DESTINO: element.querySelectorAll("td")[12].querySelector("select").value,
            OBSERVACIONES: element.querySelectorAll("td")[13].querySelector("input").value
        });

        codigosOulet.push(element.querySelectorAll("td")[11].textContent);

    })
    
    Swal.fire({
        icon: 'info',
        title: 'Desea autorizar la solicitud?',
        showDenyButton: true,
        confirmButtonText: 'Autorizar',
        denyButtonText: 'No autorizar',
        }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {

    
            $.ajax({
                url: "Controller/RecodificacionController.php?accion=validarCodigosOulet",
                type: "POST",
                data: {
                    codigosOulet: codigosOulet,
                    numSolicitud: numSolicitud,
                    nombreSuc: nombreSuc

                },
                success: function (response) {
          
                }
            });
    
            $.ajax({
                url: "Controller/RecodificacionController.php?accion=autorizar",
                type: "POST",
                data: {
                    data: data,
                    numSolicitud: numSolicitud,
                    outlet: outlet
                },
                success: function (response) {

               
                    $.ajax({
                        url: "Controller/SendEmailController.php?accion=autorizarSolicitud",
                        type: "POST",
                        data: {
                            numSolicitud: numSolicitud,
                            numSuc: document.querySelector("#nombreSuc").getAttribute("attr-realvalue"),
                            nombreSuc: document.querySelector("#nombreSuc").value,
                        },
                        success: function (response) {
                            Swal.fire('La solicitud fue autorizada!', '', 'success').then((result) => {    
                            }).then((result) => {
                                location.href = "seleccionDeSolicitudesSup.php";
                            } )

                        }
                    });


                }
            });

       

        } else if (result.isDenied) {
        Swal.fire('No se realizaron cambios!', '', 'info')
        }
        })
   



}

const parseNumber = (number) => {
    number = parseInt(number);
  
    newNumber = number.toLocaleString('de-De', {
        style: 'decimal',
        maximumFractionDigits: 0,
        minimumFractionDigits: 0
    });
  
    return newNumber;
}

