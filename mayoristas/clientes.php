<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$vendedor = $_SESSION['vendedor'];

?>
<!doctype html>
<html charset="UTF-8">
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

</br>
<div class="container-fluid">

<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$cid = odbc_connect($dsn, $user, $pass);

if(!$cid){echo "</br>Imposible conectarse a la base de datos!</br>";}

$sql="


SELECT COD_CLIENT, RAZON_SOCI FROM GVA14
WHERE COD_VENDED = '$vendedor'
AND COD_CLIENT LIKE 'MA%'
AND FECHA_INHA = '1800-01-01'
ORDER BY 1

";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


<h2 align="center">Seleccionar Cliente</h2></br>

<nav style="margin-left:20%; margin-right:20%">


<table class="table table-striped" id="id_tabla">

        <tr>

				<td ><strong>CODIGO</strong></td>
				
				<td align='center'><strong>NOMBRE</strong></td>
					
        </tr>

		
        <?php
	
		$total = 0;
       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr ><!--style="font-size:smaller">-->

                <td><?php echo $v['COD_CLIENT'] ;?></td>
					
				<td><a href="pedidos.php?cliente=<?php echo $v['COD_CLIENT'] ;?>"><?php echo $v['RAZON_SOCI'] ;?></td>
						
		</tr>

		
		
        <?php

		$total = $total+1;
		
        }

        ?>

		
			
			
</table>







</nav>

</div>


</body>
</html>

<?php
}
?>
