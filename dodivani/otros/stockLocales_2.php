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
		<body onLoad="condiciones(a)">

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
		
		EXEC SJ_ROTACIONES_3 910, 849, 900
		
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
				<th ><small>SAN ISIDRO<br>Stock</small></th>				
				<th ><small>Venta</small></th>				
				<th ><small>REMEROS<br>Stock</small></th>				
				<th ><small>Venta</small></th>				
				<th ><small>CABILDO<br>Stock</small></th>		
				<th ><small>Venta</small></th>								
				<th ><small>CANT<br>SUG</small></th>			
				<th >LOCAL</th>	
				
			</tr>
			
		</thead>
		
		<tbody>
		<?php
		$num = 0;
		while($v=odbc_fetch_array($result)){
		
		?>
			<tr id="row">


			<td ><small><?php echo $v['COD_ARTICU'] ;?></small></td>	
								
			<td ><small><?php echo $v['DESCRIPCIO'] ;?> </small> </td>				
			<td ><small><?php echo $v['RUBRO'] ;?> </small> </td>

			<td style="border-left: 1px solid black"><?php echo (int)($v['STOCK_CENTRAL']) ;?>  </td>

			<td id="a_stock_" style="border-left: 1px solid black"><?php echo (int)($v['910_STOCK']) ;?>  </td>
			<td id="a_vendido_" ><?php echo (int)($v['910_VENDIDO']) ;?>  </td>

			<td id="b_stock_" style="border-left: 1px solid black"><?php echo (int)($v['849_STOCK']) ;?>  </td>				
			<td id="b_vendido_" ><?php echo (int)($v['849_VENDIDO']) ;?>  </td>

			<td id="c_stock_" style="border-left: 1px solid black"><?php echo (int)($v['900_STOCK']) ;?>  </td>				
			<td id="c_vendido_" ><?php echo (int)($v['900_VENDIDO']) ;?>  </td>

			<td id="cant_" > <?php echo (int)($v['CANT']) ;?> </td>
			<td id="suc_" > <?php echo $v['SUC'] ;?> </td>


			</tr>
		<?php
		$num++;
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

		<script type="text/javascript" src="datatables/functions.js"></script>		
		
		<script type="text/javascript">
			var a = <?php echo $num;?>;
		</script>
		
		
		</body>
		
		
		
		</html>

		<?php

	
}
?>

