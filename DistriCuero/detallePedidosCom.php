<?php

require_once 'Class/Pedido.php';

$orden = $_GET['orden'];

$pedido = new Pedido();
$todosLosPedidos = $pedido->traerDetallePedidosCom($orden);

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
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"rel="stylesheet"/>
    <!-- Export excel -->
    <script src="bower_components\jquery\dist\jquery.min.js"></script>
    <script src="bower_components\jquery-table2excel\dist\jquery.table2excel.min.js"></script>

    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">
    <title>Detalle notas de pedido</title>
</head>
<body>

<div id="contenedorList">
    <h3 class="mb-4 mt-4 ml-4" id="titleSelect"><i class="fas fa-clipboard-list"></i></i>  Detalle Orden: <?php echo $orden ?></h3>
        <div class="row ml-2">
            <div class="ml-2">      
                <a type="button" class="btn btn-primary ml-4 mb-4" id="btn_back2" href="listOrdenesComercial.php"><i class="fa fa-arrow-left"></i>  Volver</a>
            </div>
            <div class="col-2">
				<button class="btn btn-success" id="btnExport"><i class="fa fa-file-excel-o"></i> Exportar</button>
			</div>
        </div>

    <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tablePedidosCom" style="width: 70%">
            <thead class="thead-dark">
                <th scope="col" style="width: 10%">Fecha</th>
                <th scope="col" style="width: 15%">Nota pedido</th>
                <th scope="col" style="width: 15%">Cliente</th>
                <th scope="col" style="width: 15%">Articulo</th>
                <th scope="col" style="width: 15%">Descripcion</th> 
                <th scope="col" style="width: 8%">Cantidad</th> 
            </thead>

            <tbody id="table">

                <?php
                foreach($todosLosPedidos as $valor => $key){
                ?>

                <tr>
                    <td><?=  $newDate = $key['FECHA']->format('Y-m-d') ?></td>
                    <td><?=  $key['NRO_NOTA_PEDIDO'] ?></td>
                    <td><?=  $key['COD_CLIENT'] ?></td>
                    <td><?=  $key['COD_ARTICU'] ?></td>
                    <td><?=  $key['DESCRIPCIO'] ?></td>
                    <td><?=  $key['CANTIDAD']?></td>      
                </tr>   
                
                <?php
                }   
                ?>

            </tbody>
            
        </table>
        
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Plugin to export Excel -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

<script>

    $(document).ready(() => {
                $("#btnExport").click(function(){
        $("#tablePedidosCom").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: "Detalle pedidos",
            filename: "Detalle notas de pedido", //do not include extension
            fileext: ".xls" // file extension
        }); 
        });

    });

</script>

</body>
</html>