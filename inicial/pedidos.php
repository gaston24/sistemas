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
WHERE CENTRAL = 1 
--AND A.COD_ARTICU NOT IN (SELECT COD_ARTICU FROM SOF_DISTRIBUCION_INICIAL_RELACION WHERE COD_CLIENT = '$codClient')

";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


<h2 align="center">Administración de Pedidos</h2></br>

<table class="table table-striped">

        <tr>

				<td >FECHA</td>
		
                <td >CONTENEDOR</td>
				
				<td >CODIGO</td>
				
				<td >DESCRIPCION</td>

				
				
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr ><!--style="font-size:smaller">-->

                <td><?php echo $v['FECHA'] ;?></td>
				
				<td><?php echo $v['CONTENEDOR'] ;?></td>
				
				<td><a href="detallePedidos.php?codArt=<?php echo $v['COD_ARTICU'] ;?>&contenedor=<?php echo $v['CONTENEDOR'] ;?>"><?php echo $v['COD_ARTICU'] ;?></a></td>
				
				<td><?php echo $v['DESCRIPCIO'] ;?></td>
				
			
		</tr>

		
        <?php

        }

        ?>

		
        		
</table>


</div>


</body>
</html>

<?php
}
?>
