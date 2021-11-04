<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alta Articulo</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">


</head>
<body>

<div class="modal fade" id="AltaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Alta de  artículo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
        <!-- <form class="col" id="formularioAlta" method="GET" action="Class/altaArticulo.php"> -->
                <div class="form-group">
                    <label>Articulo</label>
                    <input type="text" class="form-control" id="codArticulo" name="articulo" onChange="traerDescripcion()" required>
                    <small id="errorArticulo"></small>
                </div>
                <div class="form-group">
                    <label>Descripcion</label>
                    <input type="text" class="form-control" id="inputDescrip" name="descrip" readonly>
                </div>
                <div class="form-group">
                    <label>Precio estimado</label>
                    <input type="text" class="form-control" id="inputPrecio" name="precio" readonly>
                </div>
                <div class="form-group">
                    <label>Temporada</label>
                    <input type="text" class="form-control" id="inputTemp" name="temporada" readonly>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" checked>
                    <label class="form-check-label" for="exampleCheck1" value="1">Activado</label>
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