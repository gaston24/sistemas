
<?php


require 'Class/articulo.php';

$articulo = new Articulo();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir articulos</title>
     <!-- Bootstrap CSS -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Including Font Awesome CSS from CDN to show icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="Css/style.css">

</head>
<body>

    <?php

        $todosLosArticulos = $articulo->traerArticulos();

    ?>
<div class="row">
<h3><i class="fa fa-calendar-times-o" aria-hidden="true"></i> Articulos excluidos</h3>
        <div>       
            <a type="button" class="btn btn-warning" id="btn_edit" data-toggle="modal" data-target="#altaModal"><i class="fa fa-edit"></i>  Editar</a>
        </div>
        <div>       
            <a type="button" class="btn btn-success" id="btn_import" data-toggle="modal" data-target="#altaModalImport"><i class="fa fa-file-excel-o"></i>  Importar</a>
        </div>
        <div>       
            <button type="button" class="btn btn-danger" id="btn_delete" onclick="deleteArticulo()"><i class="fa fa-trash-o" ></i>  Eliminar</button>
        </div>

    <div id="busqRapida">
        <label id="textBusqueda">Busqueda rapida:</label>
        <input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control form-control-sm"></input>  
    </div>
</div>

<div class="table-responsive" id="tableIndex">
            <table class="table table-hover table-condensed table-striped text-center">
                <thead class="thead-dark" style="font-size: small;">
                    <th scope="col" style="width: 0.5%">ID</th>
                    <th scope="col" style="width: 2%">FECHA</th>
                    <th scope="col" style="width: 0.5%">HORA</th>
                    <th scope="col" style="width: 1%">ARTICULO</th>
                    <th scope="col" style="width: 8%">DESCRIPCION</th>
                    <th scope="col" style="width: 0.5%">CANTIDAD</th>
                    <th scope="col" style="width: 7%">OBSERVACIONES</th>
                    <th style="width:0.5%">SELECCIONAR</th>
                </thead>

                <tbody id="table" style="font-size: small;">
                    <?php
                    
                    $todosLosArticulos = json_decode($todosLosArticulos);
                    // var_dump($todosLosArticulos);
                    foreach ($todosLosArticulos as $valor => $value) {
                        
                    ?>


                        <tr>
                            <td><?= $value->ID ?></td>
                            <td><?= substr($value->FECHA->date, 0, 10); ?></td>
                            <td><?= $value->HORA_CARGA ?></td>
                            <td><?= $value->COD_ARTICU ?></td>
                            <td><?= $value->DESCRIPCION ?></td>
                            <td><?= $value->CANT ?></td>
                            <td><?= $value->OBSERVACIONES ?></td>
                            <td><input id="check" type="checkbox" onclick="contar(this);"></td>
                        </tr>

                    <?php
                    }
                    ?>

                </tbody>
            </table>

</body>

    <script src="main.js" charset="utf-8"></script>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
<script>

    $('#altaModal').modal('toggle')

    $('#altaModalImport').modal('toggle')

</script>

<?php

include('./altaArticulo.php');
include('./importar.php');

?>

</html>