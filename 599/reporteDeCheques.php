<?php
include_once "controller/traerEquis.php";
// if($_POST){
//     $campo = $_POST['inputBuscar'] == "" ? '%' : $_POST['inputBuscar'];
//     $a=traerTodos($_POST['inputBuscar']);
// }else{
    
//     $a = traerTodos();
// }

// $totalCobrado = 0;
// $totalDeuda = 0;
// $newArray = [];
// $remitoActual = "";
// foreach ($a as $remito => $value) {
//     // var_dump($a[$remito]['COD_PRO_CL']);
//     if($remitoActual != $value['COD_PRO_CL']){

//         foreach ($a as $val ) {

//             if($value['COD_PRO_CL'] == $val['COD_PRO_CL'] ){

//                 if ($val['CHEQUEADO'] == 1 ){
//                     $totalCobrado = $totalCobrado + $val['IMPORTE_TO'];
//                 }else{
//                     $totalDeuda = $totalDeuda + $val['IMPORTE_TO'];
//                 }
                
//             }
    
//         }
//         $remitoActual = $value['COD_PRO_CL'];
//         $newArray[$remito]['totalCobrado']=$totalCobrado;
//         $newArray[$remito]['totalDeuda']=$totalDeuda;
//         $newArray[$remito]['nombreCliente']=$value['RAZON_SOCI'];
//         $newArray[$remito]['codCliente']=$value['COD_PRO_CL'];
//     }

// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Composicion De Remitos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" type="text/css" href="select2/select2.min.css">

    <script src="select2/select2.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    </link>

</head>

<body>

    <div class="alert alert-secondary">
        <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
            <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h5>Reporte de Cheques</h5></div>
                <div class="card card-1">
                    
                    <div class="row" style="margin-left:50px">
                        <h2><strong><i class="bi bi-bank" style="margin-right:20px;font-size:50px"></i>Reporte de Cheques recibidos</strong></h2>

                    </div>
                    <form action="#" method="POST">
                        <div class="row" style="margin-top:10px">
                            <div class="col-3" style="margin-left:50px">Desde : <input type="date" style="width:150px; height:45px" id='desde' name="desde">  Hasta <input type="date" name="hasta" style="width:150px; height:45px"></div>
                            <div class="col-3">Estado :  
                                <select name="selectEstado" id="selectEstado" style="width:150px; height:45px">
                                    <option value="aRendir">A Rendir</option>
                                    <option value="rendido">Rendido</option>
                                </select>
                                <button class="btn btn-primary btn-submit" value="" style="height:50px;margin-left:2px">filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                            </div>
                            <div class="col">  Busqueda Rapida: <input type="text" style="height:45px" placeholder="Sobre Cualquier Campo..." name="inputBuscar"></div>

                            <!-- <div class="col-3" id="busqRapida">
                                <label id="textBusqueda">Busqueda rapida:</label>
                                <input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control form-control-sm"></input>  
                            </div> -->
                        </div>
                    </form>

                    <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                        <thead class="thead-dark">
                            <tr>
                                <th style="position: sticky; top: 0; z-index: 10; width: 600px;text-align:center" class="col-4">NRO. INTERNO</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">CLIENTE</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">BANCO EMISOR</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">IMPORTE</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">NRO. CHEQUE</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">FECHA COBRO</th>
                                <th style="position: sticky; top: 0; z-index: 10;text-align:center">RENDIDO</th>

                            </tr>
                        </thead>
                        <tbody>
                                <?php 
                                    // foreach($newArray as $b){
                                        // echo "<tr>";
                                        // echo "<td style='text-align:center' attr-codClient='".$b['codCliente']."'>".$b['nombreCliente']."</td>";
                                        // echo "<td style='text-align:center' id='colDeuda'>".$b['totalDeuda']."</td>";
                                        // echo "<td style='text-align:center'>".$b['totalCobrado']."</td>";
                                        // echo "<td style='text-align:center'><button class ='btn-success' onclick='verDetalle(this)'><i class='bi bi-pencil-square'></i></button></td>";
                                        // echo "</tr>";
                                    // }
                                // ?> 
                      
                        </tbody>
                
            </div>
        </div>



        </table>

    <script src="js/functions.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
<script src="js/jquery.table2excel.js"></script>

<script>

        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
                buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
            });
        // const totalDeudas = document.querySelectorAll("#colDeuda");
        // let valorTotalDeudas = 0
        // totalDeudas.forEach(e => {
        //     valorTotalDeudas = valorTotalDeudas + parseInt(e.textContent);
        });

        // document.querySelector("#sumValorDeuda").value = valorTotalDeudas;
        // });

        // const verDetalle = (rem) =>{
            

        //     codClient = rem.parentElement.parentElement.childNodes[0].getAttribute("attr-codClient") ;
       
        //     window.location.href = "remitosPendientesDeCobro.php?codClient="+codClient;
        // }


</script>
