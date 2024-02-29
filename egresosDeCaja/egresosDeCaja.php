<?php 
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/sistemas/assets/js/js.php';
    if(!isset($_SESSION['username']) ){
        header("Location:login.php");
    }

    require_once 'class/Egreso.php';

   $egreso = new Egreso();
   $nroSucurs = $_SESSION['numsuc'];
   
if(isset($_GET['desde']) &&$_GET['desde'] != "" ){
    $desde = $_GET['desde'];
}else{
    $desde = date("Y-m-d");
}
if(isset($_GET['hasta']) &&$_GET['hasta'] != "" ){
    $hasta = $_GET['hasta'];
}else{
    $hasta = date('Y-m-d',strtotime("+1 days"));
}

   $data = $egreso->traerGastos($desde, $hasta, $nroSucurs);
    

   
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Egresos de caja</title>
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
            thead {
                position: sticky;
                top: 0;
            }
        </style>

    </head>

    <body>
        
      
        <input type="file" name="archivos[]" id="archivos" multiple accept=".pdf, .jpg, .png" style="display: none;" />
        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:70%"></div>
        <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Egresos de caja</h6></div>
                    <div class="card card-1">
                        <div id="periodo" hidden><?= $periodo ?></div>
                        <div class="row" style="margin-left:50px; margin-top:30px">
                        
                            <h3><strong><i class="bi bi-cash" style="margin-right:20px;font-size:40px"></i>Egresos de caja</strong></h3>

                        </div>
                        <form action="#">
                          
                            <div style="margin-bottom:20px">

                                <div class="row" style="margin-top:10px">
                                        <?php
                               
                                        ?>
                                    <div style="margin-left:90px">Desde: <input type="date" style="width:145px; height:35px"   name="desde"  value="<?php echo $desde ?>"  ></div>
                                    <div style="margin-left:30px">Hasta : <input type="date" style="width:145px; height:35px"  name="hasta" value="<?php echo $hasta ?>"></div>
                                    <button class="btn btn-primary btn-submit" style="height:35px;margin-left:20px;width:110px" onclick= "">Filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                    

                                    <div style="margin-left:50%;">   
                                        <button class="btn btn-success" type="button" style="height:35px;width:110px" onclick="guardar()">Guardar <i class="bi bi-save" style=""></i></button>
                                    </div>

                                </div>

                            </div>
                        </form>
            
                        <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" style="">
                                <tr>
                                    <th style="text-align:center;width: 5%;" >FECHA</th>
                                    <th style="text-align:center;width: 5%;" >COD_COMP</th>
                                    <th style="text-align:center;width: 7%;" >N_COMP</th>
                                    <th style="text-align:center;width: 7%;">COD_CTA</th>
                                    <th style="text-align:center;width: 15%;" >DESC_CUENTA</th>
                                    <th style="text-align:center;width: 5%;" >MONTO</th>
                                    <th style="text-align:center;width: 5%;" >USUARIO</th>
                                    <th style="text-align:center;width: 15%;" >LEYENDA</th>
                                    <th style="text-align:center;width: 10%;" >IMAGENES</th>
                                </tr>
                            </thead>
                            <tbody>
                    
                 

                            <?php

                                foreach ($data as $key => $gasto) {   
                                     
                                    
                            ?>
                        
                                <tr id="bodyTable">
                                    <td><?= $gasto['FECHA']->format("Y-m-d") ?></td>
                                    <td style="text-align:center"><?= $gasto['COD_COMP'] ?></td>
                                    <td style="text-align:center"><?= $gasto['N_COMP'] ?></td>
                                    <td style="text-align:center"><?= $gasto['COD_CTA'] ?></td>
                                    <td style="text-align:center"><?= $gasto['DESC_CUENTA'] ?></td>
                                    <?php 
                                        if($gasto['MONTO'] < 0){

                                            $monto = $gasto['MONTO'] * -1;
                                            $valor = "- $". number_format($monto, 0, '.','.');
                                            echo "<td style='text-align:center'>$valor</td>";

                                        }else{

                                            $valor = "$". number_format($gasto['MONTO'], 0, '.','.');
                                            echo "<td style='text-align:center'>$valor</td>";

                                        }
                                    ?>
                                    
                                    <td style="text-align:center"><?= $gasto['USUARIO'] ?></td>
                                    <td style="text-align:center"><?= $gasto['LEYENDA'] ?></td>
                                    <td style="text-align:center;">
                                    <?php 
                                        if($gasto['guardado'] != 1){
                                    ?>
                                            <button class="btn btn-primary" type="button" style="margin-left: 5px; padding:.3rem .5rem;" onclick="elegirImagen(this)">
                                                <i class="bi bi-upload"></i> 
                                            </button>
                                    <?php
                                        }
                                    ?>

                                        <button class="btn btn-warning" style="margin-left:5px; padding:.3rem .5rem;"  onclick="mostrarImagen(this)">
                                            <i class="bi bi-eye" style="color:white"></i>
                                        </button>
                                        
                                    <?php 
                                        if($gasto['guardado'] != 1){
                                    ?>
                                            <button class="btn btn-danger" style="margin-left:5px; padding:.3rem .5rem;" onclick="eliminarArchivo(this)"><i class="bi bi-trash"></i></button>
                                    <?php
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
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
        <!-- <link rel="stylesheet" type="text/css" href="assets/select2/select2.min.css"> -->
        <!-- <script src="assets/select2/select2.min.js"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
        <script src="js/egresoCaja.js"></script>
        <script src="https://cdn.datatables.net/fixedheader/3.1.9/js/dataTables.fixedHeader.min.js"></script>

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

