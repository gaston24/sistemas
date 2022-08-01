

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



  

      //   //Captura el ID de la fila seleccionada//
      //   let length_id = 0;
		  //   let btn = document.querySelector('.btnCheck');
      //   let id = [];
      //   btn.addEventListener('click', (event) => {
      //  /*    length_id=0; */
      //     id=[];
      //       let checkboxes = document.querySelectorAll(".btnCheck:checked");
      //       checkboxes.forEach((checkbox) => {
              
      //           id.push(checkbox.value);
      //           length_id = id.length;
      //       });
      //       // return id, length_id;
      //       alert(length_id);
      //   	});


      //     function rechazarAjuste(){
      //       console.log(length_id);
      //     if(length_id == 0){ 
      //       Swal.fire({
      //         icon: 'error',
      //         title: 'Oops...',
      //         text: 'Something went wrong!',
      //         footer: '<a href="">Why do I have this issue?</a>'
      //       })
      //     } else {
      //       alert('ok');
      //     }
      //   }

        //Matriz de checkbox seleccionados//
        
        function matriz()
        {
            var checked=document.querySelectorAll("input[type=checkbox]:checked");
            var matriz=[];
          for(let i=0;i<checked.length;i++)
          {
            matriz[i]=checked[i].parentElement.parentElement.children[0].textContent;
          }
          return matriz;
        } 
        
        //Cuenta los check//  
        function contar() {
          var checkbox = document.querySelectorAll("input[type=checkbox]:checked"); //Array que contiene los checkbox
          var cont = 0; //Variable que lleva la cuenta de los checkbox pulsados
        
          for (var x=0; x < checkbox.length; x++) {
           if (checkbox[x].checked) {
            cont = cont + 1;
           }
          }
          return cont;
          //console.log(cont);
         }

         //Revisa que se haya seleccionado alguna orden y actualiza el campo ACTIVA//

        function rechazarAjuste() {
          // console.log(matriz());
          let matrize = matriz();
          let contare = contar();

          if (contare != 0) {

            Swal.fire({
              icon: 'info',
              title: 'Desea eliminar el artículo?',
              showDenyButton: true,
              showCancelButton: true,
              confirmButtonText: 'Aceptar',
              denyButtonText: 'Cancelar',
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                console.log(matrize);
                // variable env = 1 - envia pedido
                // variable env = 0 - no hace nada
                let env = 1;
                let url = (env == 1) ? 'rechazaAjuste.php' : 'PedidoTest.php';
                $.ajax({
                    url: 'Controller/'+url,
                    method: 'POST',
                    data: {
                        matriz: matrize,
                    },
                    success: function (data) {

                      Swal.fire({
                        icon: 'success',
                        title: 'Artículo aliminado correctamente!',
                        text:  console.log(data),
                        showConfirmButton: true,
                      })
                        .then(function () {
                            window.location = "ajusteLocal.php";
                        });
                    }

                });  
              } else if (result.isDenied) {
                Swal.fire('El artículo no fue eliminado', '', 'info')
              }
            })

          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error de seleccion',
              text: 'No hay ningún artículo seleccionado!'
            });
          }

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

                



  
  
    