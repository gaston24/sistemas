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
  /* 
  
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
  
   */
  
  
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

  
// MATRIZ DE PEDIDOS (LOCALES PROPIOS Y FRANQUICIAS --- MAYORISTAS)
function matrizPedidos()  {
    const matriz = Array.from(document.getElementById("tabla").rows);
    const matriz2 = matriz.filter(x=>x.querySelector('input').value !=0);
    const matriz3 = matriz2.map( function(x) {
        let valor = x.querySelectorAll('td');
        var another = [];
        valor.forEach(function(x, z){
            another[z] = (z==0||z==2) ? '' : ( (z==8) ? x.firstChild.value : (z==9 ? another[z] = x.innerHTML.trim() : another[z] = x.innerHTML) );
        })
        return another;
    })
    return matriz3;
}

function matrizPedidosMayoristas()  {
    const matriz = Array.from(document.getElementById("tabla").rows);
    const matriz2 = matriz.filter(x=>x.querySelector('input').value !=0);
    const matriz3 = matriz2.map( function(x) {
        let valor = x.querySelectorAll('td');
        var another = [];
        valor.forEach(function(x, z){
            another[z] = (z==0||z==2) ? '' : ( (z==6) ? x.firstChild.value : (z==7 ? another[z] = x.innerHTML.trim() : another[z] = x.innerHTML) );
        })
        return another;
    })
    return matriz3;
}



// PEDIDOS LOCALES Y FRANQUICIAS
function enviar() {

    $('#btnEnviar').hide();
    $('#spinnerEnviar').show();

    let totalPrecioValida = $('#totalPrecio').val();

    if (totalPrecioValida != 'NaN') {

        const matriz = matrizPedidos();

        suma = $('#total').val();

        if (suc > 100) {
            totalPedido = $('#totalPrecio').val();
            console.log(totalPedido + " " + cupo_credito);
            var diferencia = (parseInt(cupo_credito, 10) - parseInt(totalPedido, 10)) * -1;
        }

        if (suma != 0) {

            if (suc > 100 && (parseInt(totalPedido, 10) > parseInt(cupo_credito, 10))) {
                swal.fire({
                    title: "Atencion!",
                    text: "El limite de crédito fue excedido en " + diferencia + " pesos, por favor analice quitar articulos o comuníquese con ines.sica@xl.com.ar para evaluar su situación",
                    icon: "warning",
                    button: "Aceptar",
                });
                 $('#btnEnviar').show();
                 $('#spinnerEnviar').hide();
            } else {
                $("#aguarde").show();
                $("#pantalla").fadeOut();

                const matrizStock = chequeaStock(matriz);

                // console.log("matriz", matriz);
                // console.log("matrizStock", matrizStock);
                const resultCompara = comparaStock(matriz, matrizStock);

                if(resultCompara.length > 0){
                    // console.log("hay diferencias")
                    muestraDiferencia(resultCompara)
                }else{
                    // console.log("sin diferencias")
                    postear(matriz, suc, codClient, t_ped, depo, talon_ped);
                }

            }

        } else {
            swal.fire({
                title: "Error!",
                text: "No hay ningun articulo seleccionado!",
                icon: "warning",
                button: "Aceptar",
            });
            $('#btnEnviar').show();
            $('#spinnerEnviar').hide();
        }

    } else {
        swal.fire({
            title: "Error!",
            text: "Error al cargar los articulos, solo se aceptan numeros! Revise los campos cargados por favor",
            icon: "warning",
            button: "Aceptar",
        });
    }

}

// PEDIDOS ECOMMERCE
function enviarEcommerce() {

    const matriz = matrizPedidos();

    suma = $('#total').val();

    if (suma != 0) {

        postear(matriz, suc, codClient, t_ped, depo, talon_ped);

    } else {
        swal.fire({
            title: "Error!",
            text: "No hay ningun articulo seleccionado!",
            icon: "warning",
            button: "Aceptar",
        });
        $('#btnEnviar').show();
        $('#spinnerEnviar').hide();
    }
}

// PEDIDOS MAYORISTAS
function enviarMayorista() {

    let totalPrecioValida = $('#totalPrecio').val();

    if (totalPrecioValida != 'NaN') {

        const matriz = matrizPedidosMayoristas();

        suma = $('#total').val();

        if (suma != 0) {

            $("#aguarde").show();
            $("#pantalla").fadeOut();
            postear(matriz, suc, codClient, t_ped, depo, talon_ped);
            
        } else {
            swal.fire({
                title: "Error!",
                text: "No hay ningun articulo seleccionado!",
                icon: "warning",
                button: "Aceptar",
            });
            $('#btnEnviar').show();
            $('#spinnerEnviar').hide();
        }

    } else {
        swal.fire({
            title: "Error!",
            text: "Error al cargar los articulos, solo se aceptan numeros! Revise los campos cargados por favor",
            icon: "warning",
            button: "Aceptar",
        });
    }

}

// CHEQUEA STOCK
function chequeaStock(matriz) {

    let response = 
    $.ajax({
        url: 'Controlador/chequeaStock.php',
        method: 'POST',
        data: {
            matriz: matriz,
        },
        async: false,

        success: function (data) {
            return data;
        }

    });

    return JSON.parse(response.responseText);
}

function comparaStock(matrizPedidos, matrizStock){

    const comparativo = [];
    matrizPedidos.filter((x)=>{
        matrizStock.forEach((y)=>{
            if(x[1] == y[0] && parseInt(x[8]) > parseInt(y[1]) ){
                comparativo.push([y[0], y[1]]);
            }
        });
    });

    return comparativo;
}

function muestraDiferencia(resultCompara){

    // console.log(resultCompara);

    let texto = 'Los siguientes articulos no tienen el stock solicitado: ';
    resultCompara.forEach((x)=>{
        texto += x[0]+' -- ';
    });
    texto += ' -- Los articulos seran realtados, por favor, modifique las cantidades solicitadas';

    swal.fire({
        title: "Error! El stock de central se ha modificado",
        text: texto,
        icon: "warning",
        button: "Aceptar",
    });

    marcarDiferencia(resultCompara);


}

function marcarDiferencia(resultCompara){

    const matriz = Array.from(document.getElementById("tabla").rows);
    const matriz2 = matriz.filter(x=>x.querySelector('input').value !=0);
    matriz2.forEach( function(x, r) {
        let valor = x.querySelectorAll('td');
        resultCompara.forEach((y)=>{
            valor.forEach(function(a, z){
                if(a.innerHTML == y[0]){
                    x.style.backgroundColor = '#F05858';

                    x.querySelectorAll('td').item(1).style.fontWeight = "bold";
                    x.querySelectorAll('td').item(1).style.fontSize = "medium";
                    
                    x.querySelectorAll('td').item(4).innerHTML = y[1];
                    x.querySelectorAll('td').item(4).style.fontWeight = "bold";
                    x.querySelectorAll('td').item(4).style.fontSize = "medium";

                    x.querySelectorAll('td').item(8).firstChild.value = 0;
                }
            })
        })

    })
}

// ENVIA PEDIDO
function postear(matriz, suc, codClient, t_ped, depo, talon_ped) {

    // variable env = 1 - envia pedido
    // variable env = 0 - no hace nada
    let env = 1;

    let url = (env == 1) ? 'cargarPedidoNuevo.php' : 'cargarPedidoNuevoTest.php';

    Swal.fire({
        title: 'Desea registrar el pedido?',
        icon: 'info',
        showDenyButton: true,
        // showCancelButton: true,
        confirmButtonText: 'Enviar',
        denyButtonText: `Cancelar`,
      }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            $.ajax({
                url: 'Controlador/'+url,
                method: 'POST',
                data: {
                    matriz: matriz,
                    numsuc: suc,
                    codClient: codClient,
                    tipo_pedido: t_ped,
                    depo: depo,
                    talon_ped: talon_ped
                },
                success: function (data) {
                    Swal.fire({
                        title: "Pedido cargado exitosamente!",
                        text: "Numero de pedido: " + data,
                        icon: "success",
                        button: "Aceptar",
                    })
                    .then(function () {
                        window.location = "../index.php";
                    });
                }
        
            });
        } else if (result.isDenied) {
          Swal.fire('El pedido no fue enviado', '', 'info')
          $("#aguarde").fadeOut();
          $("#pantalla").show();
          $('#btnEnviar').show();
          $('#spinnerEnviar').hide();
        }
      })

}

