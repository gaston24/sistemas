<?php

require_once 'Class/Orden.php';

$orden = new Orden();
$todasLasOrdenes = $orden->traerOrdenesTodas();

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
    <title>Seleccion de ordenes</title>
</head>
<body>

<div id="contenedorList">
    <h3 class="mb-4 mt-4 ml-4" id="titleSelect"><i class="fa fa-check-square-o"></i>  Selección de Ordenes</h3>

            <div class="ml-2">   
                <a type="button" class="btn btn-primary ml-4 mb-4" id="btn_back2" href="navbar.html"><i class="fa fa-arrow-left"></i>  Volver</a>
            </div>

    <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableGestionOrden" style="width: 70%">
            <thead class="thead-dark">
                <th scope="col" style="width: 8%">Fecha</th>
                <th scope="col" style="width: 8%">Hora</th>
                <th scope="col" style="width: 10%">Orden</th>
                <th scope="col" style="width: 10%">Articulos</th>
                <th scope="col" style="width: 5%">Estado</th>  
                <th scope="col" style="width: 2%"></th>  
                <th scope="col" style="width: 2%"></th> 
            </thead>

            <tbody id="table">

                <?php
                foreach($todasLasOrdenes as $valor => $key){
                ?>

                <tr>
                    <td><?=  $key['FECHA']?></td>
                    <td><?=  $key['HORA']?></td>
                    <td><?= $key['NRO_ORDEN'] ?></td>
                    <td><?=  $key['ARTICULOS']?></td>
                    <td id="novedadPed" name="novedadPed">
                            <?php if($key['ACTIVA']== 1){ ?>
                            <a>ACTIVA!</a>
                            <?php } else { ?> <?php } ?>
                        </td>
                    <td>
                      <a href="OrdenVsNotaPedido.php?orden=<?= $key['NRO_ORDEN'] ?>"><button type="button" class="btn btn-sm btn-warning"><i class="fa fa-search"></i> Analizar</button></a>
                    </td>
                    <td>
                      <a href="detallePedidosCom.php?orden=<?= $key['NRO_ORDEN'] ?>"><button type="button" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Exportar</button></a>
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