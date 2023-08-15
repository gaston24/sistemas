const mostrarImagen = (divImagen, startIndex = 0) => {

 
    let codigosImagenes = [];
  
    let codigoArticulo = divImagen.parentElement.parentElement.querySelectorAll("td")[0].textContent;

    let numSolicitud = document.querySelector("#numSolicitud").value;
    let carouselElement = document.querySelector('#carruselImagenes'); 
    console.log(codigoArticulo);

    carouselElement.innerHTML = ''; 

    $.ajax({

      url: "Controller/RecodificacionController.php?accion=contarImagenes",
      type: "POST",
      data: {
        codArticulo:codigoArticulo,
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

const pasarImagen = (pos) => {
 
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
  