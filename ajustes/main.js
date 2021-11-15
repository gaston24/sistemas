
function ocultarBoton () {
    var btn = document.getElementById('btn_ajustar');
    
    btn.style.display = 'none';
}

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

//   $('#btn_ajustar').on('click', function(){

//     Swal.fire({
//         icon: 'success',
//         title: 'Ajuste realizado con Exito!',
//         showConfirmButton: true,
//         timer: 3000
//     })

//   })

  //Cuenta la cantidad de artículos a ajustar//

   function contar(){
    let elems = document.getElementsByName("nuevo[]");
    let cont = 0;
        for(var k=0;k<elems.length;k++){
            if(elems[k].value.length > 0){ cont++; }
        }

        document.getElementById('total').value = cont;
    }


    function procesar() {
    
    cont = $('#total').val();
  
    if (cont != 0) {
  
        Swal.fire({
            icon: 'success',
            title: 'Ajuste realizado exitosamente!',
            showConfirmButton: true,
          })
            .then(function () {
                window.location = "ajusteLocal.php";
            });
  
    } else {
      Swal.fire({
        icon: 'error',
        title: 'Error de carga',
        text: 'Debe ingresar al menos un artículo!',
        showConfirmButton: true,
      })
      .then(function () {
        window.location = "ajusteLocal.php";
    });

    }
  
  }