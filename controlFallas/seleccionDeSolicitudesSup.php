<?php 
    session_start();
    require_once 'class/Recodificacion.php';
 
    $desde = (isset($_GET['desde'])) ? $_GET['desde'] : date('Y-d-m', strtotime('-1 month'));
    $hasta = (isset($_GET['hasta'])) ? $_GET['hasta'] : date('Y-d-m');
 

    $estado = (isset($_GET['estado'])) ? $_GET['estado'] : '%';

    $recodificacion = new Recodificacion();
    $nroSucurs = $_SESSION['numsuc'];
   
    // $desdeFormat = date('Y-m-d', strtotime($desde));

    $fecha_objeto = DateTime::createFromFormat('Y-d-m', $desde);
    $desdeFormat = $fecha_objeto->format('Y-m-d');

    $fecha_objeto = DateTime::createFromFormat('Y-d-m', $hasta);
    $hastaFormat = $fecha_objeto->format('Y-m-d');


    $result = $recodificacion->traerSolicitudes($nroSucurs, $desdeFormat, $hastaFormat, $estado, 1);
    $locales = $recodificacion->traerLocales();

    
   
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
            input[type='search'] {
                margin-right:45px
            }
            .dataTables_length{
                margin-left:50px
            }
            .dataTables_info{
                margin-left:50px
            }
            #tablaArticulos_paginate{
                margin-right:42px 
            }
            
        </style>
    </head>

    <body>
        
      
        <input type="file" name="archivos[]" id="archivos" multiple accept=".pdf, .jpg, .png" style="display: none;" />
        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>
        <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Seleccion de Solicitud</h6></div>
                    <div class="card card-1">
                        <div id="periodo" hidden><?= $periodo ?></div>
                        <div class="row" style="margin-left:50px; margin-top:30px">
                            <h3><strong><i class="bi bi-list-task" style="margin-right:20px;font-size:40px"></i>Lista de Solicitudes </strong></h3>
                        </div>
                        <form class="form-inline" action="#">

                            <div style="margin-bottom:20px">

                                <div class="row" style="margin-top:10px">

                                    <div style="margin-left:90px">Desde : <input type="date" class="form-control form-control-sm" id="desde" name="desde" value="<?=  $desde ?>"></div>
                                    <div style="margin-left:30px">Hasta: <input type="date" class="form-control form-control-sm" id="hasta"  name="hasta" value="<?=  $hasta ?>"></div>
                                    <div style="margin-left:30px">Estado: 
                                        <select name="estado" id="estado" class="form-control form-control-sm">

                                            <option value="%">Todos</option>
                                            <option value="1">Solicitada</option>
                                            <option value="2">Autorizada</option>
                                            <option value="3">Enviada</option>

                                        </select>
                                    </div>
                                    <button class="btn btn-primary btn-submit ml-2" style="margin-top: -0.15em;">Filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                                </div>

                            </div>

                        </form>

                        <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" style="">
                                <tr>
                                    <th style="text-align:center;width:10%" >FECHA</th>
                                    <th style="text-align:center;width:10%" >NUMERO</th>
                                    <th style="text-align:center;width:10%" >CLIENTE</th>
                                    <th style="text-align:center;width:20%" >EMISOR</th>
                                    <th style="text-align:center;width:10%">UNIDADES</th>
                                    <th style="text-align:center;width:15%" >ESTADO</th>
                                    <th style="text-align:center;width:10%" >ULT.ESTADO</th>
                                    <th style="text-align:center;width:10%" >ACCION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                foreach ($result as $key => $encabezado) {
                    
                                    switch ($encabezado['ESTADO']) {
                                            
                                        case '1':
                                            $estado = "Solicitada  <button class='btn btn-success' style='background-color:purple;margin-left:18px; border-style:none; padding: .3rem .6rem;'' ><i class='bi bi-box-arrow-in-up'></i></button>";
                                            $accion = "<a href='autorizarSolicitud.php?numSolicitud=$encabezado[ID]&tipoU=2' class='href'><button class='btn btn-primary' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-pencil-square'></i></button></a>";
                                            break;

                                        case '2':
                                            $estado = "Autorizada  <button class='btn btn-success' style='margin-left:10px; border-style:none; padding: .3rem .6rem;'' ><i class='bi bi-check2-square'></i></button>";
                                            $accion = "<a href='mostrarSolicitud.php?numSolicitud=$encabezado[ID]&tipoU=2' class='href'><button class='btn btn-warning' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-eye'></i></button></a>";
                                            break;

                                        case '3':
                                            $estado = "Enviada  <button class='btn btn-primary' style='margin-left:30px; border-style:none; padding: .3rem .6rem;'' ><i class='fa fa-paper-plane'></i></button>";
                                            $accion = "<a href='mostrarSolicitud.php?numSolicitud=$encabezado[ID]&tipoU=2' class='href'><button class='btn btn-warning' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-eye'></i></button></a>";
                                            break;

                                        case '4':
                                            $valorIdBorrador =$encabezado['ID'] - 1;
                                            $estado = "Borrador  <button class='btn btn-danger' style='margin-left:25px; border-style:none; padding: .3rem .6rem;'' ><i class='fa-solid fa-eraser'></i></button>";
                                            $accion = "<a href='mostrarSolicitud.php?numSolicitud=$encabezado[ID]&tipoU=2' class='href'><button class='btn btn-warning' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-eye'></i></button></a>";
                                            break;
                                        
                                        default:
                                            break;
                                    }
                                    $usuario = str_replace("_"," ",$encabezado['USUARIO_EMISOR']);
                                    echo "<tr>";
                                    echo "<td style='text-align:center;'>".$encabezado['FECHA']->format('d/m/Y')."</td>";
                                    echo "<td style='text-align:center;'>".$encabezado['ID']."</td>";

                                    foreach ($locales as $key => $local) {
                                        if($local['NRO_SUCURSAL'] == $encabezado['NUM_SUC']){
                                            echo "<td style='text-align:center;'>".$local['DESC_SUCURSAL']."</td>";
                                        }
                                    }
                                    echo "<td style='text-align:center;'>".$usuario."</td>";
                                    echo "<td style='text-align:center;'>".$encabezado['cantidad_total_articulos']."</td>";
                                    echo "<td style='text-align:center;'>".$estado."</td>";
                                    echo "<td style='text-align:center;'>".$encabezado['UPDATED_AT']->format('d/m/Y H:i')."</td>";
                                    echo "<td style='text-align:center;'>$accion</td>";
                                    echo "</tr>";

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
        <!-- <script src="js/controlFallas.js"></script> -->
    </body>

</html>
<script>
  $('#tablaArticulos').DataTable({
        "bLengthChange": true,
        "language": {
                    "lengthMenu": "mostrar _MENU_ registros",
                    "info":           "Mostrando registros del _START_ al _END_ de un total de  _TOTAL_ registros",
                    "paginate": {
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },

        },
    
        
        "bInfo": true,
        "aaSorting": false,
        'columnDefs': [
            {
                "targets": "_all", 
                "className": "text-center",
                "sortable": false,
         
            },
        ],
        "oLanguage": {
    
            "sSearch": "Busqueda rapida:",
            "sSearchPlaceholder" : "Sobre cualquier campo"
            
    
        },
    });


</script>
<!-- <script src="js/gastosTesoreria.js"></script> -->

