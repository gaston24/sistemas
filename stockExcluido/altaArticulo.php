<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar cantidad excluida</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">


</head>
<body>

<div class="modal fade" id="altaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel"><i class="fa fa-edit" aria-hidden="true" style="font-size: 25px;"></i> Editar cantidad excluida</h4>
        </button>
      </div>
      <div class="modal-body">
      
        <!-- <form class="col" id="formularioAlta" method="GET" action="Class/altaArticulo.php"> -->
                <div class="form-group">
                    <label>Articulo</label>
                    <select class="form-control" id="codArticulo" name="articulo" onChange="traerDescripcion()" required>
                      <option selected disabled></option>
                          <?php
                          foreach ($todosLosArticulos as $valor => $value) {
                          ?>
                              <option value="<?= $value->COD_ARTICU; ?>"><?= $value->COD_ARTICU.' - '.$value->DESCRIPCION.' - '.$value->CANT.' Unid.'; ?></option>
                          <?php
                          }
                          ?>
                    </select>
                    <!-- <input type="text" class="form-control" id="codArticulo" name="articulo" tabindex="1" onChange="traerDescripcion()" required> -->
                    <!-- <small id="errorArticulo"></small> -->
                </div>
                <div class="form-group">
                    <label>Descripcion</label>
                    <input type="text" class="form-control" id="inputDescrip" name="descrip" readonly>
                </div>
                <div class="form-group">
                    <label>Cantidad</label>
                    <input type="text" class="form-control" id="inputCant" name="cantidad">
                </div>
                <div class="form-group">
                    <label>Observaciones</label>
                    <input type="text" class="form-control" id="inputObs" name="observaciones">
                </div>
        </div>
      <div class="modal-footer">
        <button class="btn btn-primary" onclick="insertarArticulo()">Ingresar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>



</body>
</html>