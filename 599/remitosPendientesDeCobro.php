<?php
    session_start();
    include_once "controller/traerEquis.php";
    $detalleDeRemito = traerDetalle($_GET['codClient']);

    ?>

    <!DOCTYPE html>
    <html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv='cache-control' content='no-cache'>
    <meta http-equiv='expires' content='0'>
    <meta http-equiv='pragma' content='no-cache'>
    <title>Remitos pendientes de cobro</title>

    <?php 
        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/css/css.php';
    ?>


    </link>

    </head>

    <body>

    <div class="alert">
        
        <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
            <div class="wrapper wrapper--w680">
                <div style="color:white; text-align:center">
                    <h6>Remitos pendientes de cobro</h6>
                </div>
                <div class="card card-1">
                    
                    <div class="row" style="margin-left:1rem">
                        <h3><i class="bi bi-cash" style="margin-right:20px;font-size:50px"></i>Remitos pendientes de cobro</h3>
                    </div>
                    <div id="user" hidden><?= $_GET['userName'] ?></div>
                    <div class="row" style="margin-left:50px;margin-top">
                        <div><label>Total deuda: </label><input class="form-control" type="text" id="totalDeuda" readonly></div>
                        <div id="divImporteAabonar" style="margin-left: 1rem"><label>Importe a abonar:</label><input class="form-control" type="text" id="importeAbonar" readonly></div>
                        <div id="divImporteConDescuento" style="margin-left: 1rem"> <label>Importe con Descuento:</label><input class="form-control" type="text" id="importeConDescuento" readonly></div>
                        <div style="margin-left: 1rem"><label>% Descuento:</label><input class="form-control" type="text" id="descuento" onchange="calcularDescuento()" placeholder="Colocar números enteros"></div>
                        <div style="margin-top: 2rem; margin-left: 0.5rem"><button class="btn btn-primary" value="" id="btnConfirmar">Confirmar <i class="bi bi-check-square"></i></button></div>
                        <div style="margin-top: 2rem; margin-left: 1rem"><button class="btn btn-success btn_exportar" id="btnExport"><i class="fa fa-file-excel-o"></i>Exportar<i class="bi bi-file-earmark-excel"></i></button></div>
                    </div>

                    <div class="row" style="margin-left: 1rem;margin-top:1rem">
                        <div hidden id="codClient"><?= $_GET['codClient'] ?></div>
                        <div style="width: 500px">
                            <h5><strong>Cliente : <p style="color: red;"><?= $detalleDeRemito[0]['RAZON_SOCI'] ?></p></strong></h5>
                        </div>
                    </div>


                    <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                        <thead class="thead-dark">
                            <tr>
                                <th style="position: sticky; top: 0; z-index: 10; width: 200px;text-align:center" class="col-1">FECHA</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">CLIENTE</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">REMITO</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">MONTO</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($detalleDeRemito as $value) {
                                if ($value['CHEQUEADO'] == 1) {
                                    continue;
                                }
                                echo "<tr>";
                                echo "<td style='text-align:center' >" . $value['FECHA_MOV']->format('Y-m-d') . "</td>";
                                echo "<td style='text-align:center' >" . $value['RAZON_SOCI'] . "</td>";
                                echo "<td style='text-align:center' >" . $value['N_COMP'] . "</td>";
                                echo "<td style='text-align:center' id='monto'>" . $value['IMPORTE_TO'] . "</td>";
                                echo "<td style='text-align:center;width:20px' ><input type='checkbox' style='width:20px' onchange='checkMonto()' ></td>";
                                echo "</tr>";
                            }

                            ?>

                        </tbody>
                    </table>

                </div>
            </div>

            <?php 
                require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/js/js.php';
            ?>

            <script src="js/jquery.table2excel.js"></script>
            

    </body>

    </html>

