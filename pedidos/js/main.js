$(document).ready(function(){
    $("#aguarde").fadeOut()
  });

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
    
    
$('#myModal').on('shown.bs.modal', function () {
    $('#myInput').trigger('focus')
})


function myFunction() {
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



function precioTotal() {
    var precioTodos = 0;
    var p = document.querySelectorAll("#id_tabla #precio"); 
    var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']");
    var i;

    for (i = 0; i < p.length; i++) {
        precioTodos += parseInt(0+p[i].innerHTML * x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
    }

    document.getElementById('totalPrecio').value = precioTodos;

    // console.log("cupo: "+cupo_credito+ " ejecutado: "+precioTodos);

    var diferencia = (cupo_credito - precioTodos)*-1;

    if(parseInt(precioTodos, 10) > parseInt(cupo_credito, 10)){
        document.getElementById("cupoCreditoExcedido").innerHTML = "<strong style='color: red;'>CUPO DE CREDITO EXCEDIDO EN "+diferencia+" PESOS</strong>";
    }else{
        document.getElementById("cupoCreditoExcedido").innerHTML = "";
    }


};


$('#distribucion').hover(
    function(){
        $('#tool').tooltip('show');
    }, 
    function(){
        $('#tool').tooltip('hide');
    }
);