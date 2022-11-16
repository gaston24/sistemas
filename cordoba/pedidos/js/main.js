const form = document.getElementById("formulario");
let b = 0;
let stock;
let stockModificado = [];
let indice;
let preloader=document.querySelector('.container');
let documentTitle=document.title;//será usado para identificar si se deberá traer stock de art generales o accesorios en traerStock.php

let totalArticulos = document.getElementById("total");
let totalPrecio = document.getElementById("totalPrecio");

let artCargados = [];

function total() {
  var suma = 0;

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_812[]']");
  var i;
  for (i = 0; i < x.length; i++) {
    suma += parseInt(0 + x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_813[]']");
  var i;
  for (i = 0; i < x.length; i++) {
    suma += parseInt(0 + x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_814[]']");
  var i;
  for (i = 0; i < x.length; i++) {
    suma += parseInt(0 + x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_815[]']");
  var i;
  for (i = 0; i < x.length; i++) {
    suma += parseInt(0 + x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_816[]']");
  var i;
  for (i = 0; i < x.length; i++) {
    suma += parseInt(0 + x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_876[]']");
  var i;
  for (i = 0; i < x.length; i++) {
    suma += parseInt(0 + x[i].value);
  }

  document.getElementById("total").value = suma;
}

function precioTotal() {
  var precioTodos = 0;
  var p = document.querySelectorAll("#id_tabla #precio");

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_812[]']");
  var i;
  for (i = 0; i < p.length; i++) {
    precioTodos += parseInt(0 + p[i].innerHTML * x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_813[]']");
  var i;
  for (i = 0; i < p.length; i++) {
    precioTodos += parseInt(0 + p[i].innerHTML * x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_814[]']");
  var i;
  for (i = 0; i < p.length; i++) {
    precioTodos += parseInt(0 + p[i].innerHTML * x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_815[]']");
  var i;
  for (i = 0; i < p.length; i++) {
    precioTodos += parseInt(0 + p[i].innerHTML * x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_816[]']");
  var i;
  for (i = 0; i < p.length; i++) {
    precioTodos += parseInt(0 + p[i].innerHTML * x[i].value);
  }

  var x = document.querySelectorAll("#id_tabla input[name='cantPed_876[]']");
  var i;
  for (i = 0; i < p.length; i++) {
    precioTodos += parseInt(0 + p[i].innerHTML * x[i].value);
  }

  document.getElementById("totalPrecio").value = precioTodos;

  // console.log("Cupo disponible: "+cupo_credito+ " - Pedidos total: "+precioTodos);

  // var diferencia = (cupo_credito - precioTodos)*-1;
  // const number = diferencia;
  // diferencia = number.toLocaleString().toLocaleString('en-US', { maximumFractionDigits: 2 });

  // if(parseInt(precioTodos, 10) > parseInt(cupo_credito, 10)){
  //     document.getElementById("cupoCreditoExcedido").innerHTML = "<strong style='color: red;'>CUPO DE CREDITO EXCEDIDO EN "+diferencia+" PESOS</strong>";
  // }else{
  //     document.getElementById("cupoCreditoExcedido").innerHTML = "";
  // }
}

function pulsar(e) {
  tecla = document.all ? e.keyCode : e.which;
  return tecla != 13;
}

/************************************************************************************************************************************************************ */

//guardo el array con todos los input text, del cual se escuchará el evento change para detectar la fila donde se está cambiando el valor
let inputCantidad = document.querySelectorAll("input[type=text]");

inputCantidad.forEach((el) => el.addEventListener("change", verificarTotal));

/************************************************************************************************************************************************************ */

function verificarTotal(e) {
  //capturo el total de stocken casa central para un articulo a partir del change en un input de la fila
  let total = parseInt(
    e.target.parentElement.parentElement.children[6].textContent
  );

  //guardo el arreglo con los inputs de cantidad de la fila seleccionada
  let fila =
    e.target.parentElement.parentElement.querySelectorAll("input[type=text]");

  //guardo el precio del articulo de la fila en cuestión
  let precioArticulo = parseInt(
    e.target.parentElement.parentElement.querySelector("#precio").textContent
  );

  let totalFila = 0;

  //sumo los value de los input de la fila
  fila.forEach((el) => (totalFila += parseInt(el.value)));

  //comparo la suma de la fila con el total stock del articulo
  if (totalFila > total) {
    totalArticulos.value -= totalFila; //borro los cambios en el "Total de Articulos"
    totalPrecio.value -= totalFila * precioArticulo; //borro los cambios en "Importe Total"
    Swal.fire({
      icon: "error",
      title: "Error...",
      text: "La cantidad ingresada es mayor al stock!",
    });
    fila.forEach((el) => parseInt((el.value = 0)));
  } else {
    //si está ok cargo el codigo de articulo y cantidad solicitada al arreglo artCargados, luego comparo con el stock en central por si hubo cambios en el stock y este es menor al solicitado
    let codigoArticulo =
      e.target.parentElement.parentElement.children[1].innerHTML;
    //verifico si el codigo ya se encuentra en el arreglo artcargados, en ese caso se actualiza la cantidad, para no duplicar los registros
    if (buscarArticulo(codigoArticulo) != -1) {
      artCargados[indice].cantidad = totalFila;
    } else {
      artCargados.push({ codigo: codigoArticulo, cantidad: totalFila });
    }
  }
}

function buscarArticulo(codigo) {
  indice = artCargados.findIndex((el) => {
    return el.codigo == codigo;
  });
  return indice;
}

/***********************************************************************************************************************************************************  */
//controlar stock
/*  formulario.addEventListener("submit",controlar); */
formulario.addEventListener("submit", function (e) {
  e.preventDefault();
  preloader.style.display='block';
  traerStock();

  /*  e.preventDefault() */
});
/* document.getElementById('btnEnviar').addEventListener('click',controlar); */

function controlar() {
  /*  e.preventDefault(); */
  /*  form.preventDefault(); */
  console.log("que queres");
  traerStock();
  /* controlarStockActual(); */
}

let conexion1;
function traerStock() {
  // vuelvo a traer el stock para chequear si este fue modificado
  conexion1 = new XMLHttpRequest();
  conexion1.onreadystatechange = () => {
    if (conexion1.readyState == 4 && conexion1.status == 200) {
      console.log("entraste a traerStock");
      stock = JSON.parse(conexion1.responseText);
      console.log('erer: '+stock);
      controlarStockActual();
    }
  };

  conexion1.open("GET", "traerStock.php?title="+documentTitle, true);
  conexion1.send();
}
var encontrado;
function controlarStockActual() {
  let b;
  for (let i = 0; i < artCargados.length; i++) {
    b = 0;
    for (let x = 0; x < stock.length; x++) {
      if (stock[x].COD_ARTICU == artCargados[i].codigo.trim()) {
        b = 1;
        encontrado = stock[x];
        console.log("asdf" + encontrado);
        if (artCargados[i].cantidad > encontrado.CANT_STOCK) {
          stockModificado.push(artCargados[i].codigo);
        }
        break;
      }
    }
    if (b == 0) {
      stockModificado.push(artCargados[i].codigo);
    }
    /*   encontrado = stock.find(el => 
      el.COD_ARTICU = artCargados[i].codigo.trim()
    ); */
  }
  preloader.style.display='none';
  console.log("encontrado: " + encontrado);
  if (stockModificado.length > 0) {
    Swal.fire({
      icon: "error",
      title: "Error...",
      text:
        "El stock se ha modificado en los siguientes articulos: " +
        stockModificado.join(" , "),
    }).then((result) => {
      if (result.isConfirmed) {
        location.reload();
      }
    });
  } else {
    /*  form.submit(); */
    form.action = "cargarPedidoNuevoCordoba.php";
    form.submit();
  }
}
