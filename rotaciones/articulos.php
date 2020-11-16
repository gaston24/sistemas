<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Consulta de Stock</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head>
<body>


<div style="width:20%; float:left; display:block; margin-top:2%; margin-left:2%">
	<button onClick="location.href='index.php'" class="btn btn-primary btn-sm">Volver</button>
</div>
<div style="width:75%; float:right; display:block;">
	<form action="" id="pedidos" style="margin:20px">
	CODIGO DEL ARTICULO:
	<input type="text" name="codigo" placeholder="Código de articulo" id="caja">
	<input type="submit" value="CONSULTAR"  class="btn btn-primary btn-sm"></br>
	</form>
</div>

</br></br>
<div class="container">
<?php
if(isset ($_GET['codigo'])){
$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";
$codArt = $_GET['codigo'];

$cid = odbc_connect($dsn, $user, $pass);

if(!$cid){echo "</br>Imposible conectarse a la base de datos!</br>";}

$sql="
SET DATEFORMAT YMD


SELECT A.COD_CLIENT, C.RAZON_SOCI, CAST(A.FECHA_PEDI AS DATE) FECHA_PEDI, A.NRO_PEDIDO, A.LEYENDA_1, CAST(B.CANT_PEDID AS INT) CANT
FROM GVA21 A
INNER JOIN GVA03 B
ON A.TALON_PED = B.TALON_PED AND A.NRO_PEDIDO = B.NRO_PEDIDO
INNER JOIN GVA14 C
ON A.COD_CLIENT = C.COD_CLIENT
WHERE B.COD_ARTICU LIKE '$codArt'
AND A.COD_SUCURS LIKE '03'
ORDER BY 1, 4



";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>





<table class="table table-striped">

        <tr>

				<td align="left">COD CLIENTE</td>
		
                <td align="left">NOMBRE</td>
				
				<td align="center">FECHA PEDIDO</td>
				
				<td align="center">NRO PEDIDO</td>
				
				<td align="center">TIPO PEDIDO</td>
				
				<td align="center">CANT</td>

        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

                <td><?php echo $v['COD_CLIENT'] ;?></a></td>
				
				<td><?php echo $v['RAZON_SOCI'] ;?></a></td>
				
				<td align="center"><?php echo $v['FECHA_PEDI'] ;?></a></td>
				
				<td align="center"><?php echo $v['NRO_PEDIDO'] ;?></a></td>
				
				<td align="center"><?php echo $v['LEYENDA_1'] ;?></a></td>
				
				<td align="center"><?php echo $v['CANT'] ;?></a></td>

		</tr>

		
        <?php

        }

        ?>

		
        		
</table>


<?php
}
?>

</div>
<script>
window.onload = function() {
  var input = document.getElementById("caja").focus();
}
</script>
</body>
</html>

<?php
}
?>
