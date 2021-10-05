function mostrarDatos(){
 var textEnMensaje = document.getElementById("textBox").value;
 document.getElementById("prueba").innerHTML = textEnMensaje;
 

}

function busquedaRapida() {
	var input, filter, table, tr, td, td2, i, txtValue;
	input = document.getElementById('textBox');
	filter = input.value.toUpperCase();
	table = document.getElementById("bodyTable");
	tr = table.getElementsByTagName('tr');
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

function changeStatus(remito){
  
  var status = $('#select-'+remito).val();


  $.ajax({
      url: 'controlador/actuaStatusRemito.php',
      method: 'POST',
      dateType: 'json',
      data: {
          ncomp : remito, 
          status: status 
      },
      success: function(data) {
          // console.log(data)
      }
  });
}

function changeNroAjuste(remito, context){
  console.log(remito);
  console.log(context.value)

  $.ajax({
    url: 'controlador/actuaAjusteRemito.php',
    method: 'POST',
    dateType: 'json',
    data: {
        ncomp : remito, 
        ajuste: context.value 
    },
    success: function(data) {
        // console.log(data)
    }
  });

}

function validaAjuste(){
  const matriz = Array.from(document.getElementById("id_tabla").rows);
  matriz.forEach( function(r, ri) {
    let valor = r.querySelectorAll('td');
      valor.forEach(function(d, di){
        if(di == 10 && d.firstChild.value == ''){
          valor.item(9).firstElementChild.disabled = true
        }
        if(di == 10 && d.firstChild.value != ''){
          valor.item(9).firstElementChild.disabled = false
        }
      })
  })
}

$(document).ready(()=>validaAjuste())