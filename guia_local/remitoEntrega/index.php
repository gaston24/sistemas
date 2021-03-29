<?php

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
$nComp = $_GET['nComp'];
include 'class/remito.php';
$remitos = new Remito();
$list = $remitos->traerRemito($nComp);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Remitos - Entrega Local</title>
    <link rel="shortcut icon" href="../../assets/css/icono.jpg" />
    <link rel="stylesheet" href="../../assets/css/bootstrap/bootstrap.min.css" >
    <link rel="stylesheet" href="style.css" >
    <script src="../../assets/css/bootstrap/jquery-3.5.1.slim.min.js" ></script>
    <script src="../../assets/css/bootstrap/popper.min.js" ></script>
    <script src="../../assets/css/bootstrap/bootstrap.min.js" ></script>
</head>
<body>

    
  
<div class="container">


    <?php
    if(isset($_GET['nComp'])){
        foreach($list[0] as $lista){
    ?>

        <div class="row" id="rotulo">

        <div class="asd" >
        
            <div class="row border border-dark">
            
                <div class="col-4"></div>
                <div class="col-4"><img src="../../assets/css/logo.jpg" width="100%" height="100%" class="img-thumbnail"></div>
                <div class="col-4"></div>
            
            </div>

            <div class="border border-dark mt-2 mb-2  bg-dark text-white " >
                <h4 class="text-center">COMPROBANTE DE ENTREGA DEL PRODUCTO</h4>
            </div>
            <div class="border border-dark mt-2 mb-2  bg-dark text-white " >
                <h3 class="text-center">DATOS DEL COMPRADOR</h3>
            </div>

            <div class="row border border-dark">

            
                <div class="col-4 mt-2 mb-2"><strong>Nº COMP: </strong> <?=$lista['N_COMP']?></div>
                <div class="col-4 mt-2 mb-2"><strong>NOMBRE: </strong> <?=$lista['NOMBRE']?></div>
                <div class="col-4 mt-2 mb-2"><strong>DNI: </strong> <?=$lista['N_CUIT']?></div>
            
            
            </div>
            <div class="row border border-dark">

            
            
                <div class="col-4 mt-2 mb-2"><strong>TELEFONO: </strong> <?=$lista['TELEFONO']?></div>
                <div class="col-4 mt-2 mb-2"><strong>BANCO: </strong> <?=$lista['BANCO']?></div>
                <div class="col-4 mt-2 mb-2"><strong>TARJETA: </strong> <?=$lista['TARJETA']?></div>
            
            </div>

            <div class="border border-dark mt-2 mb-2  bg-dark text-white " >
                <h3 class="text-center">DATOS DE LA COMPRA</h3>
            </div>

            <div class="row border border-dark">

                <div class="col-3 mt-2 mb-2"><strong>CODIGO</strong></div>
                <div class="col-3 mt-2 mb-2"><strong>DESCRIPCION</strong></div>
                <div class="col-3 mt-2 mb-2"><strong>CANT</strong></div>
                <div class="col-3 mt-2 mb-2"><strong>IMPORTE</strong></div>

            </div>


            <div class="">
            
            <?php
                foreach($list[1] as $lista){
            ?>

            <div class="row">

            
                <div class="col-3 mt-2 mb-2"><?=$lista['COD_ARTICU']?></div>
                <div class="col-3 mt-2 mb-2"><?=$lista['DESCRIPCIO']?></div>
                <div class="col-3 mt-2 mb-2"><?=$lista['CANT']?></div>
                <div class="col-3 mt-2 mb-2"><?=$lista['IMP_ARTICULO']?></div>
            
            
            </div>
            
            <?php
                }
            ?>
        
            </div>
        </div>

        </div>

    <?php
        }
    }
    
    ?>

    <div class="row border border-dark" style="position: fixed; bottom: 0; width: 100%; height: 10%;"> 
            <div style="margin-left:5%" >
                <strong>Firma del comprador o autorizado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fecha de entrega:</strong>
            </div>
        <div >
            <p style="margin-left:5%">Al firmar el comprobante declaro haber recibido conforme el producto.</p>
            <p style="margin-left:5%">IMPORTANTE: En caso de contracargos por parte del titular de la tarjeta, se presentará este documento como prueba de la entrega del producto.</p>
        </div>
    </div>

</div>




<script src="Controlador/main.js"></script>

<script>
$(document).ready(function(){
    window.print();
});

</script>



</body>
</html>

<?php
}
?>

