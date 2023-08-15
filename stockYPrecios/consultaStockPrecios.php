<?php 
    session_start();
    require_once '../ajustes/Class/Articulo.php';
    $maestroArticulos = new Articulo();
    $todosLosArticulos = $maestroArticulos->traerMaestroArticulo();

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
                                    <select name="selectArticulo" id="selectArticulo" class = "form-control selectArticulo" style="width: 13rem;" onchange="traerArticulo(this)">
                                        <option value=""></option>
                                        <?php foreach ($todosLosArticulos as $key => $value) { 
                                            echo '<option value="'.$value["COD_ARTICU"].'-'.$value["DESCRIPCIO"].'-'.$value['PRECIO'].' ">'.$value['COD_ARTICU'].' | '.$value['DESCRIPCIO'].'</option>';
                                        } ?>
                                    </select> 
                                    <button class="btn btn-primary btn-sm">Buscar <i class="bi bi-search"></i></button>
                                </div>
                               <div id="cont">

                                    <div>
                                        Articulo : <input type="text" id="articulo" disabled>
                                        </select>
                                    </div>
                                    <div>
                                        Descripcion : <input type="text" id="descripcion" disabled>
                                        </select>
                                    </div>
                                    <div >
                                        Stock : <input type="text" id="stock" disabled>
                                        </select>
                                    </div>
                                    <div>
                                        Precio : <input type="text" id="precio" disabled>
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

$('.selectArticulo').select2({
        placeholder: 'Ingresar artículo...',
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
</script>
<!-- <script src="js/gastosTesoreria.js"></script> -->

