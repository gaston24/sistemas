<?php

require '../../../Controlador/dsn_central.php';

//////////// DECLARA VARIABLES

$matriz = $_POST['matriz'];

//////////// CONSULTA STOCK DE LOS ARTICULOS SELECCIONADOS Y DEVUELVE

$articulosConcat = '';

foreach($matriz as $key => $value){
	$articulosConcat .="'".$value[1]."', ";
}

$articulosConcat = substr($articulosConcat, 0, -2);


$sqlStock = "
	SET DATEFORMAT YMD
	SELECT COD_ARTICU, CANT_STOCK FROM STA19 
	WHERE COD_ARTICU IN (".
	$articulosConcat.	
	") AND COD_DEPOSI = '01'"
	;

$result=odbc_exec($cid,$sqlStock)or die(exit("Error en odbc_exec"));

$data = [];

while($v=odbc_fetch_array($result)){
	$data[] = array(
		$v['COD_ARTICU'],
		$v['CANT_STOCK'],
	);
}

// $data = [
// 	['XT0SST02Z010136', 1],
// 	['XT0WSI05Z120139', 1],
// 	['XT0WSI05Z120140', 39],
// 	['XT1SSI01Z010136', 1],
// 	['XT1SSI01Z010137', 1],
// 	['XT1SSI01Z010138', 1],
// 	['XT1SSI01Z010139', 12]
// ];

echo json_encode($data);
