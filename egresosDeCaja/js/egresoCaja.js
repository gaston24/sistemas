
const limitarArchivos = (input,nComp,codCta) => {

  const maxFiles = 3;

  if (input.files.length > maxFiles) {

      alert(`Solo se pueden seleccionar un máximo de ${maxFiles} archivos.`);

      input.value = ''; 

  }

  cargarArchivos(input,nComp,codCta);

}

const elegirImagen = (e) => {

  let nComp = e.parentElement.parentElement.querySelectorAll("td")[2].textContent;
  let codCta = e.parentElement.parentElement.querySelectorAll("td")[3].textContent;
  document.querySelector('#archivos').setAttribute("onchange",`limitarArchivos(this,'${nComp}','${codCta}')`);
  document.getElementById('archivos').click();

}

const cargarArchivos = (input,nComp,codCta) => {


eliminarArchivo(input,false,nComp,codCta); // Limpiamos Los Archivos cargados Para Ese Codigo de Articulo

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

enviarImagenes(nComp,codCta);

};

const enviarImagenes = (nComp,codCta) => {
  let nroSucursal = document.querySelector("#nroSucursal").textContent;
  // nComp = codArticulo.split("-")[0];

  const input = document.getElementById('archivos');
  let files = input.files;
  nameFile = nComp+nroSucursal+codCta;
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

      alert(response); 

    },
    error: function() {

      alert('Error al enviar las imágenes al servidor.');

    }

  });
  
}


const mostrarImagen = (divImagen, startIndex = 0) => {
  let codigosImagenes = [];

  let nroSucursal = document.querySelector("#nroSucursal").textContent;
  let nombre = divImagen.parentElement.parentElement.querySelectorAll("td")[2].textContent + nroSucursal + divImagen.parentElement.parentElement.querySelectorAll("td")[3].textContent;
  let carouselElement = document.querySelector('#carruselImagenes'); 

  carouselElement.innerHTML = ''; 

  $.ajax({
      url: "Controller/EgresoCajaController.php?accion=contarImagenes",
      type: "POST",
      data: { nComp: nombre },
      success: function (response) {
          response = JSON.parse(response);

          if (response['cantidad'] > 0) {
              for (let index = 0; index < response['nombre'].length; index++) {
                  codigosImagenes.push(response['nombre'][index] + '.jpg');
              }

              let modalContent = document.createElement('div');
              modalContent.className = 'modal-dialog modal-dialog-centered modal-fullscreen';

              let modalBody = document.createElement('div');
              modalBody.className = 'modal-content';

              let modalHeader = document.createElement('div');
              modalHeader.className = 'modal-header';
              
              let closeButton = document.createElement('button');
              closeButton.type = 'button';
              closeButton.className = 'close';
              closeButton.setAttribute('data-dismiss', 'modal');
              closeButton.setAttribute('aria-label', 'Close');
              closeButton.innerHTML = '<span aria-hidden="true">&times;</span>';
              modalHeader.appendChild(closeButton);
              
              modalBody.appendChild(modalHeader);

              let carousel = document.createElement('div');
              carousel.innerHTML = "";
              carousel.className = 'carousel slide';
              carousel.setAttribute('data-bs-ride', 'carousel');
              carousel.id = 'imageCarousel';

              let carouselInner = document.createElement('div');
              carouselInner.className = 'carousel-inner h-100';
              carouselInner.style.overflowY = 'hidden'; // Ocultar barra de desplazamiento vertical

              codigosImagenes.forEach((imagen, index) => {
                  validarExistenciaArchivo('../../../Imagenes/egresosCaja/' + imagen, function (existe) {
                      if (existe) {
                          let carouselItem = document.createElement('div');
                          carouselItem.className = index === startIndex ? 'carousel-item active h-100' : 'carousel-item h-100';
                          carouselItem.style = "text-align:center; position: relative;"; // Asegura que el botón de rotar esté posicionado relativo a la imagen
                          let imgElement = document.createElement('img');
                          imgElement.src = '../../../Imagenes/egresosCaja/' + imagen;
                          imgElement.className = 'd-block img-fluid';
                          imgElement.style = 'max-height: 80vh; width: auto;';

                          carouselItem.appendChild(imgElement);
                          carouselInner.appendChild(carouselItem);

                          // Crear el botón de rotar y añadirlo a cada ítem del carrusel
                          let rotateButton = document.createElement('button');
                          rotateButton.type = 'button';
                          rotateButton.className = 'btn btn-primary';
                          rotateButton.style.width = '150px';
                          rotateButton.style.position = 'absolute';
                          rotateButton.style.bottom = '20px';
                          rotateButton.style.left = '50%';
                          rotateButton.style.transform = 'translateX(-50%)';
                          rotateButton.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Rotar Imagen';
                          rotateButton.addEventListener('click', function () {
                              rotarImagen();
                          });

                          carouselItem.appendChild(rotateButton);
                      }
                  });
              });

              carousel.appendChild(carouselInner);
              modalBody.appendChild(carousel);
              modalContent.appendChild(modalBody);
              carouselElement.appendChild(modalContent);

              let prevControl = document.createElement('button');
              prevControl.className = 'carousel-control-prev';
              prevControl.type = 'button';
              prevControl.setAttribute('data-bs-target', '#imageCarousel');
              prevControl.setAttribute('data-bs-slide', 'prev');
              prevControl.innerHTML = '<span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>';
              prevControl.addEventListener('click', function() {
                  pasarImagen(-1);
              });

              let nextControl = document.createElement('button');
              nextControl.className = 'carousel-control-next';
              nextControl.type = 'button';
              nextControl.setAttribute('data-bs-target', '#imageCarousel');
              nextControl.setAttribute('data-bs-slide', 'next');
              nextControl.innerHTML = '<span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>';
              nextControl.addEventListener('click', function() {
                  pasarImagen(1);
              });

              carousel.appendChild(prevControl);
              carousel.appendChild(nextControl);

              let modal = new bootstrap.Modal(carouselElement);
              modal.show();

          } else {
              Swal.fire({
                  icon: 'info',
                  title: 'Sin imágenes',
                  text: 'No hay imágenes para mostrar.'
              });
          }
      }
  });
}

const pasarImagen = (pos) => {
  let items = document.querySelectorAll(".carousel-item");

  for (let index = 0; index < items.length; index++) {
      if (items[index].classList.contains("active")) {
          items[index].classList.remove("active");

          let newIndex = (index + pos + items.length) % items.length;
          items[newIndex].classList.add("active");
          break; // Detener el bucle una vez que se encontró el siguiente elemento activo
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

const eliminarArchivo = (div,alerta = true,nComp = null,codCta = null) => {

let nroSucursal = document.querySelector("#nroSucursal").textContent;
if(!nComp) {

  numComp = div.parentElement.parentElement.querySelectorAll("td")[2].textContent +nroSucursal+ div.parentElement.parentElement.querySelectorAll("td")[3].textContent;

}else{

  numComp = nComp+nroSucursal+codCta;

}


$.ajax({
  url: "Controller/EgresoCajaController.php?accion=eliminarArchivo",
  type: "POST",
  data: {
    nComp: numComp,
  },
  success: function (response) {
      if(alerta != false){

      alert("Se eliminó el archivo correctamente");

      }
  
  }
});


}


const guardar = (esBorrador = false) => {


let allTr = document.querySelectorAll("#bodyTable");
let dataArticulos = [];
let nComp = [];

let nroSucursal = document.querySelector("#nroSucursal").textContent;


allTr.forEach(e => {

  nComp.push(e.querySelectorAll("td")[2].textContent+nroSucursal+e.querySelectorAll("td")[3].textContent);

})

$.ajax({

  url: "Controller/EgresoCajaController.php?accion=contarImagenes",
  type: "POST",
  data: {
    arrayNcomp: nComp,
  },
  success: function (response) {
      let nroSucursal = document.querySelector("#nroSucursal").textContent;
      let error = false

      response = JSON.parse(response);
      
      if(response['cantidad'] > 0) {

        $.ajax({
          url: "Controller/EgresoCajaController.php?accion=guardar",
          type: "POST",
          data: {
            listaDeComprobantes : response['nombre'],
            nroSucursal : nroSucursal
          },
          success: function (response) {
            alert("Se guardó correctamente");
            location.reload(); 
          }

          });
      
        
       

      }else{

        

      }

     
      
  }
});

return 1



}


const rotarImagen = () => {
let carousel = document.querySelector('#carruselImagenes .carousel-inner');
let activeItem = carousel.querySelector('.carousel-item.active img');

// Obtener el ángulo actual de rotación (en grados)
let currentRotation = activeItem.getAttribute('data-rotation') || 0;

// Incrementar el ángulo de rotación en 90 grados
let newRotation = (parseInt(currentRotation) + 90) % 360;

// Aplicar la rotación a la imagen
activeItem.style.transform = `rotate(${newRotation}deg)`;

// Actualizar el atributo data-rotation
activeItem.setAttribute('data-rotation', newRotation);
}