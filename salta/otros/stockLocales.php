<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../../login.php");

}else{
	
		?>
		<!doctype html>
		<html>
		<head>
		<title>Pedidos General</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css">

		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">

		
		</head>
		<body>

		<?php

		$dsn = "1 - CENTRAL";
		$user = "sa";
		$pass = "Axoft1988";
		$suc = $_SESSION['numsuc'];
		
		$_SESSION['tipo_pedido'] = 'GENERAL';
		$_SESSION['depo'] = '01';
		
		$codClient = $_SESSION['username'];
		
		//$dsneito = substr($_SESSION['dsnLocal'], 0, 3);
		
		$cid = odbc_connect($dsn, $user, $pass);


		$sql="

		SET DATEFORMAT YMD
		
		EXEC SJ_ROTACIONES_SALTA
		
		";

		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
		
		
		
		?>
		
		<div class="container-fluid mt-1">
		<div class="row">
		<div class="col-lg-12">
		<div class="table-responsive">
		
		
		
		<table id="table" class="display nowrap table-striped table-bordered table-sm "  style="width:100%">
		
		<thead>
			
			<tr >
	
				<th >CODIGO</th>
				<th >DESCRIPCION</th>
				<th >RUBRO</th>				
				<th ><small>CENTRAL</small></th>				
				<th ><small>PASEO<br>Stock</small></th>				
				<th ><small>Venta</small></th>				
				<th ><small>PORTAL<br>Stock</small></th>				
				<th ><small>Venta</small></th>				
				<th ><small>ALTO NOA<br>Stock</small></th>		
				<th ><small>Venta</small></th>								
				<th ><small>CANT<br>SUG</small></th>			
				<th >LOCAL</th>	
				
			</tr>
			
		</thead>
		
		<tbody>
		<?php

		while($v=odbc_fetch_array($result)){
		
		?>
			<tr >


			<td ><?php echo $v['COD_ARTICU'] ;?></td>	
								
			<td ><?php echo $v['DESCRIPCIO'] ;?>  </td>				
			<td ><?php echo $v['RUBRO'] ;?>  </td>

			<td style="border-left: 1px solid black"><?php echo (int)($v['STOCK_CENTRAL']) ;?>  </td>

			<td style="border-left: 1px solid black"><?php echo (int)($v['866_STOCK']) ;?>  </td>
			<td ><?php echo (int)($v['866_VENDIDO']) ;?>  </td>

			<td style="border-left: 1px solid black"><?php echo (int)($v['845_STOCK']) ;?>  </td>				
			<td ><?php echo (int)($v['845_VENDIDO']) ;?>  </td>

			<td style="border-left: 1px solid black"><?php echo (int)($v['844_STOCK']) ;?>  </td>				
			<td ><?php echo (int)($v['844_VENDIDO']) ;?>  </td>

			<td > <?php echo (int)($v['CANT']) ;?> </td>
			<td > <?php echo $v['SUC'] ;?> </td>


			</tr>
		<?php
		}

		?>
		
		</tbody>

		</table>
		
		

		<div>
		<div>
		<div>
		<div>
		


		<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>

		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="datatables/main.js"></script>

		<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>

		<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>		
		
		
		
		</body>
		
		
		
		</html>

		<?php

	
}
?>

