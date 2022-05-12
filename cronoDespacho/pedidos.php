<?php

require 'Class/pedido.php';
require 'Class/canal.php';
require 'Class/cliente.php';

$canal = new Canal();
$todosLosCanales = $canal->traerCanales();

$cliente = new Cliente();
$todosLosClientes = $cliente->traerClientes();

$pedido = new Pedido();

$desde = isset($_GET['desde']) ? $_GET['desde'] : date("Y-m-d");
$hasta = isset($_GET['hasta']) ? $_GET['hasta'] : date("Y-m-d");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos pendientes</title>
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.css" />
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<form class="form-row mt-2">

            <div class="col- mt-2" id="contCanal">
                    <label">Canal</label>
                    <select id="inputCanal" class="form-control form-control-sm" name="canal">
                        <option selected></option>
                        <?php
                    foreach($todosLosCanales as $canal => $key){
                    ?>
                    <option value="<?= $key['CANAL'] ?>"><?= $key['CANAL'] ?></option>
                    <?php   
                    }
                    ?>
                    </select>
            </div>

            <div class="col-2 mt-2" id="contCliente">
                    <label">Cliente</label>
                    <select id="inputCliente" class="form-control form-control-sm" name="cliente">
                        <option selected></option>
                        <?php
                    foreach($todosLosClientes as $cliente => $key){
                    ?>
                    <option value="<?= $key['RAZON_SOCI'] ?>"><?= $key['RAZON_SOCI'] ?></option>
                    <?php   
                    }
                    ?>
                    </select>
            </div>

            <div class="form-row" id="contDate">
                <div class="col-">
                    <label class="col-sm col-form-label">Desde:</label>
                    <input type="date" class="form-control form-control-sm" name="desde" value="<?= $desde ?>">
                </div>
                <div class="col-">
                    <label class="col-sm col-form-label">Hasta:</label>
                    <input type="date" class="form-control form-control-sm" name="hasta" value="<?= $hasta ?>">
                </div>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" id="btn_filtro_2">Filtrar<i class="fa fa-filter"></i></button>
            </div>

            <!-- <div id="busqRapida2">
                <label id="textBusqueda">Busqueda rapida:</label>
                <input type="text" id="textBox" placeholder="Sobre cualquier campo..." onkeyup="myFunction()" class="form-control form-control-sm"></input>
            </div> -->
            <div id="totalArticulos">
                <label id="labelTotal">Total artículos</label> 
                <input name="total_todo" id="total" value="0" type="text" class="form-control text-center total" readonly>
            </div>

            <div id="totalPedidos">
                <label id="labelTotal">Total pedidos</label> 
                <input name="total_todo" id="totalPed" value="0" type="text" class="form-control text-center total" readonly>
            </div>

</form>

        <?php

        if (isset($_GET['canal'])){ 

        if ($_GET['canal']!= ''){
            $canal = $_GET['canal'];}
            else {
            $canal = '%';
            }
        
        if ($_GET['cliente']!= ''){
            $cliente = $_GET['cliente'];}
            else {
            $cliente = '%';
            }
        
        $todosLosPedidos = $pedido->traerPedidos($canal, $cliente, $desde, $hasta);

        ?>

        <div class="table-responsive2" id="tablePedidos">
            <table class="table table-hover table-condensed table-striped text-center">
                <thead class="thead-dark">
                    <th scope="col" style="width: 5%">FECHA</th>
                    <th scope="col" style="width: 1%">HORA</th>
                    <th scope="col" style="width: 1%">CLIENTE</th>
                    <th scope="col" style="width: 19%">NOMBRE</th>
                    <th scope="col" style="width: 1%">TALONARIO</th>
                    <th scope="col" style="width: 1%">PEDIDO</th>
                    <th scope="col" style="width: 1%">UNIDADES</th>
                    <th scope="col" style="width: 1%">FECHA<br>DESPACHO</th>
                    <th scope="col" style="width: 1%">2DA FECHA<br>DESPACHO</th>
                    <th scope="col" style="width: 1%">DIA</th>
                    <th scope="col" style="width: 1%">PRIORIDAD</th>
                    <th scope="col" style="width: 1%">ESTADO</th>
                </thead>

                <tbody id="table">
                    <?php
                    $todosLosPedidos = json_decode($todosLosPedidos);
                   /*  print_r($todosLosPedidos);  */
                    foreach ($todosLosPedidos as $valor => $value) {
                        // var_dump($value->FECHA);
                    ?>

                      
                        <tr>
                            <td><?= substr($value->FECHA->date,0,10); ?></td>
                            <td><?= $value->HORA_INGRESO; ?></td>
                            <td><?= $value->COD_CLIENT; ?></td>
                            <td><?= $value->RAZON_SOCI; ?></td>
                            <td><?= $value->TALON_PED; ?></td>
                            <td class="pedido"><?= $value->NRO_PEDIDO; ?></td>
                            <td class="sumTotal"><?= $value->CANT_PEDIDO; ?></td>
                            <td><?= substr($value->PROX_DESPACHO->date,0,10); ?></td>
                            <td><?= substr($value->RE_DESPACHO->date,0,10); ?></td>
                            <td><?= $value->RUTA; ?></td>
                            <td><?= $value->PRIORIDAD; ?></td>
                            <td id='estado'>
                                <?php if(substr($value->PROX_DESPACHO->date,0,10) != substr($value->RE_DESPACHO->date,0,10)){ ?>
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                <?php } else { ?> <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>

            </table>

        <?php
         }
        ?>

        </div>

</body>

<script src="main.js" charset="utf-8"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>


$(document).ready(function(){
    sumarTotal()
  });
  const sumarTotal = ()=>{
      var data = [];
      $("td.sumTotal").each(function(){
          data.push(parseFloat($(this).text()));
      });
      var suma = data.reduce(function(a,b){ return a+b; },0);
      $("#total").val(new Intl.NumberFormat("de-DE").format(suma));
  }

  $(document).ready(function(){
        var rows = 0;
        $(".pedido").each(function(){
            rows++;
        })
        $("#totalPed").val(new Intl.NumberFormat("de-DE").format(rows))
    });

</script>

</html>