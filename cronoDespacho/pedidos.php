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
    <link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css" >
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Including Font Awesome CSS from CDN to show icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->

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
                    <option value="<?= $key['RAZON_SOCI'] ?>"><?= $key['COD_CLIENT'].' - '.$key['RAZON_SOCI'] ?></option>
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
            <div id="totalPedidos">
                <label id="labelTotal">Pedidos</label> 
                <input name="total_todo" id="totalPed" value="0" type="text" class="form-control form-control-sm text-center total" readonly>
            </div>
            <div id="totalArticulos">
                <label id="labelTotal">Artículos</label> 
                <input name="total_todo" id="total" value="0" type="text" class="form-control form-control-sm text-center total" readonly>
            </div>            
            <div id="busqRapida2">
                <label id="textBusqueda">Busqueda rapida:</label>
                <input type="text" id="textBox" placeholder="Sobre cualquier campo..." onkeyup="myFunction()" class="form-control form-control-sm"></input>
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

        <div class="table-responsive" id="tablePedidos" style="height: 87vh;">
            <table class="table table-hover table-condensed table-striped text-center">
                <thead class="thead-dark">
                    <th scope="col" style="width: 5%">FECHA</th>
                    <th scope="col" style="width: 1%">HORA</th>
                    <th scope="col" style="width: 1%">CLIENTE</th>
                    <th scope="col" style="width: 19%">NOMBRE</th>
                    <th scope="col" style="width: 1%">TALONARIO</th>
                    <th scope="col" style="width: 1%">PEDIDO</th>
                    <th scope="col" style="width: 1%">UNIDADES</th>
                    <th scope="col" style="width: 1%">COMPROBANTE</th>
                    <th scope="col" style="width: 1%"></th>
                    <th scope="col" style="width: 1%">FECHA<br>DESPACHO</th>
                    <th scope="col" style="width: 1%">2DA FECHA<br>DESPACHO</th>
                    <th scope="col" style="width: 1%">DIA</th>
                    <th scope="col" style="width: 1%">PRIORIDAD</th>
                    <th scope="col" style="width: 1%"></th>
                    <th scope="col" style="width: 1%"></th>
                    <th scope="col" style="width: 1%"></th>
                    <th scope="col" style="width: 1%"></th>
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
                            <td><?= $value->N_COMP; ?></td>
                            <td id='condicion'>
                                <?php if(isset($value->TIPO_COMP)){ ?>
                                    <i class="bi bi-info-circle-fill" aria-hidden="true" title="<?= $value->TIPO_COMP.' / '.$value->EMBALAJE.' / '.$value->DESPACHO?>" data-toggle="tooltip" data-placement="top"></i>
                                <?php } else { ?> <?php } ?>
                            </td>
                            <td><?= substr($value->PROX_DESPACHO->date,0,10); ?></td>
                            <td><?= substr($value->RE_DESPACHO->date,0,10); ?></td>
                            <td><?= $value->RUTA; ?></td>
                            <td><?= $value->PRIORIDAD; ?></td>
                            <td id='estado'>
                                <?php if(substr($value->PROX_DESPACHO->date,0,10) != substr($value->RE_DESPACHO->date,0,10)){ ?>
                                    <i class="fa fa-exclamation-triangle" aria-hidden="true" title="DEMORADO" data-toggle="tooltip" data-placement="top"></i>
                                <?php } else { ?> <?php } ?>
                            </td>
                            <td id='estado1'>
                                <?php if(isset($value->FIN_PICKING)){ ?>
                                    <i class="fa fa-cart-plus" aria-hidden="true" title="PREPARADO" style="display: flex; text-align: right; color:#28a745; font-size: 1.5em;" data-toggle="tooltip" data-placement="top"></i>
                                <?php } else if (isset($value->INI_PICKING)) { ?>
                                    <i class="fa fa-cart-plus" aria-hidden="true" title="PREPARACION" style="display: flex; text-align: right; color:#ffc107; font-size: 1.5em;"  data-toggle="tooltip" data-placement="top"></i>
                                <?php{ ?> <?php } ?>
                            </td>
                            <td id='estado2'>
                                <?php if(isset($value->N_COMP)){ ?>
                                    <i class="bi bi-clipboard2-check-fill" aria-hidden="true" title="FACTURADO" data-toggle="tooltip" data-placement="top"></i>
                                <?php } else { ?> <?php } ?>
                            </td>
                            <td id='estado3'>
                                <?php if(isset($value->FECHA_GUIA)){ ?>
                                    <i class="fas fa-truck" aria-hidden="true" title="DESPACHADO" data-toggle="tooltip" data-placement="top"></i>
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

 

    $(function () {
    $('[data-toggle="tooltip"]').tooltip()
    })

</script>

</html>