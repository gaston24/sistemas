<?php
// session_start(); 
// if(!isset($_GET['userName'])){
// 	header("Location:http://192.168.0.13:8000/");
// }else{
    include_once "controller/traerEquis.php";
    include_once "controller/ejecutarSpController.php";

    $inputBuscar = isset($_GET['inputBuscar']) ? $_GET['inputBuscar'] : '%' ;
    $selectEstado = isset($_GET['selectEstado']) ? $_GET['selectEstado'] : '%' ;
    $selectTalonario = isset($_GET['selectTalonario']) ?  $_GET['selectTalonario'] : 'X';

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

    cargarEquisTable();

   $remitos = listarRemitos($desde, $hasta, $selectEstado, $inputBuscar,$selectTalonario);

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Consulta Detalle de Remitos</title>
        <?php 
                
            require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/css/css.php';
        
        ?>

    </head>

    <body>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h5>Detalles de Remitos</h5></div>
                    <div class="card card-1">
                        
                        <div class="row" style="margin-left:50px">
                            <h2><strong><i class="bi bi-card-text" style="margin-right:20px;font-size:50px"></i>Consulta Detalle de Remitos</strong></h2>

                        </div>
                        <form action="#" method="GET">
                            <div class="row" style="margin-top:10px">

                                <div class="col-3" style="margin-left:50px">Desde : <input type="date" style="width:150px; height:45px" id='desde' name="desde" value="<?php echo $desde; ?>">  Hasta <input type="date" name="hasta" style="width:150px; height:45px" value="<?= $hasta ?>"></div>
                                <div class="col-2">Estado :  
                                    <select name="selectEstado" id="selectEstado" style="width:150px; height:45px">
                                        <option value="%" disabled selected><?= isset($_GET['selectEstado']) ?  $_GET['selectEstado'] : 'Todos'?></option>
                                        <option value="DEBE">Debe</option>
                                        <option value="COBRADO">Cobrado</option>
                                        <option value="RENDIDO">Rendido</option>
                                        <option value="ANULADO">Anulado</option>
                                        <option value="%">Todos</option>
                                    </select>  
                                    <!-- <button class="btn btn-primary btn-submit" value="" style="height:50px;margin-left:2px">filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button> -->
                                </div>
                                <div class="col--3">Talonario :  
                                     <select name="selectTalonario" id="selectTalonario" style="width:150px; height:45px">
                                        <option value="X">Todos</option>                                     
                                        <option value="X00599">599</option>                                     
                                        <option value="X00600">600</option>                                     
                                        <option value="X00601">601</option>                                     
                                        <option value="X00602">602</option>                                     
                                        <option value="X00603">603</option>                                     
                                        <option value="X00604">604</option>                                     
                                    </select>  
                                    <button class="btn btn-primary btn-submit" value="" style="height:50px;margin-left:2px">filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                                </div>
                                <!-- <div class="col-3">  Busqueda Rapida: <input type="text" style="height:45px" placeholder="Sobre Cualquier Campo..." name="inputBuscar"></div> -->
                                <div class="col" style="text-align:right;margin-right:80px">    <h4><button class="btn btn-success btn_exportar" id="btnExport" style=" height:45px"><i class="fa fa-file-excel-o"></i> Exportar<i class="bi bi-file-earmark-excel"></i></button></h4></div>

                            </div>
                        </form>

                        <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark">
                                <tr>
                                    <th  class="col-1">FECHA</th>
                                    <th >CLIENTE</th>
                                    <th >REMITO</th>
                                    <th >MONTO</th>
                                    <th >UNIDADES</th>
                                    <th >NRO.GUIA</th>
                                    <th >VENDEDOR</th>
                                    <th ></th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                foreach ($remitos as $remito) {

                                ?>
                                <tr>
                                    <td><?= $remito['FECHA_MOV']->format("Y-m-d") ?></td>
                                    <td><?= $remito['RAZON_SOCI'] ?></td>
                                    <td><?= $remito['N_COMP'] ?></td>
                                    <td>$ <?php echo number_format($remito['IMPORTE_TO'], 0, ',', '.') ?></td>
                                    <td><?= (int)$remito['CANT_ART'] ?></td>
                                    <td><?= $remito['GC_GDT_NUM_GUIA'] ?></td>
                                    <td><?= $remito['NOMBRE_VEN'] ?></td>
                                
                                    <?php switch($remito['estado']){
                                        case "ANULADO":
                                            echo "<td ><h3><i class='bi bi-x-square-fill' style='color:#6f42c1'  data-toggle='tooltip' title='Anulado'></i></h3></td>";
                                            break;
                                        case "COBRADO":
                                            echo "<td><h3><i class='bi bi-plus-square-fill' style='color:#ffc107'  data-toggle='tooltip' title='Cobrado'></i></h3></td>";
                                            break;
                                        case "RENDIDO":
                                            echo "<td ><h3><i class='bi bi-check-square-fill' style='color:#28a745'  data-toggle='tooltip' title='Rendido'></i></h3></td>";
                                            break;
                                            
                                        case "DEBE":
                                            echo "<td  ><h3><i class='bi bi-dash-square-fill' style='color:#dc3545'  data-toggle='tooltip' title='Debe'></i></h3></td>";
                                            break;
                                    }
                                    ?>
                          
                                </tr>
                                <?php
                       
                                }?>
                        
                            </tbody>
            
                        </table>
                    </div>
                </div>

            </div>
        </div>
        <?php 
            require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/js/js.php';
        ?>
    </body>

</html>


<script>

        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
                buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
            });

        });
        $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
            });
         $("#btnExport").click(function() {

            $('input[type=number]').each(function(){
                this.setAttribute('value',$(this).val());
            });

            $("table").table2excel({
                // exclude CSS class
                exclude: ".noExl",
                name: "Worksheet Name",
                filename: "Remitos", //do not include extension
                fileext: ".xls", // file extension
            });
        });



</script>
<!-- <?php
// }
?> -->