<?php
require_once $_SERVER['DOCUMENT_ROOT'] .'/sistemas/class/conexion.php';
	
$conn = new Conexion();
$cid = $conn->conectar('central');


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
	SELECT COD_ARTICU, CAST(STOCK_DISPONIBLE AS INT) CANT_STOCK FROM STOCK_CENTRAL_DISPONIBLE 
	WHERE COD_ARTICU IN (".
	$articulosConcat.	
	")"
	;

$result = sqlsrv_query($cid, $sqlStock);

$data = [];

while($v=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
	$data[] = array(
		$v['COD_ARTICU'],
		$v['CANT_STOCK'],
	);
}

// while($v=odbc_fetch_array($result)){
// 	$data[] = array(
// 		$v['COD_ARTICU'],
// 		$v['CANT_STOCK'],
// 	);
// }

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
