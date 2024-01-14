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

 //Quitar el evento submit de la tecla enter//

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
      if(e.keyCode == 13) {
        e.preventDefault();
      }
    }))
  });

const mostrarSpinner = () => {
  let spinner = document.querySelector('.boxLoading')
	spinner.classList.add('loading')
}


const filtrar = () => {
  mostrarSpinner();
  let rubro = document.querySelector('#inputRubro').value
  let temporada = document.querySelector('#inputTemp').value

  if(rubro == ''){
    rubro = '%'
  }

  if(temporada == ''){
    temporada = '%'
  }


  $.ajax({
    url: 'Controller/maestroDestinosController.php?accion=filtrar',
    method: 'POST',
    data: {
      rubro: rubro,
      temporada: temporada
    },
    success: function (response) {
      data = JSON.parse(response)


      var tableBody = document.querySelector("#tableBody");

      $('#tableMaestro').DataTable().destroy();

      tableBody.innerHTML = ''; 
  

      

      data.forEach(function (v) {


          var row = document.createElement("tr");

        
           // Crear celda para la imagen
          var imgCell = document.createElement("td");
          imgCell.classList.add("imagen");

          // Crear enlace 'a'
          var imgLink = document.createElement("a");
          imgLink.setAttribute("target", "_blank");
          imgLink.setAttribute("data-toggle", "modal");
          imgLink.setAttribute("data-target", "#exampleModal" + v["COD_ARTICU"]);
          imgLink.setAttribute("href", "../../../Imagenes/" + v["COD_ARTICU"].substring(0, 13) + ".jpg");

          // Crear imagen 'img'
          var imgElement = document.createElement("img");
          imgElement.setAttribute("src", "../../../Imagenes/" + v["COD_ARTICU"].substring(0, 13) + ".jpg");
          imgElement.setAttribute("alt", "Sin imagen");
          imgElement.setAttribute("height", "50");
          imgElement.setAttribute("width", "50");

          // Agregar la imagen al enlace
          imgLink.appendChild(imgElement);

          // Agregar el enlace a la celda de la imagen
          imgCell.appendChild(imgLink);

          // Agregar la celda de la imagen a la fila
          row.appendChild(imgCell);


          var codArticuCell = document.createElement("td");
          codArticuCell.textContent = v["COD_ARTICU"];
          row.appendChild(codArticuCell);

          var descripcionCell = document.createElement("td");
          descripcionCell.textContent = v["DESCRIPCION"];
          row.appendChild(descripcionCell);

          var destinoCell = document.createElement("td");
          destinoCell.textContent = v["DESTINO"];
          row.appendChild(destinoCell);

          var temporadaCell = document.createElement("td");
          temporadaCell.textContent = v["TEMPORADA"];
          row.appendChild(temporadaCell);

          var rubroCell = document.createElement("td");
          rubroCell.textContent = v["RUBRO"];
          row.appendChild(rubroCell);

          tableBody.appendChild(row);
      });

      activarDatatable();
    
      let spinner = document.querySelector('.boxLoading')
      spinner.classList.remove('loading')
    }
  })

}

const activarDatatable = () => {
  $('#tableMaestro').DataTable({
    "bLengthChange": true,
    "lengthMenu": [ [100], [100] ],
    "language": {
                "lengthMenu": "mostrar _MENU_ registros",
                "info":           "Mostrando registros del _START_ al _END_ de un total de  _TOTAL_ registros",
                "paginate": {
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },

    },

    
    "bInfo": false,
    "aaSorting": false,
    'columnDefs': [
        {
            "targets": "_all", 
            "className": "text-center",
            "sortable": false,
    
        },
    ],
    "oLanguage": {

        "sSearch": "Busqueda rapida:",
        "sSearchPlaceholder" : "Sobre cualquier campo"
        

    },
  });
}