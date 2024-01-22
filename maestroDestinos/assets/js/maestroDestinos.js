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
      url: 'Controller/maestroDestinosController.php?accion=filtrarDestinos',
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
  
            var codArticuCell = document.createElement("td");
            codArticuCell.textContent = v["COD_ARTICU"];
            row.appendChild(codArticuCell);

            var sinonimoCell = document.createElement("td");
            sinonimoCell.textContent = v["SINONIMO"];
            row.appendChild(sinonimoCell);
  
            var descripcionCell = document.createElement("td");
            descripcionCell.textContent = v["DESCRIPCION"];
            row.appendChild(descripcionCell);
  
            var destinoCell = document.createElement("td");
            var selectDestino = document.createElement("select");
            selectDestino.classList.add("form-control");
            selectDestino.setAttribute("name", "destino");

            // Iterar sobre las opciones posibles (por ejemplo, "continuo" y "discontinuo")
            ["CONTINUO", "DISCONTINUO"].forEach(function (optionValue) {
                var option = document.createElement("option");
                option.value = optionValue;
                option.textContent = optionValue;
                
                // Establecer como seleccionado si coincide con el valor de la base de datos
                if (optionValue === v["DESTINO"]) {
                    option.selected = true;
                }

                selectDestino.appendChild(option);
            });
            selectDestino.style.textAlign = "center";
            selectDestino.setAttribute("onchange", "cambiarDestino(this)")
            destinoCell.appendChild(selectDestino);
            row.appendChild(destinoCell);
  
            var temporadaCell = document.createElement("td");
            temporadaCell.textContent = v["TEMPORADA"];
            row.appendChild(temporadaCell);
  
            var rubroCell = document.createElement("td");
            rubroCell.textContent = v["FAMILIA"];
            row.appendChild(rubroCell);

            var liquidacionoCell = document.createElement("td");
            var liquidacionDestino = document.createElement("select");
            liquidacionDestino.classList.add("form-control");
            liquidacionDestino.setAttribute("name", "destino");

            // Iterar sobre las opciones posibles (por ejemplo, "continuo" y "discontinuo")
            ["SI", "NO"].forEach(function (optionValue) {
                var option = document.createElement("option");
                option.value = optionValue;
                option.textContent = optionValue;
                
                // Establecer como seleccionado si coincide con el valor de la base de datos
                if (optionValue === v["LIQUIDACION"]) {
                    option.selected = true;
                }

                liquidacionDestino.appendChild(option);
            });
            liquidacionDestino.style.textAlign = "center";
            liquidacionDestino.setAttribute("onchange", "cambiarLiquidacion(this)")
            liquidacionoCell.appendChild(liquidacionDestino);
            row.appendChild(liquidacionoCell);

            var ultimaModCell = document.createElement("td");
            if(v["FECHA_MOD"] == null){

              ultimaModCell.textContent = "";
              
            }else{

              var fechaParseada = new Date(v["FECHA_MOD"].date);
              var año = fechaParseada.getFullYear();
              var mes = ('0' + (fechaParseada.getMonth() + 1)).slice(-2);  // Agregar 1 ya que los meses van de 0 a 11
              var dia = ('0' + fechaParseada.getDate()).slice(-2);
              var fechaFormateada = año + '-' + mes + '-' + dia;
              ultimaModCell.textContent = fechaFormateada;
            }
            row.appendChild(ultimaModCell);
  
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

  
const cambiarEntorno = (t) =>{
    let spinner = document.querySelector("#boxLoading");
  
    spinner.classList.add("loading");
  console.log(t.checked)
  return 1
    if(t.checked == false){
  
      document.querySelector("#btn_active").setAttribute("data-target","#modalActiveUruguay")
  
      document.querySelector("#btn_edit").setAttribute("data-target","#modalParametersUy")
  
      $.ajax({
        url: "Controller/stockDeSeguridadController.php?accion=cambiarEntornoUy",
        method: "GET",
        success: function (data) {
          data = JSON.parse(data);
          let tabla = document.getElementById('table');
          tabla.innerHTML = '';
  
          data.forEach((row) => {
            tabla.innerHTML += `
            <tr>
            <td style="display:none;">${row['ID']}</td>
            <td style="display:none;">${row['WAREHOUSE_ID']}</td>
            <td>${row['VTEX_CUENTA']}</td>
            <td>${row['DESC_SUCURSAL']}</td>
            <td><input type="number" class="inputNumber" name="BILLETERAS_DE_CUERO" value="${row['BILLETERAS_DE_CUERO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="BILLETERAS_DE_VINILICO" value="${row['BILLETERAS_DE_VINILICO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CALZADOS" value="${row['CALZADOS']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CAMPERAS" value="${row['CAMPERAS']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CARTERAS_DE_CUERO" value="${row['CARTERAS_DE_CUERO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CARTERAS_DE_VINILICO" value="${row['CARTERAS_DE_VINILICO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CHALINAS" value="${row['CHALINAS']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CINTOS_DE_CUERO" value="${row['CINTOS_DE_CUERO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CINTOS_DE_VINILICO" value="${row['CINTOS_DE_VINILICO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="INDUMENTARIA" value="${row['INDUMENTARIA']}" disabled></td>
            <td><input type="number" class="inputNumber" name="LENTES" value="${row['LENTES']}" disabled></td>
            <td><input type="number" class="inputNumber" name="RELOJES" value="${row['RELOJES']}" disabled></td>
            </tr>
            `;
          });
          spinner.classList.remove("loading")
        }
  
  
      });
    }else{
  
      document.querySelector("#btn_active").setAttribute("data-target","#modalActive")
  
      $.ajax({
        url: "Controller/stockDeSeguridadController.php?accion=cambiarEntornoArg",
        method: "GET",
        success: function (data) {
          data = JSON.parse(data);
          let tabla = document.getElementById('table');
          tabla.innerHTML = '';
  
          data.forEach((row) => {
            tabla.innerHTML += `
            <tr>
            <td style="display:none;">${row['ID']}</td>
            <td style="display:none;">${row['WAREHOUSE_ID']}</td>
            <td>${row['VTEX_CUENTA']}</td>
            <td>${row['DESC_SUCURSAL']}</td>
            <td><input type="number" class="inputNumber" name="BILLETERAS_DE_CUERO" value="${row['BILLETERAS_DE_CUERO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="BILLETERAS_DE_VINILICO" value="${row['BILLETERAS_DE_VINILICO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CALZADOS" value="${row['CALZADOS']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CAMPERAS" value="${row['CAMPERAS']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CARTERAS_DE_CUERO" value="${row['CARTERAS_DE_CUERO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CARTERAS_DE_VINILICO" value="${row['CARTERAS_DE_VINILICO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CHALINAS" value="${row['CHALINAS']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CINTOS_DE_CUERO" value="${row['CINTOS_DE_CUERO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="CINTOS_DE_VINILICO" value="${row['CINTOS_DE_VINILICO']}" disabled></td>
            <td><input type="number" class="inputNumber" name="INDUMENTARIA" value="${row['INDUMENTARIA']}" disabled></td>
            <td><input type="number" class="inputNumber" name="LENTES" value="${row['LENTES']}" disabled></td>
            <td><input type="number" class="inputNumber" name="RELOJES" value="${row['RELOJES']}" disabled></td>
            </tr>
            `;
          });
          spinner.classList.remove("loading")
        }
  
  
      });
  
    }
  }
  

  const liquidar = () => {
    Swal.fire({
      icon:"question",
      title: "Desea dar por finalizada la liquidación?",
      showDenyButton: true,
      confirmButtonText: "Confirmar",
      denyButtonText: "Cancelar"
      }).then((result) => {
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
    
        let table = document.querySelector("#tableBody").querySelectorAll("tr");
        let liquidacionSiString = "("
        let liquidacionNoString = "("

        table.forEach((tr) => {

          if(tr.querySelectorAll("td")[6].querySelector("select").value == "SI"){
            
            liquidacionSiString += "'" + tr.querySelectorAll("td")[0].textContent + "',"

          }else{

            liquidacionNoString += "'" + tr.querySelectorAll("td")[0].textContent + "',"

          }
        
        });
        liquidacionSiString = liquidacionSiString.slice(0,-1)
        liquidacionSiString += ")"
        liquidacionNoString = liquidacionNoString.slice(0,-1)
        liquidacionNoString += ")"

        $.ajax ({
          url: "Controller/maestroDestinosController.php?accion=liquidar",
          method: "POST",
          data: {
            liquidacionSiString: liquidacionSiString,
            liquidacionNoString: liquidacionNoString
          },
          success: function (response) {
            console.log(response)
              Swal.fire("Liquidación finalizada!", "", "success");
          }
        
        })
      // console.log(table)
      } else if (result.isDenied) {
      Swal.fire("No se realizaron cambios", "", "info");
      }
      });
  }

  const cambiarDestino = (t) => {

    let codArticu = t.parentElement.parentElement.querySelectorAll("td")[0].textContent
    let destino = t.value
    $.ajax ({
      url: "Controller/maestroDestinosController.php?accion=cambiarDestino",
      method: "POST",
      data: {
        codArticu: codArticu,
        destino: destino
      },
      success: function (response) {
        console.log(response)
      }
    
    })
  }

  const cambiarLiquidacion = (t) => {
      
      let codArticu = t.parentElement.parentElement.querySelectorAll("td")[0].textContent
      let liquidacion = t.value
      $.ajax ({
        url: "Controller/maestroDestinosController.php?accion=cambiarLiquidacion",
        method: "POST",
        data: {
          codArticu: codArticu,
          liquidacion: liquidacion
        },
        success: function (response) {
          console.log(response)
        }
      
      })

  }

  const mostrarModalImport = () => {
    resetearImport();
    $("#modalCA").modal("toggle");
  }

  const resetearImport = () => {

    document.querySelector("#modalCA").innerHTML = `
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="modalDoc">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel"><span id="titleModal"><i class="bi bi-file-earmark-excel" aria-hidden="true" style="font-size: 25px;"></i>Importar archivo excel </h4></span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> 
            </div>
            <div class="modal-body" id="modalImportBody">
                <div class="mb-3">
                        <div class="input-group">
                                <input type="text" class="form-control" id="nombreInput" placeholder="Selecciona un archivo" readonly>
                                <label class="input-group-text" id="Examinar" for="formFile" style="width: 50%; background-color: #044f8e; color: white; cursor: pointer;">
                                    <i class="bi bi-folder2-open" style="margin-right:5px;margin-left:30%"></i> Examinar
                                    <input class="form-control" type="file" id="formFile" style="position: absolute; clip: rect(0, 0, 0, 0); width: 1px; height: 1px; margin: -1px; padding: 0; overflow: hidden; border: 0;" onchange="displayFileName(this)">
                                </label>
                          
                        </div>
                </div>


            </div>
                
            <div class="modal-footer">
                
                <button type="button" class="btn btn-success" data-dismiss="modal">Aceptar</button>
            </div>
        </div>
    </div>
    `

            
  }
  const mostrarModalTemp = () =>{
    $("#modalTemporadas").modal("toggle")
}