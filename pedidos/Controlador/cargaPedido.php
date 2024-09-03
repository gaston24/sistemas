<?php

	require_once $_SERVER['DOCUMENT_ROOT']."/sistemas/class/extralarge.php";
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

	</script>

	<?php


?>




