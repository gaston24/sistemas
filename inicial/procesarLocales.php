<?php
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../login.php");

}else{

include 'comprometerStock.php';


$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";
$fecha = date('Y-m-d');
$fechaCompleta = $fecha.' 00:00:00.000';

$contenedor = $_SESSION['contenedor'];
$codArt = $_SESSION['codArt'];

foreach ($_POST['numsuc'] as $clave => $valor) {


$suc = $_POST['numsuc'][$clave];
$codClient = $_POST['codClient'][$clave];
$cantArt = $_POST['cantPed'][$clave];


if($cantArt > 0){
	




$cid = odbc_connect($dsn, $user, $pass);

//////////// TOMAR EL PROXIMO NUMERO Y EL PUNTO DE VENTA DEL TALONARIO 97 

$sqlProx = "
	SET DATEFORMAT YMD
	SELECT PROXIMO, SUCURSAL FROM GVA43 WHERE TALONARIO = '96' 
	";

$resultProx=odbc_exec($cid,$sqlProx)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($resultProx)){
	$prox = $v['PROXIMO'];
	$ptoVta = $v['SUCURSAL'];
	
}
	

////////// DESCIFRAR PROXIMO NUMERO, CONVERTIRLO EN VARIABLE	
	
$sqlProxDes = "
	SET DATEFORMAT YMD
	SELECT DBO.Fn_obtenerproximonumero('$prox')proxDes 
	";
ini_set('max_execution_time', 300);
$resultProxDes=odbc_exec($cid,$sqlProxDes)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($resultProxDes)){
	$proxDes = $v['proxDes'];
}

/////////// NUMERO DE PEDIDO PARA CARGAR EN TANGO

$numPed = (string)($ptoVta).(string)($proxDes);

/////////// NUMERO DE PEDIDO PARA CIFRAR Y ACTUALIZAR TABLA GVA43

$proxPed = substr((string)('0000000').(string)($proxDes+1),-8);


$sqlActuaProx = "
	SET DATEFORMAT YMD
	SELECT DBO.Fn_encryptarproximonumero('$proxPed')proxEnc
	";
ini_set('max_execution_time', 300);
$resultProx=odbc_exec($cid,$sqlActuaProx)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($resultProx)){
	$proxPedDes = $v['proxEnc'];
}

/////////// ACTUALIZAR TABLA CON EL PROXIMO NUMERO

$sqlActuaProxDes = "
	SET DATEFORMAT YMD
	UPDATE GVA43 SET PROXIMO = '$proxPedDes' WHERE TALONARIO = '96'
	";

odbc_exec($cid,$sqlActuaProxDes)or die(exit("Error en odbc_exec"));


/************** AHORA SE CARGA EL PEDIDO **********/

/////////////// ENCABEZADO

//echo $codClient.' '.$fecha.' '.$numPed.'</br>';



	if($cantArt>0){

		
		$sqlRelacion =
		"
		INSERT INTO SOF_DISTRIBUCION_INICIAL_RELACION (CONTENEDOR, COD_ARTICU, CANT, COD_CLIENT, FECHA_PEDI)
		VALUES ('$contenedor', '$codArt', $cantArt, '$codClient', '$fecha');
		";
		
		odbc_exec($cid,$sqlRelacion)or die(exit("Error en odbc_exec"));

	}





$nroRenglon = 1;
	
if($cantArt>0){


$sqlCargaPedidoEncabezado = "
SET DATEFORMAT YMD

EXEC SJ_PEDIDO_ENCABEZADO '$codClient', '01', 'DISTRIBUCION INICIAL', 1, 'ZZ', '$numPed', 96
	
";

odbc_exec($cid,$sqlCargaPedidoEncabezado)or die(exit("Error en odbc_exec"));

/////////////// DETALLE






	//echo $clave.' '.$valor.' '.$cantArt.'</br>';
		
	$sqlVerificaArt = "SELECT * FROM STA03 WHERE COD_ARTICU = '$codArt'";
	
	$resultVerifica = odbc_exec($cid, $sqlVerificaArt)or die(exit("Error en odbc_exec"));
	
	
	
	
	$sqlLista = "SELECT NRO_LISTA FROM GVA14 WHERE COD_CLIENT = '$codClient'";
	
	ini_set('max_execution_time', 300);
	$resultLista = odbc_exec($cid, $sqlLista)or die(exit("Error en odbc_exec"));
	
	while($v=odbc_fetch_array($resultLista)){
			
			$nroLista = $v['NRO_LISTA'];
	}
	
		
	if(odbc_num_rows($resultVerifica)==0){
				
		$sqlCargaPedidoDetalle = "

		EXEC SJ_PEDIDO_DETALLE $cantArt, '$codArt', '$codArt', $nroRenglon, 0, 0, 0, $nroLista, '$numPed', 96

		";
		ini_set('max_execution_time', 300);
		odbc_exec($cid, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));


		comp_stock($cantArt, $codArt, '01');


	
		$nroRenglon++;

	}else{
		
		
		$sqlCargaPedidoDetalle = "

		EXEC SJ_PEDIDO_DETALLE $cantArt, '$codArt', '$codArt', $nroRenglon, 0, 1, 1, $nroLista, '$numPed', 96

		";

		ini_set('max_execution_time', 300);
		odbc_exec($cid, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));

		$ultRenglon = $nroRenglon;

		comp_stock($cantArt, $codArt, '01');
		
		$nroRenglon++;
		
		
		
		$sqlKit = "SELECT COD_INSUMO, CANTIDAD FROM STA03 WHERE COD_ARTICU = '$codArt'";
		
		ini_set('max_execution_time', 300);
		$resultExplota = odbc_exec($cid, $sqlKit)or die(exit("Error en odbc_exec"));
		
		while($v=odbc_fetch_array($resultExplota)){
			
			$codInsumo = $v['COD_INSUMO'];
			$cantInsumo = $v['CANTIDAD'];
				
			$cantArt2 = $cantInsumo * $cantArt;
			
			$sqlCargaPedidoDetalle = "

			EXEC SJ_PEDIDO_DETALLE $cantArt2, '$codInsumo', '$codArt', $nroRenglon, $ultRenglon, 0, 1, $nroLista, '$numPed', 96
			
			";
			
			ini_set('max_execution_time', 300);
			odbc_exec($cid, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));

			comp_stock($cantArt2, $codInsumo, '01');
		
			$nroRenglon++;
			
			
		}	
		
		
		
		
		
	}

}
		
}else{
	echo $codClient.' no hay nada<br>';
}

header("Location:admin.php");

}

}	
?>