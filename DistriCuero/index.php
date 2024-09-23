<?php

require 'Class/temporada.php';
require 'Class/Articulo.php';
require 'Class/Rubro.php';
include 'formAlta.php';

$rubro = new Rubro();
$todosLosRubros = $rubro->traerRubros();

$temporada = new Temporada();
$todasLasTemporadas = $temporada->traerTemporadas();

$maestroArticulos = new Articulo();

$todosLosArticulos = []; // Inicializa la variable $todosLosArticulos

if (isset($_GET['rubro'])) { 
    $rubroFiltro = $_GET['rubro'] !== '' ? $_GET['rubro'] : '%';
    $temporadaFiltro = $_GET['temporada'] !== '' ? $_GET['temporada'] : '%';
    $todosLosArticulos = $maestroArticulos->traerMaestro($rubroFiltro, $temporadaFiltro);
}

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">

    <title>Gestión de Artículos</title>
</head>
<body>

<div class="row mt-4" id="contenedorTitle">
    <div>   
        <a type="button" class="btn btn-primary" id="btn_back2" href="navbar.html"><i class="fa fa-arrow-left"></i> Volver</a>
    </div>
    <div id="titleIndex">
        <h3><i class="fa fa-archive"></i> Gestión de Artículos</h3>
    </div>
    <div>
        <button type="button" class="btn btn-success" id="btn_orden" onclick="enviaOrden()">Enviar<i class="fa fa-cloud-upload"></i></button>
    </div>
</div>

<form>
    <div class="form-row ml-3 mb-3 contenedor">
        <div class="col-sm-2 mt-2">
            <label for="inputCity">Rubro</label>
            <select id="inputRubro" class="form-control form-control-sm" name="rubro">
                <option selected></option>
                <?php foreach ($todosLosRubros as $key): ?>
                    <option value="<?= $key['DESCRIP'] ?>"><?= $key['DESCRIP'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>  

        <div class="col-sm-1 mt-2" id="contTemp">
            <label for="inputState2">Temporada</label>
            <select id="inputTemp" class="form-control form-control-sm" name="temporada">
                <option selected></option>
                <?php foreach ($todasLasTemporadas as $key): ?>
                    <option value="<?= $key['TEMPORADA'] ?>"><?= $key['TEMPORADA'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary" id="btn_filtro">Filtrar<i class="fa fa-filter"></i></button>
        </div>

        <div class="mt-2" id="busqRapida">
            <label id="textBusqueda">Búsqueda rápida:</label>
            <input type="text" id="textBox" placeholder="Sobre cualquier campo..." onkeyup="myFunction()" class="form-control form-control-sm">
        </div>

        <div id="contTotal">
            <label id="labelTotal">Total artículos</label> 
            <input name="total" id="total" value="0" type="text" class="form-control" readonly>
        </div>

        <div class="form-check" id="check">
            <label class="form-check-label ml-1" id="labelCheck">
                <input type="checkbox" onclick="marcar(this);"> Select All
            </label>
        </div>
    </div>
</form>

<?php if (!empty($todosLosArticulos)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableOrden">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" style="width: 10%">Foto</th>
                    <th scope="col" style="width: 15%">Artículo</th>
                    <th scope="col" style="width: 25%">Descripción</th>
                    <th scope="col" style="width: 20%">Rubro</th>
                    <th scope="col" style="width: 10%">Precio estimado</th>
                    <th scope="col" style="width: 20%">Temporada</th>
                    <th scope="col" style="width: 2%">Tope Cantidad</th>
                    <th scope="col" style="width: 5%">Lanzamiento</th>
                    <th scope="col" style="width: 5%">Activo</th>
                    <th scope="col" style="width: 5%">Detalle kit</th>
                </tr>
            </thead>
            <tbody id="table">
                <?php foreach ($todosLosArticulos as $key): 
                    $cod_articu = substr($key['COD_ARTICU'], 0, 13);
                    $jpg_path = "../../Imagenes/{$cod_articu}.jpg";
                    $png_path = "../../Imagenes/{$cod_articu}.png";

                    // Verifica cuál archivo existe
                    if (file_exists($jpg_path)) {
                        $image_path = $jpg_path;
                        $image_ext = 'jpg';
                    } elseif (file_exists($png_path)) {
                        $image_path = $png_path;
                        $image_ext = 'png';
                    } else {
                        $image_path = '../../Imagenes/default.jpg'; // Imagen por defecto si no se encuentra el archivo
                        $image_ext = 'jpg'; // Puedes ajustar el tipo de imagen por defecto si es necesario
                    }
                ?>
                <tr>
                    <td>
                        <a target="_blank" data-toggle="modal" data-target="#exampleModal<?= $cod_articu; ?>" href="<?= $image_path; ?>">
                            <img src="<?= $image_path; ?>" alt="Sin imagen" height="50" width="50">
                        </a>
                    </td>
                    <td><?= $key['COD_ARTICU'] ?></td>
                    <td><?= $key['DESCRIPCIO'] ?></td>
                    <td><?= $key['RUBRO'] ?></td>
                    <td><?= number_format($key['PRECIO'], 0, ",", ".") ?></td>
                    <td><?= $key['TEMPORADA'] ?></td>
                    <td><input type="number" min="0" id="topeCant"></td>
                    <td>
                        <select name="select" id="select">
                            <option value="" disabled selected></option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </td>
                    <td><input type="checkbox" name="checkTd" onclick="contar(this);"></td>
                    <?php if ($key['RUBRO'] == 'KITS'): ?>
                        <td>
                            <a href="detalleKits.php?cod_kit=<?= $key['COD_ARTICU'] ?>">
                                <button type="button" class="btn btn-sm btn-warning" style="width: 80px;">
                                    <i class="fa fa-search"></i> Ver
                                </button>
                            </a>
                        </td>
                    <?php else: ?>
                        <td style="width: 80px;"></td>
                    <?php endif; ?>
                </tr>

                <!-- Modal for each item -->
                <div class="modal fade" id="exampleModal<?= $cod_articu; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body" align="center">
                                <img src="<?= $image_path; ?>" alt="<?= $cod_articu; ?>.<?= $image_ext; ?> - imagen no encontrada" height="400" width="400">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<script src="main.js" charset="utf-8"></script>
<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>
