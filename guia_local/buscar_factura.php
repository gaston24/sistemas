<?php 
session_start(); 
require_once $_SERVER['DOCUMENT_ROOT'] . '/sistemas/assets/js/js.php';
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
<link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">
	<title>Comprobantes de ecommerce</title>	


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
	
.loading {
	position: fixed;
	left: 0px;
	top: 0px;
	width: 100%;
	height: 100%;
	z-index: 9999;
	background: url('images/spinnervlll.gif') 50% 50% no-repeat rgb(240, 240, 240);
	opacity: .8;
  }

</style>

</head>

<body>	
	

<?php

$hoy = date("Y-m-d");

if(!isset($_GET['desde'])){
	// $ayer = date('Y-m').'-'.strright(('0'.((date('d')))),2);
	$ayer = date("Y-m-d",strtotime($hoy."- 1 days"));
}else{
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];
}
?>
	
	
	<form id="sucu" method="GET" style="margin-top:15px">
	<div class="container col mb-3">
		<div class="boxLoading"></div>
		<div class="form-row mb-1">
			<button type="button" class="btn btn-primary" style="margin-left:1%; width:max-content; height:max-content" onClick="window.location.href='../index.php'">Inicio</button>
			
		<div class="col-sm-2">
			<label class="col-sm col-form-label">Desde:</label>
			<input type="date" class="form-control form-control-sm ml-2 col" name="desde" value="<?php if(!isset($_GET['desde'])){	echo date("Y-m-d",strtotime($hoy."- 30 days"));}else{ echo $_GET['desde'] ;}?>">
		</div>
	  
		<div class="col-sm-2">
			<label class="col-sm col-form-label">Hasta:</label>
			<input type="date"  class="form-control form-control-sm ml-2 col" name="hasta" value="<?php if(!isset($_GET['hasta'])){ echo $hoy;}else{ echo $_GET['hasta'] ;}?>">
		</div>
			<div class="col">
				<label class="col-sm col-form-label ml-3">Búsqueda:</label>
				<input type="text" style="display: inline-block; margin-left: 30px; width:50%;" class="form-control form-control-sm" id="textBox1" name="comprobante" placeholder="Factura o cliente" class="form-control form-control"></input>
					<!--<button><i class="fas fa-search"></i></button>-->
				<input type="submit" value="Buscar" class="btn btn-primary ml-2" onclick="mostrarSpinner()">
			</div>
				
			<div class="col-sm-2">
				<label class="col-sm col-form-label">Búsqueda rápida:</label>
				<input type="text" style="margin-left: 30px; float: right;" class="form-control form-control-sm" onkeyup="myFunction()" id="textBox" name="comprobante2" placeholder="Sobre cualquier campo..." class="form-control form-control"></input>
			</div>
		</div>
	</div>

<?php


if(isset($_GET['comprobante'])){
	$comp = $_GET['comprobante'];

	include_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';

	$conn = new Conexion;

	$cid = $conn->conectar('central');


	$sql=
		"
		SET DATEFORMAT YMD

		SELECT TOP 200 CAST(A.FECHA_PEDI AS DATE) FECHA, A.LEYENDA_2 NOMBRE, E.N_COMP, A.NRO_PEDIDO PEDIDO, CAST(E.IMPORTE AS DECIMAL(10,2)) IMP_COMPROBANTE, B.COD_ARTICU, F.DESCRIPCIO, CAST(B.CANT_PEDID AS INT) CANT, CAST(B.PRECIO AS DECIMAL(10,2)) IMP_ARTICULO,
		ISNULL(I.CARD_FIRST_DIGITS+'-'+I.CARD_LAST_DIGITS, '') TARJETA , I.ISSUER BANCO, J.N_CUIT,
		ISNULL(REPLACE(G.NOMBRE_SUC, 'RT - SUC - ', ''), C.XML_CA_1111_SELECCIONABLE_PARA_TIPO_STOCK) ORIGEN, C.XML_CA_1111_METODO_ENTREGA TIPO_ENVIO, L.TIENDA,  
		CASE WHEN AUDITORIA = 1 THEN 1 ELSE 0 END CONTROLADO,
		CASE WHEN E.N_COMP IS NOT NULL THEN 1 ELSE 0 END FACTURADO, 
		CASE WHEN M.FECHA_ENTREGADO  IS NOT NULL THEN 1 ELSE 0 END ENTREGADO, 
		M.FECHA_ENTREGADO 
		FROM GVA21 A
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
		LEFT JOIN (SELECT PEDIDO, FECHA_ENTREGADO FROM SJ_LOCAL_ENTREGA_TABLE GROUP BY PEDIDO, FECHA_ENTREGADO	) M ON A.NRO_PEDIDO = M.PEDIDO COLLATE Latin1_General_BIN
		WHERE (E.N_COMP = '$comp' OR A.LEYENDA_2 LIKE '%$comp%') AND
		A.COD_CLIENT = '000000'	AND (A.FECHA_PEDI BETWEEN '$desde' AND '$hasta') AND A.TALON_PED = '99'
		ORDER BY A.FECHA_PEDI DESC

		";

		
		ini_set('max_execution_time', 300);
$result=sqlsrv_query($cid,$sql)or die(exit("Error en odbc_exec"));

?>

<div class="container-fluid">
<table class="table table-sm table-striped table-condensed table-bordered" id="tablaFactura">
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
			<td width="1%"></td>
        </tr>
	</thead>
	<tbody id="table">
		<?php
		while($v=sqlsrv_fetch_array($result)){
		?>


		<tr>

		<td class="col-1" style="font-size: 11px;"><?= $v['FECHA']->format('d/m/Y'); ;?></td>
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
		<td width="1%"> <a href="remitoEntrega/?nComp=<?= $v['N_COMP'] ;?>" target=”_blank”> <i class="bi bi-file-richtext-fill" data-toggle="tooltip" data-placement="left" title="Print" id="iconPrint"></i></a></td>
		<td width="1%">
				<?php if($v['FACTURADO']== 1){ ?>
					<i class="bi bi-file-earmark-text-fill" data-toggle="tooltip" data-placement="left" title="Facturado" id="iconFacturado"></i>
						<?php }else if($v['FACTURADO']== 0){?>
						<?php } ?>
		</td>
		<td width="1%">
				<?php if($v['CONTROLADO']== 1){ ?>
					<i class="bi bi-clipboard2-check-fill" data-toggle="tooltip" data-placement="left" title="Controlado" id="iconControlado"></i>
						<?php }else if($v['CONTROLADO']== 0){?>
						<?php } ?>
		</td>
		<td width="1%">
				<?php if($v['ENTREGADO']== 1){ ?>
					<i class="bi bi-box-seam-fill" data-toggle="tooltip" data-placement="left" title="Entregado <?php echo $v['FECHA_ENTREGADO'] ?>" id="iconEntregado"></i>
						<?php }else if($v['ENTREGADO']== 0){?>
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

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="main.js"></script>


</body>
</html>