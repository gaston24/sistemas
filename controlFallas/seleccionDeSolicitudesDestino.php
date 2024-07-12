<?php 
    session_start();
    if(!isset($_SESSION['username']) || ($_SESSION['usuarioUy'] == 1)){
        header("Location:login.php");
    }

    require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';
 
    $desde = (isset($_GET['desde'])) ? $_GET['desde'] : date('Y-m-d', strtotime('-1 month'));
    $hasta = (isset($_GET['hasta'])) ? $_GET['hasta'] : date('Y-m-d');
 

    $estado = (isset($_GET['estado'])) ? $_GET['estado'] : '%';

    $recodificacion = new Recodificacion();
    $nroSucurs = $_SESSION['numsuc'];
   

    $result = $recodificacion->traerSolicitudes(null, $desde, $hasta, $estado, 1, $nroSucurs);


    foreach ($result as $key => &$value) {

        if($value['ESTADO'] == 6){

            if($estado == 5){

                unset($result[$key]);

            }

            if($estado == 3){
                    
                unset($result[$key]);

            }

            continue;

        }

        $existe = 0;

        if($value['N_COMP'] != null){

            $existe = $recodificacion->comprobarIngresada($value['N_COMP']);
        }
        
        
        if($existe == 1){
          

            $value['ESTADO'] = 5;

            if($estado == 3){
                    
                unset($result[$key]);

            }

        }else{

            if($estado == 5 && $value['ESTADO'] != 5){

                unset($result[$key]);

            }


        }
    }

    $locales = $recodificacion->traerLocales(0);


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Solicitud de Recodificacion</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        
        </link>
        <style>
            input[type='search'] {
                margin-right:45px
            }
            .dataTables_length{
                margin-left:50px
            }
            .dataTables_info{
                margin-left:50px
            }
            #tablaArticulos_paginate{
                margin-right:42px 
            }
            #barcode {
                display: flex;
                flex-wrap: wrap;
             
            }
            #barcode > div {
                margin-left: 15px;
                margin-bottom: 10px; /* Espacio entre las filas */
                text-align: center; /* Centra el contenido dentro de cada div */
                box-sizing: border-box; /* Incluye el padding y border en el ancho y alto */
            }

        </style>
    </head>

    <body>
    <div id="barcode" style="" hidden>
    </div>

        <div id="bodyCompleto">
            <input type="file" name="archivos[]" id="archivos" multiple accept=".pdf, .jpg, .png" style="display: none;" />
            <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>
            <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

            <div class="alert alert-secondary">
                <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                    <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Seleccion de Solicitud</h6></div>
                        <div class="card card-1">
                            <div id="periodo" hidden><?= $periodo ?></div>
                            <div class="row" style="margin-left:50px; margin-top:30px">
                                <h3><strong><i class="bi bi-list-task" style="margin-right:20px;font-size:40px"></i>Lista de Solicitudes </strong></h3>
                            </div>
                            <form class="form-inline" action="#">

                                <div style="margin-bottom:20px">

                                    <div class="row" style="margin-top:10px">

                                        <div style="margin-left:90px">Desde : <input type="date" class="form-control form-control-sm" id="desde" name="desde" value="<?=  $desde ?>"></div>
                                        <div style="margin-left:30px">Hasta: <input type="date" class="form-control form-control-sm" id="hasta"  name="hasta" value="<?=  $hasta ?>"></div>
                                        <div style="margin-left:30px">Estado: 
                                            <select name="estado" id="estado" class="form-control form-control-sm">

                                                <option value="%" <?= (isset($_GET['estado']) && $_GET['estado'] == '%') ? 'selected' : '' ?>>Todos</option>
                                                <option value="3" <?= (isset($_GET['estado']) && $_GET['estado'] == '3') ? 'selected' : '' ?>>Enviada</option>
                                                <option value="5" <?= (isset($_GET['estado']) && $_GET['estado'] == '5') ? 'selected' : '' ?>>Ingresada</option>
                                                <option value="6" <?= (isset($_GET['estado']) && $_GET['estado'] == '6') ? 'selected' : '' ?>>Ajustada</option>

                                            </select>
                                        </div>
                                        <button class="btn btn-primary btn-submit ml-2" style="margin-top: -0.15em;">Filtrar <i class="bi bi-funnel-fill" style="color:white"></i></button>
                                        <a href="http://192.168.0.143:8080/sistemas/index.php" class="btn btn-secondary" style="height:38px; width:170px; margin-left: 2rem;">Volver Al Menú <i class="bi bi-arrow-counterclockwise"></i></a>
                                    </div>
                        

                                </div>

                            </form>

                            <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px" cellspacing="0" data-page-length="100">
                                <thead class="thead-dark" style="">
                                    <tr>
                                        <th style="text-align:center;width:10%" >FECHA</th>
                                        <th style="text-align:center;width:10%" >NUMERO</th>
                                        <th style="text-align:center;width:10%" >SUCURSAL</th>
                                        <th style="text-align:center;width:20%" >EMISOR</th>
                                        <th style="text-align:center;width:10%">UNIDADES</th>
                                        <th style="text-align:center;width:15%" >ESTADO</th>
                                        <th style="text-align:center;width:10%" >ULT.ESTADO</th>
                                        <th style="text-align:center;width:10%" >REMITO</th>
                                        <th style="text-align:center;width:10%" >ACCION</th>
                                        <th style="text-align:center;width:10%" >ETIQUETAS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    
                                    foreach ($result as $key => $encabezado) {
                                        if($encabezado['ESTADO'] == '4'){
                                            continue;
                                        }
                                        switch ($encabezado['ESTADO']) {
                                            case '1':
                                                $estado = "Solicitada  <button class='btn btn-success' style='background-color:purple;margin-left:18px; border-style:none; padding: .3rem .6rem;'' ><i class='bi bi-box-arrow-in-up'></i></button>";
                                                $accion = "<a href='mostrarSolicitud.php?numSolicitud=$encabezado[ID]&tipoU=1&destino=1' class='href'><button class='btn btn-warning' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-eye'></i></button></a>";                       
                                                break;

                                            case '2':
                                                $estado = "Autorizada  <button class='btn btn-success' style='margin-left:10px; border-style:none; padding: .3rem .6rem;'' ><i class='bi bi-check2-square'></i></button>";
                                                $accion = "<a href='mostrarSolicitud.php?numSolicitud=$encabezado[ID]&tipoU=1&destino=1' class='href'><button class='btn btn-warning' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-eye'></i></button></a>";
                                                break;

                                            case '3':
                                                $estado = "Enviada  <button class='btn btn-primary' style='margin-left:30px; border-style:none; padding: .3rem .6rem;'' ><i class='fa fa-paper-plane'></i></button>";
                                                $accion = "<a href='mostrarSolicitudDestino.php?numSolicitud=$encabezado[ID]&estado=Enviada' class='href'><button class='btn btn-warning' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-eye'></i></button></a>";
                                                break;

                                            
                                            case '5':
                                                $estado = "Ingresada <button class='btn btn-success' style='background-color:#17a2b8;margin-left:18px; border-style:none; padding: .3rem .6rem;'' ><i class='bi bi-save'></i></button>";
                                                $accion = "<a href='recodificacionDeArticulos.php?numSolicitud=$encabezado[ID]' class='href'><button class='btn btn-primary' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-pencil-square'></i></button></a>";
                                                break;
                                            
                                            
                                            case '6':
                                                $estado = "Ajustada <button class='btn btn-success' style='background-color:#fd7e14;margin-left:18px; border-style:none; padding: .3rem .6rem;'' ><i class='bi bi-recycle'></i></button>";
                                                $accion = "<a href='mostrarSolicitudDestino.php?numSolicitud=$encabezado[ID]&estado=Ajustada' class='href'><button class='btn btn-warning' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-eye'></i></button></a>";
                                                break;
                                            
                                                
                                            default:
                                                break;
                                        }
                                        $usuario = str_replace("_"," ",$encabezado['USUARIO_EMISOR']);
                                        echo "<tr>";
                                        echo "<td style='text-align:center;'>".$encabezado['FECHA']->format('d/m/Y')."</td>";
                                        echo "<td style='text-align:center;'>".$encabezado['ID']."</td>";
                                        foreach ($locales as $key => $local) {
                                            
                                            if($local['NRO_SUCURSAL'] == $encabezado['NUM_SUC']){
                                                echo "<td style='text-align:center;'>".$local['DESC_SUCURSAL']."</td>";
                                            }
                                        }
                                        echo "<td style='text-align:center;'>".$usuario."</td>";
                                        echo "<td style='text-align:center;'>".$encabezado['cantidad_total_articulos']."</td>";
                                        echo "<td style='text-align:center;'>".$estado."</td>";
                                        echo "<td style='text-align:center;'>".$encabezado['UPDATED_AT']->format('d/m/Y H:i')."</td>";
                                        echo "<td style='text-align:center;'>".$encabezado['N_COMP']."</td>";
                                        echo "<td style='text-align:center;'>$accion</td>";
                                        echo "<td style='text-align:center;'><button class='btn btn-secondary' onclick='generateBarcode(this)' style='border-style:none; padding: .3rem .6rem;'><i class='bi bi-printer-fill'></i></button></td>";
                                        echo "</tr>";

                                    }
                                    ?>
                                
                                </tbody>
                
                            </table>
                        

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
            <link rel="stylesheet" type="text/css" href="assets/select2/select2.min.css">
            <script src="assets/select2/select2.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
            <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
            <!-- <script src="js/controlFallas.js"></script> -->
            <!-- <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script> -->
             <script src="js/jquery-barcode.js"></script>
            
    </body>

</html>
<script>
  $('#tablaArticulos').DataTable({
        "bLengthChange": true,
        "language": {
                    "lengthMenu": "mostrar _MENU_ registros",
                    "info":           "Mostrando registros del _START_ al _END_ de un total de  _TOTAL_ registros",
                    "paginate": {
                        "next":       "Siguiente",
                        "previous":   "Anterior"
                    },

        },
    
        
        "bInfo": true,
        "aaSorting": false,
        'columnDefs': [
            {
                "targets": "_all", 
                "className": "text-center",
                "sortable": false,
         
            },
        ],
        "oLanguage": {
    
            "sSearch": "Busqueda rapida:",
            "sSearchPlaceholder" : "Sobre cualquier campo"
            
    
        },
    });
   
  
    function generateBarcode(div) {


        // Obtener el valor del código de barras desde el input
        let id = div.parentElement.parentElement.children[1].innerText;
        let nroSucursal = document.getElementById('nroSucursal').innerText;

     

        
        $.ajax({
            type: "POST",
            url: "Controller/RecodificacionController.php?accion=traerArticulosEnSolicitud",
            data: {id: id},
            success: function (response) {
                let articulos = JSON.parse(response);

                document.querySelector('#barcode').innerHTML = '';

                if(articulos.length == 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'No hay articulos en la solicitud!',
                    })
                    return;
                }

                articulos.forEach((articulo, index) => {
                
                    let value = articulo['NUEVO_CODIGO'] ;
                    if(articulo['DESTINO'] != nroSucursal){
                        return;
                    }
                    if(articulo['NUEVO_CODIGO'] == null || articulo['NUEVO_CODIGO'] == ''){
                        return;
                    }



                        
                    // Configuración del código de barras
                    var settings = {
                    format: 'CODE93',
                    lineColor: '#000000',
                    width: 4,   
                    height: 150, 
                    displayValue: true
                };


                    // Generar el código de barras en el elemento con id 'barcode'
                    let divCodigo = document.createElement('div');

                    divCodigo.id = 'barcode'+index;
            
                    
                    document.getElementById('barcode').appendChild(divCodigo);
                    
                    
                    $('#barcode'+index).barcode(value, 'code93', settings);
                    let lastChild = document.querySelector('#barcode'+index).querySelector(`div:last-child`);
                    let nuevoDivDescripcion = document.createElement('div');
                    nuevoDivDescripcion.innerHTML = articulo['DESCRIPCION'];
                    nuevoDivDescripcion.style.textAlign = 'center';
                    nuevoDivDescripcion.style.fontSize = '12px';

                    let nuevoDivPrecio = document.createElement('div');
                    nuevoDivPrecio.innerHTML = '$ ' + articulo['PRECIO'];
                    nuevoDivPrecio.style.textAlign = 'center';
                    nuevoDivPrecio.style.fontSize = '12px';

                    document.querySelector('#barcode'+index).appendChild(nuevoDivDescripcion)
                    
                    document.querySelector('#barcode'+index).appendChild(nuevoDivPrecio)
                });

                document.querySelector('#barcode').hidden = false;

                document.getElementById('bodyCompleto').hidden = true;
              
                print();

    
                document.querySelector('#barcode').hidden = true;

    
                document.getElementById('bodyCompleto').hidden = false ;
                
            }
        });
    
}


</script>


