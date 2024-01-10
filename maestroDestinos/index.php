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
        <div id="titleIndex">
            <h3><i class="fa fa-archive ml-4"></i>  Maestro de Destinos</h3>
        </div>
            
    </div>

  <form>
        <div class="form-row mb-3 contenedor">

        <div class="col- mt-2">
            <label for="inputCity">Rubro</label>
            <select id="inputRubro" class="form-control form-control-sm" name="rubro">
                <option selected></option>
                    <?php
                        foreach($todosLosRubros as $rubro => $key){
                    ?>
                <option value="<?= $key['RUBRO'] ?>"><?= $key['RUBRO'] ?></option>
                    <?php
                    }
                    ?>

                    
            </select>
        </div>  
        
            <div class="col- mt-2" id="contTemp">
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

            <div class="from-group">
                <button type="submit" class="btn btn-primary ml-2" style="margin-top:2.2rem" id="btn_filtro">Filtrar<i class="fa fa-filter"></i></button>
                <button class="btn btn-success" id="btnExport" style="margin-top:2.2rem; margin-left: 1rem"><i class="fa fa-file-excel-o"></i> Exportar</button>
                <button class="btn btn-secondary" id="btnExport" style="margin-top:2.2rem; margin-left: 1rem"><i class="fa fa-file-excel-o"></i> Novedades</button>
            </div>

            <div class="mt-2" id="busqRapida">
                <label id="textBusqueda">Busqueda rapida:</label>
                <input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control form-control-sm"></input>  
            </div> 
        </div>

    </form>

    <?php

    if (isset($_GET['rubro'])){ 
    
    if ($_GET['rubro']!= ''){
        $rubro = $_GET['rubro'];}
        else {
        $rubro = '%';
        }
      
    if ($_GET['temporada']!= ''){
      $temporada = $_GET['temporada'];}
    else {
      $temporada = '%';
    }

    $todosLosArticulos = $maestroArticulos->traerArticulos($rubro, $temporada);

    ?>
   
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

            <tbody id="table">

            <?php
            foreach($todosLosArticulos as $valor => $key){
            ?>
            

                <tr>
                <td class="imagen"><a target="_blank" data-toggle="modal" data-target="#exampleModal<?= substr($key['COD_ARTICU'], 0, 13); ?>" href="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg"><img src="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg" alt="Sin imagen" height="50" width="50"></a></td>
                    <td><?=  $key['COD_ARTICU']?></td>
                    <td><?=  $key['DESCRIPCION']?></td>
                    <td><?=  $key['DESTINO']?></td>
                    <td><?=  $key['TEMPORADA']?></td>
                    <td><?=  $key['RUBRO']?></td>
                    <div class="modal fade" id="exampleModal<?= substr($key['COD_ARTICU'], 0, 13); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-body" align="center">
											<img src="../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg" alt="<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg - imagen no encontrada" height="400" width="400">
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
										</div>
									</div>
								</div>
							</div>                    
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
        
    <script src="main.js" charset="utf-8"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Plugin to export Excel -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>

<script>

    // $(document).ready(() => {
    //             $("#btnExport").click(function(){
    //     $("#tableMaestro").table2excel({
    //         // exclude CSS class
    //         exclude: ".noExl",
    //         name: "Detalle pedidos",
    //         filename: "Detalle notas de pedido", //do not include extension
    //         fileext: ".xls", // file extension
    //     }); 
    //     });
    //     e.preventDefault();
    // });

    const buttonExportar = document.querySelector("#btnExport")
    buttonExportar.addEventListener("click", function(e){
        e.preventDefault();
        $("#tableMaestro").table2excel({
            // exclude CSS class
            exclude: ".imagen",
            name: "Detalle pedidos",
            filename: "Detalle notas de pedido", //do not include extension
            fileext: ".xls", // file extension
        }); 
    })

</script>

  </body>
</html>