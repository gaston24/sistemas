<?php
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../../login.php");

}else{
	
//	if($_SESSION['nuevoPedido']==0 && $_SESSION['cargaPedido']==1){

//var_dump($_POST);

include '../../../Controlador/comprometer_stock.php';

$locales = array
( 
	'FRSAL1', 
	'FRSAL2', 
	'FRSAL3' 
);


function backup(){
	$dsn = "1 - CENTRAL";
	$user = "sa";
	$pass = "Axoft1988";
	$cid = odbc_connect($dsn, $user, $pass);
	
	$sqlLimpia = "SET DATEFORMAT YMD TRUNCATE TABLE SJ_CORDOBA_BACKUP";
	odbc_exec($cid,$sqlLimpia)or die(exit("Error en odbc_exec"));	
	
	for($i=0;$i<count($_POST['codArt']);$i++){
		$codArt = $_POST['codArt'][$i];
		$local_812 = $_POST['cantPed_812'][$i];
		$local_813 = $_POST['cantPed_813'][$i];
		$local_814 = $_POST['cantPed_814'][$i];
		$local_815 = $_POST['cantPed_815'][$i];
		$local_816 = $_POST['cantPed_816'][$i];
		$local_876 = $_POST['cantPed_876'][$i];
		
		//echo $codArt.' - '.$local_812.' - '.$local_813.' - '.$local_814.' - '.$local_815.' - '.$local_816.' - '.$local_876.'<br>';
		$sqlInsert = "INSERT INTO SJ_CORDOBA_BACKUP VALUES ('$codArt', $local_812, $local_813, $local_814, $local_815, $local_816, $local_876)";
		odbc_exec($cid,$sqlInsert)or die(exit("Error en odbc_exec"));	
	}
}

//anulada la funcion de backup
//backup();



for($i=0;$i<3;$i++){
//$local = $locales[$x];

//echo $locales[$i];


switch($locales[$i])
{
case 'FRSAL1': $suc = 844; break;
case 'FRSAL3': $suc = 845; break;
case 'FRSAL2': $suc = 866; break;
}

$x = 0;

switch($locales[$i])
{
case 'FRSAL1': $total_ped = array_sum($_POST['cantPed_844']); break;
case 'FRSAL3': $total_ped = array_sum($_POST['cantPed_845']); break;
case 'FRSAL2': $total_ped = array_sum($_POST['cantPed_866']); break;

}



$x = (int)($total_ped);



switch($locales[$i])
{
case 'FRSAL1': $cant_local = $_POST['cantPed_844']; break;
case 'FRSAL3': $cant_local = $_POST['cantPed_845']; break;
case 'FRSAL2': $cant_local = $_POST['cantPed_866']; break;

}



	
	



if($x!=0){

	$dsn = "1 - CENTRAL";
	$user = "sa";
	$pass = "Axoft1988";
	//$suc = $_SESSION['numsuc'];
	$codClient = $locales[$i];

	//echo $suc.' - '.$codClient.' - '.$x.'</br>';
	
	
	
	$t_ped = $_SESSION['tipo_pedido'];

	$fecha = date('Y-m-d');
	$fechaCompleta = $fecha.' 00:00:00.000';

	$depo = $_SESSION['depo'];

	$cid = odbc_connect($dsn, $user, $pass);

	//////////// TOMAR EL PROXIMO NUMERO Y EL PUNTO DE VENTA DEL TALONARIO 97 

	$sqlProx = "
		SET DATEFORMAT YMD
		SELECT PROXIMO, SUCURSAL FROM GVA43 WHERE TALONARIO = '97' 
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
		UPDATE GVA43 SET PROXIMO = '$proxPedDes' WHERE TALONARIO = '97'
		";

	odbc_exec($cid,$sqlActuaProxDes)or die(exit("Error en odbc_exec"));


	////////////////////////////AHORA SE CARGA EL PEDIDO 

	/////////////// ENCABEZADO

	//echo $codClient.' '.$fecha.' '.$numPed.'</br>';

	$sqlCargaPedidoEncabezado = "
	SET DATEFORMAT YMD
	INSERT INTO GVA21
	(
	CIRCUITO, COD_CLIENT, COD_SUCURS, 
	COD_TRANSP, COD_VENDED, COMP_STK, 
	COND_VTA, COTIZ, ESTADO, 
	EXPORTADO, FECHA_APRU, FECHA_ENTR, 
	FECHA_PEDI, LEYENDA_1, MON_CTE, 
	N_LISTA, N_REMITO, NRO_PEDIDO, 
	NRO_SUCURS, ORIGEN, PORC_DESC, 
	REVISO_FAC, REVISO_PRE, REVISO_STK, 
	TALONARIO, TALON_PED, TOTAL_PEDI, 
	TIPO_ASIEN, ID_ASIENTO_MODELO_GV, TAL_PE_ORI, 
	FECHA_INGRESO, FECHA_ULTIMA_MODIFICACION, ID_DIRECCION_ENTREGA, 
	ES_PEDIDO_WEB, FECHA_O_COMP, WEB_ORDER_ID, 
	GC_ECOMMERCE_CERRADO, TOTAL_DESC_TIENDA, PORCEN_DESC_TIENDA, 
	HORA_INGRESO
	)
	VALUES
	(
	1, '$codClient', '$depo', 
	(SELECT COD_TRANSP FROM GVA14 WHERE COD_CLIENT = '$codClient'), 'ZZ', 1, 
	(SELECT COND_VTA FROM GVA14 WHERE COD_CLIENT = '$codClient'), 1, 2, 
	0, '1800-01-01', '1800-01-01', 
	'$fecha', 'PRUEBA - PEDIDO $t_ped', 1, 
	(SELECT NRO_LISTA FROM GVA14 WHERE COD_CLIENT = '$codClient'), ' 000000000000', ' '+'$numPed', 
	0, 'E', (SELECT PORC_DESC FROM GVA14 WHERE COD_CLIENT = '$codClient'), 
	'A', 'A', 'A', 
	0, 120, 0, 
	'', 3, 0,
	'1800-01-01', '1800-01-01', (SELECT ID_DIRECCION_ENTREGA FROM DIRECCION_ENTREGA WHERE COD_CLIENTE = '$codClient'), 
	0, '1800-01-01', 0, 
	0, 0, 0, 
	(SELECT LEFT((CAST((CONVERT(TIME, GETDATE()  )) AS VARCHAR(8))), 2)+SUBSTRING((CAST((CONVERT(TIME, GETDATE()  )) AS VARCHAR(8))), 4, 2)+RIGHT((CAST((CONVERT(TIME, GETDATE()  )) AS VARCHAR(8))), 2))
	)	
	";

	odbc_exec($cid,$sqlCargaPedidoEncabezado)or die(exit("Error en odbc_exec"));

	/////////////// DETALLE

	$nroRenglon = 1;

	//for($i = 0; $i < count($_POST['codArt']); $i++){
		
	foreach ($_POST['codArt'] as $clave => $valor) {
		$cantArt = $cant_local[$clave];
		$rubro = $_POST['rubro'][$clave];
		$stock = $_POST['stock'][$clave];
		//echo $clave.' '.$valor.' '.$cantArt.'</br>';	}
		
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
					
				$sqlCargaPedidoDetalle = "
				INSERT INTO GVA03
				(
				CAN_EQUI_V, CANT_A_DES, CANT_A_FAC, CANT_PEDID, CANT_PEN_D, CANT_PEN_F, COD_ARTICU, DESCUENTO, N_RENGLON, NRO_PEDIDO, PEN_REM_FC, PEN_FAC_RE, 
				PRECIO, TALON_PED, 
				CANT_A_DES_2, CANT_A_FAC_2, CANT_PEDID_2, CANT_PEN_D_2, CANT_PEN_F_2, PEN_REM_FC_2, ID_MEDIDA_VENTAS, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, RENGL_PADR,
				PROMOCION, PRECIO_ADICIONAL_KIT, KIT_COMPLETO, INSUMO_KIT_SEPARADO, PRECIO_LISTA, PRECIO_BONIF, DESCUENTO_PARAM
				)
				VALUES
				(
				1, $cantArt, $cantArt, $cantArt, $cantArt, $cantArt, '$valor', 0, $nroRenglon, ' '+'$numPed', 0, 0, 
				(SELECT PRECIO FROM GVA17 WHERE COD_ARTICU = '$valor' AND NRO_DE_LIS = (SELECT NRO_LISTA FROM GVA14 WHERE COD_CLIENT = '$codClient') ), 120,
				0, 0, 0, 0, 0, 0, 7, 7, 'V', 0,
				0, 0, 0, 0, 0, 0, 0
				)
				";

				odbc_exec($cid, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));
			
				$nroRenglon++;

				comp_stock($cantArt, $valor, $depo);
			
			}

		}else{
			
			if($cantArt>0){
				
				if($rubro != 'PACKAGING' && $cantArt > 15 ){
				
					$cantArt = 15;
				
				}
				
				
				$sqlCargaPedidoDetalle = "
				INSERT INTO GVA03
				(
				CAN_EQUI_V, CANT_A_DES, CANT_A_FAC, CANT_PEDID, CANT_PEN_D, CANT_PEN_F, COD_ARTICU, DESCUENTO, N_RENGLON, NRO_PEDIDO, PEN_REM_FC, PEN_FAC_RE, 
				PRECIO, TALON_PED, 
				CANT_A_DES_2, CANT_A_FAC_2, CANT_PEDID_2, CANT_PEN_D_2, CANT_PEN_F_2, PEN_REM_FC_2, ID_MEDIDA_VENTAS, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, RENGL_PADR,
				PROMOCION, PRECIO_ADICIONAL_KIT, KIT_COMPLETO, INSUMO_KIT_SEPARADO, PRECIO_LISTA, PRECIO_BONIF, DESCUENTO_PARAM, COD_ARTICU_KIT
				)
				VALUES
				(
				1, $cantArt, $cantArt, $cantArt, $cantArt, $cantArt, '$valor', 0, $nroRenglon, ' '+'$numPed', 0, 0, 
				(SELECT PRECIO FROM GVA17 WHERE COD_ARTICU = '$valor' AND NRO_DE_LIS = (SELECT NRO_LISTA FROM GVA14 WHERE COD_CLIENT = '$codClient') ), 120,
				0, 0, 0, 0, 0, 0, 7, 7, 'V', 0,
				1, 0, 1, 0, 0, 0, 0, '$valor'
				)
				";

				odbc_exec($cid, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));
			
				$ultRenglon = $nroRenglon;
				
				$nroRenglon++;
				
				
				
				$sqlKit = "SELECT COD_INSUMO, CANTIDAD FROM STA03 WHERE COD_ARTICU = '$valor'";
				
				$resultExplota = odbc_exec($cid, $sqlKit)or die(exit("Error en odbc_exec"));
				
				while($v=odbc_fetch_array($resultExplota)){
					
					$codArt = $v['COD_INSUMO'];
					$cantInsumo = $v['CANTIDAD'];
					
					$cantArt2 = $cantInsumo * $cantArt;
					
					$sqlCargaPedidoDetalle = "
					INSERT INTO GVA03
					(
					CAN_EQUI_V, CANT_A_DES, CANT_A_FAC, CANT_PEDID, CANT_PEN_D, CANT_PEN_F, COD_ARTICU, DESCUENTO, N_RENGLON, NRO_PEDIDO, PEN_REM_FC, PEN_FAC_RE, 
					PRECIO, TALON_PED, 
					CANT_A_DES_2, CANT_A_FAC_2, CANT_PEDID_2, CANT_PEN_D_2, CANT_PEN_F_2, PEN_REM_FC_2, ID_MEDIDA_VENTAS, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, RENGL_PADR,
					PROMOCION, PRECIO_ADICIONAL_KIT, KIT_COMPLETO, INSUMO_KIT_SEPARADO, PRECIO_LISTA, PRECIO_BONIF, DESCUENTO_PARAM, COD_ARTICU_KIT
					)
					VALUES
					(
					1, $cantArt2, $cantArt2, $cantArt2, $cantArt2, $cantArt2, '$codArt', 0, $nroRenglon, ' '+'$numPed', 0, 0, 
					(SELECT PRECIO FROM GVA17 WHERE COD_ARTICU = '$codArt' AND NRO_DE_LIS = (SELECT NRO_LISTA FROM GVA14 WHERE COD_CLIENT = '$codClient') ), 120,
					0, 0, 0, 0, 0, 0, 7, 7, 'P', $ultRenglon,
					0, 0, 1, 0, 0, 0, 0, '$valor'
					)
					";

					odbc_exec($cid, $sqlCargaPedidoDetalle)or die(exit("Error en odbc_exec"));
				
					$nroRenglon++;

					comp_stock($cantInsumo, $codArt, $depo);
					
					
				}	
			
			}
			
			
			
		}

			
	}

	//$_SESSION['cargaPedido']=0;

	//include('mail.php');

	echo "<script>setTimeout(function () {window.location.href= '../index.php';},1000);</script>";

	
	
}   


else{
		//echo $_SESSION['nuevoPedido'].' '.$_SESSION['cargaPedido'];
		header("Location:error.php");
	}
}

}	
?>