<?php

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$codClient = $_SESSION['codClient'];  

$numeroOrden = $_GET['orden'];

require_once 'Class/Orden.php';

$orden = new Orden();
$todasLasOrdenes = $orden->traerDetalleOrden($numeroOrden);

?> 

<!doctype html> 
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <!-- Including Font Awesome CSS from CDN to show icons -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">

    <title>Nota de pedido</title>
  </head>
  <body>

  <nav class="navbar navbar-expand-md bg-dark navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="listOrdenesActivas.php" style="color: #28a745;"><i class="fa fa-arrow-left"></i> ATRAS</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
      <a class="nav-link" href="pedidosSucursal.php"><i class="fa fa-list"></i> Seleccionar ordenes</a>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="pedidosSucursal.php"><i class="fa fa-calendar-check-o"></i> Notas de pedido</a>
      </li>
    </ul>
  </div>
</nav>

<div id="contenedorList">
    <h3 class="" id="title_nota">
        <i class="fa fa-cart-arrow-down"></i>  Carga nota de pedido / Orden:  <?php echo $numeroOrden ?>
    </h3>

        <div class="form-row ml-4 mb-3 contenedor">
           
            <div id="contBusqRapida">
                <label id="textBusqueda">Busqueda rapida:</label>
                <input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control"></input>  
            </div>
            <div>
                 <label id="labelTotal">Total artículos</label> 
				<input name="total_todo" id="total" value="0" type="text" class="form-control" readonly>
			</div>
            <div>
                 <label id="labelTotal">Importe Total</label> 
				<input name="total_todo" id="totalPrecio" value="0" type="text" class="form-control" readonly>
			</div>
            <!-- <div class="col-1">   
                <a type="button" class="btn btn-primary" id="btn_back" href="listOrdenesActivas.php"><i class="fa fa-arrow-left"></i>  Volver</a>
            </div> -->
            <div>
                <button type="submit" class="btn btn-success" id="btn_enviar" onclick="enviaPedido()">Enviar
                <i class="fa fa-cloud-upload"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table text-center ml-4" id="tablePed">
                <thead class="thead-dark">
                    
                        <th scope="col" style="width: 15%">Foto</th>
                        <th scope="col" style="width: 15%">Articulo</th>
                        <th scope="col" style="width: 20%">Descripción</th>
                        <th scope="col" style="width: 20%">Rubro</th>
                        <th scope="col" style="width: 15%">Precio estimado</th>
                        <th scope="col" style="width: 20%">Temporada</th>
                        <th scope="col" style="width: 20%"></th>
                        <th scope="col" style="width: 5%">Cantidad</th>
                    
                </thead>

                <tbody id="table">

                <?php
                foreach($todasLasOrdenes as $valor => $key){

                    $minimo = ($key['LANZAMIENTO'] == 1) ? 1 : 0;
                
                ?>
    
                    <tr>
                    <td><a target="_blank" data-toggle="modal" data-target="#exampleModal<?= substr($key['COD_ARTICU'], 0, 13); ?>" href="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg"><img src="../../../Imagenes/<?= substr($key['COD_ARTICU'], 0, 13); ?>.jpg" alt="Sin imagen" height="50" width="50"></a></td>
                        <td id="articuloPed" name="articuloPed"><?=  $key['COD_ARTICU']?></td>
                        <td id="descripcioPed" name="descripcioPed"><?=  $key['DESCRIPCIO']?></td>
                        <td id="rubro" name="rubro"><?=  $key['RUBRO']?></td>
                        <td id="precioPed" name="precioPed"><?= $key['PRECIO_ESTIMADO'] ?></td>
                        <td id="temporadaPed" name="temporadaPed"><?=  $key['TEMPORADA'] ?></td>
                        <td id="novedadPed" name="novedadPed">
                            <?php if($key['LANZAMIENTO']== 1){ ?>
                            <a>LANZAMIENTO!</a>
                            <?php } else { ?> <?php } ?>
                        </td>
                        <td><input type="number" tabindex="1" value="<?=$minimo?>" pattern="^[0-9]" min="<?=$minimo?>" id="inputNum" name="inputNum[]" onchange="total(); precioTotal()"></td>                
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
        </div>
    </div>

	<script>
		
		var codClient = "<?= $codClient; ?>"; 
        var orden = "<?= $numeroOrden; ?>"; 
	
	</script>
        
    <script src="main.js" charset="utf-8"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  </body>
</html>


<?php
 }   
?>