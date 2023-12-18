<!DOCTYPE html>
    <html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reporte De Asistencias </title>
       
        <?php 
            require_once "assets/css/css.php";
            
        ?>
    <style>
        
    </style>

    </head>

    <body>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h6>Reporte De Asistencias </h6></div>
                    <div class="card card-1" style="height: 800px;">
                        <div id="username" hidden><?= $_SESSION['username'] ?></div>
                        <div class="row" style="margin-left:50px">

                            <h3><i class="bi bi-clock-history" style="margin-right: 20px; font-size: 50px;"></i>Reporte De Asistencias </h3>
                        </div>
                        <div id="boxLoading"></div>


                        <div class="row" style="margin-left:65px;margin-top:20px"  >
                            <div class="table-responsive" id="tableIndex" style="max-height: 500px; overflow-y: auto;">
                              
                                    <table class="table table-hover table-condensed table-striped text-center" style="width: 70%;border: solid 1px;margin-left:10%" cellspacing="0" data-page-length="100">
                                        <thead class="thead-dark" style="font-size: small;">
                                            <th scope="col" style="width: 6%">Reporte De Asistencias </th>
                                        
                                        </thead>
                                        <tbody id="tableVb" >
                                            <tr>
                                                <td style="white-space: nowrap;">
                                                    Desde:
                                                    <input type="date" id="desde" name="desde" style="margin-right:20px" value="<?=  date("Y-m-d", strtotime( '-1 days' ) ) ?>">
                                                    Hasta:
                                                    <input type="date" id="hasta" name="hasta" style="margin-right:20px" value="<?= date("Y-m-d") ?>">
                                                    Usuario:
                                                    <input type="text" id="usuario" name="usuario" style="margin-right:20px" >
                                                    Sucursal:
                                                    <input type="text" id="sucursal" name="sucursal" style="margin-right:20px" >

                                                    <button class="btn btn-primary" type="button" onclick="buscar()">Buscar <i class="bi bi-search"></i></button>
                                                    <button class="btn btn-secondary" type="button" onclick="exportar()">Exportar <i class="bi bi-file-earmark-excel"></i></button>
                                                    
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table id="tablaReporte" >
                                                        <thead>
                                                            <tr>
                                                                <th style="width:10%">FECHA.REG</th>
                                                                <th style="width:10%">SUCURSAL</th>
                                                                <th style="width:5%" >LEGAJO</th>
                                                                <th style="width:15%">APELLIDO/NOMBRE</th>
                                                                <th style="width:5%">ENTRADA</th>
                                                                <th style="width:5%">SALIDA</th>
                                                                <th style="width:5%">AUSENTE</th>
                                                                <th style="width:15%">LLEGA TARDE</th>
                                                                <th style="width:15%">TOTAL TRABAJADO</th>
                                                    
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

        <?php 
            require_once "assets/js/js.php"
        ?>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>
        


        
    </body>

    </html>

