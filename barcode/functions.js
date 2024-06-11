document.addEventListener("DOMContentLoaded", iniciarEscucha);

let b = 0; //bandera->indica si el form tiene los inputs cargados correctamente
/* let descripcion = document.getElementById("descripcion_falla");
let codigo = document.getElementById("codigo"); */


/* if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
  form.reset();
  descripcion.classList.remove('error');
  codigo.focus();
} */

function iniciarEscucha() {
  document
    .getElementById("barcodeValue")
    .addEventListener("keydown", (e) => buscarCodigo(e));
    document
    .getElementById("barcodeValue")
    .addEventListener("change", (e) => buscarCodigo(e));
    document
    .getElementById("barcodeValue")
    .addEventListener("keyup", (e) => buscarCodigo(e));
    document
    .getElementById("deleteTextSearch")
    .addEventListener("click", () => document
    .getElementById("barcodeValue").value="");
    
    document
    .getElementById("barcodeValue")
    .addEventListener("keypress", (e) => buscarCodigo(e));
  /*  document.getElementById('codigo').addEventListener('change',(e)=>console.log(e.target.value)); */
 /*  document.getElementById("submit").addEventListener("click", cargarTalonario); */

}


/* function cargarTalonario(e) {
  if (b == 1 && descripcion.value != "" && codigo.value != "" ) {
    openPage();
    form.reset();
    descripcion.classList.remove("error");
    codigo.focus();
  } else {
    if (descripcion.value == "" && codigo.value=="") {
      descripcion.classList.add("error");
      codigo.classList.add("error");
    }else
    {
      if(descripcion.value=="")
      {
        descripcion.classList.add("error");
        descripcion.focus();
      }else{
        codigo.classList.add("error");
        codigo.focus();
      }
    }
  }
} */

let conexion;

function buscarCodigo(e) {

  console.log(e.target.value);
  conexion = new XMLHttpRequest();
  conexion.onreadystatechange = mostrarResultados;

  conexion.open("GET", "articulos.php?codigo=" + e.target.value, true);
  conexion.send();
}

/* let escribirInput = document.getElementById("descripcion_articulo"); */

let msj_error = document.getElementById("mensaje");

let descripcion_articulo;

function mostrarResultados() {
  b = 0;
 /*  msj_error.classList.remove("error"); */
  if (conexion.readyState == 4) {
    let resultado = conexion.responseText;
    console.log(resultado);
    if (resultado == "error" ||  document
    .getElementById("barcodeValue").value == '') {
      msj_error.style.display="inline-block";
    } else {
      console.log('hola');
      b = 1;
      msj_error.style.display="none";
      document.getElementById("descripcion").value=resultado;
     console.log(resultado)
    }
  }
}

openPage = function () {
  let dir =
    "talonario.php?codigo=" +
    document.getElementById("codigo").value +
    "&descripcion=" +
    document.getElementById("descripcion_articulo").value +
    "&descripcion_falla=" +
    document.getElementById("descripcion_falla").value;
  /*  location.href =dir;   */
  window.open(dir, "_blank");
  /* window.location.replace(dir); */
  /*  "2.html?Key="+scrt_var */
};

function clearInp() {
  let arr = (document.getElementsByTagName("input").value = "");
  console.log(arr);
}
