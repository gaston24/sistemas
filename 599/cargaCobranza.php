<?php
include_once "controller/traerEquis.php";
$detalleRemito = traerDetalle($_GET['codCliente']);
$cliente = $detalleRemito[0]['RAZON_SOCI'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de Cobranza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    

    </link>

</head>

<body>

    <div class="alert alert-secondary">
        <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
            <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h5>Carga de Cobranza</h5></div>
                <div class="card card-1">
                    
                    <div class="row" style="margin-left:50px">

                        <h2><i class="bi bi-cash" style="margin-right:20px;font-size:50px"></i>Carga de Cobranza</h2>

                    </div>

                    <div class="row" style="margin-left:50px;margin-top">
                       <div class="col-2"></div>
                        <div class="col">    <h4><button class="btn btn-primary" id="btnExport" style=" height:45px" onclick="confirmarCobro('<?= $_GET['codCliente'] ?>')"><i class="fa fa-file-excel-o"></i> Confirmar Cobro<i class="bi bi-check-square"></i></i></button></h4></div>
                    </div>
                    <div class="row" style="margin-left:50px;margin-top">
                        <div class="col-3" id="cliente" attr-cliente="<?= $cliente?>"><strong>Cliente : </strong ><?= $cliente?></div>
                    </div>
                    <div class="row" style="margin-left:60px;margin-top">
                        <div class="col-3"><strong>Monto a Cobrar : <input type="text" style="width:150px; height:50px;text-align:center"  value ="$<?= $_GET['importeAbonar'] ?>" readonly id="montoACobrar" attr-valorReal="<?= $_GET['importeAbonar'] ?>"></strong></div>
                    </div>
                    <div class="row" style="margin-left:60px;margin-top:20px">
                        <div class="col-3"><strong>Cobro Efectivo : <input type="text" style="width:150px; height:50px;text-align:center" id="cobroEfectivo" onchange="setearValores()"></strong></div>
                    </div>
                    <div class="row" style="margin-left:60px;margin-top:20px">
                        <div class="col-3"><strong>Cobro Cheque : <input type="text" style="width:150px; height:50px;text-align:center" id="cobroCheque" readonly></strong><button class="btn btn-success" value="" style="margin-left:20px" data-toggle="modal" data-target="#exampleModal" onclick="completarModal('<?= $_GET['codCliente'] ?>')"><i class="bi bi-plus-square"></i></button></div>
                        <div hidden id="idCheque"></div>
                    </div>
                    <div class="row" style="margin-left:60px;margin-top:20px">
                        <div class="col-3"><strong>Saldo a Cobrar : <input type="text" style="width:150px; height:50px;text-align:center"  readonly id="saldoCobrar"></strong></div>
                    </div>
         

            </div>
        </div>



        </table>
    <div class="modal fade  bd-example-modal-lg " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Carga de Cheques</h5>
                </div>
                <div class="modal-body">
                    <div class="row" style="margin-left:10px">Saldo a cobrar : <input type="text" style="width:100px;height:30px;margin-left:10px" readonly id="modalSaldo"></div>

                    <div style="margin-top:10px">
                            
                        <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center" class="col-1">NRO.INTERNO</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">BANCO EMISOR</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">MONTO</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">NRO.CHEQUE</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">FECHA COBRO</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center" ></th>
                                </tr>
                            </thead>
                            <tbody id="tableCheques">
                    
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar<i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-primary"  data-dismiss="modal" data-toggle="modal" onclick="registrarCheque('<?= $_GET['codCliente'] ?>')">Cargar <i class="bi bi-check-square"></i></button>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/cargaCobranza.js"></script>
</body>

</html>
<script>

        $(document).ready(function() {
            let monto = document.querySelector("#montoACobrar");
            
            let newMonto = monto.getAttribute("attr-valorreal")
            newMonto = parseFloat(newMonto)
            
            newMonto = newMonto.toLocaleString('de-De', {
                style: 'decimal',
                maximumFractionDigits: 2,
                minimumFractionDigits: 2
            }); 
        
            monto.value ="$" +newMonto
        });
 
</script>