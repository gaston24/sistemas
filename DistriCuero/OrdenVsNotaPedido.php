<?php

require_once 'Class/Orden.php';

$orden = $_GET['orden'];

$nota = new Orden();
$todasLasNotas = $nota->traerOrdenesConNotaPedido($orden);

?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
    <link rel="stylesheet" href="css/style.css">
    <title>Lista notas de pedidos</title>
</head>
<body>

<div id="contenedorList">
    <h3 class="mb-4 mt-4 ml-4" id="titleSelect"><i class="fa fa-list"></i>  Notas de Pedido por Orden:  <?php echo $orden ?></h3>
    <div class="row">
        <div class="row ml-1">
            <div class="ml-2">   
                <a type="button" class="btn btn-primary ml-4" id="" href="listOrdenesComercial.php"><i class="fa fa-arrow-left"></i>  Volver</a>
            </div>
            <div class="ml-4 mb-3" id="contBusqRapida">
                <label id="textBusqueda">Busqueda rapida:</label>
                <input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control"></input>  
            </div>
        </div>
        
        <div class="row ml-4">
        <div class="col-xs-3 mb-4">
            <div class="card">
              <div class="card-body">
                <div class="d-flex">
                  <div class="align-self-center">
                    <i class="fas fa-shopping-bag text-success fa-3x"></i>
                  </div>
                  <div class="text-end ml-2 text-center">
                    <h4><input type="text" value="" id="inputCliente" style="width: 80px; text-align: center;border: none; outline:none; font-size: 1.25rem"></h4>
                    <p class="mb-0">NP cargadas</p>
                  </div>
                </div>
              </div>
            </div>
          </div>  
          <div class="col-xs-3 mb-4 ml-2">
            <div class="card">
              <div class="card-body">
                <div class="d-flex  ">
                  <div class="align-self-center">
                    <i class="fas fa-chart-pie text-warning fa-3x"></i>
                  </div>
                  <div class="text-end ml-2 text-center">
                    <h4><input type="text" value="" id="inputSolicitados" style="width: 50px; text-align: center;border: none; outline:none; font-size: 1.25rem">%</h4>
                    <p class="mb-0">% Cargadas</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-3 mb-4 ml-2">
            <div class="card">
              <div class="card-body">
                <div class="d-flex  ">
                  <div class="align-self-center">
                    <i class="fa fa-shopping-cart text-success fa-3x"></i>
                  </div>
                  <div class="text-end ml-2">
                  <h4><input type="text" value="" id="inputCant" style="width: 100px; text-align: center;border: none; outline:none; font-size: 1.25rem"></h4>
                    <p class="mb-0 text-center">Total Unid.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-3 mb-4 ml-2">
            <div class="card">
              <div class="card-body">
                <div class="d-flex  ">
                  <div class="align-self-center">
                    <i class="fas fa-money text-success fa-3x"></i>
                  </div>
                  <div class="text-end ml-2">
                    <h4><input type="text" value="" id="input$" style="width: 100px; text-align: center;border: none; outline:none; font-size: 1.25rem"></h4>
                    <p class="mb-0 text-center">Total $</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-condensed table-striped text-center ml-4" id="tableOrdenVsNota" style="width: 75%">
            <thead class="thead-dark">
                <th scope="col" style="width: 15%">N° sucursal</th>
                <th scope="col" style="width: 15%">Cliente</th>
                <th scope="col" style="width: 20%">Sucursal</th>
                <th scope="col" style="width: 10%">Fecha</th>
                <th scope="col" style="width: 10%">Hora</th>
                <th scope="col" style="width: 15%">Nota de pedido</th>
                <th scope="col" style="width: 10%">Total</th>
                <th scope="col" style="width: 5%">Unidades</th>  
                <th scope="col" style="width: 10%">Estado</th>
                <th scope="col" style="width: 5%"></th>   
            </thead>

            <tbody id="table">

                <?php
                foreach($todasLasNotas as $valor => $key){
                ?>

                <tr>
                    <td><?=  $key['NRO_SUCURSAL'] ?></td>
                    <td><?=  $key['COD_CLIENT'] ?></td>
                    <td><?=  $key['DESC_SUCURSAL'] ?></td>
                    <td><?=  $key['FECHA'] ?></td>
                    <td><?=  $key['HORA']?></td>
                    <td id="notaPed"><?= $key['NRO_NOTA_PEDIDO'] ?></a></td>
                    <td><?=  "$".number_format($key['TOTAL'], 0, ",",".")?></td>
                    <td id="cantidad"><?=  $key['CANTIDAD']?></td>
                      <?php if($key['ESTADO']== 'RECHAZADA'){ ?>
                        <td style="color: #dc3545;"><?=  $key['ESTADO']?></td>
					            <?php } else { ?> 
                        <td><?=  $key['ESTADO']?></td>
                      <?php } ?>  
                    <!-- <td>
                      <a href="detallePedidoSuc.php?notaPedido=<?= $key['NRO_NOTA_PEDIDO'] ?>"><i class="fa fa-search" style="color: #ffc107; font-size: 20px;"></i></a>
                    </td>           -->
                    <?php if(isset($key['NRO_NOTA_PEDIDO'])) {?>
                    <td><i class="bi bi-trash-fill" aria-hidden="true" title="Eliminar"></i></td>
                    <?php } else { ?> 
                    <td></td>
                    <?php } ?>  
                </tr>   
                
                <?php
                }   
                ?>

            </tbody>
            
        </table>
        
    </div>


    <script src="main.js" charset="utf-8"></script> 
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- MDB -->
    <script type="text/javascript"  src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>          
</body>
</html>
