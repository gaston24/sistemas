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

  let inputAjuste = document.querySelectorAll('.fila-base');

  inputAjuste.forEach(
    x=>{
      let td = x.querySelectorAll('td');
      if(td[6]){
        if(td[6].querySelector('select').value == 'ACEPTADO' && (td[7].querySelector('INPUT').value == '' || td[7].querySelector('INPUT').value == '0') ){
          Swal.fire({
            icon: 'error',
            title: 'Debe indicar un numero de ajuste',
            showConfirmButton: true,
          }).then(()=>{
            location.reload()
          })
        }
      }else{
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
    }
  )


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

// function validaAjuste(){
//   const matriz = Array.from(document.getElementById("id_tabla").rows);
//   matriz.forEach( function(r, ri) {
//     let valor = r.querySelectorAll('td');
//       valor.forEach(function(d, di){
//         if(di == 7 && d.firstChild.value == ''){
//           valor.item(6).firstElementChild.disabled = true
//         }
//         if(di == 7 && d.firstChild.value != ''){
//           valor.item(6).firstElementChild.disabled = false
//         }
//       })
//   })
// }

// $(document).ready(()=>{
//   if (window.location.href.indexOf('control_auditoria.php')>-1){
//     validaAjuste();
//   }
// })