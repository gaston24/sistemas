<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
$permiso = $_SESSION['permisos'];
$suc = $_SESSION['codClient'];
$numSuc = $_SESSION['numsuc'];

?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Entregas de ecommerce</title>	
<?php include '../../css/header_simple.php'; ?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<!-- Including Font Awesome CSS from CDN to show icons -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="css/style.css">
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
	
	<title>Entregas Ecommerce</title>	
	<form action="procesar.php" id="sucu" method="post" style="margin-top:2rem">
	<div class="form-row mb-4" id="headerCheck">
		<button type="button" class="btn btn-primary" id="inicio" onClick="window.location.href='../index.php'">Inicio</button>
		<label style="margin-left:1%; margin-right:1%">Busqueda:</label>
		<input type="text" id="textBox"  placeholder="Busqueda rapida" onkeyup="myFunction()"  class="form-control form-control-sm col-md-2"></input>
		<!--<button><i class="fas fa-search"></i></button>-->
		<button type="submit" class="btn btn-primary ml-2">Guardar <i class="bi bi-cloud-arrow-up-fill"></i></i></button>
	</div>	

<?php

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	SET DATEFORMAT YMD

	SELECT ORIGEN, NRO_ORDEN_ECOMMERCE, RAZON_SOCIAL, N_COMP, LOCAL_ENTREGA, GC_GDT_NUM_GUIA, FECHA, N_IMPUESTO, ENTREGADO, FECHA_ENTREGADO, HORA_ENTREGA, FECHA_PEDIDO, PEDIDO, DEPOSITO, METODO_ENVIO FROM SJ_LOCAL_ENTREGA_TABLE
	WHERE N_IMPUESTO = '$numSuc' AND FECHA_PEDIDO >= GETDATE()-30
	GROUP BY ORIGEN, NRO_ORDEN_ECOMMERCE, RAZON_SOCIAL, N_COMP, LOCAL_ENTREGA, GC_GDT_NUM_GUIA, FECHA, N_IMPUESTO, ENTREGADO, FECHA_ENTREGADO, HORA_ENTREGA, FECHA_PEDIDO, PEDIDO, DEPOSITO, METODO_ENVIO
	ORDER BY ENTREGADO ASC, N_COMP
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="table-responsive">
	<table class="table table-hover table-condensed table-striped text-center">
		<thead class="thead-dark" style="font-size: 13px; font-weight: bold">
				<td class="col-">CANAL</td>
				<td class="col-">FECHA PED.</td>
				<td class="col-">PEDIDO</td>
				<td class="col-">ORDEN</td>
				<td class="col-">CLIENTE</td>
				<td class="col-">DEPOSITO</td>
				<td class="col-">METODO ENTREGA</td>
				<td class="col-">GUIA</td>
				<td class="col-">FECHA GUIA</td>
				<td class="col-">RETIRO</td>
				<td class="col-">FECHA ENTREGA</td>
				<td class="col-">HORA ENTREGA</td>
		</thead>

		<tbody id="table" class="text-center" style="font-size: 12px;">
			<?php
			while($v=odbc_fetch_array($result)){
			?>

			<tr >
				<td class="col-"><?= $v['ORIGEN'] ;?></td>
				<td class="col-"><?= $v['FECHA_PEDIDO'] ;?></td>
				<td class="col-"><?= $v['PEDIDO'] ;?></td>
				<td class="col-"><input readonly disabled class="form-control-plaintext" value="<?= $v['NRO_ORDEN_ECOMMERCE'] ;?>"></td>
				<td class="col-"><?= $v['RAZON_SOCIAL'] ;?></td>
				<td class="col-"><?= $v['DEPOSITO'] ;?></td>
				<td class="col-"><?= $v['METODO_ENVIO'] ;?></td>
				<td class="col-"><?= $v['GC_GDT_NUM_GUIA'] ;?></td>
				<td class="col-"><?= substr($v['FECHA'], 0, 10) ;?></td>
				<td class="col-">
					<input name="orden[]" type="checkbox" <?php if($v['FECHA_ENTREGADO']!= '' ){echo 'checked onclick="return false;"';}else{echo 'value= "'.$v['NRO_ORDEN_ECOMMERCE'].'" ';} ?> > 
				</td>
				<td class="col-"><?= $v['FECHA_ENTREGADO'] ;?></td>
				<td class="col-"><?= $v['HORA_ENTREGA'] ;?></td>
			</tr>


			<?php

			}

			?>


		</tbody>
	</table>
</div>
</form>

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