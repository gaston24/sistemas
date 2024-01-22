<?php 
    session_start();
    if(!isset($_SESSION['username']) || ($_SESSION['usuarioUy'] == 1)){
        header("Location:login.php");
    }

   require_once __DIR__.'/../class/remito.php';
   require_once '../ajustes/class/Articulo.php';
   require_once '../ajustes/class/Ajuste.php';
   require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';
   
   if(!isset($_SESSION['numsuc'])){
        header('Location: ../login.php');
   }

   $nroSucurs = $_SESSION['numsuc'];

   $recodificacion = new Recodificacion();
   $numSolicitud = $recodificacion->traerNumSolicitud();

   $data = new Remito();
   $usuarios = $data->listarUsuarios($nroSucurs);
    


   $maestroArticulos = new Articulo();
   $todosLosArticulos = $maestroArticulos->traerMaestroArticulo();
   if(isset($_GET['numSolicitud'])){
        $numSolicitud = [];
        $numSolicitud[0]['ultimo_id'] = $_GET['numSolicitud'];
    }
    
   $borradorEnc = $recodificacion->buscarBorradorEnc($numSolicitud[0]['ultimo_id']+1,4);
   if(count($borradorEnc) > 0){
       $borradorDet = $recodificacion->buscarBorradorDet($numSolicitud[0]['ultimo_id']+1);
       
   }
   $esBorrador = false;
   if(count($borradorEnc) > 0){
       $esBorrador = true;
    }
    $outlet = 0;
    if($_SESSION['esOutlet'] == 1)
    {
        $outlet = 1;
    }

   
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

            .select2-dropdown.select2-dropdown--above {

                width:300px;
            }
        </style>

    </head>

    <body>
        
      
        <input type="file" name="archivos[]" id="archivos" multiple accept=".pdf, .jpg, .png" style="display: none;" />
        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>
        <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

        <div class="alert alert-secondary">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w880"><div style="color:white; text-align:center"><h6>Solicitud De Recodificacion</h6></div>
                    <div class="card card-1">
                        <div id="periodo" hidden><?= $periodo ?></div>
                        <div id="outlet" hidden><?= $outlet ?></div>
                        <div class="row" style="margin-left:50px; margin-top:30px">

                        
                            <h3><strong><i class="bi bi-pencil-square" style="margin-right:20px;font-size:40px"></i>Carga solicitud de recodificacion </strong></h3>


                        </div>

                        <div style="margin-bottom:20px">

                            <div class="row" style="margin-top:10px">

                                <div style="margin-left:90px">Fecha Solicitud: <input type="date" style="width:145px; height:35px" value =<?= ($borradorEnc) ? $borradorEnc[0]['FECHA']->format("Y-m-d") : date("Y-m-d") ?> id="fecha"  disabled ></div>
                                <div style="margin-left:90px">Usuario Emisor:
                                    <select name="usuario" id="usuario" style="width:15rem; height:35px;" class="usuario">

                                        <option value="" <?php echo (empty($existeUsuarioEnDb)) ? "selected" : ""; ?>></option>
                                        
                                        <?php
                                            foreach ($usuarios as $usuario => $key) {

                                            $usuario = $key['NOMBRE_VEN'];

                                            $existeUsuarioEnDb = (isset($borradorEnc[0]['USUARIO_EMISOR'])) ? $borradorEnc[0]['USUARIO_EMISOR'] : "";
                                        ?>
                                            <option value="<?= $key['NOMBRE_VEN'] ?>" <?php echo (isset($borradorEnc) && $usuario == $existeUsuarioEnDb) ? "selected" : ""; ?>> <?= $key['NOMBRE_VEN'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group">   
                                    <!-- <button class="btn btn-secondary" type="button" value="" style="height:35px;margin-left:200px;width:100px">Borrador <i class="bi bi-pencil-square" style=""></i></button> -->
                                    <button class="btn btn-secondary" style="height:35px;margin-left:10%;width:110px" onclick="borrador()">Guardar <i class="bi bi-save" style=""></i></button>

                                    <button class="btn btn-primary btn-submit" style="height:35px;margin-left:5px;width:110px" onclick= "solicitar(<?= $esBorrador ?>)">Solicitar <i class="bi bi-cloud-upload" style="color:white"></i></button>

                                    <a href="seleccionDeSolicitudes.php" class="btn btn-secondary" style="margin-top: -16.5%; margin-left: 80%; width:150px">Volver Al Listado</a>

                                </div>

                            </div>

                            <div class="row" style="margin-top:-1.5%;">

                                <div style="margin-left:90px">N° solicitud <input type="text" style="width:145px; height:35px; margin-left:30px" value="<?=  ($numSolicitud[0]['ultimo_id']+1) ?>" id="numSolicitud" disabled></div>
                                <div style="margin-left:90px">Estado: <input type="text" style="width:145px; height:35px; margin-left:55px" id="estado" disabled> </div>

                            </div>

                        </div>
            
                        <table class="table table-striped table-bordered table-sm table-hover" id="tablaArticulos" style="width: 95%; height:100px; margin-left:50px" cellspacing="0" data-page-length="100">
                            <thead class="thead-dark" style="">
                                <tr>
                                    <th style="text-align:center;width: 15%;" >Articulo</th>
                                    <th style="text-align:center;width: 15%;" >Descripcion</th>
                                    <th style="text-align:center;width: 7%;" >Precio</th>
                                    <th style="text-align:center;width: 7%;">Cantidad</th>
                                    <th style="text-align:center;width: 30%;" >Descripcion Falla</th>
                                    <th style="text-align:center;width: 10%;" >Fotos</th>
                                    <th style="text-align:center;width: 10%;" >Fila</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    if(isset($borradorDet)){
                                               
                                        foreach ($borradorDet as $key => $detalle) {
                                  
                                            echo '
                                            <tr id="bodyArticulos"> 
                                                <td>
                                                    <select name="selectArticulo" id="selectArticulo" class="selectArticulo" onchange="mostrarDescripcion(this)" style="width:250px">
                                                    <option value="" selected disabled>Buscar artículo...</option>
                                                    ';
                                                    foreach ($todosLosArticulos as $key => $value) {
                                                
                                                        if($detalle['COD_ARTICU'] == $value['COD_ARTICU']){
                                                            echo '<option value="'.$value["COD_ARTICU"].'?'.$value["DESCRIPCIO"].'?'.$value['PRECIO'].' " selected>'.$value['COD_ARTICU'].' | '.$value['DESCRIPCIO'].'</option>';
                                                        }else{

                                                            echo '<option value="'.$value["COD_ARTICU"].'?'.$value["DESCRIPCIO"].'?'.$value['PRECIO'].' ">'.$value['COD_ARTICU'].' | '.$value['DESCRIPCIO'].'</option>';
                                                        }
                                                    }

                                                    echo '
                                                    </select>
                                                </td>

                                                <td style="text-align:center">'.$detalle['DESCRIPCION'].'</td>
                                                <td style="text-align:center">$'.number_format($detalle['PRECIO'], 0, ",",".").'</td>
                                                <td style="text-align:center">'.$detalle['CANTIDAD'].'</td>
                                                <td style="text-align:center"><input type="text" style="width:400px" onchange="comprobarFila(this)" value="'.$detalle['DESC_FALLA'].'"></td>
                                                <td style="text-align:center">
                                                    
                                                    <button class="btn btn-primary" type="button" style="margin-left: 5px; padding:.3rem .5rem;" onclick="elegirImagen(this)">
                                                    <i class="bi bi-upload"></i> 
                                                    </button>

                                                    <button class="btn btn-warning" style="margin-left:5px; padding:.3rem .5rem"  onclick="mostrarImagen(this)">
                                                    <i class="bi bi-eye" style="color:white"></i>
                                                    </button>

                                                    <button class="btn btn-danger" style="margin-left:5px; padding:.3rem .5rem" onclick="eliminarArchivo(this)"><i class="bi bi-trash"></i></button>

                                                </td>
                                                <td style="text-align:center">
                                                    <button class="btn btn-danger" title="Eliminar fila" style="margin-left:5px; padding:.3rem .5rem;" onclick="eliminarFila(this)" >
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            ';
                                        }

                                    }else{
                 
                                ?>
                                        <tr id="bodyArticulos">
                                            <td>
                                                <select name="selectArticulo" id="selectArticulo" class="selectArticulo" onchange="mostrarDescripcion(this)" style="width:250px">
                                                <option value="" selected disabled>Buscar artículo...</option>
                                                <?php 
                                                foreach ($todosLosArticulos as $key => $value) {
                                            
                                                    echo '<option value="'.$value["COD_ARTICU"].'?'.$value["DESCRIPCIO"].'?'.$value['PRECIO'].'">'.$value['COD_ARTICU'].' | '.$value['DESCRIPCIO'].'</option>';
                                                }
                                                ?>
                                                </select>

                                            </td>
                                            <td style="text-align:center"></td>
                                            <td style="text-align:center"></td>
                                            <td style="text-align:center"></td>
                                            <td style="text-align:center;"><input type="text" style="width:400px" onchange="comprobarFila(this)"></td>
                                            <td style="text-align:center;">


                                            <button class="btn btn-primary" title="Subir" type="button" style="margin-left: 5px; padding:.3rem .5rem;" onclick="elegirImagen(this)">
                                                <i class="bi bi-upload"></i> 
                                            </button>

                                            <button class="btn btn-warning" title="Ver" style="margin-left:5px; padding:.3rem .5rem;"  onclick="mostrarImagen(this)">
                                                <i class="bi bi-eye" style="color:white"></i>
                                            </button>

                                            <button class="btn btn-danger" title="Eliminar" style="margin-left:5px; padding:.3rem .5rem;" onclick="eliminarArchivo(this)"><i class="bi bi-trash"></i></button></td>
                                            <td style="text-align:center">
                                                <button class="btn btn-danger" title="Eliminar fila" style="margin-left:5px; padding:.3rem .5rem;" onclick="eliminarFila(this)" >
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </td>
                                          
                                        </tr>
                              <?php 
                                    }
                                ?>
                                        
                            </tbody>
            
                        </table>
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
        <script src="js/controlFallas.js"></script>
    </body>

</html>
<script>
    $('.usuario').select2();

    $('.selectArticulo').select2({
        placeholder: 'Buscar artículo...',
        minimumInputLength: 3,
        data: function(params) {
            // Obtener los datos del Local Storage
            const storedData = JSON.parse(localStorage.getItem('articulos'));

            // Filtrar los datos para que coincidan con el término de búsqueda
            const filteredData = storedData.filter(item => item.text.includes(params.term));

            // Devolver los datos filtrados para que Select2 los utilice
            return {
            results: filteredData
            };
        }
    });
    $(document).ready(function() {
        existeBorrador()


    })
    

</script>
<!-- <script src="js/gastosTesoreria.js"></script> -->

