const selectPedido = document.querySelectorAll(".bi-trash-fill");
const selectPedidoActivar = document.querySelectorAll(".bi-check-circle-fill");

selectPedido.forEach((select) =>
    select.addEventListener("click", eliminarPedido)
  );

  selectPedidoActivar.forEach((select) =>
   select.addEventListener("click", activarPedido)
 );

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
          else if(z==7) {another[z] = x.firstChild.value}
          else if(z==8) {another[z] = x.firstChild.value}
          else if(z==9) {another[z] = ''}
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
  let contar2 = $('#totalOrdenes').val();

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

//Revisa que se haya seleccionado alguna orden e inserta la orden en la tabla RO_ORDENES_RECHAZADAS//

function rechazaOrdenes() {
  const matriz = matrizOrdenes();
  let contar = $('#totalOrdenes').val();

  if (contar != 0) {

    postearOrdenes3(matriz);

  } else {
    Swal.fire({
      icon: 'error',
      title: 'Error de seleccion',
      text: 'No hay ninguna orden seleccionada!'
    });
  }

}

function postearOrdenes3(matriz) {

  // variable env = 1 - envia pedido
  // variable env = 0 - no hace nada
  let env = 1;
  let url = (env == 1) ? 'rechazaOrden.php' : 'PedidoTest.php';
  $.ajax({
      url: 'Controller/'+url,
      method: 'POST',
      data: {
          matriz: matriz,
      },

      success: function (data) {
        Swal.fire({
          icon: 'success',
          title: 'Orden rechazada exitosamente!',
          text: "Numero de orden: " + data,
          showConfirmButton: true,
        })
          .then(function () {
              window.location = "listOrdenesActivas.php";
          });
      }

  });

}

function validarCredito(){  
 var importeNP = document.getElementById('totalPrecio').value;      
  if((parseInt(creditoDisp.replace(/[$.]/g, "")) < parseInt(importeNP.replace(/[$.]/g, ""))) && (parseInt(importeNP.replace(/[$.]/g, "")) > 0)){
    Swal.fire({
      icon: 'info',
      title: 'Atención',
      text: 'El crédito disponible es insuficiente! $',
    })
  }else{
      alert(creditoDisp.replace(/[$.]/g, ""))
        enviaPedido();
  
  }
}

//Suma el total de artículos del pedido//

function total(div) {

  let cant = div.value;
  let max = div.getAttribute("max");

  if(max != ""){
    
    if(cant > max){

      Swal.fire({
        icon: 'error',
        title: 'Error de carga',
        text: 'La cantidad supera el stock disponible: '+max
      });
      div.value = 0;

    }
    
  }

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

    Swal.fire({
      icon: 'info',
      title: 'Desea guardar la nota de pedido?',
      showDenyButton: true,
      showCancelButton: true,
      confirmButtonText: '<i class="fa fa-thumbs-up"></i> Guardar',
      denyButtonText: '<i class="fa fa-thumbs-down"></i> Descartar',
      cancelButtonText: '<i class="fa fa-times"></i> Cancelar',
    }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        postear(matriz, codClient, orden);
      } else if (result.isDenied) {
        Swal.fire('La nota de pedido no fue guardada', '', 'info')
      }
    })
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
          precioTodos += parseInt(0+p[i].innerHTML.replace(/[$.]/g, "") * x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
         }

      document.getElementById('totalPrecio').value = new Intl.NumberFormat("es-ar",{style: "currency", currency: "ARS", minimumFractionDigits: 0}).format(precioTodos);
      // new Intl.NumberFormat("es-ar",{style: "currency", currency: "ARS", minimumFractionDigits: 0}).format(precioTodos);
      
  }

  // Coloca separador de miles //

  function formatNumber (n) {
    n = String(n).replace(/\D/g, "");
    return n === '' ? n : Number(n).toLocaleString();
  }

  
  //************************************************** */

  /**************** establecer dafault valores 0 y/o 1 en inputNum ******************* */

let inputNum=document.querySelectorAll('#inputNum');

    inputNum.forEach((num) => {
      num.addEventListener("keyup", (num) => {
        verificarNum(num);
      });
    });
    
    function verificarNum(num) {
      if (
        num.target.parentElement.parentElement.childNodes[13].textContent.indexOf(
          "LANZAMIENTO"
        ) > -1
      ) {
          num.target.value =  num.target.value == 0 ||  num.target.value == 1 ?  num.target.value = 1:  num.target.value ;
      }else{
        num.target.value =
        num.target.value != "" ? num.target.value : (num.target.value = 0);
      }
    }


    // Calculo de Kpis por orden //

total_notaPedidos();
  function total_notaPedidos()
  {
    let tabla=table.childNodes;
    let total_pedidos = 0;
    let total_$=0;
    let cantidad=0;
    for(x=0;x<tabla.length;x++)
    {
      if((tabla[x].nodeName=='TR')&&(tabla[x].childNodes[15].textContent !=''))
      {
        total_pedidos+=1;
         total_$+= parseFloat(tabla[x].childNodes[13].textContent.slice(1).replaceAll('.', '').replace(',', '.'));
         console.log(x+': '+parseFloat(tabla[x].childNodes[13].textContent.slice(1)));
         cantidad+=parseInt(tabla[x].childNodes[15].textContent);
      }
    }
    document.getElementById('inputCliente').value=total_pedidos+' / '+table.rows.length;
    document.getElementById('input$').value=formatNumber(total_$);
     document.getElementById('inputCant').value=cantidad;
     document.getElementById('inputSolicitados').value = ((total_pedidos/table.rows.length) *100).toFixed();
    console.log(total_$);
    console.log(cantidad);
  }


  //Eliminar pedido//

function eliminarPedido(e){
  let Dato = e.target;
  let notaPedido = Dato.parentElement.parentElement.children[5].textContent;
  console.log(notaPedido)
  Swal.fire({
    title: "Desea eliminar la nota de pedido?",
    text: "Ya no se podra recuperar la informacion!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    cancelButtonText: "Cancelar",
    confirmButtonText: "Aceptar",
  }).then((result) => {
    if (result.isConfirmed) {
      let env = 1;
      let url = env == 1 ? "eliminarPedido.php" : "test.php";
      $.ajax({
        url: "controller/" + url,
        method: "POST",
        data: {
          notaPedido: notaPedido.replace(/\s+/g, ""),
        },
        success: function (data) {
          Swal.fire({
            icon: "success",
            title: "Nota de pedido eliminada correctamente!",
            text: "Nota de pedido: " + data,
            showConfirmButton: true,
          }).then(function () {
            window.history.back();
          });
        },
      });
    }
  });
}

  //Revertir rechazo de orden (activar pedido)//

  function activarPedido(e){
    let Dato = e.target;
    let codClient = Dato.parentElement.parentElement.children[1].textContent;//
    let NroOrden= document.getElementById('nroOrden').value ;
    console.log(codClient)
    console.log(NroOrden)
    Swal.fire({
      title: "Desea revertir el rechazo de la orden?",
      text: "La orden quedará nuevamente activa para el cliente!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      cancelButtonText: "Cancelar",
      confirmButtonText: "Aceptar",
    }).then((result) => {
      if (result.isConfirmed) {
        let env = 1;
        let url = env == 1 ? "anulaRechazoOrden.php" : "test.php";
        $.ajax({
          url: "controller/" + url,
          method: "POST",
          data: {
            codClient: codClient.replace(/\s+/g, ""),
            NroOrden:NroOrden
          },
          success: function (data) {
            Swal.fire({
              icon: "success",
              title: "La orden fue habilitada correctamente!",
              text: "Orden: " + data,
              showConfirmButton: true,
            }).then(function () {
              window.history.back();
            });
          },
        });
      }
    });
  }
   