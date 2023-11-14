

const mostrarDescripcion = (div) => {
    
    let value = $(div).val()

    let descripcion = div.parentElement.parentElement.querySelectorAll("td")[1];
    let precio = div.parentElement.parentElement.querySelectorAll("td")[2];
    let cantidad = div.parentElement.parentElement.querySelectorAll("td")[3];

    cantidad.textContent = "1";
    descripcion.textContent = value.split("-")[1];
    // console.log(value);
    precio.textContent = "$"+ parseNumber(value.split("-")[2]);


}

const limitarArchivos = (input,codArticulo) => {

    const maxFiles = 3;

    if (input.files.length > maxFiles) {

      Swal.fire({
        icon: 'error',
        title: 'Error de carga',
        text: `Sólo se pueden subir hasta ${maxFiles} fotos!`
      })


        input.value = ''; 
        return 1;

    }

    
    cargarArchivos(input,codArticulo);

}

const elegirImagen = (e) => {

    let codArticulo = e.parentElement.parentElement.querySelectorAll("td")[0].querySelector("select").value;
    document.querySelector('#archivos').setAttribute("onchange",`limitarArchivos(this,'${codArticulo}')`);
    document.getElementById('archivos').click();

}
  
const cargarArchivos = (input,codArticulo) => {


  eliminarArchivo(input,false,codArticulo); // Limpiamos Los Archivos cargados Para Ese Codigo de Articulo

  const file = document.getElementById('archivos').files[0];

  // Verificamos si se seleccionó un archivo

  if (file) {

      // Creamos un objeto de tipo FileReader para leer el contenido del archivo
      const reader = new FileReader();

      // Definimos el evento "onload" para cuando se complete la lectura del archivo
      reader.onload = function(e) {
      // Obtenemos la URL de la imagen cargada
      uploadedImage = e.target.result;

      // Mostramos la imagen en el contenedor de vista previa
      const imagePreview = document.getElementById('imagePreview');
      //   imagePreview.innerHTML = `<img src="${uploadedImage}" alt="Imagen Cargada" width="200" />`;
      };

      // Leemos el contenido del archivo como una URL de datos
      reader.readAsDataURL(file);
      
  }

  enviarImagenes(codArticulo);

};

const enviarImagenes = (codArticulo) => {
   
    codArticulo = codArticulo.split("-")[0];
  
    const input = document.getElementById('archivos');
    let files = input.files;
    let numSolicitud = document.querySelector("#numSolicitud").value; 
    nameFile = numSolicitud+codArticulo ;
    const formData = new FormData();
    const maxFiles = 3;

    // Validamos El Total de Fotos Cargadas (Maximo 3)

    if (files.length > maxFiles) {

      alert(`Solo se pueden seleccionar un máximo de ${maxFiles} archivos.`);
      return;

    }

    for (let i = 0; i < files.length; i++) {

        const file = files[i];
        
        const modifiedFile = new File([file], nameFile, {
          type: file.type,
          lastModified: file.lastModified,
        });
    
        formData.append('archivos[]', modifiedFile);

    }

    $.ajax({

      url: 'upload_image.php',
      type: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) { 

        Swal.fire({
          icon: 'success',
          title: 'Carga exitosa',
          text: `Se subieron ${files.length} archivos correctamente!`
        })

        quitarErrorImagen()

      },
      error: function() {

        alert('Error al enviar las imágenes al servidor.');

      }

    });
    
}

const quitarErrorImagen = () => {
  let divErrorImagen = document.querySelectorAll("#errorImagen");

  divErrorImagen.forEach(e => {
      e.remove();
    }
  );

}

const mostrarImagen = (divImagen, startIndex = 0) => {

 
    let codigosImagenes = [];
  
    let codigoArticulo = divImagen.parentElement.parentElement.querySelectorAll("td")[0].querySelector("select").value;

    let numSolicitud = document.querySelector("#numSolicitud").value;
    let carouselElement = document.querySelector('#carruselImagenes'); 
  
    carouselElement.innerHTML = ''; 
    console.log(codigoArticulo.split("-")[0],numSolicitud)
    $.ajax({

      url: "Controller/RecodificacionController.php?accion=contarImagenes",
      type: "POST",
      data: {
        codArticulo:codigoArticulo.split("-")[0],
        numSolicitud:numSolicitud

      },

      success: function (response) {

        response = JSON.parse(response);

        if(response['cantidad'] > 0){

          
          for (let index = 0; index < response['nombre'].length  ; index++) {
            
            codigosImagenes.push(response['nombre'][index] + '.jpg');

          }
          
          let modalContent = document.createElement('div');
          modalContent.className = 'modal-dialog modal-dialog-centered';

          let modalBody = document.createElement('div');
          modalBody.className = 'modal-content';

          let carousel = document.createElement('div');
          carousel.innerHTML = "";
          carousel.className = 'carousel slide';
          carousel.setAttribute('data-ride', 'carousel');

          let carouselInner = document.createElement('div');
          carouselInner.className = 'carousel-inner';


          codigosImagenes.forEach((imagen, index) => {

            validarExistenciaArchivo('assets/uploads/' + imagen, function(existe) {

              if (existe) { 

                let carouselItem = document.createElement('div');
                carouselItem.className = index === startIndex ? 'carousel-item active' : 'carousel-item';
                carouselItem.style = "text-align:center"
                let imgElement = document.createElement('img');
                imgElement.src = 'assets/uploads/' + imagen;
                imgElement.classList.add('img-fluid');

                carouselItem.appendChild(imgElement);
                carouselInner.appendChild(carouselItem);
              }

            });
          });

          carousel.appendChild(carouselInner);
          modalBody.appendChild(carousel);
          modalContent.appendChild(modalBody);
          carouselElement.appendChild(modalContent);

          // Crear los controles "anterior" y "siguiente" del carrusel
          let prevControl = document.createElement('a');
          prevControl.className = 'carousel-control-prev';
          prevControl.href = '#carruselImagenes';
          prevControl.role = 'button';
          prevControl.setAttribute('data-slide', 'prev');
          prevControl.setAttribute("onclick",'pasarImagen(-1)')
          let prevIcon = document.createElement('span');
          prevIcon.className = 'carousel-control-prev-icon';
          prevIcon.style = 'background-color: black';
          prevIcon.setAttribute('aria-hidden', 'true');
          prevControl.appendChild(prevIcon);


          let nextControl = document.createElement('a');
          nextControl.className = 'carousel-control-next';
          nextControl.href = '#carruselImagenes';
          nextControl.role = 'button';
          nextControl.setAttribute('data-slide', 'next');
          nextControl.setAttribute("onclick",'pasarImagen(1)')
          let nextIcon = document.createElement('span');
          nextIcon.className = 'carousel-control-next-icon';
          nextIcon.style = 'background-color: black';
          nextIcon.setAttribute('aria-hidden', 'true');
          nextControl.appendChild(nextIcon);


          carousel.appendChild(prevControl);
          carousel.appendChild(nextControl);

          // Activa el carrusel de Bootstrap
          $(carousel).carousel();

          // Muestra el modal
          $('#carruselImagenes').modal('show');

        }

      }
    })


}

const pasarImagen = (pos) =>{

  let items = document.querySelectorAll(".carousel-item")
  
  for (let index = 0; index < items.length; index++) {
    
    if (items[index].classList.contains("active")) {

      if (items[index + pos] !== undefined) {
  
        items[index].classList.remove("active");
      
        items[index + pos].classList.add("active");
        break; // Detener el bucle una vez que se encontró el siguiente elemento activo

      }

    }

  }

}

const copiarFila = (div) => {

  div.setAttribute("onchange","");

  let tablaArticulos = document.getElementById('tablaArticulos');

  let filas = tablaArticulos.getElementsByTagName('tr');

  let filaOriginal = filas[filas.length - 1];

  let filaClonada = filaOriginal.cloneNode(true);


  let arrayDeDiv = [1,2,3];
  let data = localStorage.getItem("articulos");
  let Alltd = filaClonada.querySelectorAll("td");

  
  Alltd.forEach((e,x) => {

    if (arrayDeDiv.includes(x)){
  

      e.textContent = ''; 

    }

    if(x == 4){

      e.querySelector("input").value = "";
      e.querySelector("input").setAttribute("onchange","comprobarFila(this)");
      e.querySelector("input").style.border = "1px solid";

    }
    if(x == 5){

      if( e.querySelector("div") != null){

        e.querySelector("div").remove();

      }
     
    }
      
  });

  let opciones =  `
  <select name="selectArticulo" id="selectArticulo" class="selectArticulo" onchange="mostrarDescripcion(this)" style="width:250px">
  <option value="" selected disabled>Seleccione un artículo</option>
  `; 

  JSON.parse(data).forEach((e, x) => {
    
      opciones += `
        <option value="${e.COD_ARTICU}-${e.DESCRIPCIO}-${e.PRECIO}">${e.COD_ARTICU} | ${e.DESCRIPCIO}</option>
      `;

  });

  opciones += `</select>`;


filaClonada.querySelectorAll("td")[0].innerHTML = opciones;




filaOriginal.after(filaClonada);


  $('.selectArticulo').select2({
    placeholder: 'Buscar artículo...',
    minimumInputLength: 3,
    data: function(params) {
        // Obtener los datos del Local Storage
        const storedData = JSON.parse(localStorage.getItem('articulos'));
        // console.log(storedData)
        // Filtrar los datos para que coincidan con el término de búsqueda
        const filteredData = storedData.filter(item => item.text.includes(params.term));

        // Devolver los datos filtrados para que Select2 los utilice
        return {
        results: filteredData
        };
    }
  });
}

const traerArticulos = () => {

  $.ajax({
    url: "Controller/RecodificacionController.php?accion=traerArticulos",
    type: "GET",
    success: function (response) {
 
      localStorage.setItem("articulos", response);
    }

  });
}

document.addEventListener("DOMContentLoaded", traerArticulos);


const validarExistenciaArchivo = (rutaArchivo, callback) => {
  const img = new Image();
  img.onload = function() {
    // La imagen se ha cargado correctamente, por lo que el archivo existe
    callback(true);
  };
  img.onerror = function() {
    // La imagen no se pudo cargar, por lo que el archivo no existe
    callback(false);
  };
  img.src = rutaArchivo;
}

const eliminarArchivo = (div,alerta = true,articulo = null) => {
  let codArticulo = "";

  if(!articulo) {

    codArticulo = div.parentElement.parentElement.querySelectorAll("td")[0].querySelector("select").value;

  }else{

    codArticulo = articulo;

  }

  codArticulo = codArticulo.split("-")[0];
  
  let numSolicitud = document.querySelector("#numSolicitud").value;

  $.ajax({
    url: "Controller/RecodificacionController.php?accion=eliminarArchivo",
    type: "POST",
    data: {
      codArticulo: codArticulo,
      numSolicitud: numSolicitud
    },
    success: function (response) {
        if(alerta != false){

          Swal.fire({
            icon: 'info',
            title: 'Borrado confirmado',
            text: 'Se eliminaron las fotos correctamente!'
          })

        }
      
    }
  });

  
}

const comprobarFila = (div) => {
  if (div.parentElement.parentElement.querySelectorAll("td")[0].querySelector("select").value != "" &&
      div.parentElement.parentElement.querySelectorAll("td")[4].querySelector("input").value != "")
    {

      copiarFila(div);

    }

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

const solicitar = async (esBorrador = false) => {

  let nroSucursal = document.querySelector("#nroSucursal").textContent;
  let fecha = document.querySelector("#fecha").value;
  let usuario = document.querySelector("#usuario").value;
  let estado = document.querySelector("#estado").value;
  let numSolicitud = document.querySelector("#numSolicitud").value;
  let allTr = document.querySelectorAll("#bodyArticulos");
  let dataArticulos = [];
  let codArticulos = [];
  allTr.forEach(e => {
    
    codArticulos.push(e.querySelectorAll("td")[0].querySelector("select").value.split("-")[0]);
  })


  if(document.querySelector("#bodyArticulos").querySelector("td").querySelector("select").value == ""){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Debe ingresar al menos un artículo',
    })
    return 1;

  }
  let stock = await comprobarStock();

  if(stock == false ){
    return 1  
  }
  

  $.ajax({
  
    url: "Controller/RecodificacionController.php?accion=contarImagenes",
    type: "POST",
    data: {
      codArticulos: codArticulos,
      numSolicitud: numSolicitud

    },
    success: function (response) {
        let error = false

        response = JSON.parse(response);

        if(response['cantidad'] > 0) {

          codArticulos.forEach(element => {   

            if(response['nombre'].includes(element) == false){
              allTr.forEach(e => {

                if(element == e.querySelectorAll("td")[0].querySelector("select").value.split("-")[0]){

                  if( e.querySelectorAll("td")[5].querySelector("div") != null){

                    e.querySelectorAll("td")[5].querySelector("div").remove();

                  }
                 if(e.querySelectorAll("td")[0].querySelector("select").value != ""){
                  e.querySelectorAll("td")[5].innerHTML = e.querySelectorAll("td")[5].innerHTML + `<div id="errorImagen" style="font-size:20px;color:red">* cargar imagen</div>`
                 }
                  error = true;
                }
              })
            }else{
              
              allTr.forEach(e => {

                if(element == e.querySelectorAll("td")[0].querySelector("select").value.split("-")[0]){

                  if( e.querySelectorAll("td")[5].querySelector("div") != null){

                    e.querySelectorAll("td")[5].querySelector("div").remove();

                  }
                 
                }
              })

            }

          });
 
        }else{

          allTr.forEach(e => {

            if( e.querySelectorAll("td")[5].querySelector("div") != null){

              e.querySelectorAll("td")[5].querySelector("div").remove();

            }
            if(e.querySelectorAll("td")[0].querySelector("select").value != ""){
              e.querySelectorAll("td")[5].innerHTML = e.querySelectorAll("td")[5].innerHTML + `<div id="errorImagen" style="font-size:20px;color:red">* cargar imagen</div>`
             }

            error =  true;
          })

        }

        if(error == false){

          allTr.forEach(e => {

    
  

            if (e.querySelectorAll("td")[0].querySelector("select").value != "" && e.querySelectorAll("td")[4].querySelector("input").value != ""){
        
              dataArticulos.push({
                codArticulo: e.querySelectorAll("td")[0].querySelector("select").value.split("-")[0],
                descripcion: e.querySelectorAll("td")[1].textContent,
                precio: e.querySelectorAll("td")[2].textContent.replace(/[$.]/g, ""),
                cantidad: e.querySelectorAll("td")[3].textContent,
                descFalla: e.querySelectorAll("td")[4].querySelector("input").value
              });
        
            }else{
        
              if(e.querySelectorAll("td")[0].querySelector("select").value != "" && e.querySelectorAll("td")[4].querySelector("input").value == ""){
        
                e.querySelectorAll("td")[4].querySelector("input").style.border = "1px solid red";
              
                error = true;
              }
        
            }
        
          });
        
          if(error == true){
        
            alert("Debe completar los Campos Obligatorios");
              return;
          }
        
          Swal.fire({
            icon: 'info',
            title: 'Desea confirmar la solicitud?',
            showDenyButton: true,
            confirmButtonText: 'Confirmar',
            denyButtonText: 'Cancelar',
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

              $.ajax({
        
                url: "Controller/RecodificacionController.php?accion=solicitar",
                type: "POST",
                data: {
                  nroSucursal: nroSucursal,
                  fecha: fecha,
                  usuario: usuario,
                  estado: estado,
                  numSolicitud: numSolicitud,
                  dataArticulos: dataArticulos,
                  esBorrador: esBorrador
                },
                success: function (response) {
                   
                  Swal.fire('La solicitud fue confirmada!', '', 'success').then((result) => {
                      $.ajax({
                        url: "Controller/SendEmailController.php?accion=confirmarSolicitud",
                        type: "POST",
                        data: {
                          numSolicitud:numSolicitud
                        },
                        success: function (response) {
                          console.log(response)
                          location.href = "seleccionDeSolicitudes.php";
                        }
                      });
                  });
          
            
                }
    
              });

          
            } else if (result.isDenied) {
              Swal.fire('La solicitud no fue confirmada!', '', 'info')
            }
            })

        }
    }
  });

  return 1
  


}

const borrador = () => {

  let nroSucursal = document.querySelector("#nroSucursal").textContent;
  let fecha = document.querySelector("#fecha").value;
  let usuario = document.querySelector("#usuario").value;
  let estado = document.querySelector("#estado").value;
  let numSolicitud = document.querySelector("#numSolicitud").value;
  let allTr = document.querySelectorAll("#bodyArticulos");
  let dataArticulos = [];


  allTr.forEach(e => {

    if (e.querySelectorAll("td")[0].querySelector("select").value != "" && e.querySelectorAll("td")[4].querySelector("input").value != ""){

      dataArticulos.push({
        codArticulo: e.querySelectorAll("td")[0].querySelector("select").value.split("-")[0],
        descripcion: e.querySelectorAll("td")[1].textContent,
        precio: e.querySelectorAll("td")[2].textContent.replace(/[$.]/g, ""),
        cantidad: e.querySelectorAll("td")[3].textContent,
        descFalla: e.querySelectorAll("td")[4].querySelector("input").value
      });

    }

  });

  if(document.querySelector("#bodyArticulos").querySelector("td").querySelector("select").value == ""){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Debe ingresar al menos un artículo',
    })
    return 1;

  }

  $.ajax({

    url: "Controller/RecodificacionController.php?accion=borrador",
    type: "POST",
    data: {
      nroSucursal: nroSucursal,
      fecha: fecha,
      usuario: usuario,
      estado: estado,
      numSolicitud: numSolicitud,
      dataArticulos: dataArticulos
    },
    success: function (response) {
        Swal.fire({
          icon: 'success',
          title: 'Solicitud Guardada Correctamente',
          showConfirmButton: false,
          timer: 1500
        }).then ((result) => {

          location.href = "seleccionDeSolicitudes.php";

        });

    }
  });


}


const existeBorrador = () =>{
  
  let bodyArticulos = document.querySelectorAll("#bodyArticulos");

  bodyArticulos.forEach(articulo => {
      if(articulo.querySelectorAll("td")[0].querySelector("select").value != ""){

        articulo.querySelectorAll("td")[4].querySelector("input").setAttribute("onchange","");

      }
   
  });
  comprobarFila(bodyArticulos[bodyArticulos.length-1].querySelectorAll("td")[4].querySelector("input"));
  
}


const comprobarStock = async () => {

  let articulos = document.querySelectorAll("#selectArticulo");
  let codArticulos = [];


  articulos.forEach(articulo => {
    if(articulo.value != ""){

      codArticulos.push({
        articulo:articulo.value.split("-")[0],
        cantidad:articulo.parentElement.parentElement.querySelectorAll("td")[3].textContent
      });

    }

  });

    let  response = await $.ajax({
      url : "Controller/RecodificacionController.php?accion=comprobarStock",
      type: "POST",
      data: {
        codArticulos:codArticulos
      },
    });

    let result = ""

    response = JSON.parse(response);
    articulos.forEach(element => {
        if(response.length > 0){
          if(response.includes(element.value.split("-")[0])){

            element.parentElement.parentElement.querySelectorAll("td")[3].style = "text-align:center;color:red";  

          }
          Swal.fire({
            icon: 'error',
            title: 'Error de stock',
            text: 'Hay artículos sin stock! Corregir y volver a solicitar'
          })
          result = false;

        }else{

          result = true;

        }
    });
    return result;
    
}

const eliminarFila = (div) => {

  let allTr = document.querySelectorAll("#bodyArticulos");

  if(allTr.length <= 1){
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'La Solicitud Debe tener Al Menos Un Artículo',
    })

  }else{

    div.parentElement.parentElement.remove();

  }
}

