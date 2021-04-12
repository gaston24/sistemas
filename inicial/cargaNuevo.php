<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$codClient = $_SESSION['codClient'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Carga Inicial</title>
<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>

</br>
<div class="container-fluid">

<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$cid = odbc_connect($dsn, $user, $pass);

if(!$cid){echo "</br>Imposible conectarse a la base de datos!</br>";}

$sql="

SET DATEFORMAT YMD

SELECT A.FECHA, A.CONTENEDOR, A.COD_ARTICU, B.DESCRIPCIO, CAST(C.PRECIO AS float) PRECIO
FROM SOF_DISTRIBUCION_INICIAL A
INNER JOIN STA11 B
ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU
INNER JOIN GVA17 C
ON A.COD_ARTICU = C.COD_ARTICU
WHERE A.FECHA >= GETDATE()-180
--AND CENTRAL = 1 
AND ESTADO = 1 
AND C.NRO_DE_LIS = 30
AND A.COD_ARTICU COLLATE Latin1_General_BIN 
NOT IN (SELECT COD_ARTICU FROM SOF_DISTRIBUCION_INICIAL_RELACION WHERE COD_CLIENT = '$codClient' AND FECHA_PEDI >= GETDATE()-180)

";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


<h2 align="center">Articulos Disponibles</h2></br>

<form method="post" action="procesar.php">

<table class="table table-striped">

        <tr>

				<td >FECHA</td>
		
                <td >CONTENEDOR</td>
				
				<td></td>
				
				<td >CODIGO</td>
				
				<td></td>
				
				<td >DESCRIPCION</td>
				
				<td >CANTIDAD</td>
				
				<?php if(substr($codClient, 0, 2) == 'FR')
				{
				?>

					<td >PRECIO</td>

				<?php
				}
				?>

        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr ><!--style="font-size:smaller">-->

                <td><?php echo $v['FECHA'] ;?></td>
				
				<td><?php echo $v['CONTENEDOR'] ;?></td>
				
				<td><input name="contenedor[]" value="<?php echo $v['CONTENEDOR'] ;?>"  hidden></td>
				
				<td><?php echo $v['COD_ARTICU'] ;?></td>
				
				<td><input name="codArt[]" value="<?php echo $v['COD_ARTICU'] ;?>"  hidden></td>
				
				<td><?php echo $v['DESCRIPCIO'] ;?></td>
				
				<td><input type="text" name="cantPed[]" id="cantPedi" value="0" size="4" onkeyup="totalizar()"></td>

				<?php if(substr($codClient, 0, 2) == 'FR')
				{
				?>

					<td><a id="precioArt"><?php echo number_format($v['PRECIO'], 0, ",", ".") ;?></a></td>

				<?php
				}
				?>
				
				
		</tr>

		
        <?php

        }

        ?>

		
        		
</table>

<input type="submit" id="btnAceptar" value="Aceptar" class="btn btn-primary btn-sm" style="margin-left:80%">

</form>

</div>

<div>
</br></br></br></br>
<h2 align="center" id="cupoCreditoExcedido"></a></h2>
<h5 align="center">En caso de querer mas cantidades, enviar un mail a <a href="mailto:asistentesupervision@xl.com.ar">Comercial</a></h5>
</div>

<script>
var cupoCredi = <?= (int)$_SESSION['totalDisponible'];  ?>;
</script>

<script src="../pedidos/js/envio.js"></script>
<script src="../pedidos/js/main.js"></script>

</body>
</html>

<?php
}
?>
