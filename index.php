<?php

session_start();
if (!isset($_SESSION['username'])) {
	header("Location:login.php");
} else {

$permiso 	= $_SESSION['permisos'];
$codClient 	= $_SESSION['codClient'];
$deposi 	= $_SESSION['deposi'];
$ven 		= $_SESSION['vendedor'];
$local 		= $_SESSION['descLocal'];
$dashboard	= $_SESSION['dashboard'];
$habPedidos = $_SESSION['habPedidos'];


require 'class/fechaEntrega.php';


	try {

	$fechaEntrega 	= new Fecha();
	$proximaEntrega = $fechaEntrega->traerFechaEntrega($codClient);
	$proximaEntrega = json_decode($proximaEntrega);
    
} catch (PDOException $e){
        echo $e->getMessage();
}

?>
	<!DOCTYPE HTML>
	<html charset="UTF-8">

<head>
<title>XL Extralarge - Inicio</title>	
<meta charset="UTF-8"></meta>
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<?php include_once __DIR__.'/ajustes/css/headers/include_index.php'; ?>
<?php include_once __DIR__.'/assets/css/fontawesome/css.php';?>
<link rel="stylesheet" href="ajustes/css/msj-seincomp.css">
<link rel="stylesheet" href="css/style.css">
</head>

<body>	

	<div class="container">
		<?php
		include_once 'Controlador/nav_menu.php';
		?>
		<div class="form-group" style="margin-top: 1rem; margin-left: 7%;">
			<div class="col-">
				<div class="mb-4">
					<div class="row" style="display: flex; justify-content: center;">
						<div class="col-"><h2>Bienvenido/a</h2></div>
					</div>
					<div class="row" style="display: flex; justify-content: center;">
						<div class="col-"><h2 class="text-secondary"><?php echo $local; ?></h2></div>
					</div>
				</div>
				<?php
				if($_SESSION['tipo']!= 'MAYORISTA'){
				?>
					<div class="col-" style="display: flex; justify-content: center;">
						<div class="col-"><h5>Próximo despacho: <a class="text-secondary"><?=' '.substr( $proximaEntrega[0]->FECHA->date, 0, 10);?></a></h5></div>
					</div>
				<?php		
				}
				?>
			</div>

			<div class="col-" style="margin-top: 2rem; display: flex; justify-content: center;"> 
				<img src="Controlador/logo.jpg" style="height: 150px; width: 200px">
			</div>
		</div>
		
							
		<div class="col-" style="margin-top: 2rem; display: flex; justify-content: center;">
			<div style="text-align: center; width: 24rem">
				<?php
				if($_SESSION['tipo'] == 'MAYORISTA'){
					include __DIR__.'\class\extralarge.php';
					$xl = new Extralarge();
					$datos = $xl->traerDatosMayorista($codClient, $ven);
				?>
					<ul class="list-group">

						<li class="list-group-item list-group-item-secondary" style="text-align: center;">
							Plazo Promedio de Pago	
						</li>
						<li class="list-group-item">
							Plazo 12 meses 
							<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$datos['ppp12meses'], 0, ".",".");;  ?></span>
						</li>
						<li class="list-group-item">
							Saldo CC
							<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$datos['saldo'], 0, ".",".");  ?></span>
						</li>
						<li class="list-group-item">
							Vencidas
							<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$datos['vencidas'], 0, ".",".");;  ?></span>
						</li>
						<li class="list-group-item">
							A vencer
							<span class="badge badge-primary badge-pill"><?= "$".number_format((int)$datos['aVencer'], 0, ".",".");;  ?></span>
						</li>
						<li class="list-group-item list-group-item-info" >
							<a href="ppp/mayoristas/pppDetalle.php?cliente=<?= $codClient;?>">Detalles comprobantes
						</li>

					</ul>	
				<?php
				}
				?>	

			</div>	
		</div>
	
		<?php if($habPedidos == 0 && $_SESSION['numsuc'] > 300) {
			echo '<h1 class="text text-center text-danger">Inhabilitado para realizar pedidos</h1>';
		}
		?>
		<?php if($_SESSION['numsuc']>800)
		{
			include('Seincomp/msjSeincomp.php');
		}
?>

	</div>
	</div>

		<?php include_once __DIR__ . '\assets\css\fontawesome\js.php'; ?>
		<script>
			
			let codClient = '<?= $codClient; ?>'
			let estado = [];

			let consultado = null;
			
			traerCantidadPedidos(codClient);

			const nuevoPedido = (tipo, idTipo) => {

				consultado = buscarTipo(estado, tipo);

				if (consultado.CANT_PEDIDOS >= 5) {
					alerta();
				} else {
					location.href = 'pedidos/pedidos.php?tipo='+idTipo;
				}
			}

			const buscarTipo = (x, tipo) => {
				return estado.filter(x=>{ 
					return x.TIPO == tipo
				})
			}

			function alerta() {
				Swal.fire({
					icon: "error",
					title: "No puede continuar",
					text: "TIENE REALIZADO 2 PEDIDOS ESTA SEMANA"
				});
			}


			/*********************************************************** */

			function traerCantidadPedidos(codClient) {

				let server = (window.location.href.includes("sistemas")) ? window.location.href.split('/sistemas')[0]+'/sistemas' : window.location.origin;
				
				fetch(server+"/Controlador/extralargeController.php?action=limitePedidos&codClient=" + codClient)
					.then((response) => response.json())
  					.then((data) => { estado = data; } );
			}

			/******************************************* */
		</script>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	</body>
<?php
}
?>