<?php
include_once "controller/traerEquis.php";
$a = traerDetalle($_GET['codCliente']);
$cliente = $a[0]['RAZON_SOCI'];

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
    
    <link rel="stylesheet" type="text/css" href="select2/select2.min.css">

    <script src="select2/select2.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
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
                        <div class="col">    <h4><button class="btn btn-primary" id="btnExport" style=" height:45px" onclick="confirmarCobro()"><i class="fa fa-file-excel-o"></i> Confirmar Cobro<i class="bi bi-check-square"></i></i></button></h4></div>
                    </div>
                    <div class="row" style="margin-left:50px;margin-top">
                        <div class="col-3"><strong>Cliente : </strong><?= $cliente?></div>
                    </div>
                    <div class="row" style="margin-left:60px;margin-top">
                        <div class="col-3"><strong>Monto a Cobrar : <input type="text" style="width:150px; height:50px;text-align:center"  value ="$<?= $_GET['importeAbonar'] ?>" readonly id="montoACobrar" attr-valorReal="<?= $_GET['importeAbonar'] ?>"></strong></div>
                    </div>
                    <div class="row" style="margin-left:60px;margin-top:20px">
                        <div class="col-3"><strong>Cobro Efectivo : <input type="text" style="width:150px; height:50px;text-align:center" id="cobroEfectivo" onchange="calcularSaldo()"></strong></div>
                    </div>
                    <div class="row" style="margin-left:60px;margin-top:20px">
                        <div class="col-3"><strong>Cobro Cheque : <input type="text" style="width:150px; height:50px;text-align:center" id="cobroCheque" readonly></strong><button class="btn btn-success" value="" style="margin-left:20px" data-toggle="modal" data-target="#exampleModal" onclick='completarModal()' ><i class="bi bi-plus-square"></i></button></div>
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
                                    <th style="position: sticky; top: 0; z-index: 10; width: 200px;text-align:center" class="col-1">NRO.INTERNO</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">BANCO EMISOR</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">MONTO</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">NRO.CHEQUE</th>
                                    <th style="position: sticky; top: 0; z-index: 10;text-align:center">FECHA COBRO</th>
                                </tr>
                            </thead>
                            <tbody id="tableCheques">
                    
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar<i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-primary"  data-toggle="modal" data-target="#exampleModale">Cargar <i class="bi bi-check-square"></i></button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade  bd-example-modal-lg " id="exampleModale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Informacion del Cheque</h5>
                </div>
                <div class="modal-body">
                    <div style="margin-top:10px">
                        <div class="row">
                            <div class="col" style="margin-bottom:5px">Numero de Interno : <input type="text" id="numeroDeInterno"> </div>
                            <div class="col" style="margin-bottom:5px">Banco :<input type="text" style="margin-left:48px" id="banco"> </div>
                        </div>
                        <div class="row">
                            <div class="col" style="margin-bottom:5px">Monto : <input type="text" style="margin-left:87px" id="chequeMonto"> </div>
                            <div class="col" style="margin-bottom:5px">Nro Cheque : <input type="text" id="numeroDeCheque"></div>
                        </div>
                        <div class="row">
                            <div class="col">Fecha cobro : <input type="text" style="margin-left:48px" value=" <?= date('Y-m-d H\:i\:s.v'); ?>" readonly id="fechaCobro"></div>
                        </div>
                            
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar<i class="bi bi-x"></i></button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="registrarCheque()">Confirmar  <i class="bi bi-bank"></i></button>
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

</body>

</html>
<script>




        $(document).ready(function() {
           

        });
        const completarModal=()=>{
            traerCheques();
            let saldo = document.querySelector("#modalSaldo");
            let montoACobrar = document.querySelector("#montoACobrar").getAttribute("attr-valorReal"); 
            let cobroEfectivo = document.querySelector("#cobroEfectivo").value;
            saldo.value = montoACobrar - cobroEfectivo;

        }

        const  confirmarCobro = () =>{
            let cobroEfectivo = document.querySelector("#cobroEfectivo").value;
            let cobroCheque = document.querySelector("#cobroCheque").value;
            let montoACobrar = document.querySelector("#montoACobrar").getAttribute("attr-valorReal");
            let saldoCobrar = document.querySelector("#saldoCobrar").getAttribute("attr-valorReal");


            if(parseInt(montoACobrar) != parseInt(saldoCobrar)){
                Swal.fire({
                    icon: 'warning',
                    title: 'El importe cobrado no coincide con el importe a cobrar ¿Desea continuar?',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: 'Cancelar',
                    }).then((result) => {
                        / Read more about isConfirmed, isDenied below /
                        if (result.isConfirmed) {
                            Swal.fire('Saved!', '', 'success')
                        } else if (result.isDenied) {
                            Swal.fire('El proceso fue cancelado', '', 'info')
                    }
                })
            }
            
            if(cobroEfectivo < 1 && cobroCheque < 1 ){
                Swal.fire({
                    icon: 'error',
                    title: 'Error de carga',
                    text: 'Debe cargar el importe a cobrar!'
                })
            }   
            if(saldoCobrar == 0){

                Swal.fire({
                    icon: 'warning',
                    title: '¿Desea registrar el cobro?',
                    showDenyButton: true,
                    confirmButtonText: 'Aceptar',
                    denyButtonText: 'Cancelar',
                }).then((result) => {
                    / Read more about isConfirmed, isDenied below /
                    if (result.isConfirmed) {
                        let remitosEnCadena = sessionStorage.getItem("Remitos");
                        var arrayDeRemitos = remitosEnCadena.split("-");

                        $.ajax({
                            url: "controller/ejecutarCobroController.php",
                            type: "POST",
                            data: {
                                remitos: arrayDeRemitos,
                            },
                            success: function(data) {
                                Swal.fire('Saved!', '', 'success')
                                
                            }
                        });



                    } else if (result.isDenied) {
                        Swal.fire('El cobro fue cancelado', '', 'info')
                    }
                })
            }


        }
        const calcularSaldo = ()=>{
            let montoACobrar = document.querySelector("#montoACobrar").getAttribute("attr-valorReal");
            let saldoCobrar = document.querySelector("#saldoCobrar");
            let cobroEfectivo = document.querySelector("#cobroEfectivo").value;
            let cobroCheque = document.querySelector("#cobroCheque").value;

            if(cobroEfectivo < 1){
                cobroEfectivo = 0;
            }
            if(cobroCheque < 1){
                cobroCheque = 0;
            }

            let total = parseInt(montoACobrar) - (parseInt(cobroEfectivo) + parseInt(cobroCheque));
            
            saldoCobrar.value =`$ ${total} `
            saldoCobrar.setAttribute("attr-valorReal", total);

        }

        const registrarCheque =  () =>{
            // let data ='[{"id":2,"cod_client":"MASPO2","num_interno":1,"num_cheque":"44","banco":"3","monto":"2.00","fecha_cheque":{"date":"2023-04-12 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"fecha_carga":{"date":"2023-04-12 11:20:48.173000","timezone_type":3,"timezone":"Europe\/Berlin"}},{"id":1,"cod_client":"MASPO2","num_interno":1,"num_cheque":"4","banco":"3","monto":"2.00","fecha_cheque":{"date":"2023-04-12 00:00:00.000000","timezone_type":3,"timezone":"Europe\/Berlin"},"fecha_carga":{"date":"2023-04-12 11:15:31.647000","timezone_type":3,"timezone":"Europe\/Berlin"}}]'
            // data = JSON.parse(data)
            // console.log(data)
            // return 1
            let fechaCobro = document.querySelector("#fechaCobro").value;
            let banco = document.querySelector("#banco").value;
            let numeroDeCheque = document.querySelector("#numeroDeCheque").value;
            let numeroDeInterno = document.querySelector("#numeroDeInterno").value;
            let chequeMonto = document.querySelector("#chequeMonto").value;
            let codClient = '<?= $_GET['codCliente'] ?>';



                    $.ajax({

                        url: "controller/registrarChequeController.php",
                        type: "POST",
                        data: {
                            fechaCobro: fechaCobro,
                            banco: banco,
                            numeroDeCheque: numeroDeCheque,
                            numeroDeInterno: numeroDeInterno,
                            chequeMonto: chequeMonto,
                            codClient: codClient
                        },
                        success: function(data) {
                            traerCheques();
                           
                        }
                    });
        }


        const traerCheques = () =>{
            let cobroCheque =  document.querySelector("#cobroCheque");

            $.ajax({

                url: "controller/traerChequeController.php",
                type: "POST",
                data: {
                codClient: '<?= $_GET['codCliente'] ?>'
                },
                success: function(result) {
                    let tableBody = document.querySelector("#tableCheques");
                    tableBody.innerHTML = "";
                    resultArray = JSON.parse(result)
                    resultArray.forEach(element => {
                        
                        let tr = document.createElement('tr');

                        let tdfechaCobro = document.createElement('td');
                        let tdBanco = document.createElement('td');
                        let tdNumeroDeCheque = document.createElement('td');
                        let tdNumeroDeInterno = document.createElement('td');
                        let tdChequeMonto   = document.createElement('td');

                        tdfechaCobro.innerHTML = element['fecha_cheque']['date'].split(" ")[0];
                        tdBanco.innerHTML = element['banco'];
                        tdNumeroDeCheque.innerHTML = element['num_cheque'];
                        tdNumeroDeInterno.innerHTML = element['num_interno'];
                        tdChequeMonto.innerHTML = element['monto'];
                        tdChequeMonto.setAttribute("id", "tdChequeMonto");

                        tr.appendChild(tdNumeroDeInterno);
                        tr.appendChild(tdBanco);
                        tr.appendChild(tdChequeMonto);
                        tr.appendChild(tdNumeroDeCheque);
                        tr.appendChild(tdfechaCobro);

                        tableBody.appendChild(tr);
                        
                    });

                    let montos = document.querySelectorAll("#tdChequeMonto");
                    console.log(montos)
                    let total = 0;
                    montos.forEach(element => {
                        total += parseInt(element.innerHTML);
                    });
                    console.log(total);
                    cobroCheque.value = total
                    calcularSaldo()

                }
            });
        }

</script>
