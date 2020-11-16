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
	<title>Comprobantes de ecommerce</title>	
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
	
	
	<form id="sucu" method="GET" style="margin:20px">
	<div class="form-row mb-1">
		<button type="button" class="btn btn-primary" style="margin-left:1%" onClick="window.location.href='../index.php'">Inicio</button>
		<label style="margin-left:1%; margin-right:1%">Busqueda:</label>
		<input type="text" id="textBox" name="comprobante" placeholder="Factura o cliente" class="form-control form-control-sm col-md-2"></input>
		<!--<button><i class="fas fa-search"></i></button>-->
		<input type="submit" value="Buscar" class="btn btn-primary" style="margin-left:1%">
	</div>	

<?php

if(isset($_GET['comprobante'])){
	$comp = $_GET['comprobante'];


$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";

$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	SET DATEFORMAT YMD

	SELECT CAST(A.FECHA_EMIS AS DATE) FECHA, 
	ISNULL(E.RAZON_SOCI, '') NOMBRE, 
	A.N_COMP, ISNULL(D.NRO_PEDIDO, '') PEDIDO , CAST(A.IMPORTE AS DECIMAL(10,2)) IMP_COMPROBANTE, 
	B.COD_ARTICU, C.DESCRIPCIO, CAST(B.CANTIDAD AS INT) CANT, CAST(B.IMP_NETO_P * 1.21 AS DECIMAL(10,2)) IMP_ARTICULO
	FROM GVA12 A
	INNER JOIN GVA53 B
	ON A.T_COMP = B.T_COMP AND A.N_COMP = B.N_COMP
	INNER JOIN STA11 C
	ON B.COD_ARTICU = C.COD_ARTICU
	LEFT JOIN GVA55 D
	ON A.T_COMP = D.T_COMP AND A.N_COMP = D.N_COMP
	LEFT JOIN GVA38 E 
	ON (E.T_COMP = 'FAC' AND A.N_COMP = E.N_COMP)-- OR (E.T_COMP = 'PED' AND E.N_COMP = D.NRO_PEDIDO)
	WHERE A.COD_CLIENT = '000000'
	AND A.FECHA_EMIS >= GETDATE()-180
	AND A.T_COMP = 'FAC'
	AND (A.N_COMP = '$comp' OR E.RAZON_SOCI LIKE '%$comp%')
	ORDER BY A.N_COMP
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="container-fluid">
<table class="table table-striped table-condensed"  >
	<thead>
        <tr >
			<td class="col-"><h6>FECHA</h6></td>
			<td class="col-"><h6>NOMBRE</h6></td>
			<td class="col-"><h6>COMPROBANTE</h6></td>
			<td class="col-"><h6>PEDIDO</h6></td>
			<td class="col-"><h6>IMP_COMP</h6></td>
			<td class="col-"><h6>CODIGO</h6></td>
			<td class="col-"><h6>DESCRIPCION</h6></td>
			<td class="col-"><h6>CANT</h6></td>
			<td class="col-"><h6>IMP_ART</h6></td>
        </tr>
	</thead>
	<tbody id="table">
		<?php
		while($v=odbc_fetch_array($result)){
		?>


		<tr >

		<td class="col-"><?php echo $v['FECHA'] ;?></td>
		<td class="col-"><?php echo $v['NOMBRE'] ;?></td>
		<td class="col-"><?php echo $v['N_COMP'] ;?></td>
		<td class="col-"><?php echo $v['PEDIDO'] ;?></td>
		<td class="col-"><?php echo $v['IMP_COMPROBANTE'] ;?></td>
		<td class="col-"><?php echo $v['COD_ARTICU'] ;?></td>
		<td class="col-"><?php echo $v['DESCRIPCIO'] ;?></td>
		<td class="col-"><?php echo $v['CANT'] ;?></td>
		<td class="col-"><?php echo $v['IMP_ARTICULO'] ;?></td>
			
		

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