
const limitarArchivos = (input,nComp) => {

    const maxFiles = 3;

    if (input.files.length > maxFiles) {

        alert(`Solo se pueden seleccionar un máximo de ${maxFiles} archivos.`);

        input.value = ''; 

    }

    cargarArchivos(input,nComp);

}

const elegirImagen = (e) => {

    let nComp = e.parentElement.parentElement.querySelectorAll("td")[2].textContent;
    document.querySelector('#archivos').setAttribute("onchange",`limitarArchivos(this,'${nComp}')`);
    document.getElementById('archivos').click();

}
  
const cargarArchivos = (input,nComp) => {


  eliminarArchivo(input,false,nComp); // Limpiamos Los Archivos cargados Para Ese Codigo de Articulo

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

  enviarImagenes(nComp);

};

const enviarImagenes = (nComp) => {
   
    // nComp = codArticulo.split("-")[0];
  
    const input = document.getElementById('archivos');
    let files = input.files;
    nameFile = nComp ;
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
  
    let nComp = divImagen.parentElement.parentElement.querySelectorAll("td")[2].textContent;

    
    let carouselElement = document.querySelector('#carruselImagenes'); 
  
    carouselElement.innerHTML = ''; 

    $.ajax({

      url: "Controller/EgresoCajaController.php?accion=contarImagenes",
      type: "POST",
      data: {
        nComp:nComp
     

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
  
const eliminarArchivo = (div,alerta = true,nComp = null) => {


  if(!nComp) {

    numComp = div.parentElement.parentElement.querySelectorAll("td")[2].textContent;

  }else{

    numComp = nComp;

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
  allTr.forEach(e => {

    nComp.push(e.querySelectorAll("td")[2].textContent);

  })

  $.ajax({
  
    url: "Controller/EgresoCajaController.php?accion=contarImagenes",
    type: "POST",
    data: {
      arrayNcomp: nComp,
    },
    success: function (response) {
        let error = false

        response = JSON.parse(response);
        
        if(response['cantidad'] > 0) {

          $.ajax({
            url: "Controller/EgresoCajaController.php?accion=guardar",
            type: "POST",
            data: {
              listaDeComprobantes : response['nombre']
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