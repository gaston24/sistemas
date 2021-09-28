
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

// PEDIDOS LOCALES Y FRANQUICIAS
function enviar() {

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
                swal({
                    title: "Atencion!",
                    text: "El limite de crédito fue excedido en " + diferencia + " pesos, por favor analice quitar articulos o comuníquese con ines.sica@xl.com.ar para evaluar su situación",
                    icon: "warning",
                    button: "Aceptar",
                });
            } else {
                // $("#aguarde").show();
                // $("#pantalla").fadeOut();

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
            swal({
                title: "Error!",
                text: "No hay ningun articulo seleccionado!",
                icon: "warning",
                button: "Aceptar",
            });
        }




    } else {
        swal({
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
        swal({
            title: "Error!",
            text: "No hay ningun articulo seleccionado!",
            icon: "warning",
            button: "Aceptar",
        });
    }


}

// PEDIDOS MAYORISTAS
function enviarMayorista() {

    let totalPrecioValida = $('#totalPrecio').val();

    if (totalPrecioValida != 'NaN') {

        const matriz = matrizPedidos();

        suma = $('#total').val();

        if (suma != 0) {
            $("#aguarde").show();
            $("#pantalla").fadeOut();
            postear(matriz, suc, codClient, t_ped, depo, talon_ped);

        } else {
            swal({
                title: "Error!",
                text: "No hay ningun articulo seleccionado!",
                icon: "warning",
                button: "Aceptar",
            });
        }

    } else {
        swal({
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
            if(x[1] == y[0] && x[8] > y[1] ){
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

    swal({
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

    $.ajax({
        url: 'Controlador/cargarPedidoNuevo.php',
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
            swal({
                title: "Pedido cargado exitosamente!",
                text: "Numero de pedido: " + data,
                icon: "success",
                button: "Aceptar",
            })
                .then(function () {
                    window.location = "../index.php";
                })
                ;

        }

    });

}

