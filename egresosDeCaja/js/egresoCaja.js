
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
  let nombre = divImagen.parentElement.parentElement.querySelectorAll("td")[2].textContent +nroSucursal+divImagen.parentElement.parentElement.querySelectorAll("td")[3].textContent;
  let carouselElement = document.querySelector('#carruselImagenes'); 

  carouselElement.innerHTML = ''; 

  $.ajax({

    url: "Controller/EgresoCajaController.php?accion=contarImagenes",
    type: "POST",
    data: {
      nComp:nombre

    },

    success: function (response) {

      response = JSON.parse(response);

      if(response['cantidad'] > 0){

        
        for (let index = 0; index < response['nombre'].length  ; index++) {
          
          codigosImagenes.push(response['nombre'][index] + '.jpg');

        }
        
        let modalContent = document.createElement('div');
        modalContent.className = 'modal-dialog modal-dialog-centered';
        modalContent.style = 'max-width: 100%;';

        let modalBody = document.createElement('div');
        modalBody.className = 'modal-content';

        let carousel = document.createElement('div');
        carousel.innerHTML = "";
        carousel.className = 'carousel slide';
        carousel.setAttribute('data-ride', 'carousel');

        let carouselInner = document.createElement('div');
        carouselInner.className = 'carousel-inner';


        codigosImagenes.forEach((imagen, index) => {

          validarExistenciaArchivo('../../../Imagenes/egresosCaja/' + imagen, function(existe) {

            if (existe) { 

              let carouselItem = document.createElement('div');
              carouselItem.className = index === startIndex ? 'carousel-item active' : 'carousel-item';
              carouselItem.style = "text-align:center"
              let imgElement = document.createElement('img');
              imgElement.src = '../../../Imagenes/egresosCaja/' + imagen;

              carouselItem.appendChild(imgElement);
              carouselInner.appendChild(carouselItem);
            }

          });
        });

        let closeButton = document.createElement('button');
        closeButton.type = 'button';
        closeButton.className = 'close';
        closeButton.setAttribute('data-dismiss', 'modal');
        closeButton.setAttribute('aria-label', 'Close');
        closeButton.innerHTML = '<span aria-hidden="true" title="Cerrar" style="font-size:50px; margin-right: 0.5rem; color: red;">&times;</span>';
    
        // Agregar el botón de cierre al encabezado del modal
        let modalHeader = document.createElement('div');
        modalHeader.appendChild(closeButton);
        modalBody.appendChild(modalHeader);

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
        
        let rotateButton = document.createElement('button');
        rotateButton.type = 'button';
        rotateButton.style.width = '400px'
        rotateButton.style.height = '50px'
        rotateButton.style.marginTop = '10px'
        rotateButton.style.marginBottom = '10px'
        rotateButton.style.marginLeft = '35%'
        rotateButton.className = 'btn btn-primary'; // Puedes ajustar las clases según tu estilo
        rotateButton.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Rotar Imagen';
        rotateButton.addEventListener('click', function() {
            rotarImagen();
        });
        
        modalBody.appendChild(rotateButton);

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