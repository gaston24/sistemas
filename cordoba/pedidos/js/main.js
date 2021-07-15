function total() {
    
    var suma = 0;

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_812[]']");
    var i;
    for (i = 0; i < x.length; i++) {
        suma += parseInt(0+x[i].value);
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_813[]']");
    var i;
    for (i = 0; i < x.length; i++) {
        suma += parseInt(0+x[i].value);
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_814[]']");
    var i;
    for (i = 0; i < x.length; i++) {
        suma += parseInt(0+x[i].value);
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_815[]']");
    var i;
    for (i = 0; i < x.length; i++) {
        suma += parseInt(0+x[i].value);
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_816[]']");
    var i;
    for (i = 0; i < x.length; i++) {
        suma += parseInt(0+x[i].value);
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_876[]']");
    var i;
    for (i = 0; i < x.length; i++) {
        suma += parseInt(0+x[i].value);
    }

    document.getElementById('total').value = suma;
}


function precioTotal() {

    var precioTodos = 0;
    var p = document.querySelectorAll("#id_tabla #precio"); 

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_812[]']");
    var i;
    for (i = 0; i < p.length; i++) {
        precioTodos += parseInt(0+p[i].innerHTML * x[i].value); 
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_813[]']");
    var i;
    for (i = 0; i < p.length; i++) {
        precioTodos += parseInt(0+p[i].innerHTML * x[i].value); 
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_814[]']");
    var i;
    for (i = 0; i < p.length; i++) {
        precioTodos += parseInt(0+p[i].innerHTML * x[i].value); 
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_815[]']");
    var i;
    for (i = 0; i < p.length; i++) {
        precioTodos += parseInt(0+p[i].innerHTML * x[i].value); 
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_816[]']");
    var i;
    for (i = 0; i < p.length; i++) {
        precioTodos += parseInt(0+p[i].innerHTML * x[i].value); 
    }

    var x = document.querySelectorAll("#id_tabla input[name='cantPed_876[]']");
    var i;
    for (i = 0; i < p.length; i++) {
        precioTodos += parseInt(0+p[i].innerHTML * x[i].value); 
    }

    document.getElementById('totalPrecio').value = precioTodos;

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
    tecla = (document.all) ? e.keyCode : e.which;
    return (tecla != 13);
}