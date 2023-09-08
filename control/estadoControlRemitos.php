<?php

require_once 'class/control.php';
$control = new Remito();


$fecha_actual = date("Y-m-d");

if(isset($_GET['desde']) && $_GET['desde'] != "" ){
    $desde = $_GET['desde'];
}else{
    $desde = date("Y-m-d",strtotime($fecha_actual."- 1 week"));
}

if(isset($_GET['hasta']) && $_GET['hasta'] != "" ){
    $hasta = $_GET['hasta'];
}else{
    $hasta = date("Y-m-d",strtotime($fecha_actual."- 1 day"));
}

$sucursal = (isset($_GET['selectSucursal'])) ? $_GET['selectSucursal'] : "%" ;
$locales = $control->traerLocales();




$estado = (isset($_GET['selectEstado'])) ? $_GET['selectEstado'] : "%" ;

$data = $control->traerEstadoControlRemitos($desde, $hasta, $sucursal, $estado);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Estado control de remitos</title>

        <!-- INCLUDE CSS FILES -->
        <link rel="stylesheet" href="css/estadoControlRemitos.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>

        div.dataTables_wrapper div.dataTables_length label {
            margin-left: 1rem;
        }

        div.dataTables_wrapper div.dataTables_filter label {
            margin-right: 1.5rem;
        }

    </style>


    </head>

    <body>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w680"><div style="color:white; text-align:center;"><h6>Estado Control de Remitos</h6></div>
                    <div class="card card-1">
                        <div id="boxLoading"></div>
                        <div class="row" style="margin-left:25px">
                            <h3 class="mt-3"><strong><i class="bi bi-check-circle" style="margin-right:20px;font-size:35px;"></i>Estado Control de Remitos</strong></h3>
                        </div>

                        <form class="form-inline ml-4" action="#" method="get" style="margin-bottom:20px">

                            <div class="row" style="margin-top:10px">

                                <label class="ml-2">Desde: </label>
                                <input Class="form-control ml-1" type="date" id='desde' name="desde" value="<?php  echo $desde ?>">
                                <label class="ml-2">Hasta: </label>
                                <input  Class="form-control ml-1" type="date" id='hasta' name="hasta" value="<?php echo $hasta; ?>">
                                
                                <label class="ml-2">Desde: </label>
                                    <select Class="form-control ml-1" name="selectSucursal" id="selectSucursal" class="selectSucursal">

                                        <option value="%">TODOS</option>
                                        <?php
                                            foreach ($locales as $key => $local) {
                                        ?>
                                            <option value="<?= $local['NRO_SUCURSAL'] ?>" <?= ($local['NRO_SUCURSAL'] = $sucursal) ? "selected" : "" ?> ><?= $local['DESC_SUCURSAL'] ?></option>
                                        <?php
                                            }
                                        ?>
            
                                    </select>

                                
                                
                                    <label class="ml-2">Desde: </label> 

                                    <select class="form-control ml-1" name="selectEstado" id="selectEstado" style="width:150px; height:40px">

                                            <option value="%" <?= ( "%" == $estado) ? "selected" : "" ?>>TODOS</option>
                                            <option value="REMITIDO" <?= ( "REMITIDO" == $estado) ? "selected" : "" ?>>REMITIDO</option>
                                            <option value="DESPACHADO" <?= ( "DESPACHO" == $estado) ? "selected" : "" ?>>DESPACHADO</option>
                                            <option value="INGRESADO" <?= ( "INGRESADO" == $estado) ? "selected" : "" ?>>INGRESADO</option>
                                            <option value="CONTROLADO" <?= ( "CONTROLADO" == $estado) ? "selected" : "" ?>>CONTROLADO</option>
            
                                    </select>

                                

                                <div style="margin-left:1rem">   
                                    <button class="btn btn-primary btn-submit" id="btnSubmit" value="" onclick="mostrarSpiner()">filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                                    <button type="button"  class="btn btn-success" id="btnExport" style="margin-left:1rem">Exportar <i class="bi bi-file-earmark-excel"></i></button>
                                
                                </div>

                            </div>

                        </form>
                        
                        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>

            
                        <table class="table table-striped table-bordered" id="tablaControl" style="width:98%; margin-left:1rem;" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" >
                                <tr style="text-align:center">

                                    <th style="width:15%"> FECHA </th>
                                    <th style="width:10%" > REMITO</th>
                                    <th style="width:5%" > NRO.SUCURSAL.ORIGEN</th>
                                    <th style="width:5%" > SUCURSAL.ORIGEN</th>
                                    <th style="width:5%" > NRO.SUC.DESTINO</th>
                                    <th style="width:5%" > SUCURSAL DESTINO </th>
                                    <th style="width:5%" > GUIA </th>
                                    <th style="width:10%" > FECHA GUIA </th>
                                    <th style="width:10%" > FECHA INGRESO </th>
                                    <th style="width:10%" > FECHA CONTROL </th>
                                    <th style="width:5%" ></th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                    foreach ($data as $key => $remito) {
                           
                                ?>
                                <tr>
                                    <td style="text-align:center" ><?= $remito['FECHA']->format("Y-m-d") ?></td>
                                    <td style="text-align:center" ><?= $remito['N_COMP'] ?></td>
                                    <td style="text-align:center" ><?= $remito['NRO_SUCURS'] ?></td>
                                    <td style="text-align:center" ><?= $remito['DESC_SUC_ORIG'] ?></td>
                                    <td style="text-align:center" ><?= $remito['SUC_DESTIN'] ?></td>
                                    <td style="text-align:center" ><?= $remito['DESC_SUC_DESTIN'] ?></td>
                                    <td style="text-align:center" ><?= $remito['NRO_GUIA'] ?></td>
                                    <td style="text-align:center" ><?= (isset($remito['FECHA_GUIA'])) ? $remito['FECHA_GUIA']->format("Y-m-d") : ""  ?></td>
                                    <td style="text-align:center" title="<?= $remito['USER_INGRESO'] ?>" data-toggle="tooltip" ><?= (isset($remito['FECHA_INGRESO'])) ? $remito['FECHA_INGRESO']->format("Y-m-d") : ""  ?></td>
                                    <td style="text-align:center" ><?= (isset($remito['FECHA_CONTROL'])) ? $remito['FECHA_CONTROL']->format("Y-m-d") : ""  ?></td>
                                    <td style="text-align:center" >
                                        <?php 
                                        switch ($remito['ESTADO']) {

                                            case 'INGRESADO':
                                                echo '<button class="btn btn-warning" title="INGRESADO" data-toggle="tooltip" > <i class="bi bi-box-arrow-in-right"></i> </button>';
                                                break;

                                            case 'DESPACHADO':
                                                echo '<button class="btn btn-primary" title="DESPACHADO" data-toggle="tooltip" ><i class="bi bi-truck"></i></button>';
                                                break;

                                            case 'CONTROLADO':
                                                echo '<button class="btn btn-success" title="CONTROLADO" data-toggle="tooltip" ><i class="bi bi-check-square"></i></button>';
                                                break;
                                            case 'REMITIDO':
                                                echo '<button class="btn btn-secondary" title="REMITIDO" data-toggle="tooltip" > <i class="bi bi-file-check"></i> </button>';
                                                break;
                                            
                                  
                                        }
                                        
                                        
                                        ?>
                                    </td>
                                </tr>
                                        
                                <?php
                                    }
                                ?>
                             
                            </tbody>
            
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </body>

</html>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="js/estadoControlRemitos.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $("#btnExport").click(function() {
        $("#tablaControl").table2excel({
            // exclude CSS class
            exclude: ".noE  xl",
            name: "Ventas por medio de pago",
            filename: "Ventas por medio de pago", //do not include extension
            fileext: ".xlsx" // file extension
        });
    });
    $('.selectSucursal').select2();

    // $(document).ready(function() {

    //     // document.querySelector('.select2-selection.select2-selection--single').style.height = "40px";

    // })

</script>
