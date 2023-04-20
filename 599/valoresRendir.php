<?php 
include_once "controller/traerEquis.php";
$cheques = traerCheques();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valores A Rendir</title>
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
            <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h5>Valores A Rendir</h5></div>
                <div class="card card-1">
                    
                    <div class="row" style="margin-left:50px">

                        <h2><i class="bi bi-cash" style="margin-right:20px;font-size:50px"></i>Valores A Rendir</h2>

                    </div>

                    <div class="row" style="margin-left:50px;margin-top">
                        <div class="col-3">    <h4>Total efectivo : <input type="text" style="width:150px; height:45px" id="totalEfectivo" value="0,00"></h4></div>

                        <div class="col">    <h4>Total Cheques : <input type="text" style="width:150px; height:45px" id="totalCheque" value="0,00"></h4></div>
                      
                        <div class="col">    <h4><button class="btn btn-success btn_exportar" id="btnExport" style=" height:45px;width:150px;" onclick="rendir()"><i class="fa fa-file-excel-o"></i> Rendir<i class="bi bi-file-earmark-excel"></i></button></h4></div>
                    </div>

                    <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                        <thead class="thead-dark">
                            <tr style="text-align:center">
                                <th style=" width: 600px;text-align:center" class="col-1">CLIENTE</th>
                                <th style="text-align:center">EFECTIVO</th>
                                <th style="text-align:center">CHEQUES</th>
                                <th style="width: 100px;text-align:center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cheques as $key => $value) {
                            ?>
                                <tr style="text-align:center">
                                    <td><?php echo $value['nombre_cliente'] ?></td>
                                    <td id="importeEfectivo"><?php echo $value['importe_efectivo'] ?></td>
                                    <td id="importeCheque"><?php echo $value['importe_cheque'] ?></td>
                                    <td id="idCobro" hidden><?php echo $value['id'] ?></td>  
                                    <td><input type="checkbox" name="a" id="checkCalcularTotales" style="width:30px;height:30px;" onchange="calcularTotales(this)"></td>
                                </tr>

                            <?php } ?>
                      
                        </tbody>
                
            </div>
        </div>



        </table>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="js/valoresArendir.js"></script>
    

</body>

</html>
