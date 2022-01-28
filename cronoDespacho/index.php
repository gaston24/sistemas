<?php

require 'Class/cronograma.php';
require 'Class/TipoCrono.php';

$cronoDespacho = new Cronograma();

$tipo = new TipoCrono();
$todosLosTipos = $tipo->traerTipo();

$cronoElegido = isset($_GET['tipo']) ? $_GET['tipo'] : 'NORMAL';

foreach ($todosLosTipos as $value) {
    if($cronoElegido == $value['ID'] ){
        $cronoElegido = $value['TIPO_CRONO'];
    }    
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.css" />
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.0/sweetalert2.js"></script>
    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <div class="row mt-4" id="contenedorTitle">
        <div id="titleIndex">
            <h3><i class="fa fa-truck ml-4"></i> Crono Despacho: <a style="color: #6c757d;"><?= $cronoElegido ?></a></h3>
            <div id="contCheck">
                <label class="ml-4">Normal</label><input type="radio" class="ml-2 valores" name="activo" id="check1" value="1" <?php if ($todosLosTipos[0]["ACTIVO"] == 1) {
                                                                                                                                    echo 'checked';
                                                                                                                                ?> disabled <?php } ?>>
                <label class="ml-4">Lunes feriado</label><input type="radio" class="ml-2 valores" name="activo" id="check2" value="2" <?php if ($todosLosTipos[1]["ACTIVO"] == 1) {
                                                                                                                                            echo 'checked';
                                                                                                                                        ?> disabled <?php } ?>>
                <label class="ml-4">Viernes Feriado</label><input type="radio" class="ml-2 valores" name="activo" id="check3" value="3" <?php if ($todosLosTipos[2]["ACTIVO"] == 1) {
                                                                                                                                            echo 'checked';
                                                                                                                                        ?> disabled <?php } ?>>
                <button type="submit" class="btn btn-primary" id="btnActive" value="Activar" name="activar">Activar <i class="fa fa-check-circle"></i></button>
            </div>
        </div>

    </div>

    <form class="mb-3 mt-4" action="#">
        <div class="form-row contenedor">

            <div class="col-sm-2 mt-2">
                <label>Cronograma</label>
                <select id="inputTipo" class="form-control form-control-sm" name="tipo">
                    <?php
                    foreach ($todosLosTipos as $tipo => $key) {
                        $selected = ($key['TIPO_CRONO'] == $cronoElegido) ? 'selected' : '';
                    ?>
                        <option value="<?= $key['ID'] ?>" <?=$selected ?> ><?= $key['TIPO_CRONO'] ?></option>
                    <?php
                    }
                    ?>


                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" id="btn_filtro">Filtrar<i class="fa fa-filter"></i></button>
            </div>

            <div id="busqRapida">
                <label id="textBusqueda">Busqueda rapida:</label>
                <input type="text" id="textBox" placeholder="Sobre cualquier campo..." onkeyup="myFunction()" class="form-control form-control-sm"></input>
            </div>
            

    </form>

    <?php

    if (isset($_GET['tipo'])) {

        if ($_GET['tipo'] != '') {
            $tipo = $_GET['tipo'];
        }

        $todosLosClientes = $cronoDespacho->traerCronograma($cronoElegido);

    ?>
        <div class="table-responsive">
            <table class="table table-hover table-condensed table-striped text-center">
                <thead class="thead-dark">
                    <th scope="col" style="width: 2%">NRO_SUC</th>
                    <th scope="col" style="width: 4%">COD_CLIENT</th>
                    <th scope="col" style="width: 18%">SUCURSAL</th>
                    <th scope="col" style="width: 3%">PRIORIDAD</th>
                    <th scope="col" style="width: 2%">LUNES</th>
                    <th scope="col" style="width: 2%">MARTES</th>
                    <th scope="col" style="width: 2%">MIERCOLES</th>
                    <th scope="col" style="width: 2%">JUEVES</th>
                    <th scope="col" style="width: 2%">VIERNES</th>
                    <th scope="col" style="width: 2%">SABADO</th>
                </thead>

                <tbody id="table">
                    <?php
                    $todosLosClientes = json_decode($todosLosClientes);
                    foreach ($todosLosClientes as $valor => $value) {
                    ?>

                        <tr>
                            <td><?= $value->NRO_SUCURSAL; ?></td>
                            <td><?= $value->COD_CLIENT; ?></td>
                            <td><?= $value->SUCURSAL; ?></td>
                            <td ><select name="prioridad" id="prioridad" class="selectPrioridad" onChange="cambioPrioridad('<?=$value->COD_CLIENT ?>', this.options.selectedIndex)">
                                <option value="<?= $value->PRIORIDAD ?>" selected disabled><?= $value->PRIORIDAD ?> </option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            </td>
                            <td><input type="checkbox" name="LUNES" id="" onclick="actualizarDia('<?= $value->COD_CLIENT ?>', this.name,event)" <?php if ($value->LUNES == 1) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>></td>
                            <td><input type="checkbox" name="MARTES" id="" onclick="actualizarDia('<?= $value->COD_CLIENT ?>', this.name,event)" <?php if ($value->MARTES == 2) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>></td>
                            <td><input type="checkbox" name="MIERCOLES" id="" onclick="actualizarDia('<?= $value->COD_CLIENT ?>', this.name,event)" <?php if ($value->MIERCOLES == 3) {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?>></td>
                            <td><input type="checkbox" name="JUEVES" id="" onclick="actualizarDia('<?= $value->COD_CLIENT ?>', this.name,event)" <?php if ($value->JUEVES == 4) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>></td>
                            <td><input type="checkbox" name="VIERNES" id="" onclick="actualizarDia('<?= $value->COD_CLIENT ?>', this.name,event)" <?php if ($value->VIERNES == 5) {
                                                                                                                                                        echo 'checked';
                                                                                                                                                    } ?>></td>
                            <td><input type="checkbox" name="SABADO" id="" onclick="actualizarDia('<?= $value->COD_CLIENT ?>', this.name,event)" <?php if ($value->SABADO == 6) {
                                                                                                                                                    echo 'checked';
                                                                                                                                                } ?>></td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>

            </table>

        <?php
    }
        ?>
        </div>

</body>
<script src="main.js" charset="utf-8"></script>
</html>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<script>
  
  
</script>