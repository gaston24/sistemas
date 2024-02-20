<?php

session_start();
if (!isset($_SESSION['username'])) {
  header("Location:../login.php");
} else {

  require_once 'Class/Orden.php';

  $codClient = $_SESSION['codClient'];


  $orden = new Orden();
  $todasLasOrdenes = $orden->traerOrdenesActivas($codClient);

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/preloader.css">
    <title>Seleccion de ordenes</title>
  </head>

  <body>

    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
      <!-- Brand -->
      <a class="navbar-brand" href="../index.php" style="color: #28a745;"><i class="fa fa-arrow-left"></i> ATRAS</a>

      <!-- Toggler/collapsibe Button -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar links -->
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" disabled><i class="fa fa-list"></i> Seleccionar ordenes</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="pedidosSucursal.php"><i class="fa fa-calendar-check-o"></i> Notas de pedido</a>
          </li>
        </ul>
      </div>
    </nav>

    <div id="contenedorList">
      <h3 class="mb-4 mt-4 ml-4" id="titleSelect"><i class="fa fa-check-square-o"></i> Selección de Ordenes</h3>

      <div class="form-row mb-3">
        <div class="mt-2" id="busqRapida">
          <label id="textBusqueda">Busqueda rapida:</label>
          <input type="text" id="textBox" placeholder="Sobre cualquier campo..." onkeyup="myFunction()" class="form-control form-control-sm"></input>
        </div>
        <div class="form-row" id="contTotales">
          <div>
            <label id="labelTotal">Ordenes seleccionadas</label>
            <input name="total" id="totalOrdenes" value="0" type="text" class="form-control" readonly>
          </div>

        </div>
        <div>
          <button type="button" class="btn btn-danger" id="btn_rechazar" onclick="rechazaOrdenes()">Rechazar <i class="fa fa-times-circle"></i></button>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableGestionOrden" style="width: 80%">
          <thead class="thead-dark">
            <th scope="col" style="width: 15%">Fecha</th>
            <th scope="col" style="width: 20%">Hora</th>
            <th scope="col" style="width: 20%">Orden</th>
            <th scope="col" style="width: 10%">Articulos</th>
            <th scope="col" style="width: 5%">Estado</th>
            <th scope="col" style="width: 2%">Seleccionar</th>
            <th scope="col" style="width: 2%"></th>
          </thead>

          <tbody id="table">

            <?php
            foreach ($todasLasOrdenes as $valor => $key) {
            ?>

              <tr>
                <td><?= $key['FECHA'] ?></td>
                <td><?= $key['HORA'] ?></td>
                <td><?= $key['NRO_ORDEN'] ?></td>
                <td><?= $key['ARTICULOS'] ?></td>
                <td id="novedadPed"><a>ACTIVA!</a></td>
                <?php 
                  if($key['LANZAMIENTO'] != 1){

                    echo '<td><input type="checkbox" name="checkTd" onclick="contarGestion()"></input></td>';

                  }else{
                      
                      echo '<td></td>';
                  }
                ?>
                
                <td>
                  <a href="notaPedido.php?orden=<?= $key['NRO_ORDEN'] ?>"><i class="fa fa-search spinner" style="color: #ffc107; font-size: 20px;"></i></a>
                  <div id="boxLoading"></div>
                </td>
              </tr>

            <?php
            }
            ?>

          </tbody>

        </table>

      </div>
    </div>

    <script src="main.js" charset="utf-8"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  </body>

  </html>

<?php
}
?>

<script>

//Spinner listOrdenesActivas.php//
var btn = document.querySelectorAll('.fa-search');
   btn.forEach(el => {
     el.addEventListener("click", ()=>{$("#boxLoading").addClass("loading")});
   })

</script>
