<?php

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

require_once 'Class/Pedido.php';

$notaPedido = $_GET['notaPedido'];

$pedido = new Pedido();
$todosLosPedidos = $pedido->traerDetallePedido($notaPedido);

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
    <title>Detalle nota de pedido</title>
</head>
<body>

    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="../index.php">Volver</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="listOrdenesActivas.php">Seleccionar ordenes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="pedidosSucursal.php">Notas de pedido</a>
      </li>
    </ul> 
  </div>
</nav>

<div id="contenedorList">
    <h3 class="mb-4 mt-4 ml-4" id="titleSelect"><i class="fa fa-check-square-o"></i>  Detalle nota de pedido: <?php echo $notaPedido ?></h3>

            <!-- <div class="ml-2">   
                <a type="button" class="btn btn-primary ml-4 mb-4" id="btn_back2" href="../index.php"><i class="fa fa-arrow-left"></i>  Volver</a>
            </div> -->

    <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableGestionOrden" style="width: 80%">
            <thead class="thead-dark">
                <th scope="col" style="width: 10%">Fecha</th>
                <th scope="col" style="width: 10%">Hora</th>
                <th scope="col" style="width: 15%">Orden</th>
                <th scope="col" style="width: 15%">Articulo</th>
                <th scope="col" style="width: 15%">Descripcion</th>
                <th scope="col" style="width: 15%">Rubro</th>
                <th scope="col" style="width: 8%">Precio</th>   
                <th scope="col" style="width: 8%">Cantidad</th> 
            </thead>

            <tbody id="table">

                <?php
                foreach($todosLosPedidos as $valor => $key){
                ?>

                <tr>
                    <td><?=  $newDate = $key['FECHA']->format('Y-m-d') ?></td>
                    <td><?=  $key['HORA']?></td>
                    <td><?=  $key['NRO_ORDEN'] ?></td>
                    <td><?=  $key['COD_ARTICU'] ?></td>
                    <td><?=  $key['DESCRIPCIO'] ?></td>
                    <td><?=  $key['RUBRO'] ?></td>
                    <td><?=  "$".number_format($key['PRECIO_ESTIMADO'], 0, ".",",")?></td>
                    <td><?=  $key['CANTIDAD']?></td>      
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