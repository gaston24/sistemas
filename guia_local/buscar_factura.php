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

$hoy = date("Y-m-d");

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
			<input type="date" class="form-control form-control ml-2 col" name="desde" value="<?php if(!isset($_GET['desde'])){	echo date("Y-m-d",strtotime($hoy."- 30 days"));}else{ echo $_GET['desde'] ;}?>">
		</div>
	  
		<div class="col-sm-2">
			<label class="col-sm col-form-label">Hasta:</label>
			<input type="date"  class="form-control form-control ml-2 col" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $hoy;}else{ echo $_GET['hasta'] ;}?>">
		</div>
			<div class="col">
				<label class="col-sm col-form-label ml-3">Búsqueda:</label>
				<input type="text" style="display: inline-block; margin-left: 30px; width:50%;" id="textBox1" name="comprobante" placeholder="Factura o cliente" class="form-control form-control"></input>
					<!--<button><i class="fas fa-search"></i></button>-->
				<input type="submit" value="Buscar" class="btn btn-primary ml-2">
			</div>
				
			<div class="col">
				<label class="col-sm col-form-label" style="margin-left: 210px;">Búsqueda rápida:</label>
				<input type="text" style="margin-left: 30px; width:60%; float: right;" onkeyup="myFunction()" id="textBox" name="comprobante2" placeholder="Sobre cualquier campo..." class="form-control form-control"></input>
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

	SELECT CAST(A.FECHA_PEDI AS DATE) FECHA, A.LEYENDA_2 NOMBRE, E.N_COMP, A.NRO_PEDIDO PEDIDO, CAST(E.IMPORTE AS DECIMAL(10,2)) IMP_COMPROBANTE, B.COD_ARTICU, F.DESCRIPCIO, CAST(B.CANT_PEDID AS INT) CANT, CAST(B.PRECIO AS DECIMAL(10,2)) IMP_ARTICULO,
	ISNULL(I.CARD_FIRST_DIGITS+'-'+I.CARD_LAST_DIGITS, '') TARJETA , I.ISSUER BANCO, J.N_CUIT,
	ISNULL(REPLACE(G.NOMBRE_SUC, 'RT - SUC - ', ''), C.XML_CA_1111_SELECCIONABLE_PARA_TIPO_STOCK) ORIGEN, C.XML_CA_1111_METODO_ENTREGA TIPO_ENVIO, L.TIENDA,  
	CASE WHEN AUDITORIA = 1 THEN 1 ELSE 0 END CONTROLADO,
	CASE WHEN E.N_COMP IS NOT NULL THEN 1 ELSE 0 END FACTURADO FROM GVA21 A
	INNER JOIN GVA03 B ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED
	INNER JOIN 
	(
		SELECT 
		COD_TRANSP,
		GVA24_CAMPOS_ADICIONALES.XML_CA.value('CA_1111_SELECCIONABLE_PARA_TIPO_STOCK', 'varchar(15)') XML_CA_1111_SELECCIONABLE_PARA_TIPO_STOCK,
		GVA24_CAMPOS_ADICIONALES.XML_CA.value('CA_1111_METODO_ENTREGA', 'varchar(20)') XML_CA_1111_METODO_ENTREGA
		FROM GVA24
		OUTER APPLY GVA24.CAMPOS_ADICIONALES.nodes('CAMPOS_ADICIONALES') as GVA24_CAMPOS_ADICIONALES(XML_CA)
	) C ON A.COD_TRANSP = C.COD_TRANSP
	LEFT JOIN GVA55 D ON A.TALON_PED = D.TALON_PED AND A.NRO_PEDIDO = D.NRO_PEDIDO
	LEFT JOIN GVA12 E ON D.T_COMP = E.T_COMP AND D.N_COMP = E.N_COMP
	INNER JOIN STA11 F ON B.COD_ARTICU = F.COD_ARTICU
	LEFT JOIN STA22 G ON E.COD_SUCURS = G.COD_SUCURS
	LEFT JOIN GC_ECOMMERCE_PEDIDO H ON A.ID_NEXO_PEDIDOS_ORDEN = H.ID_GC_ECOMMERCE_ORDER
	LEFT JOIN GC_ECOMMERCE_PAYMENT_DETAIL I ON H.ID_GC_ECOMMERCE_ORDER = I.ID_GC_ECOMMERCE_ORDER
	LEFT JOIN GVA38 J ON J.T_COMP = 'PED' AND A.NRO_PEDIDO = J.N_COMP
	LEFT JOIN (SELECT NRO_PEDIDO, NRO_ORDEN_ECOMMERCE, AUDITORIA FROM SOF_AUDITORIA GROUP BY NRO_PEDIDO, NRO_ORDEN_ECOMMERCE, AUDITORIA) K ON A.ORDER_ID_TIENDA = K.NRO_ORDEN_ECOMMERCE COLLATE Latin1_General_BIN
	LEFT JOIN 
		(
			SELECT NRO_SUCURSAL, DESC_SUCURSAL TIENDA,
			SUCURSAL_CAMPOS_ADICIONALES.XML_CA.value('CA_423_ID_DIRECCION_SUCURSAL_ENTREGA_VTEX', 'varchar(100)') XML_CA_423_ID_DIRECCION_SUCURSAL_ENTREGA_VTEX
			FROM SUCURSAL
			OUTER APPLY SUCURSAL.CAMPOS_ADICIONALES.nodes('CAMPOS_ADICIONALES') as SUCURSAL_CAMPOS_ADICIONALES(XML_CA)
			WHERE SUCURSAL_CAMPOS_ADICIONALES.XML_CA.value('CA_423_ID_DIRECCION_SUCURSAL_ENTREGA_VTEX', 'varchar(100)') != ''
		) L ON A.LEYENDA_3 = L.XML_CA_423_ID_DIRECCION_SUCURSAL_ENTREGA_VTEX
	WHERE (E.N_COMP = '$comp' OR A.LEYENDA_2 LIKE '%$comp%') AND
	A.COD_CLIENT = '000000'	AND A.FECHA_PEDI BETWEEN '$desde' AND '$hasta' AND A.TALON_PED = '99'   
	ORDER BY A.NRO_PEDIDO

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="container-fluid">
<table class="table table-sm table-striped table-condensed table-bordered">
	<thead style="background-color: #343a40; color: white">
        <tr >
			<td class="col-1" style="font-size: 12px; font-weight:bold;">FECHA</td>
			<td class="col-2" style="font-size: 12px; font-weight:bold;">NOMBRE</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">COMPROBANTE</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">PEDIDO</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">IMP_COMP</td>
			<td class="col-1" style="font-size: 12px; font-weight:bold;">CODIGO</td>
			<td class="col-3" style="font-size: 12px; font-weight:bold;">DESCRIPCION</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">CANT</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">IMP_ART</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">DNI</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">BANCO</td>
			<td class="col-1" style="font-size: 12px; font-weight:bold;">TARJETA</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">ORIGEN</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">METODO<br>ENTREGA</td>
			<td class="col-" style="font-size: 12px; font-weight:bold;">TIENDA</td>
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

		<td class="col-1" style="font-size: 11px;"><?= $v['FECHA'] ;?></td>
		<td class="col-3" style="font-size: 11px;"><?= $v['NOMBRE'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['N_COMP'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['PEDIDO'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['IMP_COMPROBANTE'] ;?></td>
		<td class="col-1" style="font-size: 11px;"><?= $v['COD_ARTICU'] ;?></td>
		<td class="col-2" style="font-size: 11px;"><?= $v['DESCRIPCIO'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['CANT'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['IMP_ARTICULO'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['N_CUIT'] ;?></td>
		<td class="col-3" style="font-size: 11px;"><?= $v['BANCO'] ;?></td>
		<td class="col-1" style="font-size: 11px;"><?= $v['TARJETA'] ;?></td>
		<td class="col-1" style="font-size: 11px;"><?= $v['ORIGEN'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['TIPO_ENVIO'] ;?></td>
		<td class="col-" style="font-size: 11px;"><?= $v['TIENDA'] ;?></td>
		<td width="1%"> <a href="remitoEntrega/?nComp=<?= $v['N_COMP'] ;?>" target=”_blank”> <i class="fas fa-file-invoice" title="Print" id="iconPrint"></i></a></td>
		<td width="1%">
				<?php if($v['FACTURADO']== 1){ ?>
					<i class="fas fa-receipt" title="Facturado" id="iconFacturado"></i>
						<?php }else if($v['FACTURADO']== 0){?>
						<?php } ?>
		</td>
		<td width="1%">
				<?php if($v['CONTROLADO']== 1){ ?>
					<i class="fa fa-clipboard-check" title="Controlado" id="iconControlado"></i>
						<?php }else if($v['CONTROLADO']== 0){?>
						<?php } ?>
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