<?php 

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$permiso = $_SESSION['permisos'];

$codClient = $_SESSION['codClient'];
$deposi = $_SESSION['deposi'];
$ven =	$_SESSION['vendedor'];

$local = $_SESSION['descLocal'];
$dashboard = $_SESSION['dashboard'];

$habPedidos = $_SESSION['habPedidos'];

try {

	include_once '../Controlador/sql/sql_conexion.php';
    $cid = $cid_central;
    
} catch (PDOException $e){
        echo $e->getMessage();
}


$sqlProx =
"
EXEC SOF_FECHAS_2'$codClient';

";

$stmt = sqlsrv_query( $cid, $sqlProx );

$rows = array();

while( $v = sqlsrv_fetch_array( $stmt) ) {
	$proximaEntrega = $v['A']	;
}

?>
<!DOCTYPE HTML>
<html charset="UTF-8">

<head>
<title>XL Extralarge - Inicio</title>	
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="assets/css/icono.jpg" />
<link rel="stylesheet" href="assets/css/bootstrap/bootstrap.min.css" >
<script src="assets/css/bootstrap/jquery-3.5.1.slim.min.js" ></script>
<script src="assets/css/bootstrap/popper.min.js" ></script>
<script src="assets/css/bootstrap/bootstrap.min.js" ></script>



<?php include_once __DIR__.'/assets/css/fontawesome/css.php';?>



</head>
<body>	

<div class="container">
<?php

include_once 'Controlador/nav_menu.php';

?>

	<div class="row" style="text-align: center; margin-top: 1rem;">
		
		<div class="col-1">
			
		</div>
		<div class="col-10">
			<div class="row">
				<div class="col-1"></div>
				<div class="col-10"><h1>Bienvenido: <?php echo $local; ?></h1></div>
				<div class="col-1"></div>
			</div>
			<?php
				if($_SESSION['tipo']!= 'MAYORISTA'){
			?>
				<div class="row">
					<div class="col-1"></div>
					<div class="col-10"><h5>Próximo despacho:<?php echo ' '.$proximaEntrega; ?></h5></div>
					<div class="col-1"></div>
				</div>
			<?php		
				}
			?>
			
		</div>
		
	</div>

	
	
	<div class="row"> 	
		<div class="col-10" style="margin-top: 2rem; text-align: center; margin-left: 50px;"> 
			<img src="Controlador/logo.jpg" style="height: 180px; width: 270px">
		</div>
	</div>

		
	<div class="row" style="margin-top: 2rem;">
		<div class="col-5" style="margin-left: 280px;">
	
		<?php
		if($_SESSION['tipo'] == 'MAYORISTA'){
			include __DIR__.'\ppp\mayoristas\ppp_function.php';
			$datos = traer_datos($codClient, $ven);
			?>

			<ul class="list-group">


			<li class="list-group-item list-group-item-secondary" style="text-align: center;">
				Plazo Promedio de Pago
				
			</li>
			<li class="list-group-item">
				Plazo 12 meses 
				<span class="badge badge-primary badge-pill"><?php echo "$".number_format((int)$datos['ppp12meses'], 0, ".",".");;  ?></span>
			</li>
			<li class="list-group-item">
				Saldo CC
				<span class="badge badge-primary badge-pill"><?php echo "$".number_format((int)$datos['saldo'], 0, ".",".");  ?></span>
			</li>
			<li class="list-group-item">
				Vencidas
				<span class="badge badge-primary badge-pill"><?php echo "$".number_format((int)$datos['vencidas'], 0, ".",".");;  ?></span>
			</li>
			<li class="list-group-item">
				A vencer
				<span class="badge badge-primary badge-pill"><?php echo "$".number_format((int)$datos['aVencer'], 0, ".",".");;  ?></span>
			</li>
			<li class="list-group-item list-group-item-info" >
				<a href="ppp/mayoristas/pppDetalle.php?cliente=<?php echo $codClient;?>">Detalles comprobantes
				
		
			</li>

		</ul>	
	
		<?php
		}
		?>


		<?php
		if($_SESSION['tipo'] == 'FRANQUICIA'){
		?>

			<ul class="list-group">


			<li class="list-group-item list-group-item-secondary" style="text-align: center;">
				Detalle de CC
				
		   </li>
			<li class="list-group-item">
				Cupo credito 
				<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['cupoCrediCliente'], 0, ".",".");  ?></span>
			</li>
			<li class="list-group-item">
				Total deuda
				<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['totalDeuda'], 0, ".",".");  ?></span>
			</li>
			<li class="list-group-item">
				Pedidos abiertos
				<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['pedidos'], 0, ".",".");  ?></span>
			</li>
			<li class="list-group-item">
				Disponible para pedidos
				<?php if(((int)$_SESSION['cupoCredi'] / (int)$_SESSION['cupoCrediCliente']) < 0.10){ ?>
					<span class="badge badge-warning badge-pill" id="icon"><?= "$".number_format((int)$_SESSION['cupoCredi'], 0, ".",".");  ?></span>
					<p id="info" class="text-danger" style="display: none"><small>El importe disponible en $ es inferior al 10% del cupo de crédito!</small></p>
					<?php }else if(((int)$_SESSION['cupoCredi'] / (int)$_SESSION['cupoCrediCliente']) >= 0.10){ ?>
					<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['cupoCredi'], 0, ".",".");  ?></span>
				<?php } ?>				
			</li>
		</ul>	
	
		<?php
		}
		?>
		

		</div>
	
	</div>
	<?php if($habPedidos == 0 && $_SESSION['numsuc'] > 300)
            {
			echo '<h1 class="text text-center text-danger">Inhabilitado para realizar pedidos</h1>';
			}
	?>
</div>




<?php include_once __DIR__.'\assets\css\fontawesome\js.php';?>
</body>
<?php
}
?>

<script>
	var e = document.getElementById('icon');
	e.onmouseover = function() {
	document.getElementById('info').style.display = 'block';
	}
	e.onmouseout = function() {
	document.getElementById('info').style.display = 'none';
	}
</script>
