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

    <title>Gestion de articulos</title>
  </head>
  <body>
  <h3 class="mb-4 mt-4 ml-3"><i class="fa fa-archive"></i>  Gestion de Articulos</h3>

  <form>
        <div class="form-row ml-3 mb-3 contenedor">

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
        
            <div class="col-sm-1 mt-2">
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
                <button type="submit" class="btn btn-primary" id="btn_filtro">Filtrar<i class="fa fa-filter"></i></button>
            </div>

            <div class="mt-2" id="busqRapida">
                <label id="textBusqueda">Busqueda rapida:</label>
                <input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control form-control-sm"></input>  
            </div>
            
            <div>
                 <label id="labelTotal">Total artículos</label> 
				<input name="total" id="total" value="0" type="text" class="form-control" readonly>
			</div>

            <div>
                <button type="button" class="btn btn-success" id="btn_orden" onclick="enviaOrden()">Enviar
                <i class="fa fa-cloud-upload"></i>
                </button>
            </div>
            <div class="col-">   
                <a type="button" class="btn btn-primary" id="btn_back2" href="navbar.html"><i class="fa fa-arrow-left"></i>  Volver</a>
            </div>

            <div class="form-check" id="check">
                <label class="form-check-label ml-1" id="labelCheck">
                    <input type="checkbox" onclick="marcar(this);">  Select All
                </label>
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

    $todosLosArticulos = $maestroArticulos->traerMaestro($rubro, $temporada);

    ?>
   
   <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableOrden" style="width: 80%">
            <thead class="thead-dark">
                
                    <th scope="col" style="width: 10%">Foto</th>
                    <th scope="col" style="width: 15%">Articulo</th>
                    <th scope="col" style="width: 20%">Descripción</th>
                    <th scope="col" style="width: 20%">Rubro</th>
                    <th scope="col" style="width: 10%">Precio estimado</th>
                    <th scope="col" style="width: 20%">Temporada</th>
                    <th scope="col" style="width: 5%">Lanzamiento</th>
                    <th scope="col" style="width: 5%">Activo</th>
                
            </thead>

            <tbody id="table">

            <?php
            foreach($todosLosArticulos as $valor => $key){
            ?>
            

                <tr>
                    <td><a target="_blank" data-toggle="modal" data-target="#exampleModal<?= substr($v['COD_ARTICU'], 0, 13); ?>" href="../../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13); ?>.jpg"><img src="../../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13); ?>.jpg" alt="Sin imagen" height="50" width="50"></a></td>
                    <td><?=  $key['COD_ARTICU']?></td>
                    <td><?=  $key['DESCRIPCIO']?></td>
                    <td><?=  $key['RUBRO']?></td>
                    <td><?=  number_format($key['PRECIO'],0,",",".")?></td>
                    <td><?=  $key['TEMPORADA']?></td>
                    <td><select name="select" id="select">
                            <option value="" disabled selected></option>
                            <option value="0">NO</option>
                            <option value="1">SI</option>
                        </select>
                    </td>
                    <td><input type="checkbox" name="checkTd" onclick="contar(this);"></input></td>                
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