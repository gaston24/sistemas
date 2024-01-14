<?php

require 'Class/temporada.php';
require 'Class/Articulo.php';
require 'Class/Rubro.php';

$rubro = new Rubro();
$todosLosRubros = $rubro->traerRubros();

$temporada = new Temporada();
$todasLasTemporadas = $temporada->traerTemporadas();


$maestroArticulos = new Articulo();

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
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"rel="stylesheet"/>
    <!-- Export excel -->
    <script src="bower_components\jquery\dist\jquery.min.js"></script>
    <script src="bower_components\jquery-table2excel\dist\jquery.table2excel.min.js"></script>
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">

    <title>Maestro de Destinos</title>
  </head>
  <body>

    <div class="row mt-4" id="contenedorTitle">
        <div>   
            <a type="button" class="btn btn-primary" id="btn_back" onClick="window.location.href='../index.php'"><i class="fa fa-arrow-left"></i>  Volver</a>
        </div>
        <div id="boxLoading" class="boxLoading"></div>
        <div id="titleIndex">
            <h3><i class="fa fa-archive ml-4"></i>  Maestro de Destinos</h3>
        </div>
            
    </div>


        <div class="form-row mb-3 contenedor">

        <div class="col-sm-2 mt-2">
            <label for="inputCity">Rubro</label>
            <select id="inputRubro" class="form-control form-control-sm" name="rubro">
                <option ></option>
                    <?php
                  
                        foreach($todosLosRubros as $rubro => $key){
                    
                    ?>
                        <option value="<?= $key['DESCRIP'] ?>"  <?= (isset($_GET['rubro']) && ($key['DESCRIP'] == $_GET['rubro'])) ? 'selected' : '' ?>><?= $key['DESCRIP'] ?></option>
                    <?php
                    }
                    ?>

                    
            </select>
        </div>  
        
            <div class="col-sm-2 mt-2" id="contTemp">
                    <label for="inputState2">Temporada</label>
                    <select id="inputTemp" class="form-control form-control-sm" name="temporada">
                        <option selected></option>
                        <?php
                        foreach($todasLasTemporadas as $temporada => $key){
                        ?>
                            <option value="<?= $key['TEMPORADA'] ?>"><?= $key['TEMPORADA'] ?></option>
                        <?php   
                        }
                        ?>
                    </select>
            </div>

            <div class="col mt-2">
                <div class="row">
                    <div class="col">
                            <button type="submit" class="btn btn-primary mt-4 ml-4" type="button" id="btn_filtro" onclick="filtrar()">Filtrar<i class="fa fa-filter"></i></button>
                            <button class="btn btn-success mt-4 ml-4" type="button" id="btnExport"><i class="fa fa-file-excel-o"></i> Exportar</button>

                    </div>
                    
                </div>

            </div>

            <div class="col-2">
			</div>  
        </div>

   
   <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center" id="tableMaestro">
            <thead class="thead-dark">
                
                    <th scope="col" style="width: 10%" class="imagen">Foto</th>
                    <th scope="col" style="width: 15%">Articulo</th>
                    <th scope="col" style="width: 25%">Descripción</th>
                    <th scope="col" style="width: 20%">Destino</th>
                    <th scope="col" style="width: 15%">Temporada</th>
                    <th scope="col" style="width: 40%">Rubro</th>                
            </thead>

            <tbody id="tableBody">


            </tbody>
        
        </table>
       
    </div>
        
    <script src="js/main.js" charset="utf-8"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Plugin to export Excel -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel"> -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script>


    const buttonExportar = document.querySelector("#btnExport")

    buttonExportar.addEventListener("click", function(e){
        e.preventDefault();

        $('#tableMaestro').DataTable().destroy();

        $("#tableMaestro").table2excel({
            // exclude CSS class
            exclude: ".imagen",
            name: "Detalle pedidos",
            filename: "Detalle notas de pedido", // do not include extension
            fileext: ".xls", // file extension
        });

        activarDatatable();

    })

</script>

  </body>
</html>