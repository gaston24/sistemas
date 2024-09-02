
console.log("envio.js");

// MATRIZ DE PEDIDOS (LOCALES PROPIOS Y FRANQUICIAS --- MAYORISTAS)
function matrizPedidos()  {
    const matriz = Array.from(document.getElementById("tabla").rows);
    const matriz2 = matriz.filter(x=>x.querySelector('input').value != 0);
    const matriz3 = matriz2.map( function(x) {
        let valor = x.querySelectorAll('td');
        var another = [];
        valor.forEach(function(x, z){
            another[z] = (z==0||z==2) ? '' : ( (z==8) ? x.querySelector("input").value : (z==9 ? another[z] = x.innerHTML.trim() : another[z] = x.innerHTML) );
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

    if (totalPrecioValida !== 'NaN') {
        const matriz = matrizPedidos();
        const suma = parseInt($('#total').val(), 10);

        let diferencia = 0;
        if (suc > 100) {
            const totalPedido = parseInt($('#totalPrecio').val().replace(/[^\d.-]/g, ''), 10);
            console.log(totalPedido + " " + cupo_credito);
            diferencia = Math.max(totalPedido - parseInt(cupo_credito, 10), 0);
        }

        if (suma !== 0) {
            const formattedDiferencia = new Intl.NumberFormat('es-AR', { 
                style: 'currency', 
                currency: 'ARS',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(diferencia);

            if (suc > 100 && diferencia > 0) {
                Swal.fire({
                    title: "Atención!",
                    text: `El límite de crédito fue excedido en ${formattedDiferencia}, por favor analice quitar artículos o comuníquese con mariela.gueler@xl.com.ar para evaluar su situación`,
                    icon: "warning",
                    confirmButtonText: "Aceptar",
                });
                $('#btnEnviar').show();
                $('#spinnerEnviar').hide();
            } else {
                $("#aguarde").show();
                $("#pantalla").fadeOut();

                const matrizStock = chequeaStock(matriz);
                const resultCompara = comparaStock(matriz, matrizStock);

                if(resultCompara.length > 0){
                    muestraDiferencia(resultCompara);
                } else {
                    postear(matriz, suc, codClient, t_ped, depo, talon_ped);
                }
            }
        } else {
            Swal.fire({
                title: "Error!",
                text: "No hay ningún artículo seleccionado!",
                icon: "warning",
                confirmButtonText: "Aceptar",
            });
            $('#btnEnviar').show();
            $('#spinnerEnviar').hide();
        }
    } else {
        Swal.fire({
            title: "Error!",
            text: "Error al cargar los artículos, solo se aceptan números! Revise los campos cargados por favor",
            icon: "warning",
            confirmButtonText: "Aceptar",
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
    texto += ' -- Los articulos seran resaltados, por favor, modifique las cantidades solicitadas';

    swal.fire({
        title: "Error! El stock de central se ha modificado",
        text: texto,
        icon: "warning",
        button: "Aceptar",
    }).then(() => {
        $("#aguarde").fadeOut();
        $("#pantalla").show();
        $('#btnEnviar').show();
        $('#spinnerEnviar').hide();
    })

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

