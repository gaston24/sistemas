<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
$permiso = $_SESSION['permisos'];
$suc = $_SESSION['codClient'];
$numsuc = $_SESSION['numsuc'];
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Comprobantes de ecommerce</title>	
<?php include '../../css/header_simple.php'; ?>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.js"></script>
<script src="https://kit.fontawesome.com/2c36e9b7b1.js"></script>
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
	
	
	<form id="sucu" method="GET" style="margin:20px">
	<div class="container col mb-3">
		<div class="form-row mb-1">
			<button type="button" class="btn btn-primary" style="margin-left:1%; width:max-content; height:max-content" onClick="window.location.href='../index.php'">Inicio</button>
			
		<div class="col-sm-2">
			<label class="col-sm col-form-label">Desde:</label>
			<input type="date" class="form-control form-control ml-2 col" name="desde" value="<?php if(!isset($_GET['desde'])){	echo $hoy;}else{ echo $_GET['desde'] ;}?>">
		</div>
	  
		<div class="col-sm-2">
			<label class="col-sm col-form-label">Hasta:</label>
			<input type="date"  class="form-control form-control ml-2 col" name="hasta" value="<?php if(!isset($_GET['hasta'])){	echo $hoy;}else{ echo $_GET['hasta'] ;}?>">
		</div>
			<div class="col">
				<label class="col-sm col-form-label ml-3">Búsqueda:</label>
				<input type="text" style="display: inline-block; margin-left: 30px; width:50%;" id="textBox1" name="comprobante" placeholder="Factura o cliente" class="form-control form-control"></input>
					<!--<button><i class="fas fa-search"></i></button>-->
				<input type="submit" value="Buscar" class="btn btn-primary ml-2">
			</div>
				
			<div class="col">
				<label class="col-sm col-form-label" style="margin-left: 210px;">Búsqueda rápida:</label>
				<input type="text" style="margin-left: 30px; width:60%; float: right;" onkeyup="myFunction()" id="textBox" name="comprobante" placeholder="Sobre cualquier campo..." class="form-control form-control"></input>
			</div>
		</div>
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

	SELECT * FROM
	(
	SELECT
	CAST(A.FECHA_PEDI AS DATE) FECHA, 
	ISNULL(A.LEYENDA_2, '') NOMBRE, 
	C.N_COMP, ISNULL(A.NRO_PEDIDO, '') PEDIDO , CAST(C.IMPORTE AS DECIMAL(10,2)) IMP_COMPROBANTE, 
	G.COD_ARTICU, H.DESCRIPCIO, CAST(G.CANT_PEDID AS INT) CANT, CAST(G.PRECIO AS DECIMAL(10,2)) IMP_ARTICULO,
	ISNULL(F.CARD_FIRST_DIGITS+'-'+F.CARD_LAST_DIGITS, '') TARJETA , F.ISSUER BANCO, I.N_CUIT,
	CASE WHEN A.COD_TRANSP = '0135'			  THEN 'FLEX'
		 WHEN A.COD_TRANSP = '0133'			  THEN 'DOMICILIO'
		 WHEN A.COD_TRANSP IN ('0123','0131') THEN 'TIENDA'	
	END TIPO_ENVIO, ISNULL(J.NRO_SUCURSAL, K.SUCURSAL_DESTINO) SUCURSAL,
	CASE WHEN AUDITORIA = 1 THEN 1 ELSE 0 END CONTROLADO,
	CASE WHEN C.N_COMP IS NOT NULL THEN 1 ELSE 0 END FACTURADO 
	FROM GVA21 A
	LEFT JOIN GVA55 B ON A.TALON_PED = B.TALON_PED AND A.NRO_PEDIDO = B.NRO_PEDIDO
	LEFT JOIN GVA12 C ON B.T_COMP = C.T_COMP AND B.N_COMP = C.N_COMP
	LEFT JOIN NEXO_PEDIDOS_ORDEN D ON A.ID_NEXO_PEDIDOS_ORDEN = D.ID_NEXO_PEDIDOS_ORDEN   
	LEFT JOIN GC_ECOMMERCE_ORDER E ON D.order_id_tienda = E.ORDER_ID collate Latin1_General_BIN              
	LEFT JOIN GC_ECOMMERCE_PAYMENT_DETAIL F ON cast(E.ORDER_ID AS varchar) = F.ORDER_ID 
	LEFT JOIN GVA03 G ON A.TALON_PED = G.TALON_PED AND A.NRO_PEDIDO = G.NRO_PEDIDO
	LEFT JOIN STA11 H ON G.COD_ARTICU = H.COD_ARTICU
	LEFT JOIN GVA38 I ON I.T_COMP = 'PED' AND A.NRO_PEDIDO = I.N_COMP
	LEFT JOIN SUCURSAL J ON E.ID_DIRECCION_SUCURSAL_ENTREGA = J.CA_423_ID_DIRECCION_SUCURSAL_ENTREGA_VTEX
	LEFT JOIN STA22 K ON C.COD_SUCURS = K.COD_SUCURS
	LEFT JOIN (SELECT NRO_PEDIDO, NRO_ORDEN_ECOMMERCE, AUDITORIA FROM SOF_AUDITORIA GROUP BY NRO_PEDIDO, NRO_ORDEN_ECOMMERCE, AUDITORIA) L ON A.ORDER_ID_TIENDA = L.NRO_ORDEN_ECOMMERCE COLLATE Latin1_General_BIN
	WHERE A.COD_CLIENT = '000000' AND (C.N_COMP = '$comp' OR A.LEYENDA_2 LIKE '%$comp%')
	) A
	WHERE FECHA BETWEEN '$desde' AND '$hasta' AND SUCURSAL LIKE '$numsuc'
	ORDER BY PEDIDO  

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="container-fluid">
<table class="table table-sm table-striped table-condensed table-bordered"  >
	<thead>
        <tr >
			<td class="col-1"><h6>FECHA</h6></td>
			<td class="col-3"><h6>NOMBRE</h6></td>
			<td class="col-"><h6>COMPROBANTE</h6></td>
			<td class="col-"><h6>PEDIDO</h6></td>
			<td class="col-"><h6>IMP_COMP</h6></td>
			<td class="col-1"><h6>CODIGO</h6></td>
			<td class="col-2"><h6>DESCRIPCION</h6></td>
			<td class="col-"><h6>CANT</h6></td>
			<td class="col-"><h6>IMP_ART</h6></td>
			<td class="col-"><h6>DNI</h6></td>
			<td class="col-3"><h6>BANCO</h6></td>
			<td class="col-1"><h6>TARJETA</h6></td>
			<td class="col-"><h6>TIPO_ENVIO</h6></td>
			<td width="1%"></td>
			<td width="1%"></td>
			<td width="1%"></td>
        </tr>
	</thead>
	<tbody id="table">
		<?php
		while($v=odbc_fetch_array($result)){
		?>


		<tr>

		<td class="col-1"><small><?= $v['FECHA'] ;?></small></td>
		<td class="col-3"><small><?= $v['NOMBRE'] ;?></small></td>
		<td class="col-"><small><?= $v['N_COMP'] ;?></small></td>
		<td class="col-"><small><?= $v['PEDIDO'] ;?></small></td>
		<td class="col-"><small><?= $v['IMP_COMPROBANTE'] ;?></small></td>
		<td class="col-1"><small><?= $v['COD_ARTICU'] ;?></small></td>
		<td class="col-2"><small><?= $v['DESCRIPCIO'] ;?></small></td>
		<td class="col-"><small><?= $v['CANT'] ;?></small></td>
		<td class="col-"><small><?= $v['IMP_ARTICULO'] ;?></small></td>
		<td class="col-"><small><?= $v['N_CUIT'] ;?></small></td>
		<td class="col-3"><small><?= $v['BANCO'] ;?></small></td>
		<td class="col-1"><small><?= $v['TARJETA'] ;?></small></td>
		<td class="col-"><small><?= $v['TIPO_ENVIO'] ;?></small></td>
		<td width="1%"> <a href="remitoEntrega/?nComp=<?= $v['N_COMP'] ;?>" target=”_blank”> <i class="fas fa-file-invoice" title="Print" id="iconPrint"></i></a></td>
		<td width="1%">
			<small>
				<?php if($v['FACTURADO']== 1){ ?>
					<i class="fas fa-receipt" title="Facturado" id="iconFacturado"></i>
						<?php }else if($v['FACTURADO']== 0){?>
						<?php } ?>
			</small>
		</td>
		<td width="1%">
			<small>
				<?php if($v['CONTROLADO']== 1){ ?>
					<i class="fa fa-clipboard-check" title="Controlado" id="iconControlado"></i>
						<?php }else if($v['CONTROLADO']== 0){?>
						<?php } ?>
			</small>
		</td>
			

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
/* function pulsar(e) {
  tecla = (document.all) ? e.keyCode : e.which;
  return (tecla != 13);
  } */

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