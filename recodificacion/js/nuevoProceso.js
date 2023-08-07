

const listarDetalle = () => {

    let nroDeTarea = document.querySelector("#nroDeTarea").value;
    if(nroDeTarea != ""){


    $.ajax({
        url: "Controller/RecodificacionController.php?accion=listarDetalle",
        type: "POST",
        data: { 
            nroDeTarea: nroDeTarea 
        },
        success: function (response) {
            
       
            let data = JSON.parse(response);
     
            if(data.length > 0){

                document.querySelector("#ingresoNumeroTarea").hidden = true;
                
                rellenarTablaDetalle(data);

            }else{
                Swal.fire({
                    icon: "error",
                    title: "Busqueda Fallida",
                    text: `No se encontro detalle para la tarea ingresada`,
                }).then((result) => {
                return false       
                })
            }
           
        }   
    }); 

    }else{
        Swal.fire({
            icon: "error",
            title: "Busqueda Fallida",
            text: `Debe ingresar un numero de tarea`,
        }).then((result) => {
        return false       
        })
                 
    }
    

}   

const rellenarTablaDetalle = (obj) => {

    let articulos = [];
    
    obj.forEach((x,y) => {
        let codArticulo = x['COD_ARTICULO'];
        let newStr = codArticulo.slice(1)
        newStr = "O" + newStr;
        articulos[y]  = newStr
    
    });
    

    let tablaDetalle = document.querySelector("#detalleBody");
  
    tablaDetalle.innerHTML = "";

    for (let x = 0; x < obj.length; x++) {

      const tr=document.createElement('tr');
      tr.id = "trBodyDetalle";
      const td1=document.createElement('td');
      const td2=document.createElement('td');
      const td3=document.createElement('td');
      const td4=document.createElement('td');

      const td5=document.createElement('td');
      const td6=document.createElement('td');
      const td7=document.createElement('td');
      const td8=document.createElement('td');
      const td9=document.createElement('td');
  
      var inputUnico = document.createElement("input");
      inputUnico.type = "checkbox";
      inputUnico.setAttribute("onchange","comprobarCheckbox(this)")
      inputUnico.setAttribute("porcentaje","unico")

      var input10 = document.createElement("input");
      input10.type = "checkbox";
      input10.setAttribute("onchange","comprobarCheckbox(this)")
      input10.setAttribute("porcentaje","0.9")
  
      var input20 = document.createElement("input");
      input20.type = "checkbox";
      input20.setAttribute("onchange","comprobarCheckbox(this)")
      input20.setAttribute("porcentaje","0.8")


  
      var input30 = document.createElement("input");
      input30.type = "checkbox";
      input30.setAttribute("onchange","comprobarCheckbox(this)")
      input30.setAttribute("porcentaje","0.7")
    
  
      var input40 = document.createElement("input");
      input40.type = "checkbox";
      input40.setAttribute("onchange","comprobarCheckbox(this)")
      input40.setAttribute("porcentaje","0.6")

  
  
      const text1=document.createTextNode(obj[x]['COD_ARTICULO']);
      const text2=document.createTextNode(obj[x]['DESCRIPCION']);
      const text3=document.createTextNode(obj[x]['CANTIDAD']);
      const text8=document.createTextNode("");
  
      td1.appendChild(text1);
      td2.appendChild(text2);
      td3.appendChild(text3);
      td4.appendChild(inputUnico);
      td5.appendChild(input10);
  
      td6.appendChild(input20);
      td7.appendChild(input30);
      td8.appendChild(input40);
      td9.appendChild(text8);

      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td3);
      tr.appendChild(td4);
      tr.appendChild(td5);
      tr.appendChild(td6);
      tr.appendChild(td7);
      tr.appendChild(td8);
      tr.appendChild(td9);
      
      tablaDetalle.appendChild(tr);
    }
    document.querySelector("#inputDetalleNroTarea").value =  document.querySelector("#nroDeTarea").value;
    document.querySelector("#inputDetalleNroTarea").disabled = true;
    document.querySelector("#detallePorNumeroDeTarea").hidden = false;
    validarArticulos(articulos,true);
}

const comprobarCheckbox = (div) => {

    let allCheck = div.parentElement.parentElement.querySelectorAll("input");

    allCheck.forEach(element => {
 
        if(element.getAttribute("porcentaje") != div.getAttribute("porcentaje")){
            element.checked = false;
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
                div.parentElement.parentElement.querySelectorAll("td")[8].textContent = data[0]['COD_OUTLET'];
            }   
        });

    }else{
     
        let codArticulo = div.parentElement.parentElement.querySelector("td").textContent;

        let newStr = codArticulo.slice(1)
        newStr = "O" + newStr;
        div.parentElement.parentElement.querySelectorAll("td")[8].textContent =newStr
    
    }

}

const aplicarPorcentajeMasivo = (porcentaje) => {

    let allTr = document.querySelectorAll("#trBodyDetalle");

    allTr.forEach(element => {

        if(element.querySelectorAll("td")[3].querySelector("input").checked == true ){
           return true
        }

        let allChecks = element.querySelectorAll("input");

        allChecks.forEach(element => {

                element.checked = false;

        });

        let codArticulo = element.querySelectorAll("td")[0].textContent;
        let valor = porcentaje;

        $.ajax({
            url: "Controller/RecodificacionController.php?accion=traerCodigoRecodificacion",
            type: "POST",
            data: {
                codArticulo: codArticulo,
                valor: valor
            },
            success: function (response) {
                let data = JSON.parse(response)
                element.querySelectorAll("td")[8].textContent = data[0]['COD_OUTLET'];
            }
        });


        switch (porcentaje) {

            case "0.8":
                element.querySelectorAll("td")[5].querySelector("input").checked = true;
                break;

            case "0.7":
                element.querySelectorAll("td")[6].querySelector("input").checked = true;
                break;

            case "0.6":
                element.querySelectorAll("td")[7].querySelector("input").checked = true;
                break;

            default:
                break;
        }



        
    });

}

const procesar = () => {

        rellenarModal();
        $("#modalNuevo").modal("toggle");

}


const rellenarModal = () => {
 
    $.ajax({
        url: "Controller/RecodificacionController.php?accion=traerSucursales",
        type: "GET",
        success: function (data) {
            let selectDelModal =  document.querySelector("#selectSucursales");
            selectDelModal.innerHTML = "";
            JSON.parse(data).forEach(sucursal => {

                let option = document.createElement('option');
                option.value = sucursal['NRO_SUCURSAL'];
                option.textContent = sucursal['COD_CLIENT'] + "-" +sucursal['DESC_SUCURSAL'];
                selectDelModal.appendChild(option);


            });
            $('#selectSucursales').select2();
            document.querySelector(".select2-container").style.marginLeft = "80px"
          
        }   
    });

}

const recodificar = () => {

    let allTr = document.querySelectorAll("#trBodyDetalle");


    let articulos = [];

    
    allTr.forEach((x,y) => {

        articulos.push(x.querySelectorAll("td")[0].textContent);

        if(x.querySelectorAll("td")[8].textContent != ""){
            articulos.push(x.querySelectorAll("td")[8].textContent);
        }

    }); 

    validarArticulos(articulos)
   
}

const pushData = (respuesta) => {

        let articulosNoCargados = [];

        let data = JSON.parse(respuesta);
   

        data.forEach((x,y) => {
            if(x['respuesta'] == 0){
                articulosNoCargados.push(x['articulo'])
            }
        });
    
        return articulosNoCargados;

}


const validarArticulos = (articulos,seteaValores = null) => {

    $.ajax({
        url: "Controller/RecodificacionController.php?accion=validarArticulos",
        type: "POST",
        data: {
            articulo: JSON.stringify(articulos)
        },
        success: function (data) {
         
            let response = pushData(data);

            if(seteaValores == null){

                if(response.length != 0 ){
                    let listado = "";
                    response.forEach((x,y) => {
                        listado += x + "-"
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Recodificacion Fallida",
                        text: `Los siguientes articulos no se encuentran cargados en el sistema: `+ listado,
                    }).then((result) => {
                                
                    })
                            
                    
                }else {
                    calcularSaldoPartidas(articulos)
                }

            }else{

               let articulos = JSON.parse(data)
               let allTr = document.querySelectorAll("#trBodyDetalle");
      
                allTr.forEach(element => {
          
                    articulos.forEach((x,y) => {

                        
                        let codArticulo =  element.querySelector("td").textContent;
                        let newStr = codArticulo.slice(1)
                        newStr = "O" + newStr;

                        if(x['respuesta'] == 1 && newStr == x['articulo']) {

                            element.querySelectorAll("td")[3].querySelector("input").checked = true;
                            element.querySelectorAll("td")[8].textContent = x['articulo'];
                            
                        }else if(x['respuesta'] == 0 )  {
                       
                            let valor = 0.8;
                   
                            $.ajax({
                                url: "Controller/RecodificacionController.php?accion=traerCodigoRecodificacion",
                                type: "POST",
                                data: { 
                                    codArticulo: codArticulo,
                                    valor: valor
                                },
                                success: function (response) {

                                    let data = JSON.parse(response)
                                    element.querySelectorAll("td")[5].querySelector("input").checked = true;
                                    element.querySelectorAll("td")[8].textContent = data[0]['COD_OUTLET'];
                                }   
                            });

                        }

                    });

                });
                
            }
        }

    });

}

const calcularSaldoPartidas = (articulos) =>{

        $.ajax({
            url: "Controller/RecodificacionController.php?accion=calcularSaldoPartidas",
            type: "POST",
            data: {
                articulo: JSON.stringify(articulos)
            },
            success: function (data) {
                let response = JSON.parse(data)

                const array = [];
                let count = 0;

                for (const clave in response) {

                  if (response.hasOwnProperty(clave)) {
                    let elemento = [];
                    array[count] = []
                    array[count][0] = clave;
                    array[count][1] = response[clave];
                    count ++
                   
                  }

                }
                let cantidadesInvalidas = [];
                array.forEach((articulo,x) => {
                    if(articulo[1] == 0){    
                        cantidadesInvalidas.push(articulo[0])
                    }
                });

                if(cantidadesInvalidas.length != 0){
                    let listado = "";
                    cantidadesInvalidas.forEach((x,y) => {
                        listado += x + "-"
                    });
                    Swal.fire({
                        icon: "error",
                        title: "Recodificacion Fallida",
                        text: `Los siguientes articulos poseen cantidades menores a 0: `+ listado,
                    }).then((result) => {
                                
                    })
                }
            }
        });


}