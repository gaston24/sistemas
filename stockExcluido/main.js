
//Búsqueda rápida table//
document.getElementById('btn_delete').addEventListener('click',matrizOrdenes);

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

  //Verifica si el artículo existe en la STA11//

function traerDescripcion() {
  $("#inputDescrip").val("");

  var codigoAbuscar = $("#codArticulo").val();

  $.ajax({
    url: "Controller/buscarCodigo.php",
    method: "POST",
    data: {
      codArticu: codigoAbuscar,
    },
    success: function (data) {
      let datos = JSON.parse(data);
      if (datos.code != 200) {
        $("#inputDescrip").val("NO EXISTE EL ARTICULO SELECCIONADO");
      } else {
        let codigo = JSON.parse(datos.msg);
        $("#inputDescrip").val(codigo[0].DESCRIPCIO);
      }
    },
  });
}

let conexion;

function insertarArticulo() {
//agregar condición
    let articulo = document.getElementById("codArticulo").value;
    let descripcion = document.getElementById("inputDescrip").value;
    let precio = document.getElementById("inputCant").value;
    let temporada = document.getElementById("inputObs").value;

    conexion = new XMLHttpRequest();
    conexion.onreadystatechange = procesarEvento;
    conexion.open("GET","Controller/insertArticulo.php?articulo="+articulo+"&descrip="+descripcion+"&cantidad="+cantidad+"&observaciones="+observaciones, true);
    conexion.send();
    
  }

  function matrizOrdenes()
    {
      let checked=document.querySelectorAll("input[type=checkbox]:checked");
      let matriz=[];
    for(let i=0;i<checked.length;i++)
    {
      matriz[i]=checked[i].parentElement.parentElement.childNodes[1].textContent;
    }
    // console.log(matriz);
    return matriz;
    }

    //Count checkbox artículos a eliminar//

    var cont = 0;

    function contar() {
      var checkbox = document.querySelectorAll('#tableIndex input[type="checkbox"]'); //Array que contiene los checkbox

      for (var x=0; x < checkbox.length; x++) {
      if (checkbox[x].checked) {
        cont = cont + 1;
      }
      }

    }

    //Revisa que se haya seleccionado alguna orden y actualiza el campo ACTIVA//

    function deleteArticulo() {
      const matriz = matrizOrdenes();
      
      if (cont != 0) {

        postear(matriz);

      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error de seleccion',
          text: 'No hay ningun artículo seleccionado!'
        });
      }

    }

    function postear(matriz) {

      // variable env = 1 - envia pedido
      // variable env = 0 - no hace nada
      let env = 1;
      let url = (env == 1) ? 'deleteArticulo.php' : 'PedidoTest.php';
      $.ajax({
          url: 'Controller/'+url,
          method: 'POST',
          data: {
              matriz: matriz,
          },
    
          success: function (data) {
            Swal.fire({
              icon: 'success',
              title: 'Articulo/s eliminado/s exitosamente!',
              // text: "Articulo: " + data,
              showConfirmButton: true,
            })
              .then(function () {
                  window.location = "index.php";
              });
          }
    
      });
    
    }

    