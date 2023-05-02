<?php
include_once "controller/traerEquis.php";
$detalleDeRemito = traerDetalle($_GET['codClient']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remitos pendientes de cobro</title>
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
            <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h5>Remitos pendientes de cobro</h5></div>
                <div class="card card-1">
                    
                    <div class="row" style="margin-left:50px">

                        <h2><i class="bi bi-cash" style="margin-right:20px;font-size:50px"></i>Remitos pendientes de cobro</h2>

                    </div>

                    <div class="row" style="margin-left:50px;margin-top">
                        <div class="col-2">    <h4>Total deuda : <input type="text" style="width:150px;  height:60px" id="totalDeuda" readonly></h4></div>

                        <div class="col-2" id="divImporteAabonar">    <h4>Importe a abonar : <input type="text" style="width:150px; height:60px" id="importeAbonar" readonly></h4></div>
                        <div class="col-3" id="divImporteConDescuento" > <h4>importe con Descuento : <input type="text" style="height:60px" id="importeConDescuento" readonly></h4></div>

                        <!-- <div class="col">    <h4>% Descuento : <input type="text" style="height:60px" id="descuento"> </h4></div> -->
                        <div class="col-3" >    <h4>% Descuento : <input type="text" style="height:60px" id="descuento" onchange="calcularDescuento()" placeholder="Ej. 10%"> <button class="btn btn-primary"  style="height:60px;margin-left:20px"  value="" id="btnConfirmar">Confirmar <i class="bi bi-check-square"></i></button></h4></div>
                        <!-- <div class="col">    <h4><button class="btn btn-success btn_exportar" id="btnExport" style=" height:45px"><i class="fa fa-file-excel-o"></i> Exportar<i class="bi bi-file-earmark-excel"></i></button></h4></div> -->
                    </div>
                    <div class="row" >
                        <div class="col-3"></div>
                        <div class="col"></div>
                        <div class="col-3" style="text-align:right;margin-right:310px">    <h4><button class="btn btn-success btn_exportar" id="btnExport" style=" height:45px"><i class="fa fa-file-excel-o"></i> Exportar<i class="bi bi-file-earmark-excel"></i></button></h4></div>
                        
                    </div>
                    <div hidden id="codClient"><?=$_GET['codClient'] ?></div>
                    <div class="row" style="margin-left:50px;margin-top">
                        <div style="width: 500px"><h5 ><strong>Cliente : <p style="color: red;"><?= $detalleDeRemito[0]['RAZON_SOCI'] ?></p></strong></h5></div>
                    </div>
                    

                    <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                        <thead class="thead-dark">
                            <tr>
                                <th style="position: sticky; top: 0; z-index: 10; width: 200px;text-align:center" class="col-1">FECHA</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">REMITO</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">MONTO</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach ($detalleDeRemito as $value) {
                                    if ($value['CHEQUEADO'] == 1){
                                        continue;
                                    }
                                    echo "<tr>";
                                    echo "<td style='text-align:center' >".$value['FECHA_MOV']->format('Y-m-d')."</td>";
                                    echo "<td style='text-align:center' >".$value['N_COMP']."</td>";
                                    echo "<td style='text-align:center' id='monto'>".$value['IMPORTE_TO']."</td>";
                                    echo "<td style='text-align:center;width:20px' ><input type='checkbox' style='width:20px' onchange='checkMonto()' ></td>";
                                    echo "</tr>";
                                }
                        
                            ?>
                      
                        </tbody>
                    </table>
                
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
    <script src="js/remitosPendientesDeCobro.js"></script>
    <script src="js/jquery.table2excel.js"></script>

</body>

</html>

<script>
    
    const todosLosMontos = document.querySelectorAll('#monto');
    
    
    $(document).ready(function() {
            parseNumber();
            $('#myTable').DataTable({
                responsive: true,
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });



            let total = 0;

            todosLosMontos.forEach(e => {

                total = total + parseInt(e.getAttribute("attr-realValue"))
                
            }); 
            document.querySelector("#totalDeuda").value = "$ "+total.toLocaleString('de-De', {
            style: 'decimal',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0
        });
            
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
