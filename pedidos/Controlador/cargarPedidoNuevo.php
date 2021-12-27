<?php

require '../../../Controlador/dsn_central.php';
require '../../../Controlador/fecha.php';
require '../../../Controlador/carga_pedido_encabezado.php';

require '../../../Controlador/carga_pedido_detalle_simple.php';
require '../../../Controlador/carga_pedido_detalle_simple_kit.php';

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
		
		$resultVerifica = odbc_exec($cid, $sqlVerificaArt)or die(exit("Error en odbc_exec"));
			
		if(odbc_num_rows($resultVerifica)==0){
		
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

				pedido_detalle_simple_kit_encabezado($depo, $numPed, $nroRenglon, $codArt, $cantArt, $codClient, $talon_ped);
			
				$ultRenglon = $nroRenglon;
				
				$nroRenglon++;
				
				$sqlKit = "SELECT COD_INSUMO, CANTIDAD FROM STA03 WHERE COD_ARTICU = '$codArt'";
				
				$resultExplota = odbc_exec($cid, $sqlKit)or die(exit("Error en odbc_exec"));
				
				while($v=odbc_fetch_array($resultExplota)){
					
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
