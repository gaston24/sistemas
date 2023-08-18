<?php 

    session_start();
    require_once 'class/Recodificacion.php';
 
    $desde = (isset($_GET['desde'])) ? $_GET['desde'] : date('Y-d-m', strtotime('-1 month'));
    $hasta = (isset($_GET['hasta'])) ? $_GET['hasta'] : date('Y-d-m');
    $estado = (isset($_GET['estado'])) ? $_GET['estado'] : '%';

    $recodificacion = new Recodificacion();
    
    
   
    $solicitudEncabezado = $recodificacion->traerEncabezado($_GET['numSolicitud']);
    if(count($solicitudEncabezado) > 0){
        $solicitudDetalle = $recodificacion->traerDetalle($_GET['numSolicitud']);
       
    }
    $locales = $recodificacion->traerLocales();

    $localSolicitud = "";

    foreach ($locales as $key => $local) {
        
        if($local['NRO_SUCURSAL'] == $solicitudEncabezado[0]['NUM_SUC']){
            $localSolicitud = $local['DESC_SUCURSAL'];
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Autorizar Solicitud</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        </link>

    </head>

    <body>
        
        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>
        <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Solicitud envio de fallas</h6></div>
                    <div class="card card-1">
                        <div id="periodo" hidden><?= $periodo ?></div>
                        <div class="row" style="margin-left:50px; margin-top:30px">
                            <h3><strong><i class="bi bi-pencil-square" style="margin-right:20px;font-size:40px"></i>Autorización de Solicitud</strong></h3>
                        </div>
                        <form action="#">

                            <div style="margin-bottom:20px">

                                <div class="row" style="margin-top:10px">

                                    <div style="margin-left:90px">Fecha de Solicitud : <input type="date" style="width:160px; height:35px" id="desde" name="desde" value="<?= $solicitudEncabezado[0]['FECHA']->format("Y-m-d") ?>" disabled></div>
                                    <div style="margin-left:30px">Sucursal : <input type="" style="width:160px; height:35px;margin-left:16px" id="nombreSuc"  name="nombreSuc" attr-realvalue="<?= $solicitudEncabezado[0]['NUM_SUC'] ?>" value="<?=  $localSolicitud ?>" disabled></div>
                                    <div style="margin-left:30px">Usuario Emisor: <input type="" style="width:200px; height:35px" id="hasta"  name="hasta" value="<?=   str_replace("_"," ",$solicitudEncabezado[0]['USUARIO_EMISOR']) ?>" disabled></div>
                                    
                                </div>
                                <div class="row" style="margin-top:10px">

                                    <div style="margin-left:90px">N° Solicitud : <input type="text" style="width:160px; height:35px; margin-left:45px" id="numSolicitud" name="numSolicitud" value="<?=  $solicitudEncabezado[0]['ID'] ?>" disabled></div>
                                    <div style="margin-left:30px">Estado: <input type="text" style="width:160px; height:35px; margin-left:30px" id="estado"  name="estado" value="Solicitada" disabled></div>
                                    <div style="margin-left:30px"><button type="button" style="width:140px; height:35px; margin-left:900px" class ="btn btn-success" onclick="autorizar()" >Autorizar <i class='bi bi-check2-square'></i></button></div>
                                    
                                </div>

                            </div>

                        </form>

                        <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" style="">
                                <tr>
                                    <th style="text-align:center;" >ARTICULO</th>
                                    <th style="text-align:center;" >DESCRIPCION</th>
                                    <th style="text-align:center;" >PRECIO</th>
                                    <th style="text-align:center;" >DESC. FALLA</th>
                                    <th style="text-align:center;" >RECODIFICA</th>
                                    <th style="text-align:center;" >UNICO</th>
                                    <th style="text-align:center;" >- 10%</th>
                                    <th style="text-align:center;" >- 20%</th>
                                    <th style="text-align:center;" >- 30%</th>
                                    <th style="text-align:center;" >- 40%</th>
                                    <th style="text-align:center;" >NUEVO CODIGO</th>
                                    <th style="text-align:center;" >DESTINO</th>
                                    <th style="text-align:center;" >OBSERVACIONES</th>
           
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                    foreach ($solicitudDetalle as $key => $detalle) {

                                ?>
                                           <tr>
                                            <td style="text-align:center;width:7%"><?= $detalle['COD_ARTICU'] ?></td>
                                            <td style="text-align:center;width:12%"><?= $detalle['DESCRIPCION'] ?></td>
                                            <td style="text-align:center;width:7%" attr-realvalue="<?= $detalle['PRECIO'] ?>">$ <?= number_format($detalle['PRECIO'], 0, ",",".") ?></td>
                                            <td style="text-align:center;width:15%"><?= $detalle['DESC_FALLA'] ?> <button class="btn btn-warning" onclick= "mostrarImagen(this)"><i class="bi bi-eye"></i></button></td>
                                            <td style="text-align:center;width:5%"><input type="checkbox" onchange="activarRecodificacion(this)"></td>
                                            <td style="text-align:center;width:5%"><input type="checkbox" onchange="comprobarCheckbox(this)" porcentaje="unico" disabled ></td>
                                            <td style="text-align:center;width:5%"><input type="checkbox" onchange="comprobarCheckbox(this)" porcentaje="0.9" disabled ></td>
                                            <td style="text-align:center;width:5%"><input type="checkbox" onchange="comprobarCheckbox(this)" porcentaje="0.8" disabled ></td>
                                            <td style="text-align:center;width:5%"><input type="checkbox" onchange="comprobarCheckbox(this)" porcentaje="0.7" disabled ></td>
                                            <td style="text-align:center;width:5%"><input type="checkbox" onchange="comprobarCheckbox(this)" porcentaje="0.6" disabled ></td>
                                            <td style="text-align:center;width:10%"></td>
                                            <td style="text-align:center">
                                                <select style="width: 178px;height: 28px;" class="sucursal" disabled>
                                                    <option value="1" selected>CENTRAL</option>
                                                    <?php
                                                        foreach ($locales as $key => $local) {
                                                            echo '<option value="'.$local['NRO_SUCURSAL'].'">'.$local['DESC_SUCURSAL'].'</option>';
                                                        }
                                                    
                                                    ?>
                                                </selecet>
                                            </td>
                                            <td>
                                                <input type="text" >
                                            </td>
                                            <td hidden ><?= $detalle['ID'] ?></td>                                                           

                                    <?php
                                        }

                                    ?>
                                
                              
                            </tbody>
            
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        <link rel="stylesheet" type="text/css" href="assets/select2/select2.min.css">
        <script src="assets/select2/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
        <script src="js/autorizarSolicitud.js"></script>
        <script src="js/mostrarImagen.js"></script>
    </body>

</html>
<script>
  $(".sucursal").select2();

</script>
<!-- <script src="js/gastosTesoreria.js"></script> -->

