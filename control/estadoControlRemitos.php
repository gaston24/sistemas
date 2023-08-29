<?php

require_once 'class/control.php';

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

$control = new Remito();

$estado = (isset($_GET['selectEstado'])) ? $_GET['selectEstado'] : "%" ;

$data = $control->traerEstadoControlRemitos($desde, $hasta, $estado);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Estado control de remitos</title>

        <!-- INCLUDE CSS FILES -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">


    </head>

    <body>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h6>Estado Control de Remitos</h6></div>
                    <div class="card card-1">
                        
                        <div class="row" style="margin-left:50px">
                            <h3><strong><i class="bi bi-cash-stack" style="margin-right:20px;font-size:50px"></i>Estado Control de Remitos</strong></h3>
                        </div>

                        <form action="#" method="get" style="margin-bottom:20px">

                            <div class="row" style="margin-top:10px">

                                <div style="margin-left:70px;width:250px">Desde: <input type="date" style="width:170px; height:40px" id='desde' name="desde" value=""></div>
                                
                                <div style="margin-right:20px">Hasta: <input type="date" style="width:150px; height:45px" id='hasta' name="hasta" value="<?php echo $hasta; ?>"></div>
                                
                                <div >Sucursal :  

                                    <select name="selectEstado" id="selectEstado" style="width:150px; height:45px">

                                            <option value="%">TODOS</option>
                                            <option value="REMITIDO">REMITIDO</option>
                                            <option value="DESPACHADO">DESPACHADO</option>
                                            <option value="INGRESADO">INGRESADO</option>
                                            <option value="CONTROLADO">CONTROLADO</option>
            
                                    </select>

                                </div>

                                <div>   
                                    <button class="btn btn-primary btn-submit" id="btnSubmit" value="" >filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                                </div>

                            </div>

                        </form>
                        
                        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>

            
                        <table class="table table-striped table-bordered" id="myTable" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" >
                                <tr style="text-align:center">

                                    <th > FECHA </th>
                                    <th > REMITO</th>
                                    <th > NRO.SUCURSAL.ORIGEN</th>
                                    <th > SUCURSAL.ORIGEN</th>
                                    <th > NRO.SUC.DESTINO</th>
                                    <th > SUCURSAL DESTINO </th>
                                    <th > GUIA </th>
                                    <th > FECHA GUIA </th>
                                    <th > FECHA INGRESO </th>
                                    <th > FECHA CONTROL </th>
                                    <th ></th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php 
                                    foreach ($data as $key => $remito) {
                                        var_dump($remito->FECHA,1);
                                        var_dump($remito['FECHA'],2);
                                        die();
                                ?>
                                <tr>
                                    <td><?= $remito['FECHA']->format("Y-m-d") ?></td>
                                    <td><?= $remito['N_COMP'] ?></td>
                                    <td><?= $remito['NRO_SUCURS'] ?></td>
                                    <td><?= $remito['DESC_SUC_ORIG'] ?></td>
                                    <td><?= $remito['SUC_DESTIN'] ?></td>
                                    <td><?= $remito['DES_SUC_DESTIN'] ?></td>
                                    <td><?= $remito['NRO_GUIA'] ?></td>
                                    <td><?= $remito['FECHA_GUIA'] ?></td>
                                    <td><?= $remito['FECHA_INGRESO']?></td>
                                    <td><?= $remito['FECHA_CONTROL'] ?></td>
                                    <td></td>
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
<link rel="stylesheet" type="text/css" href="assets/select2/select2.min.css">
<script src="assets/select2/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>


</script>
