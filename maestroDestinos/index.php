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

        <div class="col-sm-2 mt-2">
            <label for="inputCity">Rubro</label>
            <select id="inputRubro" class="form-control form-control-sm" name="rubro">
                <option selected></option>
                    <?php
                        foreach($todosLosRubros as $rubro => $key){
                    ?>
                <option value="<?= $key['DESCRIP'] ?>"><?= $key['DESCRIP'] ?></option>
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

            <div>
                <button type="submit" class="btn btn-primary mt-4 ml-4" id="btn_filtro">Filtrar<i class="fa fa-filter"></i></button>
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
        <table class="table table-hover table-condensed table-striped text-center" id="tableOrden">
            <thead class="thead-dark">
                
                    <th scope="col" style="width: 10%">Foto</th>
                    <th scope="col" style="width: 15%">Articulo</th>
                    <th scope="col" style="width: 25%">Descripción</th>
                    <th scope="col" style="width: 20%">Destino</th>
                    <th scope="col" style="width: 20%">Temporada</th>
                    <th scope="col" style="width: 20%">Rubro</th>                
            </thead>

            <tbody id="table">

            <?php
            foreach($todosLosArticulos as $valor => $key){
            ?>
            

                <tr>
                <td><a target="_blank" data-toggle="modal" data-target="#exampleModal<?= substr($key['COD_ARTICU'], 0, 13); ?>" href="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg"><img src="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg" alt="Sin imagen" height="50" width="50"></a></td>
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