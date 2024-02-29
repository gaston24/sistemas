<?php

session_start(); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/sistemas/assets/js/js.php';
if(!isset($_SESSION['username']) || ($_SESSION['usuarioUy'] == 1)){
    header("Location:login.php");
}else{
$suc = $_SESSION['numsuc'];
$codClient = $_SESSION['codClient'];
include 'class/remito.php';
$remitos = new Remito();
$list = $remitos->traerRemitos($suc);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotulo - Remitos</title>
    <link rel="shortcut icon" href="../assets/css/icono.jpg" />
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css" >
    <script src="../assets/css/bootstrap/jquery-3.5.1.slim.min.js" ></script>
    <script src="../assets/css/bootstrap/popper.min.js" ></script>
    <script src="../assets/css/bootstrap/bootstrap.min.js" ></script>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    <?php include_once __DIR__.'/../assets/css/fontawesome/css.php';?>
    <style>
    #codigoBarra {
        font-family: 'Libre Barcode 39'; font-size: 60px;
    }
    </style>    
</head>
<body>

    
  
<div class="container mt-1">
    <div class="row" id="form"  style="visibility:visible">

        <div class="col-2">
            <a class="navbar-brand" onCLick="window.location='../index.php'"><i class="fad fa-home" title="INICIO"></i></a>
            <a class="navbar-brand" onCLick="window.location='../login.php'"><i class="far fa-times-octagon" title="CERRAR SESION"></i></a>
        </div>
    
    
        <div  class="col-10">
        
            <form action="#" method="GET">
                <div class="row">

                    <div class="col-2">
                        <label for=""><a style="vertical-align: middle;">Tipo Movimiento</a></label>
                    </div>
                    <div class="col-3">
                        <select name="tipo" id="" class="form-control">
                            <option value="RL">ROTACION LOCAL</option>
                            <option value="RF">ROTACION A FRANQUICIA</option>
                            <option value="F">DEVOLUCION FALLAS</option>
                            <option value="D">DEVOLUCION DISCONTINUO</option>
                            <option value="C">DEVOLUCION COMERCIAL</option>
                            <option value="PE">PEDIDO ECOMMERCE</option>
                        </select>
                    </div>
                    <div class="col-2">
                    <label for="" ><a style="vertical-align: middle;">Nro Remito</a></label>
                    </div>
                    <div class="col-3">
                    <select name="nroRemito" id="" class="form-control">
                        <?php
                        foreach ($list as $key => $value) {
                            ?>
                            <option value="<?=$value['NCOMP_IN_S']?>"><?=$value['N_COMP']?> - <?=$value['COD_PRO_CL']?></option>
                        <?php
                        }
                        ?>
                    </select>
                    </div>

                    <div>
                    <input type="submit" class="btn btn-primary btn-sm" value="Consultar">
                    </div>
                
                </div>
            </form>

        </div>

    </div>

    <?php
    if(isset($_GET['nroRemito'])){
        // echo $_GET['nroRemito'];
        $remitos = $remitos->traerRemito($suc, $_GET['nroRemito']);
      
        foreach($remitos as $remito){
    ?>

        <div class="row" id="rotulo">
            <div class="col-1"></div>



            <div class="col-10 border border-dark rounded m-1" id="this">


                <div class="border border-dark m-1 text-center"><h1> TIPO DE ENVIO </h1></div>
                <div class="border border-dark m-1 text-center">
                <?php switch ($_GET['tipo']) {
                    case 'RL': echo '<h1>ROTACION LOCAL</h1>'; break; 
                    case 'RF': echo '<h1>ROTACION A FRANQUICIA</h1>'; break;
                    case 'F': echo '<h1>DEVOLUCION FALLAS</h1>'; break;
                    case 'D': echo '<h1>DEVOLUCION DISCONTINUO</h1>'; break;
                    case 'C': echo '<h1>DEVOLUCION COMERCIAL</h1>'; break;
                    case 'PE': echo '<h1>PEDIDO DE ECOMMERCE</h1>'; break;
                }?>
                </div>
                <div class="row">
                    <div class="col"></div>
                    <div class="col border border-dark m-1 text-center font-weight-bold" style="height: 70px"><h1><?= $_GET['tipo'];?></h1></div>
                    <div class="col"></div>
                </div>


                <div class="m-1 row">
                    <div class="border border-dark m-1 col-2"><h2>FECHA:</h2></div>
                    <div class="border border-dark m-1 col-4"><h2><?= $remito['FECHA']->format("Y-m-d");?></h2></div>
                    <div class="border border-dark m-1 col-2"><h2>CANT:</h2></div>
                    <div class="border border-dark m-1 col-2"><h2><?= $remito['CANTIDAD'];?></h2></div>
                </div>

                <div class="m-1 row">
                    <div class="border border-dark m-1 col"><h2>REMITO:</h2></div>
                    <div class="border border-dark m-1 col"><h2><?= $remito['N_COMP'];?></h2></div>
                </div>

                <div class="m-1 row">
                    <div class="border border-dark m-1 col text-center" id="codigoBarra">*<?= $remito['N_COMP'];?>*</div>
                </div>

                <div class="m-1 row">
                <div class="border border-dark m-1 col"><h2>ORIGEN:</h2></div>
                    <div class="border border-dark m-1 col"><h2><?= $remito['SUCURSAL_ORIGEN'];?></h2></div>
                </div>

                <div class="m-1 row">
                    <div class="border border-dark m-1 col"><h2>DESTINO:</h2></div>
                    <div class="border border-dark m-1 col"><h2><?= $remito['SUCURSAL_DESTINO'];?></h2></div>
                </div>

                <div class="m-1 row">
                    <div class="border border-dark m-1 col"><h2>NRO PRECINTO:</h2></div>
                    <div class="border border-dark m-1 col"></div>
                </div>
        
            </div>




            <div class="col-1"></div>
        </div>

        


    <?php
        }
    }
    
    ?>

</div>




<script src="Controlador/main.js"></script>



</body>
</html>

<?php
}
?>

