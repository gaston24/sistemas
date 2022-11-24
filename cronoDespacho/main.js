const tipoCronograma = document.querySelector("#inputTipo")
const btnEditar=document.querySelector('.btn-warning');

document.getElementById('btn_refresh').addEventListener('click',updatefechas);

btnEditar.addEventListener('click',habilitarCheck);

const actualizarDia = (codClient, nameDia) => {

  // console.log(codClient, nameDia, tipoCronograma.value)
  
    $.ajax({
      url: './Controller/CronogramaController.php?function=actuaDia',
      method: 'POST',
      data: {
          codClient: codClient,
          nameDia: nameDia,
          tipoCronograma: tipoCronograma.value
      },
  
      success: function (data) {
        // console.log(data)   
      }
  
    });


}

function updatefechas()
{
  conexion = new XMLHttpRequest();
  conexion.open("GET", "./Class/actualizar.php?estado=1", true);
  conexion.onreadystatechange = ejecutarQuery;
  conexion.send();
}

function ejecutarQuery()
{
  if (conexion.readyState == 4) {
    // console.log(conexion.responseText);
      Swal.fire({
        icon: 'success',
        title: 'Cronograma actualizado exitosamente!',
        showConfirmButton: true,
      })
        .then(function () {
            window.location.reload();
        });
    } else {
      console.log('error');
    }
  
}


const cambioPrioridad = (codClient, prioridad) => {
  
  console.log(codClient, prioridad, tipoCronograma.value)

    $.ajax({
      url: './Controller/CronogramaController.php?function=actuaPrioridad',
      method: 'POST',
      data: {
          codClient: codClient,
          prioridad: prioridad,
          tipoCronograma: tipoCronograma.value
      },
  
      success: function (data) {
      }
  
    });

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
    sumarTotal();
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


  //habilitar checkbox

function habilitarCheck()
{
  let checks=document.querySelectorAll('.check');

  checks.forEach(check=>check.disabled=false);
}
  


  