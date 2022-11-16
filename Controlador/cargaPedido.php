<?php 
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../login.php");

}else{

?>
<head>

<title>Pedidos</title>
<meta charset="UTF-8"></meta>
<link rel="shortcut icon" href="../css/icono.jpg" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

		</head>
<div class="container-fluid">
	<div class="d-flex justify-content-center mt-1">
		<div>
			<h2>Redireccionando</h2>
		</div>
	</div>	
		
<?php

	require_once __DIR__."/../class/extralarge.php";
	$xlLocales = new Extralarge;

	try {
		
		$datosLocal = $xlLocales->traerDatosArticulos($_SESSION['username']);

		$datosLocal = json_encode($datosLocal);

	} catch (\Throwable $th) {

		die("</br></br><H2 ALIGN='CENTER'>IMPOSIBLE CONECTARSE CON ".$_SESSION['descLocal']."</H2></br></br><H2 ALIGN='CENTER'>Chequee la conexion de internet</H2>");
		print_r($th);
	}

	?>

	<script>
		let datosLocal = '<?php echo $datosLocal?>';

		localStorage.removeItem('datosLocal');

		localStorage.setItem('datosLocal', datosLocal);

		let server = window.location.href.split('/sistemas')[0];

		window.location.href = server+'/sistemas/index.php';

	</script>

	<?php

}
?>

</div>




