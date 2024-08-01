<?php 
    session_start();
    if(!isset($_SESSION['username']) ){
        header("Location:login.php");
    }

   require_once __DIR__.'/../class/remito.php';
   require_once '../ajustes/class/Articulo.php';
   require_once '../ajustes/class/Ajuste.php';
   require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';
   $nroSucurs = $_SESSION['numsuc'];

   $recodificacion = new Recodificacion();
    $numSucursal = $_SESSION['numsuc'];


    $numSolicitud = [];
    $numSolicitud[0]['ultimo_id'] = $_GET['numSolicitud'];

    
    $solicitudEncabezado = $recodificacion->traerEncabezado($numSolicitud[0]['ultimo_id']);

    if(count($solicitudEncabezado) > 0){
        
        $solicitudDetalle = $recodificacion->traerDetalle($numSolicitud[0]['ultimo_id'],$numSucursal);
       
    }

    $locales = $recodificacion->traerLocales(null);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solicitud de Recodificacion</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        </link>
        <style>

            .select2-dropdown.select2-dropdown--above {

                width:300px;
            }
        </style>

    </head>

    <body>
        
      
        <!-- <input type="file" name="archivos[]" id="archivos" multiple accept=".pdf, .jpg, .png" style="display: none;" /> -->
        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>
        <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Solicitud De Recodificacion</h6></div>
                    <div class="card card-1">
                        <div id="periodo" hidden><?= $periodo ?></div>
                        <div class="row" style="margin-left:50px; margin-top:30px">
                            <h3><strong><i class="bi bi-pencil-square" style="margin-right:20px;font-size:50px"></i>Solicitud de recodificacion </strong></h3>
                        </div>

                        <div style="margin-bottom:20px">

                            <div class="row" style="margin-top:10px">
               
                                <div style="margin-left:90px">Fecha Solicitud: <input type="date" style="width:145px; height:35px" value =<?= $solicitudEncabezado[0]['FECHA']->format("Y-m-d") ?> id="fecha"  disabled ></div>
                                
                                <div style="margin-left:90px">Usuario Emisor: 
                                   <input type="text" syle="width:145px; height:35px" disabled value="<?= str_replace("_"," ",$solicitudEncabezado[0]['USUARIO_EMISOR']) ?>">              
                                </div>
                                <div style="margin-left:30%"> 
                                <?php 
                               
                                    echo '<a href="seleccionDeSolicitudesDestino.php" class="btn btn-secondary">Volver Al Listado</a>';
                               
                                ?>
                                  
                                </div>

                            </div>

                            <div class="row" style="margin-top:10px">

                                <div style="margin-left:90px">N° solicitud <input type="text" style="width:145px; height:35px; margin-left:30px" value="<?=  ($numSolicitud[0]['ultimo_id']) ?>" id="numSolicitud" disabled></div>

                               
                                <div style="margin-left:90px">Estado: <input type="text" style="width:145px; height:35px; margin-left:55px" id="estado" value="<?= $_GET['estado'] ?> " disabled> </div>

                            </div>

                        </div>
            
                        <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px; font-size: 13px;" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" style="">
                                <tr>
                                    <th style="text-align:center;width: 10%;" >Articulo</th>
                                    <th style="text-align:center;width: 20%;" >Descripcion</th>
                                    <th style="text-align:center;width: 8%;" >Precio</th>
                                    <th style="text-align:center;width: 5%;">Cantidad</th>
                                    <th style="text-align:center;width: 15%;" >Descripcion Falla</th>
                                    <th style="text-align:center;width: 10%;" >Nuevo Codigo</th>
                                    <th style="text-align:center;width: 10%;" >Observaciones</th>
                                    <th style="text-align:center;width: 10%;" >Origen</th>
                                    <th  style="text-align:center;width: 10%;">REMITO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                         

                                        foreach ($solicitudDetalle as $key => $detalle) {
                            
                                            echo '
                                            <tr id="bodyArticulos"> 
                                                <td style="text-align:center">'.$detalle['COD_ARTICU'].'</td>
                                                <td style="text-align:center; white-space: pre-line;">'.$detalle['DESCRIPCION'].' <button class="btn btn-warning" onclick= "mostrarImagen(this)" style="margin-left:10px; border-style:none; padding: .3rem .6rem;"><i class="bi bi-eye"></i></button> </td>
                                                <td style="text-align:center">$'.number_format($detalle['PRECIO'], 0, ",",".").'</td>
                                                <td style="text-align:center">'.$detalle['CANTIDAD'].'</td>
                                                <td style="text-align:center"><input type="text" style="width:400px" onchange="comprobarFila(this)" value="'.$detalle['DESC_FALLA'].'" disabled></td>
                                                ';
                                
                                                
                                                $origen = "";
                                                foreach ($locales as $key => $local) {
                                              
                                                    if($local['NRO_SUCURSAL'] == $solicitudEncabezado[0]['NUM_SUC']){
                                                        $origen = $local['DESC_SUCURSAL'];
                                                    }

                                                }
                                                echo '
                                                    <td style="text-align:center">'.$detalle['NUEVO_CODIGO'].'</td>
                                                    <td style="text-align:center">'.$detalle['OBSERVACIONES'].'</td>
                                                    <td style="text-align:center">'.$origen.'</td>
                                               ';
                                        
                                                echo '<td style="text-align:center">'.$detalle['N_COMP'].'</td>';

                                            echo '
                                                </tr>
                                            ';
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
      
        <script src="js/mostrarImagen.js"></script>
    </body>

</html>



