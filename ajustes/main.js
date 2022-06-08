
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

  //Cuenta la cantidad de artículos a ajustar//

   function contar(){
    let elems = document.querySelectorAll("option:checked");
    let cont = 0;
        for(var k=0;k<elems.length;k++){
            if(elems[k].textContent.length > 0){ cont++; }
        }
        console.log(cont);
        document.getElementById('total').value = cont;
    }
  
    
    // $("form").submit(function(event) {
    //   if (cont == 0) {
    //     Swal.fire({
    //       icon: 'error',
    //       title: 'Error de carga',
    //       text: 'Debe ingresar al menos un artículo!',
    //       showConfirmButton: true,
    //       })
    //         .then(function () {
    //         });
    //         event.preventDefault();
    //       };
    //     })


    function procesar(event){

      cont = $('#total').val();

      if (cont == 0) {
        Swal.fire({
          icon: 'error',
          title: 'Error de carga',
          text: 'Debe ingresar al menos un artículo!',
          showConfirmButton: true,
          })
          .then(function () {
            window.location = "ajusteLocal.php";
            });
      };
    }



  $(document).ready(function(){
    let largo = $( ".ctr-busc" ).length;
    for(let x=1; x<=largo;x++){
      $('#controlBuscador'+x).select2({
        minimumInputLength: 5,
        selectOnClose: true,
      });
    }
  });


  
  
    