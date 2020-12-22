<?php
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:login.php");

}else{
	
	if($_SESSION['nuevoPedido']==0 && $_SESSION['cargaPedido']==1){

require_once '../../Controlador/dsn_central.php';
require_once '../../Controlador/comprometer_stock.php';
require_once '../../Controlador/fecha.php';
require_once '../../Controlador/carga_pedido_encabezado.php';
require_once '../../Controlador/carga_pedido_detalle.php';
require_once '../../Controlador/carga_pedido_detalle_kit.php';

$todos = $_POST['cantPed'];

$x = 0;


//////////// CHEQUEA QUE LA CANTIDAD DE ARTICULOS SEA MAYOR A CERO
for($i=0;$i<count($_POST['cantPed']);$i++){
	$x = $x + (int)($todos[$i]);
}

if($x!=0){

//////////// DECLARA VARIABLES

$suc = $_SESSION['numsuc'];
$codClient = $_SESSION['codClient'];
$t_ped = $_SESSION['tipo_pedido'];
$depo = $_SESSION['depo'];
$talon_ped = 97;

//////////// TOMAR EL PROXIMO NUMERO Y EL PUNTO DE VENTA DEL TALONARIO 97 

$sqlProx = "
	SET DATEFORMAT YMD
	SELECT PROXIMO, SUCURSAL FROM GVA43 WHERE TALONARIO = $talon_ped
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

$resultProx=odbc_exec($cid,$sqlActuaProx)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($resultProx)){
	$proxPedDes = $v['proxEnc'];
}

/////////// ACTUALIZAR TABLA CON EL PROXIMO NUMERO

$sqlActuaProxDes = "
	SET DATEFORMAT YMD
	UPDATE GVA43 SET PROXIMO = '$proxPedDes' WHERE TALONARIO = $talon_ped
	";

odbc_exec($cid,$sqlActuaProxDes)or die(exit("Error en odbc_exec"));


/************** AHORA SE CARGA EL PEDIDO **********/

/////////////// ENCABEZADO

pedido_encabezado($codClient, $depo, $fecha, $t_ped, $numPed, $talon_ped);

/////////////// DETALLE

$nroRenglon = 1;

//for($i = 0; $i < count($_POST['codArt']); $i++){
foreach ($_POST['codArt'] as $clave => $valor) {
	$cantArt = $_POST['cantPed'][$clave];
	$rubro = $_POST['rubro'][$clave];
	$stock = $_POST['stock'][$clave];
	//echo $clave.' '.$valor.' '.$cantArt.'</br>';
		
	$sqlVerificaArt = "SELECT * FROM STA03 WHERE COD_ARTICU = '$valor'";
	
	$resultVerifica = odbc_exec($cid, $sqlVerificaArt)or die(exit("Error en odbc_exec"));
		
	if(odbc_num_rows($resultVerifica)==0){
	
		if($cantArt>0){
			//echo $codArt.' '.$cantArt.'</br>';
			
			if($rubro != 'PACKAGING' && $cantArt > 15 ){
			
				$cantArt = 15;
			
			}elseif($rubro == 'PACKAGING' && $cantArt > 100 ){
				
				$cantArt = 100;
				
			}
			
			
			if($cantArt > $stock){
				
				$cantArt = $stock;
				
			}
			
			
			////// FUNCION DE CARGAR PEDIDO DETALLE
			pedido_detalle($numPed, $nroRenglon, $valor, $cantArt, $codClient, $talon_ped);
		
			$nroRenglon++;

			////// FUNCION COMPROMETER STOCK

			comp_stock($cantArt, $valor, $depo);
		
		}

	}else{
		
		if($cantArt>0){
			
			if($rubro != 'PACKAGING' && $cantArt > 15 ){
			
				$cantArt = 15;
			
			}
			
			
			////// FUNCION DE CARGAR PEDIDO DETALLE
			pedido_detalle($numPed, $nroRenglon, $valor, $cantArt, $codClient, $talon_ped);
		
			$ultRenglon = $nroRenglon;
			
			$nroRenglon++;
			
			$sqlKit = "SELECT COD_INSUMO, CANTIDAD FROM STA03 WHERE COD_ARTICU = '$valor'";
			
			$resultExplota = odbc_exec($cid, $sqlKit)or die(exit("Error en odbc_exec"));
			
			while($v=odbc_fetch_array($resultExplota)){
				
				$codArt = $v['COD_INSUMO'];
				$cantInsumo = $v['CANTIDAD'];
				
				$cantArt2 = $cantInsumo * $cantArt;
				
				////// FUNCION DE CARGAR PEDIDO DETALLE KIT
				
				pedido_detalle_kit($numPed, //PEDIDO TANGO 
				$nroRenglon, //NRO_RENGLON
				$valor, //COD_ARTICU
				$cantArt2, //CANT_PEDIDO
				$codClient, //COD_CLIENT 
				$talon_ped, //TALON_PED
				$codArt, //COD_INSUMO 
				$ultRenglon);
			
				$nroRenglon++;
				
				comp_stock($cantInsumo, $codArt, $depo);
			}	
		
		}
		
	}
		
}

$_SESSION['cargaPedido']=0;

header('Location:../index.php');

}


else{
		//SI LA CANTIDAD DE ARTICULOS ES IGUAL A CERO
		header("Location:error.php");
	}

}else{
		//echo $_SESSION['nuevoPedido'].' '.$_SESSION['cargaPedido'];
		header("Location:../index.php");
	}
}	
?>