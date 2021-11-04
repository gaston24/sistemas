//Búsqueda rápida table//

function myFunction() {
  var input, filter, table, tr, td, td2, i, txtValue;
  input = document.getElementById("textBox");
  filter = input.value.toUpperCase();
  table = document.getElementById("table");
  tr = table.getElementsByTagName("tr");
  //tr = document.getElementById('tr');

  for (i = 0; i < tr.length; i++) {
    visible = false;
    /* Obtenemos todas las celdas de la fila, no sólo la primera */
    td = tr[i].getElementsByTagName("td");

    for (j = 0; j < td.length; j++) {
      if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
        visible = true;
      }
    }
    if (visible === true) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}


//Verifica si el artículo existe en la STA11//

function traerDescripcion() {
  $("#inputDescrip").val("");
  $("#inputPrecio").val("");
  $("#inputTemp").val("");

  var codigoAbuscar = $("#codArticulo").val();

  $.ajax({
    url: "Controller/buscarCodigo.php",
    method: "POST",
    data: {
      codArticu: codigoAbuscar,
    },
    success: function (data) {
      let datos = JSON.parse(data);
      if (datos.code != 200) {
        $("#inputDescrip").val("NO EXISTE EL ARTICULO SELECCIONADO");
      } else {
        let codigo = JSON.parse(datos.msg);
        $("#inputDescrip").val(codigo[0].DESCRIPCIO);
        $("#inputPrecio").val(parseInt(codigo[0].PRECIO));
        $("#inputTemp").val(codigo[0].TEMPORADA);
      }
    },
  });
}

let conexion;

function insertarArticulo() {
//agregar condición
    let articulo = document.getElementById("codArticulo").value;
    let descripcion = document.getElementById("inputDescrip").value;
    let precio = document.getElementById("inputPrecio").value;
    let temporada = document.getElementById("inputTemp").value;

    conexion = new XMLHttpRequest();
    conexion.onreadystatechange = procesarEvento;
    conexion.open("GET","Class/altaArticulo.php?articulo="+articulo+"&descrip="+descripcion+"&precio="+precio+"&temporada="+temporada, true);
    conexion.send();
    
  }

function procesarEvento()
{
if (conexion.readyState == 4) {
  Swal.fire({
    icon: 'success',
    title: 'Guardado exitosamente',
    showConfirmButton: false,
    timer: 2000
  });
  redireccionar();
} else {
/* alert('error'); */
}
}

//Cierra el modal de carga de artículos//

function CierraModal() {
  $("#AltaModal").modal('hide');//ocultamos el modal
  $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
  location.reload(true);
}

function redireccionar() {
  setTimeout("location.href='index.php'", 4000);
}

//Array de las filas con cantidad mayor a cero//

function matrizPedidos()  { 
  const matriz = Array.from(document.getElementById("table").rows);
  const matriz2 = matriz.filter(x=>x.querySelector('input').value !=0);
  const matriz3 = matriz2.map( function(x) {
      let valor = x.querySelectorAll('td');
      var another = [];
      valor.forEach(function(x, z){
        if(z==0 || z==6) {another[z] = ''} 
          else if(z==7){another[z]= x.firstChild.value}
        else{another[z] = x.innerHTML}; 
        //another[z] = (z==0) ? '' : ( (z==6) ? x.firstChild.value : (z==7 ? another[z] = x.innerHTML.trim() : another[z] = x.innerHTML) );//verificar 2da condicion z==5
      })
      console.log(another);
      return another;
  })
  console.log(matriz3);
  return matriz3;
}

//Array de orden de pedido//

function matrizOrden()  { 
  const matriz = Array.from(document.getElementById("tableOrden").rows);
  const matriz2 = matriz.filter(x=>x.querySelector("input[type=checkbox]:checked"));
  const matriz3 = matriz2.map( function(x) {
      let valor = x.querySelectorAll('td');
      var another = [];
      valor.forEach(function(x, z){
        if(z==0)  {another[z] = ''}
          else if(z==4) {another[z] = x.innerHTML.replace(".","")}
          else if(z==6) {another[z] = x.firstChild.value}
          else if(z==7) {another[z] = x.firstChild.value}
          else if(z==7) {another[z] = ''}
        else{another[z] = x.innerHTML};
          //another[z] = (z==0) ? '' : ( (z==7) ? x.firstChild.value : (z==7 ? another[z] = x.innerHTML.trim() : another[z] = x.innerHTML) );//verificar 2da condicion z==5
      })
      console.log(another);
      return another;
  })
  console.log(matriz3);
  return matriz3;
}

//

function matrizOrdenes()
{
  let checked=document.querySelectorAll("input[type=checkbox]:checked");
  let matriz=[];
 for(let i=0;i<checked.length;i++)
 {
   matriz[i]=checked[i].parentElement.parentElement.childNodes[5].textContent;
 }
 return matriz;
}

//Count checkbox gestion de ordenes//

function contarGestion() {
  var checkbox = document.querySelectorAll('#tableGestionOrden input[type="checkbox"]'); //Array que contiene los checkbox

  var cont2 = 0; //Variable que lleva la cuenta de los checkbox pulsados

  for (var x=0; x < checkbox.length; x++) {
   if (checkbox[x].checked) {
    cont2 = cont2 + 1;
   }
  }

  document.getElementById('totalOrdenes').value = cont2;

 }

//Revisa que se haya seleccionado alguna orden y actualiza el campo ACTIVA//

function activaOrdenes() {
  const matriz = matrizOrdenes();
  contar2 = $('#totalOrdenes').val();

  if (contar2 != 0) {

    postearOrdenes(matriz);

  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error de seleccion',
      text: "Numero de orden: " + data,
      text: 'No hay ninguna orden seleccionada!'
    });
  }

}

function postearOrdenes(matriz) {

  // variable env = 1 - envia pedido
  // variable env = 0 - no hace nada
  let env = 1;
  let url = (env == 1) ? 'activarOrden.php' : 'PedidoTest.php';
  $.ajax({
      url: 'Controller/'+url,
      method: 'POST',
      data: {
          matriz: matriz,
      },

      success: function (data) {
        Swal.fire({
          icon: 'success',
          title: 'Orden activada exitosamente!',
          text: "Numero de orden: " + data,
          showConfirmButton: true,
        })
          .then(function () {
              window.location = "activaOrdenes.php";
          });
      }

  });

}

//Revisa que se haya seleccionado alguna orden y actualiza el campo ACTIVA//

function desactivaOrdenes() {
  const matriz = matrizOrdenes();
  contar2 = $('#totalOrdenes').val();

  if (contar2 != 0) {

    postearOrdenes2(matriz);

  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error de seleccion',
      text: 'No hay ninguna orden seleccionada!'
    });
  }

}

function postearOrdenes2(matriz) {

  // variable env = 1 - envia pedido
  // variable env = 0 - no hace nada
  let env = 1;
  let url = (env == 1) ? 'desactivaOrden.php' : 'PedidoTest.php';
  $.ajax({
      url: 'Controller/'+url,
      method: 'POST',
      data: {
          matriz: matriz,
      },

      success: function (data) {
        Swal.fire({
          icon: 'success',
          title: 'Orden desactivada exitosamente!',
          text: "Numero de orden: " + data,
          showConfirmButton: true,
        })
          .then(function () {
              window.location = "desactivaOrdenes.php";
          });
      }

  });

}


//Suma el total de artículos del pedido//

function total() {
  var suma = 0;
  var x = document.querySelectorAll("#tablePed input[name='inputNum[]']");

  var i;
  for (i = 0; i < x.length; i++) {
      suma += parseInt(0+x[i].value);
  }
  document.getElementById('total').value = suma;
};

//Revisa que el pedido tenga unidades cargadas y envía el pedido//


function enviaPedido() {
  const matriz = matrizPedidos();
  console.log(orden);
  suma = $('#total').val();

  if (suma != 0) {

    postear(matriz, codClient, orden);

  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error de carga',
      text: 'No hay ningun articulo seleccionado!'
    });
  }

}

// Envía pedido //
function postear(matriz, codClient, orden) {
  // variable env = 1 - envia pedido
  // variable env = 0 - no hace nada
  let env = 1;
  let url = (env == 1) ? 'pedido.php' : 'PedidoTest.php';
  console.log(orden);
  $.ajax({
      url: 'Controller/'+url,
      method: 'POST',
      data: {
          matriz: matriz,
          codClient: codClient,
          orden: orden,
      },

      success: function (data) {
        Swal.fire({
          icon: 'success',
          title: 'Pedido cargado exitosamente!',
          text: "Numero de pedido: " + data,
          showConfirmButton: true,
        })
          .then(function () {
              window.location = "listOrdenesActivas.php";
          });
      }

  });

}

//Revisa que el pedido tenga unidades cargadas y envía el pedido//

function enviaOrden() {
  const matriz = matrizOrden();
  cont = $('#total').val();

  if (cont != 0) {

    postearOrden(matriz);

  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error de carga',
      text: 'No hay ningun articulo seleccionado!'
    });
  }

}

function postearOrden(matriz) {

  // variable env = 1 - envia pedido
  // variable env = 0 - no hace nada
  let env = 1;
  let url = (env == 1) ? 'OrdenInsert.php' : 'PedidoTest.php';
  $.ajax({
      url: 'Class/'+url,
      method: 'POST',
      data: {
          matriz: matriz,
      },

      success: function (data) {
        Swal.fire({
          icon: 'success',
          title: 'Orden cargada exitosamente!',
          text: "Numero de orden: " + data,
          showConfirmButton: true,
        })
          .then(function () {
              window.location = "index.php";
          });
      }

  });

}

//Select all checkbox//

function marcar(source) {
  var checkboxes = document.querySelectorAll("input[type='checkbox']");
  for (var i = 0; i < checkboxes.length; i++) {
      if (checkboxes[i] != source)
          checkboxes[i].checked = source.checked; 
  }
}

//Count checkbox//

function contar() {
  var checkbox = document.querySelectorAll('#tableOrden input[type="checkbox"]'); //Array que contiene los checkbox

  var cont = 0; //Variable que lleva la cuenta de los checkbox pulsados

  for (var x=0; x < checkbox.length; x++) {
   if (checkbox[x].checked) {
    cont = cont + 1;
   }
  }

  document.getElementById('total').value = cont;

 }

 //Permite seelccionar un solo checkbox//

 let Checked = null;
//The class name can vary
for (let CheckBox of document.getElementsByClassName('only-one')){
	CheckBox.onclick = function(){
  	if(Checked!=null){
      Checked.checked = false;
      Checked = CheckBox;
    }
    Checked = CheckBox;
  }
}

 //Calcula el importe total del pedido//

function precioTotal() {

      var precioTodos = 0;
      var p = document.querySelectorAll("#tablePed #precioPed"); 
      var x = document.querySelectorAll("#tablePed input[name='inputNum[]']");
      var i;

      for (i = 0; i < p.length; i++) {
          precioTodos += parseInt(0+p[i].innerHTML * x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
         }

      document.getElementById('totalPrecio').value = precioTodos;
      
  }

  function validar(e) {
   
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; //Tecla de retroceso (para poder borrar)
    if (tecla==44) return true; //Coma ( En este caso para diferenciar los decimales )
    if (tecla==48) return true;
    if (tecla==49) return true;
    if (tecla==50) return true;
    if (tecla==51) return true;
    if (tecla==52) return true;
    if (tecla==53) return true;
    if (tecla==54) return true;
    if (tecla==55) return true;
    if (tecla==56) return true;
    patron = /1/; //ver nota
    te = String.fromCharCode(tecla);
    return patron.test(te);
}