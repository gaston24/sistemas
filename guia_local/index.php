<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
$permiso = $_SESSION['permisos'];
$suc = $_SESSION['codClient'];
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Entregas de ecommerce</title>	
<?php include '../../css/header_simple.php'; ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.js"></script>
<script src="https://kit.fontawesome.com/2c36e9b7b1.js"></script>
</head>

<body>	
	
<?php
if(!isset($_GET['desde'])){
	$ayer = date('Y-m').'-'.strright(('0'.((date('d')))),2);
}else{
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];
}
?>
	
		
	<form action="procesar.php" id="sucu" method="post" style="margin:20px">
	<div class="form-row mb-1">
		<button type="button" class="btn btn-primary" style="margin-left:1%" onClick="window.location.href='../index.php'">Inicio</button>
		<label style="margin-left:1%; margin-right:1%">Busqueda:</label>
		<input type="text" id="textBox"  placeholder="Busqueda rapida" onkeyup="myFunction()"  class="form-control form-control-sm col-md-2"></input>
		<!--<button><i class="fas fa-search"></i></button>-->
		<input type="submit" value="Grabar" class="btn btn-primary" style="margin-left:1%">
	</div>	

<?php

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	SET DATEFORMAT YMD

	SELECT * FROM SJ_LOCAL_ENTREGA_TABLE
	WHERE CLIENTE = '$suc'
	AND (FECHA >= GETDATE()-30 OR ORIGEN = 'ML')
	ORDER BY ENTREGADO ASC, N_COMP 
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="container">
<table class="table table-striped table-condensed"  >
	<thead>
        <tr >
			<td class="col-"><h6>CANAL</h6></td>
			<td class="col-"><h6>ORDEN</h6></td>
			<td class="col-"><h6>CLIENTE</h6></td>
			<td class="col-"><h6>GUIA</h6></td>
			<td class="col-"><h6>FECHA GUIA</h6></td>
			<td class="col-"><h6>RETIRO</h6></td>
			<td class="col-"><h6>FECHA RETIRO</h6></td>
        </tr>
	</thead>
	<tbody id="table">
		<?php
		while($v=odbc_fetch_array($result)){
		?>


		<tr >

		<td class="col-"><?php echo $v['ORIGEN'] ;?></td>
			
		<td class="col-"><input readonly class="form-control-plaintext" value="<?php echo $v['NRO_ORDEN_ECOMMERCE'] ;?>"></td>

		<td class="col-"><?php echo $v['RAZON_SOCIAL'] ;?></td>
			
		<td class="col-"><?php echo $v['GC_GDT_NUM_GUIA'] ;?></td>

		<td class="col-"><?php echo $v['FECHA'] ;?></td>

		<td class="col-">
			<input name="orden[]" type="checkbox" <?php if($v['FECHA_ENTREGADO']!= '' ){echo 'checked onclick="return false;"';}else{echo 'value= "'.$v['NRO_ORDEN_ECOMMERCE'].'" ';} ?> > 
		</td>

		<td class="col-"><?php echo $v['FECHA_ENTREGADO'] ;?></td>

		</tr>


		<?php

		}

		?>


	</tbody>
</table>

</form>
</div>
<?php


}
?>

<script>


function myFunction() {
	var input, filter, table, tr, td, td2, i, txtValue;
	input = document.getElementById('textBox');
	filter = input.value.toUpperCase();
	table = document.getElementById("table");
	tr = table.getElementsByTagName('tr');
	//tr = document.getElementById('tr');
	
	 for (i = 0; i < tr.length; i++) {
    visible = false;
    /* Obtenemos todas las celdas de la fila, no sólo la primera */
    td = tr[i].getElementsByTagName("td");
	
    for (j = 0; j < td.length; j++) {
      if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
        visible = true;
      }
    }
    if (visible === true) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}
</script>

</body>
</html>