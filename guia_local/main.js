
$('#tablaFactura').DataTable({
	searching: false, 
	pageLength: 50,  
})

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})

const mostrarSpinner = () => {
	let spinner = document.querySelector('.boxLoading')
	spinner.classList.add('loading')

}

//Busqueda rápida//

function myFunction() {
	var input, filter, table, tr, td, td2, i, txtValue;
	input = document.getElementById('textBox');
	filter = input.value.toUpperCase();
	table = document.getElementById("table");
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

//Registrar la orden como entregada//

function registrarOrden(e){
  
  var orden = e.getAttribute('value');
  Swal.fire({
  title: "Desea registrar la orden"+orden+"?",
  text: "La orden quedará registrada como entregada!",
  icon: "info",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  cancelButtonText: "Cancelar",
  confirmButtonText: "Aceptar",
  }).then((result) => {
  if (result.isConfirmed) {
      let env = 1;
      let url = env == 1 ? "registrarOrden.php" : "test.php";
      console.log(orden);
      $.ajax({
      url: 'controller/' + url,
      method: "POST",
      data: {
          orden: orden.replace(/ /g, ""),
      },
      success: function (data) {
          Swal.fire({
          icon: "success",
          title: "Orden registrada correctamente!",
          text: "Orden: " + data,
          showConfirmButton: true,
          }).then(function () {
          window.location = "index.php";
                  });
              },
          });
      }
  });
    
}