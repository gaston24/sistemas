<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coeficiente De Ajuste</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <!-- <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg"> -->
  <!-- <link rel="stylesheet" href="css/style.css"> -->

<style>

</style>
</head>

<body>

    <div class="modal fade" id="modalCA" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js" integrity="sha512-TPh2Oxlg1zp+kz3nFA0C5vVC6leG/6mm1z9+mA81MI5eaUVqasPLO8Cuk4gMF4gUfP5etR73rgU/8PNMsSesoQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

</body>    
<script>
         const displayFileName = (input) => {
            var fileName = input.files[0].name;
            input.parentNode.parentNode.querySelector('input[type="text"]').value = fileName;

            document.querySelector('#Examinar').hidden = true;

            document.querySelector(".modal-footer").innerHTML = `
                <button type="button" class="btn btn-danger" onclick="clearFileInput()">Borrar</button>
                <button type="button" class="btn btn-success" onclick="readExcelFile()">Subir<i class="bi bi-box-arrow-up"></i></button>
            `;
        }
        const readExcelFile = () => {
            let input = document.querySelector("#formFile") 
            var file = input.files[0];
            var reader = new FileReader();
     
            reader.onload = function (e) {
             
                var data = new Uint8Array(e.target.result);
                var workbook = XLSX.read(data, { type: 'array' });

                // Asumimos que solo hay una hoja en el libro
                var sheet = workbook.Sheets[workbook.SheetNames[0]];
                document.querySelector('#modalDoc').style.width = "600px";
                document.querySelector('#titleModal').innerHTML = "<i class='bi bi-arrow-repeat' aria-hidden='true' style='font-size: 25px;'></i>Actualizar maestro de destinos";

                // Convertir la hoja de cálculo a un arreglo de objetos
                var jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1 });
                var tableHtml = `<table class="table table-hover table-condensed table-striped text-center" id="tableImportExcel">
                <thead class="thead-dark">
                    <tr>
                        <th>Articulo</th>
                        <th>Descripcion</th>
                        <th>Destino</th>
                        <th>Liquidacion</th>
                    </tr>
                </thead>
                <tbody>`;


                // Ahora jsonData contiene los datos del archivo Excel
                jsonData.forEach((element,x) => {
                    if(x > 0){
                        tableHtml += `
                        <tr>
                            <td>${element[0]}</td>
                            <td>${element[1]}</td>
                            <td>${element[2]}</td>
                            <td>${element[3]}</td>
                        </tr>`;
                    }
                    
                });
                tableHtml += `</tbody></table>`;
                document.querySelector('#modalImportBody').innerHTML = tableHtml;
                document.querySelector(".modal-footer").innerHTML = `
                <button type="button" class="btn btn-success" onclick="actualizar()">Actualizar <i class='bi bi-arrow-repeat'></i></button>
            `;

               
            };

            reader.readAsArrayBuffer(file);
         
          
        }
        function clearFileInput() {
            var fileInput = document.querySelector('#formFile');
            fileInput.value = ''; // Esto limpia el valor del input de archivo
            document.querySelector('#Examinar').hidden = false;
            document.querySelector('#nombreInput').value = ""
        }
        const actualizar = () => {
          
        }
    </script>
