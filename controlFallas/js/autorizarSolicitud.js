const activarRecodificacion  = (div) => {

    let allCheck = div.parentElement.parentElement.querySelectorAll("input[type='checkbox']")
    if(div.checked){
        allCheck.forEach((e,y)=> {
            if(y != 0){
                e.disabled = false;
            }
        });
    }else{
        allCheck.forEach((e ,y)=> {
            if(y != 0){
                e.disabled = true;
                e.checked = false;
                e.parentElement.parentElement.querySelectorAll("td")[2].textContent = e.parentElement.parentElement.querySelectorAll("td")[2].getAttribute("attr-realvalue");
                e.parentElement.parentElement.querySelectorAll("td")[10].textContent = "";
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

                div.parentElement.parentElement.querySelectorAll("td")[10].textContent = data[0]['COD_OUTLET'];
                // div.parentElement.parentElement.querySelectorAll("td")[2].textContent ="$"+ parseNumber(data[0]['PRECIO_DESC_RED']);
            }   
        });

    }else{
       
        let codArticulo = div.parentElement.parentElement.querySelector("td").textContent;

        let newStr = codArticulo.slice(1)
        newStr = "O" + newStr;
        div.parentElement.parentElement.querySelectorAll("td")[10].textContent =newStr
        
    
    }

}


const autorizar = () => {
    let allTr = document.querySelectorAll("tbody tr");
    let numSolicitud = document.querySelector("#numSolicitud").value;
    
    let data = [];

    allTr.forEach((element,y) => {
        data.push({
            ID: element.querySelectorAll("td")[13].textContent,
            COD_ARTICULO: element.querySelectorAll("td")[0].textContent,
            PRECIO: element.querySelectorAll("td")[2].textContent.replace(/[$.]/g, ""),
            NUEVO_CODIGO: element.querySelectorAll("td")[10].textContent,
            DESTINO: element.querySelectorAll("td")[11].querySelector("select").value,
            OBSERVACIONES: element.querySelectorAll("td")[12].querySelector("input").value
        });

    })
 
    $.ajax({
        url: "Controller/RecodificacionController.php?accion=autorizar",
        type: "POST",
        data: {
            data: data,
            numSolicitud: numSolicitud
        },
        success: function (response) {
            Swal.fire ({
                title: "Solicitud autorizada",
                icon: "success",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                location.href = "seleccionDeSolicitudesSup.php";
            })
        }
    });



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