
// Función para detectar si es un dispositivo móvil
function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

const elegirImagen = (e) => {
    let card = e.closest('.card');
    let nComp = card.getAttribute('data-ncomp');
    let codCta = card.querySelector('.card-subtitle').textContent.split('|')[0].trim();
    
    let input = document.getElementById('archivos');
    input.setAttribute("data-ncomp", nComp);
    input.setAttribute("data-codcta", codCta);
    input.setAttribute("onchange", "cargarArchivos(this)");
    
    if (isMobile()) {
        input.setAttribute("capture", "camera");
    } else {
        input.removeAttribute("capture");
    }
    
    input.value = ''; // Resetear el valor del input
    input.click();
}

const limitarArchivos = (input, nComp, codCta) => {
    const maxFiles = 3;
    if (input.files.length > maxFiles) {
        Swal.fire({
            icon: 'error',
            title: 'Límite de archivos excedido',
            text: `Solo se pueden seleccionar un máximo de ${maxFiles} archivos.`
        });
        input.value = '';
    } else {
        cargarArchivos(input, nComp, codCta);
    }
}

const cargarArchivos = (input) => {
    const files = input.files;
    const nComp = input.getAttribute('data-ncomp');
    const codCta = input.getAttribute('data-codcta');
    const card = document.querySelector(`.card[data-ncomp="${nComp}"]`);
    
    if (!card) {
        console.error('No se encontró la tarjeta correspondiente');
        return;
    }

    const previewContainer = card.querySelector('.file-preview');
    if (!previewContainer) {
        console.error('No se encontró el contenedor de vista previa');
        return;
    }

    previewContainer.innerHTML = ''; // Limpiar previsualizaciones anteriores
    
    let imagesToUpload = [];

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(e) {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            previewItem.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="btn btn-danger btn-delete" onclick="eliminarArchivoPreview(this, '${nComp}', '${codCta}')">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            `;
            previewContainer.appendChild(previewItem);
            
            imagesToUpload.push({file: file, preview: e.target.result});

            // Si es la última imagen, llamar a enviarImagenes
            if (imagesToUpload.length === files.length) {
                enviarImagenes(nComp, codCta, imagesToUpload);
            }
        };

        reader.readAsDataURL(file);
    }
}

const eliminarArchivoPreview = (button, nComp, codCta) => {
    const previewItem = button.closest('.preview-item');
    const previewContainer = previewItem.parentElement;
    const imageIndex = Array.from(previewContainer.children).indexOf(previewItem);
    const nroSucursal = document.querySelector("#nroSucursal").textContent;
    const nombreCompleto = nComp + nroSucursal + codCta;

    eliminarArchivo(nombreCompleto, imageIndex, previewItem);
}


const enviarImagenes = (nComp, codCta) => {
    let nroSucursal = document.querySelector("#nroSucursal").textContent;
    const input = document.getElementById('archivos');
    let files = input.files;
    const formData = new FormData();
    const maxFiles = 3;

    if (files.length > maxFiles) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `Solo se pueden seleccionar un máximo de ${maxFiles} archivos.`
        });
        return;
    }

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileExtension = file.name.split('.').pop();
        const newFileName = `${nComp}${nroSucursal}${codCta}_${i}_${Date.now()}.${fileExtension}`;
        
        // Crear un nuevo objeto File con el nombre modificado
        const renamedFile = new File([file], newFileName, { type: file.type });
        
        formData.append('archivos[]', renamedFile);
    }

    formData.append('nComp', nComp);
    formData.append('nroSucursal', nroSucursal);
    formData.append('codCta', codCta);

    $.ajax({
        url: 'upload_image_mob.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.message
                });
                // Aquí podrías actualizar la vista previa o la lista de imágenes
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
            if (response.errors && response.errors.length > 0) {
                console.error('Errores:', response.errors);
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al enviar las imágenes al servidor.'
            });
        }
    });
}

const getFileExtension = (filename) => {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}

const mostrarImagen = (button) => {
    let card = button.closest('.card');
    let nComp = card.getAttribute('data-ncomp');
    let codCta = card.querySelector('.card-subtitle').textContent.split('|')[0].trim();
    let nroSucursal = document.querySelector("#nroSucursal").textContent;
    let nombre = nComp + nroSucursal + codCta;
    let carouselElement = document.querySelector('#carruselImagenes'); 

    if (!carouselElement) {
        console.error('No se encontró el elemento del carrusel');
        return;
    }

    carouselElement.innerHTML = ''; 

    $.ajax({
        url: "controller/EgresoCajaController.php?accion=contarImagenesMob",
        type: "POST",
        data: { nComp: nombre },
        dataType: 'json',
        success: function(response) {
            console.log('Respuesta del servidor:', response);
            if(response.cantidad > 0){
                let codigosImagenes = response.nombre;
                
                let modalContent = document.createElement('div');
                modalContent.className = 'modal-dialog modal-dialog-centered modal-fullscreen';

                let modalBody = document.createElement('div');
                modalBody.className = 'modal-content';

                let carousel = document.createElement('div');
                carousel.className = 'carousel slide';
                carousel.setAttribute('data-bs-ride', 'carousel');
                carousel.id = 'imageCarousel';

                let carouselInner = document.createElement('div');
                carouselInner.className = 'carousel-inner h-100';

                codigosImagenes.forEach((imagen, index) => {
                    let carouselItem = document.createElement('div');
                    carouselItem.className = index === 0 ? 'carousel-item active h-100' : 'carousel-item h-100';
                    carouselItem.innerHTML = `
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <img src="../../../Imagenes/egresosCaja/${imagen}" class="d-block img-fluid" alt="Imagen ${index + 1}" style="max-height: 80vh; width: auto;">
                        </div>
                    `;
                    carouselInner.appendChild(carouselItem);
                });

                let closeButton = document.createElement('button');
                closeButton.type = 'button';
                closeButton.className = 'btn-close position-absolute top-0 end-0 m-3';
                closeButton.setAttribute('data-bs-dismiss', 'modal');
                closeButton.setAttribute('aria-label', 'Close');

                carousel.appendChild(carouselInner);
                modalBody.appendChild(carousel);
                modalBody.appendChild(closeButton);
                modalContent.appendChild(modalBody);
                carouselElement.appendChild(modalContent);

                let prevControl = document.createElement('button');
                prevControl.className = 'carousel-control-prev';
                prevControl.type = 'button';
                prevControl.setAttribute('data-bs-target', '#imageCarousel');
                prevControl.setAttribute('data-bs-slide', 'prev');
                prevControl.innerHTML = '<span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span>';

                let nextControl = document.createElement('button');
                nextControl.className = 'carousel-control-next';
                nextControl.type = 'button';
                nextControl.setAttribute('data-bs-target', '#imageCarousel');
                nextControl.setAttribute('data-bs-slide', 'next');
                nextControl.innerHTML = '<span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span>';

                carousel.appendChild(prevControl);
                carousel.appendChild(nextControl);

                let rotateButton = document.createElement('button');
                rotateButton.type = 'button';
                rotateButton.className = 'btn btn-primary position-absolute bottom-0 start-50 translate-middle-x mb-3';
                rotateButton.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Rotar';
                rotateButton.addEventListener('click', rotarImagen);
                
                modalBody.appendChild(rotateButton);

                let modal = new bootstrap.Modal(carouselElement);
                modal.show();

                implementTouchGestures(carousel);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin imágenes',
                    text: 'No hay imágenes para mostrar.'
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar las imágenes:', textStatus, errorThrown);
            console.log('Respuesta del servidor:', jqXHR.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al cargar las imágenes.'
            });
        }
    });
}

const implementTouchGestures = (carousel) => {
    let startX;
    let endX;

    carousel.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
    }, false);

    carousel.addEventListener('touchend', (e) => {
        endX = e.changedTouches[0].clientX;
        handleSwipe();
    }, false);

    const handleSwipe = () => {
        if (startX - endX > 50) {
            // Swipe izquierda
            carousel.querySelector('.carousel-control-next').click();
        } else if (endX - startX > 50) {
            // Swipe derecha
            carousel.querySelector('.carousel-control-prev').click();
        }
    };
}

const pasarImagen = (pos) => {
    let items = document.querySelectorAll(".carousel-item");
    for (let index = 0; index < items.length; index++) {
        if (items[index].classList.contains("active")) {
            if (items[index + pos] !== undefined) {
                items[index].classList.remove("active");
                items[index + pos].classList.add("active");
                break;
            }
        }
    }
}

const validarExistenciaArchivo = (rutaArchivo, callback) => {
    const img = new Image();
    img.onload = function() {
        callback(true);
    };
    img.onerror = function() {
        callback(false);
    };
    img.src = rutaArchivo;
}

const eliminarArchivo = (nombreCompleto, imageIndex, previewItem) => {
    $.ajax({
        url: "Controller/EgresoCajaController.php?accion=eliminarArchivoMob",
        type: "POST",
        data: {
            nComp: nombreCompleto,
            index: imageIndex
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Eliminar la vista previa de la imagen
                previewItem.remove();
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar el archivo'
            });
        }
    });
}

const guardarGasto = (button) => {
    let card = button.closest('.card');
    let nComp = card.getAttribute('data-ncomp');
    let codCta = card.querySelector('.card-subtitle').textContent.split('|')[0].trim();
    let nroSucursal = document.querySelector("#nroSucursal").textContent;

    $.ajax({
        url: "Controller/EgresoCajaController.php?accion=guardarMob",
        type: "POST",
        data: {
            nComp: nComp,
            codCta: codCta,
            nroSucursal: nroSucursal
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Gasto guardado',
                    text: response.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    // Recargar la página
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.message || 'Error al guardar el gasto.'
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error:', textStatus, errorThrown);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar el gasto. Por favor, intente de nuevo.'
            });
        }
    });
}

const rotarImagen = () => {
    let activeItem = document.querySelector('#imageCarousel .carousel-item.active img');
    let currentRotation = activeItem.getAttribute('data-rotation') || 0;
    let newRotation = (parseInt(currentRotation) + 90) % 360;
    activeItem.style.transform = `rotate(${newRotation}deg)`;
    activeItem.setAttribute('data-rotation', newRotation);
}



// Función para filtrar las tarjetas basándose en la búsqueda
function filterCards() {
    var input, filter, cards, card, ncomp, i;
    input = document.getElementById('searchInput');
    filter = input.value.toUpperCase();
    cards = document.getElementsByClassName('card');

    for (i = 0; i < cards.length; i++) {
        card = cards[i];
        ncomp = card.getAttribute('data-ncomp');
        if (ncomp.toUpperCase().indexOf(filter) > -1) {
            card.style.display = "";
        } else {
            card.style.display = "none";
        }
    }

    document.getElementById('clearSearch').style.display = input.value.length > 0 ? "block" : "none";
}

// Función para limpiar el campo de búsqueda
function clearSearch() {
    var input = document.getElementById('searchInput');
    input.value = '';
    filterCards();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchInput');
    var clearButton = document.getElementById('clearSearch');

    if (searchInput) {
        searchInput.addEventListener('keyup', filterCards);
        searchInput.addEventListener('input', filterCards);
    }

    if (clearButton) {
        clearButton.addEventListener('click', clearSearch);
    }

    console.log("DOM fully loaded and parsed");
});