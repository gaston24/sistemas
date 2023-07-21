document.addEventListener("DOMContentLoaded", iniciarEscucha);

let provincia = document.querySelectorAll("#provincia");
let tarjeta = document.querySelectorAll("#tarjeta");
let cuota = document.querySelectorAll("#cuota");
let btnAplicar = document.getElementById("update");
let provinciasSet = {};
let tarjetasSet = {};
let cuotasSet = {};

function iniciarEscucha() {
  console.log("iniciando escucha");
  provincia.forEach((item) =>
    item.addEventListener("click", () => addOrDelete(provinciasSet, item.value))
  );
  tarjeta.forEach((item) =>
    item.addEventListener("click", () => addOrDelete(tarjetasSet, item.value))
  );
  cuota.forEach((item) =>
    item.addEventListener("click", () => addOrDelete(cuotasSet, item.value))
  );
  btnAplicar.addEventListener("click", actualizar);
}

function addOrDelete(objet, value) {
  // si el valor no se encuentra en el objeto lo agrega, caso contrario lo elimina. Esto se produce luego de seleccionar un checkbox y luego lo vuelve a deseleccionar.

  if (objet.hasOwnProperty(value)) {
    console.log(objet);
    delete objet[value];
  } else {
    console.log(value);
    objet[value] = true;
  }
}

function actualizar()
{
    //sweet alert
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
          confirmButton: 'btn btn-success',
          cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
      })
    /************************* */
    let spinner=document.querySelector('.loader');
    let switchbutton=document.querySelector('.switch-button .switch-button__label');//el bton switch por las propiedades que tiene no se oculta. Lo controlo desde acá. 

/***************************/
    estado=document.getElementById("switch-label").checked;//valor boton switch->rojo=eliminar verde=agregar


swalWithBootstrapButtons.fire({
  title: 'Estas segura?',
  text: "",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonText: 'Si',
  cancelButtonText: 'No',
  reverseButtons: true
}).then((result) => {
  if (result.isConfirmed) {
    spinner.style.display='flex';
    spinner.innerHTML='Procesando...';
    switchbutton.style.display='none';
    fetch("Controller/updateCuotasMasivo.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({provinciasSet,tarjetasSet,cuotasSet,estado}),
      })
        .then(function (response) {
          if (response.ok) {
            // La solicitud se completó exitosamente
            /* return response.json(); */
            swalWithBootstrapButtons.fire(
                'Ok!',
                'Las cuotas fueron actualizadas.',
                'success'
              ).then((result)=>{
                if (result.isConfirmed) {
                     /* 
              limpiar checkbox 
              */
             limpiarCheckbox();
                    location.reload();
                }
              })
             
          } else {
            swalWithBootstrapButtons.fire(
                'mmm...',
                'Algo salio mal',
                'error ' + `Error en la solicitud! Código de estado: ${response.status}`
              )
           /*  alert(
              `Error en la solicitud! Código de estado: ${response.status}`
            ); */
            throw new Error("Error en la solicitud: " + response.status);
           
          }
          /*************************** */
          spinner.style.display='none';
          switchbutton.style.display='inline-block';
          /************************** */
          
        })
        .then(function (data) {
          // Hacer algo con la respuesta recibida del servidor (data)
          /* location.reload(); */
        })
        .catch(function (error) {
          // Manejar errores
          alert(error.message);
          console.log("error: ", error);
        });
   
  } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
  ) {
    swalWithBootstrapButtons.fire(
      'Cancelled',
      'Your imaginary file is safe :)',
      'error'
    )
  }
})


   
}


function limpiarCheckbox()
{
    let contenedorCheckbox=document.querySelectorAll('.contenedor input');// si tuviera otros tipos de input modificar el argumento por input[type='checkbox']
    contenedorCheckbox.forEach(item=>{item.checked=false})
}