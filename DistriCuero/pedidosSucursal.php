<?php

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

require_once 'Class/Pedido.php';

$codClient = $_SESSION['codClient']; 

$pedido = new Pedido();
$todosLosPedidos = $pedido->traerPedidos($codClient);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">
    <title>Lista notas de pedidos</title>
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
        <a class="nav-link" href="listOrdenesActivas.php"><i class="fa fa-list"></i> Seleccionar ordenes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" disabled><i class="fa fa-calendar-check-o"></i> Consulta de Ordenes</a>
      </li>
    </ul>
  </div>  
</nav>

<div id="contenedorList">
        <div class="container">
          <div class="row">
              
                  <h3 class="mb-4" style="margin-left:-8rem;" id="titleSelect">
                      <i class="fa fa-check-square-o"></i> Listado de Ordenes
                  </h3>
              <div id="contBusqRapida" class="d-flex align-items-center" style="margin-left:23rem;">
                <label id="textBusqueda" class="mr-2">Búsqueda rápida:</label>
                <input type="text" id="textBox" placeholder="Sobre cualquier campo..." onkeyup="myFunction()" class="form-control"></input>
              </div>
          </div>
      </div>
    <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableGestionOrden" style="width: 80%">
            <thead class="thead-dark">
                <th scope="col" style="width: 15%">Fecha Orden</th>
                <th scope="col" style="width: 20%">Orden</th>
                <th scope="col" style="width: 15%">Fecha NP</th>
                <th scope="col" style="width: 20%">Hora</th>
                <th scope="col" style="width: 20%">Nota de pedido</th>
                <th scope="col" style="width: 10%">Total</th>
                <th scope="col" style="width: 5%">Unidades</th>
                <th scope="col" style="width: 10%">Estado</th>   
                <th scope="col" style="width: 5%"></th>   
            </thead>

            <tbody id="table">

                <?php
                foreach($todosLosPedidos as $valor => $key){
                ?>

                <tr>
                    <td><?=  $newDate = $key['FECHA_ORDEN']->format('Y-m-d') ?></td>
                    <td><?=  $key['NRO_ORDEN'] ?></td>
                      <?php
                          // Convertir la cadena de fecha a un objeto DateTime
                          $fechaNP = DateTime::createFromFormat('Y-m-d', $key['FECHA_NP']);
                          if ($fechaNP) {
                              $formattedDate = $fechaNP->format('Y-m-d');
                          } else {
                              $formattedDate = '';
                          }
                      ?>
                    <td><?= $formattedDate ?></td>
                    <td><?=  $key['HORA']?></td>
                    <td><?= $key['NRO_NOTA_PEDIDO'] ?></a></td>
                    <td><?=  "$".number_format($key['TOTAL'], 0, ".",",")?></td>
                    <td><?=  $key['CANTIDAD']?></td> 
                    <?php
                        $icon = '';
                        $color = '';
                        $title = $key['ESTADO'];
                        if ($key['ESTADO'] == 'RECHAZADA') {
                            $icon = '<i class="bi bi-x-circle-fill" data-toggle="tooltip" data-placement="top" style="color: #dc3545; font-size: large;" title="RECHAZADA"></i>';
                        } elseif ($key['ESTADO'] == 'CARGADA') {
                            $icon = '<i class="bi bi-check-circle-fill" data-toggle="tooltip" data-placement="top" style="color: #28a745; font-size: large; cursor:default;" title="CARGADA"></i>';
                        } elseif ($key['ESTADO'] == 'PENDIENTE') {
                            $icon = '<i class="bi bi-exclamation-circle-fill" data-toggle="tooltip" data-placement="top" style="color: #ffc107; font-size: large;" title="PENDIENTE"></i>';
                        }
                    ?>
                    <td><?= $icon ?></td>
                    <td>
                    <?php if ($key['ESTADO'] == 'CARGADA'): ?>
                      <a href="detallePedidoSuc.php?notaPedido=<?= $key['NRO_NOTA_PEDIDO'] ?>">
                          <i class="fa fa-search" style="color: ##17a2b8; font-size: 20px;"></i>
                      </a>
                    <?php endif; ?>
                    </td>          
                </tr>   
                
                <?php
                }   
                ?>

            </tbody>
            
        </table>
        
    </div>
</div>

<script>

  $(function () {
    $('[data-toggle="tooltip"]').tooltip()
  })

</script>

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