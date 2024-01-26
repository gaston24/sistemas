<?php 
require_once 'class/fichaje.php';

$fichaje = new Fichaje;

$usuarios = $fichaje->traerUsuarios();

$locales = $fichaje->traerLocales();



?>
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
 

        #tableOne {
            border-collapse: collapse;
            margin: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

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
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="table-responsive" id="tableIndex" style="overflow-y: auto;max-height:700px">
                                        
                                            <table class="table table-hover  text-center" style="width: 90%;border: solid 1px;" id="tableOne" cellspacing="0" data-page-length="100">
                                                <thead class="thead-dark" style="font-size: small;">
                                                    <th scope="col" style="width: 6%">Reporte De Asistencias </th>                                       
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td >
                                                            Desde:
                                                            <input type="date" id="desde" name="desde" style="margin-right:20px;width:10%" value="<?=  date("Y-m-d", strtotime( '-1 days' ) ) ?>">
                                                            Hasta:
                                                            <input type="date" id="hasta" name="hasta" style="margin-right:20px;width:10%" value="<?= date("Y-m-d") ?>">
                                                            Usuario:

                                                            <select name="usuario" id="usuario" class="usuario">
                                                                <option value="%">TODOS</option>
                                                                <?php 
                                                                    foreach ($usuarios as $usuario) {
                                                                        echo "<option value='$usuario[NRO_LEGAJO]'>$usuario[NRO_LEGAJO] - $usuario[APELLIDO_Y_NOMBRE]</option>";
                                                                    }

                                                                ?>
                                                            </select>
                                                            Sucursal:

                                                            <select name="sucursal" id="sucursal" class="sucursal">
                                                                <option value="%">TODOS</option>
                                                                <?php 
                                                                    foreach ($locales as $local) {
                                                                        echo "<option value='$local[NRO_SUCURS]'>$local[NRO_SUCURS] - $local[DESCRIPCION]</option>";
                                                                    }

                                                                ?>
                                                            </select>
                                                         
                                                            <button class="btn btn-primary" type="button" onclick="buscar()" id="btn-buscar">Buscar <i class="bi bi-search"></i></button>
                                                            <button class="btn btn-secondary" type="button" onclick="exportar()" id="btn-export">Exportar <i class="bi bi-file-earmark-excel"></i></button>
                                                          
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <table id="tablaReporte" style="width: 100%;" >
                                                                <thead style="font-size:13px">
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
                                                                <tbody id="detalleBody" style="font-size:13px">
                                                                
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
                                                <!-- Contenido principal -->
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
        
         <script>
            $('.usuario').select2({});
            $('.sucursal').select2({});
      
     
                
                document.querySelectorAll('.select2-container')[0].style.width = '20%'; 
                document.querySelectorAll('.select2-container')[1].style.width = '10%'; 
              
                function applyStylesBasedOnScreenWidth() {
                    var screenWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

                    if (screenWidth < 1200) {
                    

                
                        document.querySelector("#hasta").style.marginRight = '0px';
                        document.querySelector("#desde").style.marginRight = '0px';
                        document.querySelector("#btn-buscar").style.fontSize = '10px';
                        document.querySelector("#btn-export").style.fontSize = '10px'; 

                    
                    } else {
                        
                       
                        document.querySelector("#hasta").style.marginRight = '20px';
                        document.querySelector("#desde").style.marginRight = '20px';
                        document.querySelector("#btn-buscar").style.fontSize = '14px';
                        document.querySelector("#btn-export").style.fontSize = '14px'; 
                      
                    }
                }
                applyStylesBasedOnScreenWidth();
               
                window.addEventListener('resize', applyStylesBasedOnScreenWidth())
         </script>                                           

        
    </body>

    </html>

