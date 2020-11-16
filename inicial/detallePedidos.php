<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$codClient = $_SESSION['codClient'];

$_SESSION['codArt'] = $_GET['codArt'];
$_SESSION['contenedor'] = $_GET['contenedor'];

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Carga Inicial</title>
<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

<script>
			
			function pulsar(e) {
			tecla = (document.all) ? e.keyCode : e.which;
			return (tecla != 13);
			}
			
				
			function total() {
				var suma = 0;
				var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']"); //tomo todos los input con name='cantProd[]'

				var i;
				for (i = 0; i < x.length; i++) {
					suma += parseInt(0+x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
				}

				// ni idea dónde lo vas a mostrar ese dato, yo puse un input, pero puede ser cualquier otro elemento
				document.getElementById('total').value = suma;
			};
			
			
			
			
		
		</script>



</head>
<body>
<button type="button" class="btn btn-primary btn-sm" OnClick="location.href='admin.php' " style="margin:5px">Inicio</button>
</br>
<div class="container-fluid">

<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$codArt = $_SESSION['codArt'];

$cid = odbc_connect($dsn, $user, $pass);

if(!$cid){echo "</br>Imposible conectarse a la base de datos!</br>";}

$sql="


SELECT COD_CLIENT, NOM_COM, NRO_PEDIDO, COD_ARTICU, CANT 
FROM SOF_DISTRIBUCION_INICIAL_RELACION_VIEW 
WHERE COD_ARTICU = '$codArt'
GROUP BY COD_CLIENT, NOM_COM, NRO_PEDIDO, COD_ARTICU, CANT 
ORDER BY 1
";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>



<h2 align="center">Administración de Pedidos de Distribución Inicial</br>Codigo: <?php echo $_SESSION['codArt']; ?></h2></br>

<nav style="margin-left:10%; margin-right:10%">

<form method="POST" action="modificaPedido.php" onkeypress = "return pulsar(event)">

<table class="table table-striped" id="id_tabla">

        <tr>

				<td >CLIENTE</td>
				
				<td>NOMBRE</td>
		
                <td >NRO PEDIDO</td>
				
				<td ></td>
				
				<td >ARTICULO</td>

				<td>CANT</td>
				
				<td ></td>
				
				<td>MODIFICAR</td>
				
				<td>ELIMINAR</td>
				
        </tr>

		
        <?php

		$total = 0;
	   
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr ><!--style="font-size:smaller">-->

                <td><?php echo $v['COD_CLIENT'] ;?></td>
				
				<td><?php echo $v['NOM_COM'] ;?></td>
				
				<td><?php echo $v['NRO_PEDIDO'] ;?></td>
				
				<td><input type="text" name="pedido[]" value="<?php echo $v['NRO_PEDIDO'] ;?>" hidden></td>
				
				<td><?php echo $v['COD_ARTICU']; ?></td>
				
				<td><?php echo (int)($v['CANT']) ;?></td>	
				
				<td><input type="text" name="cant[]" value="<?php echo $v['CANT'] ;?>" hidden></td>	
				
				<td><input type="text" size="2" name="modificar[]" value="<?php echo (int)($v['CANT']) ;?>"></td>
				
				<td align="left" ><a href="eliminaPedidoSeleccionado.php?pedido=<?php echo $v['NRO_PEDIDO'] ?>">Eliminar</a></td>
			
		</tr>

		
        <?php

		$total = $total + $v['CANT'];
		
        }

        ?>

		
			
			
</table>



<input type="submit" value="Actualizar" class="btn btn-primary btn-sm" style="margin-left:80%">

</br>

</form>



</nav>

</div>
</br><br></br></br>

</body>
</html>

<?php
}
?>
