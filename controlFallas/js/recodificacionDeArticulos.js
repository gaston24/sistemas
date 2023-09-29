const ajustar = () =>{
    
    let allTr = document.querySelectorAll("tbody tr")
    let arrayDeArticulos = []
    let arrayRemitos = []

    allTr.forEach(tr => {

        if(tr.querySelectorAll("td")[6].querySelector("input").checked == true){

            arrayRemitos.push({
                nComp:tr.querySelectorAll("td")[1].textContent,
                articulo: tr.querySelectorAll("td")[2].textContent,
                cantidad: tr.querySelectorAll("td")[4].textContent
            })
          
        }

    })

    $.ajax({
        url: "Controller/RecodificacionController.php?accion=comprobarArticuloEnRemito",
        type: "POST",
        data: {
            arrayRemitos: arrayRemitos
        },
        success: function (response) {

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
                       
                            arrayDeArticulos.forEach(element => {

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
                                           
                                                Swal.fire('Artículos ajustados!', '', 'success').then((result) => {
                                                    location.reload();
                                                })
                                            
                                            }

                                        });
                                        
                                        
                                        

                                    }
                                });
                            });

                          

                        } else if (result.isDenied) {

                            Swal.fire('Ajuste cancelado!', '', 'info')
                            
                        }
                    })

            }
    
   
        }
    });

  

}