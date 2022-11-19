$(document).ready(function(){
  $("#aguarde").fadeOut()

  actualizarDatosTabla();

});

const actualizarDatosTabla = () => {

  let datosLocal = localStorage.getItem('datosLocal');
  datosLocal = JSON.parse(datosLocal);

  let registros = document.querySelectorAll("#trPedido");

  if(datosLocal){

    registros.forEach(x=>{
      datosLocal.forEach(y=>{
        if(x.querySelector("#codArticu").innerHTML == y.COD_ARTICU){
          x.querySelector("#cantStock").innerHTML = (y.CANT_STOCK) ? y.CANT_STOCK : 0;
          x.querySelector("#cantVendida").innerHTML = (y.VENDIDO) ? y.VENDIDO : 0;
        }
      })
    })

  }else{

    registros.forEach(x=>{
      console.log(x.querySelector("#cantStock").innerHTML)
      x.querySelector("#cantStock").style.color="red";
      x.querySelector("#cantStock").style.fontWeight = "bold";
      x.querySelector("#cantVendida").style.color="red";
      x.querySelector("#cantVendida").style.fontWeight = "bold";
    })

    document.querySelector("#sinConexion").style.display = "block";

  }



}


function pulsar(e) {
  tecla = (document.all) ? e.keyCode : e.which;
  return (tecla != 13);
}
  
      
function total() {
  var suma = 0;
  var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']");

  var i;
  for (i = 0; i < x.length; i++) {
      suma += parseInt(0+x[i].value);
  }
  document.getElementById('total').value = suma;
};
  
  
function precioTotal() {


  if(suc>100){

      var precioTodos = 0;
      var p = document.querySelectorAll("#id_tabla #precio"); 
      var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']");
      var i;

      for (i = 0; i < p.length; i++) {
          precioTodos += parseInt(0+p[i].innerHTML * x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
      }

      document.getElementById('totalPrecio').value = precioTodos;

      console.log("Cupo disponible: "+cupo_credito+ " - Pedidos total: "+precioTodos);

      var diferencia = (cupo_credito - precioTodos)*-1;
      const number = diferencia;
      diferencia = number.toLocaleString().toLocaleString('en-US', { maximumFractionDigits: 2 });

      if(parseInt(precioTodos, 10) > parseInt(cupo_credito, 10)){
          document.getElementById("cupoCreditoExcedido").innerHTML = "<strong style='color: red;'>CUPO DE CREDITO EXCEDIDO EN "+diferencia+" PESOS</strong>";
      }else{
          document.getElementById("cupoCreditoExcedido").innerHTML = "";
      }
      
  }

  


};


function totalizar(){

var cantPedido = parseFloat(document.getElementById("cantPedi").value);

var precioArt =  document.getElementById("precioArt").innerHTML;
precioArt = parseFloat(precioArt.replace(".", ""));


var totalPedido = parseFloat(cantPedido * precioArt);

var diferencia = parseInt(((cupoCredi - totalPedido)*-1), 10);
const number = diferencia;
diferencia = number.toLocaleString().toLocaleString('en-US', { maximumFractionDigits: 2 });//.replaceAll(".", "|").replaceAll(".", ",").replaceAll("|", ".");
console.log(diferencia);


if( cupoCredi < totalPedido ) {
    document.getElementById("cupoCreditoExcedido").innerHTML = "<strong style='color: red;'>CUPO DE CREDITO EXCEDIDO EN "+diferencia+" PESOS</strong>";
  document.getElementById("btnAceptar").disabled = true;
  swal({
              title: "Atencion!",
              text: "El limite de crédito fue excedido en "+ diferencia +" pesos, por favor analice quitar articulos o comuníquese con ines.sica@xl.com.ar para evaluar su situación",
              icon: "warning",
              button: "Aceptar",
            });
  }else{
  document.getElementById("cupoCreditoExcedido").innerHTML = "";
  document.getElementById("btnAceptar").disabled = false;
  }




}
  
  
$('#myModal').on('shown.bs.modal', function () {
  $('#myInput').trigger('focus')
})


function busquedaRapida() {
  var input, filter, table, tr, td, td2, i, txtValue;
  input = document.getElementById('textBox');
  filter = input.value.toUpperCase();
  table = document.getElementById("tabla");
  tr = table.getElementsByTagName('tr');


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


$('#distribucion').hover(
  function(){
      $('#tool').tooltip('show');
  }, 
  function(){
      $('#tool').tooltip('hide');
  }
);


$("#btnExport").click(function() {

  $('input[type=number]').each(function(){
      this.setAttribute('value',$(this).val());
  });

  $("table").table2excel({

      // exclude CSS class
      exclude: ".noExl",
      name: "Worksheet Name",
      filename: "Pedido", //do not include extension
      fileext: ".xls", // file extension
  });
});

function verifica() {
      
  var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']");
  var y = document.querySelectorAll("#id_tabla #stock");

  
  var i;
  for (i = 0; i < x.length; i++) {
      if( parseInt(x[i].value) > parseInt(y[i].innerHTML) ){
          alert("La cantidad ingresada es mayor al stock!");
          x[i].value = 0;
      }
  }


};

const grabarPedido = document.querySelector('#btnGrabarPedido');

grabarPedido.addEventListener("click", ()=>{
  let matriz = matrizPedidos();
  localStorage.setItem("matrizPedido", JSON.stringify(matriz));
  // console.log(matriz)
  swal({
      title: "Pedido salvado",
      text: "Carga del pedido grabada en la memoria de la maquina",
      icon: "success",
      button: "Aceptar",
  })

})

const cargarPedido = document.querySelector('#btnCargarPedido');

cargarPedido.addEventListener("click", ()=>{
  let matriz = localStorage.getItem("matrizPedido");

  matriz = JSON.parse(matriz);

  const matrizTabla = Array.from(document.getElementById("tabla").rows);
  matrizTabla.forEach( function(x) {
      matriz.forEach(w=>{
          if(x.querySelectorAll('td')[1].innerHTML == w[1] ){
              x.querySelectorAll('td')[8].firstChild.value = w[8];
          }
      })
  })

})