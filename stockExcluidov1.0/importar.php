<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Including Font Awesome CSS from CDN to show icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="Css/style.css">
    
    <title>Importar</title>
</head>
<body>

    
<div class="modal fade" id="altaModalImport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 45%;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Importar artículos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
            <div class="w3-col w3-half">
            <div class="form-group">
                <input id="fileupload" type=file name="files[]">
            </div>
            </div>
            <div class="w3-responsive table-responsive2">
            <h3 class="w3-center"><i class="fa fa-cloud-upload text-info" aria-hidden="true"></i><strong> Artículos a importar </strong></h3>
            <table id="tblItems" class="table ">
                <thead class="thead-dark" style="font-size: small;">
                <tr>
                    <th scope="col" style="width: 2%">ARTICULO</th>
                    <th scope="col" style="width: 1%">CANTIDAD</th>
                    <th scope="col" style="width: 15%">OBSERVACIONES</th>
                    <th scope="col" style="width: 15%">DESCRIPCION</th>
                </tr>
                </thead>
                <tbody style="font-size: small;"></tbody>
            </table>
            </div>

        </div>
      <div class="modal-footer">
        <button class="btn btn-success" id='save' onclick="">Guardar</button>
        <button type="button" id='close' class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>

    
</body>

<script src="main2.js" charset="utf-8"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.0/dist/xlsx.full.min.js"></script>

</html>