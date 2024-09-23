<?php 

    session_start();
    if(!isset($_SESSION['username']) ){
        header("Location:login.php");
    }

    require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';
    $recodificacion = new Recodificacion();

    $encabezadoSolicitud = $recodificacion->traerEncabezado($_GET['numSolicitud']);
    if(count($encabezadoSolicitud) > 0){
        $detalleSolicitud = $recodificacion->traerDetalle($_GET['numSolicitud']);
        
    }
    $locales = $recodificacion->traerLocales();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Enviar Solicitud</title>
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
                <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Solicitud de Recodificacion</h6></div>
                    <div class="card card-1">
                        <div id="periodo" hidden><?= $periodo ?></div>
                        <div class="row" style="margin-left:50px; margin-top:30px">
                            <h3><strong><i class="bi bi-pencil-square" style="margin-right:20px;font-size:40px"></i>Enviar Solicitud</strong></h3>
                        </div>
                        <form class="form-inline"  action="#">

                            <div class="form-group" style="margin-left:3rem; margin-top: 1rem">
                                    
                                    <label class="ml-1">Fecha de Solicitud: </label>
                                    <input class="form-control" style="margin-left:0.8rem; width:10rem"  type="date" id="desde" name="desde" value="<?= $encabezadoSolicitud[0]['FECHA']->format("Y-m-d") ?>" disabled>
                                    <label class="ml-4">Usuario Emisor: </label>
                                    <input class="form-control" style="margin-left:0.8rem; width:13rem" type="" id="hasta"  name="hasta" value="<?=  str_replace("_"," ",$encabezadoSolicitud[0]['USUARIO_EMISOR']) ?>" disabled>
                                    
                            </div>
                            <div class="form-group" style="margin-left:3rem; margin-top: 0.5rem; margin-bottom: 1rem">

                                    <label class="ml-1">N° Solicitud: </label>
                                    <input class="form-control" style="margin-left:3.5rem; width:10rem" type="text" id="numSolicitud" name="numSolicitud" value="<?=  $encabezadoSolicitud[0]['ID'] ?>" disabled>
                                    <label class="ml-4">Estado: </label>
                                    <input class="form-control" style="margin-left:4.5rem; width:13rem" type="text" style="width:160px; height:40px; margin-left:57px"  value="Autorizada" disabled>
                                    <div style="margin-left:30px"><button type="button" style="width:140px; height:40px; margin-left:650px" class ="btn btn-primary" onclick="enviar()" >Enviar <i class='fa fa-paper-plane'></i></button></div>

                            </div>

                        </form>

                        <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" style="">
                                <tr>
                                    <th style="text-align:center;width:5%" >ARTICULO</th>
                                    <th style="text-align:center;width:10%" >DESCRIPCION</th>
                                    <th style="text-align:center;width:10%" >PRECIO</th>
                                    <th style="text-align:center;width:20%" >DESCRIPCION FALLA</th>
                                    <th style="text-align:center;width:10%" >NUEVO CODIGO</th>
                                    <th style="text-align:center;width:10%" >DESTINO</th>
                                    <th style="text-align:center;width:20%" >OBSERVACIONES</th>
                                    <th style="text-align:center;width:15%" >REMITO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach ($detalleSolicitud as $key => $detalle) {
                             
                                        foreach ($locales as $key => $local) {
                                            if($local['NRO_SUCURSAL'] == $detalle['DESTINO']){
                                                $codDestino = $local['COD_CLIENT'];
                                            }
                                        }
                            
                                        $remitos = $recodificacion->traerRemitos($_SESSION['numsuc'], $codDestino);
                                ?>
                                       <tr>
                                        
                                        <td style="text-align:center"><?= $detalle['COD_ARTICU'] ?></td>
                                        <td style="text-align:center"><?= $detalle['DESCRIPCION'] ?></td>
                                        <td style="text-align:center">$ <?=  number_format($detalle['PRECIO'], 0, ".",".") ?></td>
                                        <td style="text-align:center"><?= $detalle['DESC_FALLA'] ?>  <button class="btn btn-warning" onclick="mostrarImagen(this)"><i class="bi bi-eye"></i></button></td>
                                        <td style="text-align:center"><?= $detalle['NUEVO_CODIGO'] ?> </td>
                                        <td style="text-align:center">

                                            <?php 
                                                foreach ($locales as $key => $local) {
                                                    

                                                    if($local['NRO_SUCURSAL'] == $detalle['DESTINO']){
                                                        echo $local['DESC_SUCURSAL'];
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td style="text-align:center"><?= $detalle['OBSERVACIONES'] ?> </td>
                                        <td style="text-align:center">
                                            <?php
                                            
                                            if($detalle['DESTINO'] != $_SESSION['numsuc']){ 
                                            ?>
                                                <select class="form-control selectRemito" id="selectRemito" onchange="comprobarArticuloEnRemito(this)">
                                                    <option value="" selected disabled hidden>Seleccionar remito</option>
                                                    <?php 
                                                    foreach ($remitos as $key => $remito) {
                                                        ?>
                                                        <option value="<?= $remito['N_COMP']?>"><?= $remito['N_COMP']?></option>
                                                        <?php
                                                    } 
                                                    ?>
                                                </select>
                                            <?php 
                                            }
                                            ?>
                                                </td>
                                                                                <td hidden><?= $detalle['ID'] ?></td>

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
        <script src="js/enviarSolicitud.js"></script>
        <script src="js/mostrarImagen.js"></script>
    </body>

</html>
<script>
    $(".selectRemito").select2();

</script>
<!-- <script src="js/gastosTesoreria.js"></script> -->

