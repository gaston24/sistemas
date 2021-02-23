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

    $servidor = 'SERVIDOR';
    $conexion = array( "Database"=>"LAKER_SA", "UID"=>"sa", "PWD"=>"Axoft1988", "CharacterSet" => "UTF-8");
    $cid = sqlsrv_connect($servidor, $conexion);
    
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

	<div class="row">
		

		<div class="col-3">
			
		</div>
		<div class="col-8">
			<div class="row">
				<div class="col-1"></div>
				<div class="col-10 pl-0"><h1>Bienvenido <?php echo $local; ?></h1></div>
				<div class="col-1"></div>
			</div>
			<?php
				if($_SESSION['tipo']!= 'MAYORISTA'){
			?>
				<div class="row">
					<div class="col-2"></div>
					<div class="col-8 pl-0"><h5>Próximo despacho:<?php echo ' '.$proximaEntrega; ?></h5></div>
					<div class="col-2"></div>
				</div>
			<?php		
				}
			?>
			
		</div>
		<div class="col-1">
		</div>

	</div>

	<div class="row">
	
	<div class="col"> 
	</div>
	
	<div class="col"> 

	<img src="Controlador/logo.jpg">

	</div>

	
	<div class="col">
	
		


		<?php
		if($_SESSION['tipo'] == 'MAYORISTA'){
			include __DIR__.'\ppp\mayoristas\ppp_function.php';
			$datos = traer_datos($codClient, $ven);
			?>

			<ul class="list-group">


			<li class="list-group-item list-group-item-secondary">
				Plazo Promedio de Pago
				
			</li>
			<li class="list-group-item">
				Plazo 12 meses 
				<span class="badge badge-primary badge-pill"><?php echo (int)$datos['ppp12meses'];  ?></span>
			</li>
			<li class="list-group-item">
				Saldo CC
				<span class="badge badge-primary badge-pill"><?php echo (int)$datos['saldo'];  ?></span>
			</li>
			<li class="list-group-item">
				Vencidas
				<span class="badge badge-primary badge-pill"><?php echo (int)$datos['vencidas'];  ?></span>
			</li>
			<li class="list-group-item">
				A vencer
				<span class="badge badge-primary badge-pill"><?php echo (int)$datos['aVencer'];  ?></span>
			</li>
			<li class="list-group-item list-group-item-info" >
				<a href="ppp/mayoristas/pppDetalle.php?cliente=<?php echo $codClient;?>">Detalles comprobantes
				
		
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

