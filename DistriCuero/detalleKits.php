<?php

require 'Class/Articulo.php';

$cod_kit = $_GET['cod_kit'];

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

    <title>Detalle Kits</title>
  </head>
  <body>
  <h3 class="mb-4 mt-4 ml-4"><i class="fa fa-archive"></i>  Detalle Kits</h3>

    <div class="col-">   
        <a type="button" class="btn btn-primary ml-4 mb-4" id="btn_back2" href="javascript:history.back(-1)"><i class="fa fa-arrow-left"></i>  Volver</a>
    </div>
  
  <?php

    $todosLosArticulos = $maestroArticulos->traerDetalleKit($cod_kit);

    ?>
   
   <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableOrden">
            <thead class="thead-dark">
                
                    <th scope="col" style="width: 10%">Foto</th>
                    <th scope="col" style="width: 15%">Articulo</th>
                    <th scope="col" style="width: 20%">Descripción</th>
                    <th scope="col" style="width: 5%">Cantidad</th>
                    <th scope="col" style="width: 15%">Rubro</th>
                    <th scope="col" style="width: 10%">Precio estimado</th>
                    <th scope="col" style="width: 20%">Temporada</th>                
            </thead>

            <tbody id="table">

            <?php
            foreach($todosLosArticulos as $valor => $key){
            ?>
            

                <tr>
                <td><a target="_blank" data-toggle="modal" data-target="#exampleModal<?= substr($key['COD_ARTICU'], 0, 13); ?>" href="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg"><img src="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg" alt="Sin imagen" height="50" width="50"></a></td>
                    <td><?=  $key['COD_ARTICU']?></td>
                    <td><?=  $key['DESCRIP_COD_ARTICU']?></td>
                    <td><?=  $key['CANTIDAD']?></td>
                    <td><?=  $key['RUBRO']?></td>
                    <td><?=  number_format($key['PRECIO'],0,",",".")?></td>
                    <td><?=  $key['TEMPORADA']?></td>
                                     
                </tr>   
            
            <?php
            }   
            ?>

            </tbody>
        
        </table>

       
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