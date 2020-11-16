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

SELECT A.FECHA, A.CONTENEDOR, A.COD_ARTICU, B.DESCRIPCIO
FROM SOF_DISTRIBUCION_INICIAL A
INNER JOIN STA11 B
ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU
WHERE A.FECHA >= GETDATE()-180
AND ESTADO = 1 
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
				
				<td><input type="text" name="cantPed[]" value="0" size="4" onChange="total();verifica()"></td>
				
				
		</tr>

		
        <?php

        }

        ?>

		
        		
</table>

<input type="submit" value="Aceptar" class="btn btn-primary btn-sm" style="margin-left:80%">

</form>

</div>

<div>
</br></br></br></br><h5 align="center">En caso de querer mas cantidades, enviar un mail a <a href="mailto:asistentesupervision@xl.com.ar">Comercial</a></h5>
</div>

</body>
</html>

<?php
}
?>
