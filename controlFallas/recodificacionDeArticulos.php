<?php 
    session_start();
    if(!isset($_SESSION['username']) || ($_SESSION['usuarioUy'] == 1)){
        header("Location:login.php");
    }

    require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';

    $nroSucurs = $_SESSION['numsuc'];
    $recodificacion = new Recodificacion();
    $numSolicitud = null;

    if(isset($_GET['numSolicitud'])){
        $numSolicitud = $_GET['numSolicitud'];
    }


    $data = $recodificacion->traerRecodificacionDeArticulos($numSolicitud);


    $arrayRemitos = [];
    foreach ($data as $key => $value) {

        if($value['N_COMP'] != null && !in_array($value['N_COMP'], $arrayRemitos)){

                $arrayRemitos[] = $value['N_COMP'];

        }
        
    }
    if(count($arrayRemitos) > 0){
     
        $remitos = $recodificacion->traerRemitosEnElLocal($arrayRemitos);
        
    }else{

        $remitos = [];
    }
   
   
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Recodificacion de Articulos</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        </link>
        <style>
            /* input[type='search'] {
                margin-right:80px
            } */
            .dataTables_filter {
            float: left !important;
            margin-left: 5rem; /* Ajusta el margen según sea necesario */
            margin-top: 1rem;
            margin-bottom: 1rem;
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
            .right-div {
                margin-left: 90%;
            }

            .loading {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url('images/g0R9.gif') 50% 50% no-repeat rgb(0, 0, 0);
                opacity: .8;
            }
            
        </style>
    </head>

    <body>
        
      
        <input type="file" name="archivos[]" id="archivos" multiple accept=".pdf, .jpg, .png" style="display: none;" />
        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>
        <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-1 font-robo">
                <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Recodificacion de Articulos</h6></div>
                    <div class="card card-1">
                        <div id="boxLoading"></div>
                      
                        <div class="row" style="margin-left:50px; margin-top:20px">
                            <h3><strong><i class="bi bi-pencil-square" style="margin-right:20px;font-size:40px"></i>Recodificacion de Articulos </strong></h3>
                        </div>
                        <div class="right-div">
                            <button class="btn btn-success"  onclick="ajustar()">Ajustar <i class="bi bi-pencil-square"></i></button>
                        </div>
            
                        <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" style="">
                                <tr>
                                    <th style="text-align:center;width:10%" >FECHA SOLICITUD</th>
                                    <th style="text-align:center;width:10%" >REMITO</th>
                                    <th style="text-align:center;width:10%" >CODIGO</th>
                                    <th style="text-align:center;width:20%">DESCRIPCION</th>
                                    <th style="text-align:center;width:20%" >CANTIDAD</th>
                                    <th style="text-align:center;width:20%" >NUEVO CODIGO</th>
                                    <th style="text-align:center;width:3%" ></th>
                                    <th hidden></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    foreach ($data as $key => $remito) {
                                        if(in_array($remito['N_COMP'], $remitos)){
                              
                                ?>
                                    <tr>
                                        <td  style="text-align:center;"><?= $remito['FECHA']->format("Y-m-d") ?></td>
                                        <td  style="text-align:center;"><?= $remito['N_COMP'] ?></td>
                                        <td  style="text-align:center;"><?= $remito['COD_ARTICU'] ?></td>
                                        <td  style="text-align:center;"><?= $remito['DESCRIPCION'] ?></td>
                                        <td  style="text-align:center;"><?= $remito['CANTIDAD'] ?></td>
                                        <td  style="text-align:center;"><?= $remito['NUEVO_CODIGO'] ?></td>
                                        <td  style="text-align:center;"><input type="checkbox" style="height: 16px;width: 16px;" checked hidden></td>
                                        <td hidden><?= $remito['ID_ENC'] ?></td>
                                    </tr>
                                    
                                <?php 
                                    }
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
        <script src="js/recodificacionDeArticulos.js"></script>
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
         "sDom": '<"search-container"f>t<"bottom"p>',
    });

</script>
<!-- <script src="js/gastosTesoreria.js"></script> -->

