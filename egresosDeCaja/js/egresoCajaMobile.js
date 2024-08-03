// Función para elegir imagen
const elegirImagen = (button) => {
    let card = button.closest('.card');
    let nComp = card.querySelector('.card-title').textContent.split(' - ')[1].split(' ')[0];
    let codCta = card.querySelector('.card-subtitle').textContent.split(' ')[0];
    
    let input = document.getElementById('archivos');
    input.setAttribute("onchange", `handleImageCapture(this,'${nComp}','${codCta}')`);
    input.click();
}

// Nueva función para manejar la captura de imagen
const handleImageCapture = (input, nComp, codCta) => {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        
        reader.onload = function(e) {
            // Crear una previsualización de la imagen
            let preview = document.createElement('img');
            preview.src = e.target.result;
            preview.style.maxWidth = '100%';
            preview.style.height = 'auto';
            
            // Mostrar la previsualización en un modal
            let modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Previsualización de imagen</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- La imagen se insertará aquí -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="button" class="btn btn-primary" onclick="confirmarImagen('${nComp}','${codCta}')">Confirmar</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            modal.querySelector('.modal-body').appendChild(preview);
            
            let modalInstance = new bootstrap.Modal(modal);
            modalInstance.show();
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Nueva función para confirmar la imagen
const confirmarImagen = (nComp, codCta) => {
    let input = document.getElementById('archivos');
    if (input.files && input.files[0]) {
        cargarArchivos(input, nComp, codCta);
    }
    bootstrap.Modal.getInstance(document.querySelector('.modal')).hide();
}

// Actualizar la función cargarArchivos
const cargarArchivos = (input, nComp, codCta) => {
    eliminarArchivo(null, false, nComp, codCta);
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            uploadedImage = e.target.result;
            // Aquí puedes actualizar la UI para mostrar que la imagen se ha cargado
            // Por ejemplo, cambiar el ícono del botón o mostrar un mensaje
        };
        reader.readAsDataURL(file);
        enviarImagenes(nComp, codCta);
    }
};

// El resto de las funciones (enviarImagenes, mostrarImagen, eliminarArchivo, etc.) permanecen iguales

// Asegúrate de que este código se ejecute después de que el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', () => {
    // Aquí puedes inicializar cualquier cosa necesaria al cargar la página
});