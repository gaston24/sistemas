
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


  const boton = document.getElementById('btnActive');
  let checks = document.querySelectorAll('.valores');

  boton.addEventListener('click', function(){
      checks.forEach((e)=>{
        if(e.checked == true && e.disabled != true) {
          postearCrono(e.value);
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Seleccione otro cronograma!',
            showConfirmButton: true,
            footer: '<a>El cronograma ya se encuentra activo!</a>'
          })
        }
      });
    });    
    
    //Activar cronograma//

    function postearCrono(check) {
    
      // variable env = 1 - envia pedido
      // variable env = 0 - no hace nada
      let env = 1;
      let url = (env == 1) ? 'activaCronograma.php' : '';
      $.ajax({
          url: 'Controller/'+url,
          method: 'POST',
          data: {
              check: check,
          },
    
          success: function (data) {
            Swal.fire({
              icon: 'success',
              title: 'Cronograma activado exitosamente!',
              text: "Cronograma: " + data,
              showConfirmButton: true,
            })
              .then(function () {
                  window.location = "index.php";
              });
          }
    
      });
    }
    document.getElementById('btn_filtro').addEventListener('click',verificaCronoSeleccionado);

    function verificaCronoSeleccionado(event) {
      if(document.getElementById('inputTipo').value == '')
      {
        event.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'No hay ningun cronograma seleccionado!',
          showConfirmButton: true,
        })
      }
    }

  