<?php

    session_start();

    $userName = isset($_GET['userName']) ? $_GET['userName'] : "";
    include_once "controller/traerEquis.php";
    include_once "controller/ejecutarSpController.php";

    cargarEquisTable();
    
    $todosLosRemitos = traerTodos();


    $newArray = [];
    $remitoActual = "";
    $valores = traerEfectivoCheque();

    $totalEfectivo = 0;
    $totalCheque = 0;

    foreach ($valores as  $value) {
        $totalEfectivo = $totalEfectivo + $value['importe_efectivo'];
        $totalCheque = $totalCheque + $value['importe_cheque'];
    } 

    foreach ($todosLosRemitos as $remito => $value) {
        $totalDeuda = 0;
        $totalCobrado = 0;

    
        
        if($remitoActual != $value['COD_PRO_CL']){

            foreach ($todosLosRemitos as $val ) {

                if($value['COD_PRO_CL'] == $val['COD_PRO_CL'] ){

                    if ($val['CHEQUEADO'] == 1 ){
                        if($val['importe_total'] != null){
                            $totalCobrado = $totalCobrado + $val['importe_total'];
                        }else{
                            $totalCobrado = $totalCobrado + $val['IMPORTE_TO'];
                        }
                    }else{
                        $totalDeuda = $totalDeuda + $val['IMPORTE_TO'];
                    }
                    
                }
            }
            
            $remitoActual = $value['COD_PRO_CL'];
            $newArray[$remito]['totalCobrado']=$totalCobrado;
            $newArray[$remito]['totalDeuda']=$totalDeuda;
            $newArray[$remito]['nombreCliente']=$value['RAZON_SOCI'];
            $newArray[$remito]['codCliente']=$value['COD_PRO_CL'];
        }

    }


    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Composicion De Remitos</title>
        <?php
            require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/css/css.php'; 
        ?>

        </link>

    </head>

    <body>

        <div class="alert">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                    <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h6>Composición de Remitos</h6></div>
                    <div class="card card-1 mb-2">
                        
                        <div style="margin-left: 2rem"><h3><i class="bi bi-cash"></i> Composición de saldo a cobrar</h3></div>
                                <div  id="user" hidden><?=$userName; ?></div>
                                <div class="row" style="margin-left: 20rem">
                                        <div><h5>Total deuda : <input class="form-control" type="text" id='sumValorDeuda' readonly></h5></div>
                                    <div style="margin-left: 2rem; margin-top: 2rem;"><h5>Cobranza:
                                        <div style="margin-left: 7rem;">
                                            <div style="margin-top: -3.8rem;"><label>Efectivo</label><input class="form-control" style="width: 10rem"type="text" placeholder="Efectivo" id="efectivo" value="<?= '$'.number_format($totalEfectivo, 0, ',', '.')?>" readonly></div>
                                            <div style="margin-top: -4.4rem; margin-left: 10.5rem;"><label>Cheque</label><input class="form-control" style="width: 10rem" type="text" placeholder="Cheques" id="cheques" value="<?= '$'.number_format($totalCheque, 0, ',', '.')?>" readonly></h5></div>
                                            <div style="margin-top: 1.7rem; margin-left: 1rem;"><button class="btn btn-success btn_exportar" id="btnExport"><i class="fa fa-file-excel-o"></i> Exportar<i class="bi bi-file-earmark-excel"></i></button></div>
                                        </div>
                                    </div>
                                </div>
                        
                        </div>
                    
                
                        <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="position: sticky; top: 0; z-index: 10; width: 600px;text-align:center" class="col-4">CLIENTE</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">A COBRAR</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">COBRADO</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php 
                                        foreach($newArray as $b){
                                            echo "<tr>";
                                            echo "<td style='text-align:center' attr-codClient='".$b['codCliente']."'>".$b['nombreCliente']."</td>";
                                            echo "<td style='text-align:center;' id='colDeuda' attr-realValue=".$b['totalDeuda'].">".$b['totalDeuda']."</td>";
                                            echo "<td style='text-align:center' id='colCobrado'>".$b['totalCobrado']."</td>";
                                            if($b['totalDeuda'] > 0){

                                                echo "<td style='text-align:center'><button class ='btn-success' onclick='verDetalle(this)'><i class='bi bi-pencil-square'></i></button></td>";
                                            }else{
                                                echo "<td style='text-align:center'></td>";

                                            }
                                            echo "</tr>";
                                        }
                                    ?> 
                        
                            </tbody>
                    </div>
            </div>
        </div>
        </table>


    <?php 
        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/assets/js/js.php';
    ?>

    </body>

    </html>
    <script src="js/jquery.table2excel.js"></script>



<!-- <?php
// }
?> -->