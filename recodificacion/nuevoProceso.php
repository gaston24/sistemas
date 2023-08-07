<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestion de Conceptos</title>
       
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    </head>

    <body>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h6>Nuevo proceso de recodificación</h6></div>
                    <div class="card card-1" style="height: 850px;">
                        <div id="username" hidden><?= $_SESSION['username'] ?></div>
                        <div class="row" style="margin-left:50px">

                            <h3><i class="bi bi-file-earmark-plus" style="margin-right: 20px; font-size: 50px;"></i>Nuevo proceso de recodificación</h3>
                        </div>

                        <div class="row" style="margin-left:65px;margin-top:20px" id="ingresoNumeroTarea">
                            <div class="table-responsive" id="tableIndex">
                                <table class="table table-hover table-condensed table-striped text-center" style="width: 30%;;border: solid 1px;margin-left:30%" cellspacing="0" data-page-length="100">
                                    <thead class="thead-dark" style="font-size: small;">
                                        <th scope="col" style="width: 6%">RECODIFICAR OUTLET</th>
                                    
                                    </thead>

                                    <tbody id="tableVb" style="font-size: small;">
                                        <tr>
                                            <td>Ingrese Nro. de tarea <input type="text" style="margin-left:10px;margin-right:10px;height:35px;width:90px" id="nroDeTarea"><button onclick="listarDetalle()" class ="btn btn-primary" style="height:35px;width:90px;margin-bottom:5px">CARGAR</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row" style="margin-left:65px;margin-top:20px" id="detallePorNumeroDeTarea" hidden >
                            <div class="table-responsive" id="tableIndex">
                                <table class="table table-hover table-condensed table-striped text-center" style="width: 60%;border: solid 1px;margin-left:20%" cellspacing="0" data-page-length="100">
                                    <thead class="thead-dark" style="font-size: small;">
                                        <th scope="col" style="width: 6%">RECODIFICAR OUTLET</th>
                                    
                                    </thead>

                                    <tbody id="tableVb" style="font-size: small;">
                                        <tr>
                                            <td>
                                                Nro. de tarea 
                                                <input type="text" style="margin-left:10px;margin-right:10px;height:35px;width:90px" id="inputDetalleNroTarea">
                                                <button onclick="procesar()" style="height:35px;width:90px;background-color:orange" >PROCESAR</button>
                                                <button style="margin-left:20px" onclick="aplicarPorcentajeMasivo('0.8')">20</button>
                                                <button onclick="aplicarPorcentajeMasivo('0.7')">30</button>
                                                <button onclick="aplicarPorcentajeMasivo('0.6')">40</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th style="width:20%">CODIGO</th>
                                                            <th style="width:20%">DESCRIPCION</th>
                                                            <th style="width:5%" >Cant</th>
                                                            <th style="width:5%">Unico</th>
                                                            <th style="width:5%">-10%</th>
                                                            <th style="width:5%">-20%</th>
                                                            <th style="width:5%">-30%</th>
                                                            <th style="width:5%">-40%</th>
                                                            <th style="width:20%">RECODIFICA A</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="detalleBody">
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

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
        <script src="js/nuevoProceso.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        
    </body>

    </html>
<?php
require_once "modalNuevoProceso.php";
?>
