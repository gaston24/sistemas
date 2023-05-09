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
        <?php include('Css/allStyleCss.php') ?>
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
                            <button type="button" class="btn btn-info" id="btn_export">Exportar <i class="bi-file-earmark-excel"></i></button>
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
        </div>  
            
    </body>
    <?php include('js/allScriptIndex.php'); ?>   

</html>
    
<?php

    include('./altaArticulo.php?');
    include('./importar.php');

?>

<script src="js/main2.js" charset="utf-8"></script>
