const ajustar = () =>{

    let allTr = document.querySelectorAll("tbody tr")
    let arrayDeArticulos = []
    let arrayRemitos = []
    let nroSolicitud = document.querySelector("#nroSolicitud").textContent

    allTr.forEach(tr => {

        if(tr.querySelectorAll("td")[6].querySelector("input").checked == true){

            arrayRemitos.push({
                nComp:tr.querySelectorAll("td")[1].textContent,
                articulo: tr.querySelectorAll("td")[2].textContent,
                cantidad: tr.querySelectorAll("td")[4].textContent
            })
          
        }

    })

    mostrarSpiner();
    $.ajax({
        url: "Controller/RecodificacionController.php?accion=comprobarArticuloEnRemito",
        type: "POST",
        data: {
            arrayRemitos: arrayRemitos
        },
        success: function (response) {
            console.log(response)

            let result = JSON.parse(response)
            let error = false;
            result.forEach((element,x) => {

                allTr.forEach(tr => {

                    if(tr.querySelectorAll("td")[6].querySelector("input").checked == true){

                        if(element['remito'] == tr.querySelectorAll("td")[1].textContent && element['articulo'] == tr.querySelectorAll("td")[2].textContent){

                            if(element['respuesta'] == false){

                                tr.querySelectorAll("td")[2].style="color:red"
                                error = true;

                            }else{

                                tr.querySelectorAll("td")[2].style="color:black"

                                arrayDeArticulos.push({
                                    
                                    articulo: tr.querySelectorAll("td")[5].textContent,
                                    codigo: tr.querySelectorAll("td")[2].textContent ,
                                    cant: tr.querySelectorAll("td")[4].textContent,
                                    ncomp: tr.querySelectorAll("td")[1].textContent,
                                    tcomp: '',
                                    id_enc: tr.querySelectorAll("td")[7].textContent,
                                })

                            }
                        }
                    }

                })

            });

            document.querySelector("#boxLoading").classList.remove('loading')

            if(error == true){

                Swal.fire({
                    icon: 'warning',
                    title: 'Atención!',
                    text: 'Hay artículos que no están dados de alta',
                })
                return 1;

            }else{

                Swal.fire({
                    icon: 'info',
                    title: 'Desea registrar el ajuste?',
                    showDenyButton: true,
                    confirmButtonText: 'Ajustar',
                    denyButtonText: 'Cancelar',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                       
                            mostrarSpiner();

                            $.ajax({

                                url: "Controller/RecodificacionController.php?accion=comprobarStockArticulos",
                                type: "POST",
                                data: {
                                    data:JSON.stringify(arrayDeArticulos),
                                  
                                },
                                success: function (response) {

                                    data = JSON.parse(response)
                                    
                                    if(data.length > 0){

                                        let mensaje = "Los siguientes artículos no existen el maestro: \n\n"
                                        let mensajeStock = "Los siguientes artículos no tienen stock suficiente: \n\n"
                                        let codigosNoEncontrados = '';
                                        let codigosSinStock = '';
                                        let mensajeFinal = '';

                                        data.forEach(element => {
                                            
                                            if(element[0] == '1'){

                                                codigosNoEncontrados += element[1] +',' +"\n "

                                            }else{

                                                codigosSinStock += element[1] +',' +"\n "
                                            }

                                        });

                                        if(codigosNoEncontrados != ''){

                                            mensaje += codigosNoEncontrados + "\n\n"

                                            mensajeFinal += mensaje

                                        }
                                        
                                        if (codigosSinStock != ''){

                                            mensajeStock += codigosSinStock + "\n\n"

                                            mensajeFinal += mensajeStock

                                        }
                                        document.querySelector("#boxLoading").classList.remove('loading')

                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Atención!',
                                            text: mensajeFinal,
                                        })
                                        
                                    }else{

                                          $.ajax({
                                            url: "../ajustes/ConfirmAjus.php",
                                            type: "POST",
                                            data: {
                                                data:JSON.stringify(arrayDeArticulos),
                                                ajusteNuevaRecodificacion:true
                                            },
                                            success: function (response) {

                                                $.ajax({

                                                    url:"Controller/RecodificacionController.php?accion=ajustarArticulos",
                                                    type:"POST",
                                                    data:{
                                                        arrayDeArticulos:arrayDeArticulos,
                                                        nroSolicitud:nroSolicitud
                                                    },
                                                    success:function(data){
                                                        document.querySelector("#boxLoading").classList.remove('loading')
                                                        Swal.fire('Artículos ajustados!', '', 'success').then((result) => {
                                                            
                                                            window.location.href = "seleccionDeSolicitudesDestino.php";
                                                        })
                                                    
                                                    }

                                                });
                                                
                                                
                                                

                                            }
                                        });

                                    }
                                } 


                           })

                          
                        

                          

                        } else if (result.isDenied) {

                            Swal.fire('Ajuste cancelado!', '', 'info')
                            
                        }
                    })

            }
    
   
        }
    });

  

}


const mostrarSpiner = () => {

    let spinner = document.querySelector("#boxLoading");

    spinner.className += " loading";

}



const ajustePropio  = () => {

    let arrayDeArticulos = []
    let allTr = document.querySelectorAll("tbody tr")
    allTr.forEach(tr => {

        if(tr.querySelectorAll("td")[6].querySelector("input").checked == true){

            arrayDeArticulos.push({
                                    
                articulo: tr.querySelectorAll("td")[5].textContent,
                codigo: tr.querySelectorAll("td")[2].textContent ,
                cant: tr.querySelectorAll("td")[4].textContent,
                ncomp: '',
                tcomp: '',
                id_enc: tr.querySelectorAll("td")[7].textContent,

            })

        }
    
    })



    Swal.fire({
        icon: 'info',
        title: 'Desea registrar el ajuste?',
        showDenyButton: true,
        confirmButtonText: 'Ajustar',
        denyButtonText: 'Cancelar',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
           
                mostrarSpiner();

                $.ajax({

                    url: "Controller/RecodificacionController.php?accion=comprobarStockArticulos",
                    type: "POST",
                    data: {
                        data:JSON.stringify(arrayDeArticulos),
                      
                    },
                    success: function (response) {
                        console.log(response);

                        data = JSON.parse(response)
                                    
                        if(data.length > 0){

                            let mensaje = "Los siguientes artículos no existen el maestro: \n\n"
                            let mensajeStock = "Los siguientes artículos no tienen stock suficiente: \n\n"
                            let codigosNoEncontrados = '';
                            let codigosSinStock = '';
                            let mensajeFinal = '';

                            data.forEach(element => {
                                
                                if(element[0] == '1'){

                                    codigosNoEncontrados += element[1] +',' +"\n "

                                }else{

                                    codigosSinStock += element[1] +',' +"\n "
                                }

                            });

                            if(codigosNoEncontrados != ''){

                                mensaje += codigosNoEncontrados + "\n\n"

                                mensajeFinal += mensaje

                            }
                            
                            if (codigosSinStock != ''){

                                mensajeStock += codigosSinStock + "\n\n"

                                mensajeFinal += mensajeStock

                            }
                            document.querySelector("#boxLoading").classList.remove('loading')

                            Swal.fire({
                                icon: 'warning',
                                title: 'Atención!',
                                text: mensajeFinal,
                            })
                            
                        }else{
                            
                            $.ajax({
                                url: "../ajustes/ConfirmAjus.php",
                                type: "POST",
                                data: {
                                    data:JSON.stringify(arrayDeArticulos),
                                    ajusteNuevaRecodificacion:true
                                },
                                success: function (response) {

                                    $.ajax({

                                        url:"Controller/RecodificacionController.php?accion=ajustarArticulos",
                                        type:"POST",
                                        data:{
                                            arrayDeArticulos:arrayDeArticulos
                                        },
                                        success:function(data){
                                            document.querySelector("#boxLoading").classList.remove('loading')
                                            Swal.fire('Artículos ajustados!', '', 'success').then((result) => {
                                                window.location.href = "seleccionDeSolicitudesDestino.php";
                                            })
                                        
                                        }

                                    });
                                    
                                    
                                    

                                }
                            });

                        }
                    }
                })
            }
        })

    

}