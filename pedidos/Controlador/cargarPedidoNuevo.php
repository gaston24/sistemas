<?php


require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
$cid = new Conexion();
$cid_central = $cid->conectar('central');

require 'fecha.php';
require 'carga_pedido_encabezado.php';

require 'carga_pedido_detalle_simple.php';
require 'carga_pedido_detalle_simple_kit.php';

//////////// DECLARA VARIABLES

$suc = $_POST['numsuc'];
$codClient = $_POST['codClient'];
$t_ped = $_POST['tipo_pedido'];
$depo = $_POST['depo'];
$talon_ped = 97;


//////////// TOMAR EL PROXIMO NUMERO Y EL PUNTO DE VENTA DEL TALONARIO 97 

$sqlProx = "
	SET DATEFORMAT YMD
	SELECT PROXIMO, SUCURSAL FROM GVA43 WHERE TALONARIO = $talon_ped
	";

	
$resultProx = sqlsrv_query($cid_central, $sqlProx);

while($v=sqlsrv_fetch_array($resultProx, SQLSRV_FETCH_ASSOC)){
	$prox = $v['PROXIMO'];
	$ptoVta = $v['SUCURSAL'];
}

////////// DESCIFRAR PROXIMO NUMERO, CONVERTIRLO EN VARIABLE	
	
$sqlProxDes = "
	SET DATEFORMAT YMD
	SELECT DBO.Fn_obtenerproximonumero('$prox')proxDes 
	";


$resultProxDes = sqlsrv_query($cid_central, $sqlProxDes);

while($v=sqlsrv_fetch_array($resultProxDes, SQLSRV_FETCH_ASSOC)){
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


$resultProx=sqlsrv_query($cid_central,$sqlActuaProx)or die(exit("Error en odbc_exec"));

while($v=sqlsrv_fetch_array($resultProx, SQLSRV_FETCH_ASSOC)){
	$proxPedDes = $v['proxEnc'];
}
/////////// ACTUALIZAR TABLA CON EL PROXIMO NUMERO

$sqlActuaProxDes = "
	SET DATEFORMAT YMD
	UPDATE GVA43 SET PROXIMO = '$proxPedDes' WHERE TALONARIO = $talon_ped
	";


sqlsrv_query($cid_central,$sqlActuaProxDes)or die(exit("Error en odbc_exec"));


/************** AHORA SE CARGA EL PEDIDO **********/

/////////////// ENCABEZADO

pedido_encabezado($codClient, $depo, $fecha, $t_ped, $numPed, $talon_ped);

/////////////// DETALLE

if( substr($codClient, 0, 2) == 'MA' ){
	$cantidadPedida = 6;
}else{
	$cantidadPedida = 8;
}
	
$nroRenglon = 1;

for($i = 0; $i < count($_POST['matriz']); $i++){
	if(isset($_POST['matriz'][$i][1])){

		$codArt = $_POST['matriz'][$i][1];
		$cantArt = $_POST['matriz'][$i][$cantidadPedida];
		$rubro = $_POST['matriz'][$i][3];
		$stock = $_POST['matriz'][$i][4];

		
		$sqlVerificaArt = "SELECT * FROM STA03 WHERE COD_ARTICU = '$codArt'";
		
		// $resultVerifica = odbc_exec($cid, $sqlVerificaArt)or die(exit("Error en odbc_exec"));

		$resultVerifica = sqlsrv_query($cid_central, $sqlVerificaArt)or die(exit("Error en odbc_exec"));
			
		// if(odbc_num_rows($resultVerifica)==0){
		if(sqlsrv_num_rows($resultVerifica)==0){
		

		
			if($cantArt>0){
				
				if($rubro != 'PACKAGING' && $cantArt > 15 ){
				
					$cantArt = 15;
				
				}elseif($rubro == 'PACKAGING' && $cantArt > 100 ){
					
					$cantArt = 100;
					
				}
				
				
				if($cantArt > $stock){
					
					$cantArt = $stock;
					
				}
				
				
				////// FUNCION DE CARGAR PEDIDO DETALLE // TAMBIEN COMPROMETE STOCK

				pedido_detalle_simple($depo, $numPed, $nroRenglon, $codArt, $cantArt, $codClient, $talon_ped);
			
				$nroRenglon++;

			
			}

		}else{
			
			
			if($cantArt>0){
				
				if($rubro != 'PACKAGING' && $cantArt > 15 ){
				
					$cantArt = 15;
				
				}
				
				
				////// FUNCION DE CARGAR PEDIDO DETALLE // TAMBIEN COMPROMETE STOCK

				pedido_detalle_simple_kit_encabezado(
					$depo, //CODIGO DEPOSITO ARTICULO
					$numPed, //PEDIDO TANGO 
					$nroRenglon, //NRO_RENGLON
					$codArt, //COD_ARTICU
					$cantArt, //CANT_PEDIDO
					$codClient, //COD_CLIENT 
					$talon_ped //TALON_PED
				);

			
				$ultRenglon = $nroRenglon;
				
				$nroRenglon++;
				
				$sqlKit = "SELECT COD_INSUMO, CANTIDAD FROM STA03 WHERE COD_ARTICU = '$codArt'";

				$resultExplota = sqlsrv_query($cid_central, $sqlKit)or die(exit("Error en odbc_exec"));

				while($v=sqlsrv_fetch_array($resultExplota, SQLSRV_FETCH_ASSOC)){
					
					$codArt2 = $v['COD_INSUMO'];
					$cantInsumo = $v['CANTIDAD'];
					
					$cantArt2 = $cantInsumo * $cantArt;
					
					////// FUNCION DE CARGAR PEDIDO DETALLE KIT
					pedido_detalle_simple_kit(
					$depo, //CODIGO DEPOSITO ARTICULO
					$numPed, //PEDIDO TANGO 
					$nroRenglon, //NRO_RENGLON
					$ultRenglon, //NRO_RENGLON
					$codArt, //COD_ARTICU
					$codArt2, //COD_ARTICU
					$cantArt2, //CANT_PEDIDO
					$codClient, //COD_CLIENT 
					$talon_ped //TALON_PED);
					);
					
					$nroRenglon++;

				}	
			
			}
			
		}
	}	
}




echo $numPed;


?>
