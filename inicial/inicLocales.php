<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

$codClient = $_SESSION['codClient'];

$_SESSION['codArt'] = $_GET['codArt'];
$codArt = $_GET['codArt'];

$_SESSION['contenedor'] = $_GET['contenedor'];



$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$cid = odbc_connect($dsn, $user, $pass);

if(!$cid){echo "</br>Imposible conectarse a la base de datos!</br>";}


$sql="
SELECT (CANT_PACK - CANT) DISPONIBLE FROM SOF_DISTRIBUCION_INICIAL A
INNER JOIN (SELECT COD_ARTICU, SUM(CANT) CANT FROM SOF_DISTRIBUCION_INICIAL_RELACION GROUP BY COD_ARTICU) B
ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN
WHERE A.COD_ARTICU = '$codArt'
";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result)){
$cantDisp = $v['DISPONIBLE'] ;
}

var_dump($cantDisp);

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



$sql="


SELECT NRO_SUCURSAL, B.COD_CLIENT, DESC_SUCURSAL, 
CASE NRO_SUCURSAL
WHEN 1 THEN 3
WHEN 3 THEN 3
WHEN 2 THEN 3
WHEN 7 THEN 3
WHEN 54 THEN 3
WHEN 72 THEN 2
WHEN 66 THEN 2
WHEN 10 THEN 2
WHEN 11 THEN 2
WHEN 13 THEN 3
WHEN 38 THEN 2
WHEN 75 THEN 3
WHEN 40 THEN 3
WHEN 53 THEN 3
WHEN 8 THEN 2
WHEN 6 THEN 3
WHEN 76 THEN 1
WHEN 8 THEN 3
WHEN 32 THEN 3
WHEN 33 THEN 3
WHEN 60 THEN 1
WHEN 48 THEN 3
WHEN 29 THEN 2
WHEN 70 THEN 2
WHEN 16 THEN 1
ELSE 1
END 
CANT
FROM SUCURSAL A
INNER JOIN GVA14 B
ON A.NRO_SUCURSAL = B.N_IMPUESTO
WHERE NRO_SUCURSAL IN (2, 3, 6, 7, 10, 11, 16, 29, 32, 33, 40, 48, 53, 54, 60, 66, 70, 72, 75, 76, 78, 79)
AND B.N_IMPUESTO LIKE '[0-9]%' AND LEN(B.N_IMPUESTO)<5
ORDER BY 1

";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


<h2 align="center">Cantidad a Enviar para Locales Propios</br>Codigo: <?php echo $_SESSION['codArt']; ?></h2></br>

<nav style="margin-left:20%; margin-right:20%">

<form method="POST" action="procesarLocales.php" onKeyUp = "return pulsar(event)">

<table class="table table-striped" id="id_tabla">

        <tr>

				<td >NRO SUCURSAL</td>
				
				<td></td>
		
                <td >DESCRIPCION</td>
				
				<td >CANT</td>

				<td></td>
				
        </tr>

		
        <?php

		$total = 0;
	   
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr ><!--style="font-size:smaller">-->

                <td align="center"><?php echo $v['NRO_SUCURSAL'] ;?></td>
				
				<td><input name="codClient[]" value="<?php echo $v['COD_CLIENT'] ;?>" hidden></td>
				
				<td><?php echo $v['DESC_SUCURSAL'] ;?></td>
				
				<td><input type="text" value="<?php echo $v['CANT']; ?>" name="cantPed[]" size="5"  id="articulo" onChange="total()"></td>
				
				<td><input name="numsuc[]" value="<?php echo $v['NRO_SUCURSAL'] ;?>" hidden></td>	
			
		</tr>

		
        <?php

		$total = $total + $v['CANT'];
		
        }

        ?>

		
			
			
</table>



<input type="submit" id="botonEnviar" value="Enviar Pedidos" class="btn btn-primary btn-sm" style="margin-left:80%">

</br>

</form>

<div>
	Cantidad disponible: <input  size="4" value="<?=$cantDisp ?>" type="text">
	Total de los pedidos: <input name="total_todo" size="4" id="total" value="<?php echo $total ?>" type="text">
	</br></br>
</div >

</nav>

</div>

<script>

			
function pulsar(e) {
tecla = (document.all) ? e.keyCode : e.which;
return (tecla != 13);
}

var cantDisp = <?=$cantDisp;?>



function total() {
var suma = 0;
var x = document.querySelectorAll("#id_tabla input[name='cantPed[]']"); //tomo todos los input con name='cantProd[]'

var i;
for (i = 0; i < x.length; i++) {
suma += parseInt(0+x[i].value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
}
console.log(cantDisp);
// ni idea dónde lo vas a mostrar ese dato, yo puse un input, pero puede ser cualquier otro elemento
document.getElementById('total').value = suma;

if(document.getElementById('total').value > cantDisp){
	document.getElementById("botonEnviar").disabled = true;
}else{
	document.getElementById("botonEnviar").disabled = false;
}

};





</script>

</body>
</html>

<?php
}
?>
