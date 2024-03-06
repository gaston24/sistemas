<?php 
    session_start();

    require_once $_SERVER['DOCUMENT_ROOT'] . '/sistemas/assets/js/js.php';
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <title>Solicitud de Recodificacion</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

        <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link rel="stylesheet" href="css/style.css">
        
        </link>
        <style>

            /* .select2-dropdown.select2-dropdown--above {

                width:300px;
            } */
        </style>

    </head>

    <body>
        
      
        <input type="file" name="archivos[]" id="archivos" multiple accept=".pdf, .jpg, .png" style="display: none;" />
        <div id="carruselImagenes" class="modal fade" tabindex="-1" aria-hidden="true" style="margin-left:10%;max-width:80%"></div>
        <div id="nroSucursal" hidden><?= $nroSucurs; ?></div>

        <div class="card">
            <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
                <div class="wrapper wrapper--w280"><div style="color:white; text-align:center"><h6>Consulta Stock y Precios</h6></div>
                    <div class="card card-1">
                        <div id="periodo" hidden><?= $periodo ?></div>
                        <div>
                        
                            <h4><strong><i class="bi bi-search"></i>Consulta Stock y Precios </strong></h4>

                        </div>

                        <div>

                            <div class="contInputs">

                                <div class="form-inline" style="margin-left: 0.5rem;">
                                    <input name="selectArticulo" id="selectArticulo" class = "form-control selectArticulo" type="text" style="width: 13rem;" onchange="traerArticulo(this,<?= $_SESSION['usuarioUy'] ?>)">
                                    <button class="btn btn-danger btn-sm" onclick="borrar()">Borrar <i class="bi bi-x-circle"></i></button>
                                </div>
                               <div id="cont">

                                    <div>
                                        Articulo : <input type="text" class="info" id="articulo" disabled>
                                        </select>
                                    </div>
                                    <div>
                                        Descripcion : <input type="text" class="info" id="descripcion" disabled>
                                        </select>
                                    </div>
                                    <div >
                                        Stock : <input type="text" class="info" id="stock" disabled>
                                        </select>
                                    </div>
                                    <div>
                                        Precio : <input type="text" class="info" id="precio" disabled>
                                        </select>
                                    </div>

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
        <link rel="stylesheet" type="text/css" href="assets/select2/select2.min.css">
        <script src="assets/select2/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
        <script src="js/consultaStockPrecio.js"></script>
    </body>

</html>
<script>
</script>
<!-- <script src="js/gastosTesoreria.js"></script> -->

