<?php
session_start();
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

include '../../../css/header.php'; 

$codClient = $_SESSION['codClient'];
$usuarioLocal = $_SESSION['usuario'];

$dsn = "1 - CENTRAL";
$dsnLocal = $_SESSION['dsn'];

$user = "sa";
$pass = "Axoft1988";
$rem = $_SESSION['rem'];
$nroSucurs = $_SESSION['nro_sucurs'];

$cidLocal = odbc_connect($dsnLocal, $user, $pass, SQL_CURSOR_FORWARD_ONLY);


//ASIGNA VALOR DE SUC_ORIG, SUC_DESTIN Y FECHA_REM DEL REMITO EN EL LOCAL DESTINO
$sql="
SELECT FECHA_MOV, SUC_ORIG, SUC_DESTIN FROM CTA115 WHERE N_COMP = '$rem' AND NRO_SUCURS = $nroSucurs
";
$result=odbc_exec($cidLocal,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_object($result)){
	$sucOrig = $v->SUC_ORIG;
	$sucDestin = $v->SUC_DESTIN;
	$fechaRem = $v->FECHA_MOV;
}


//MARCAR REMITO COMO REGISTRADO
$sqlActua="
UPDATE CTA115 SET TALONARIO = 1
WHERE N_COMP = '$rem'
";
odbc_exec($cidLocal,$sqlActua)or die(exit("Error en odbc_exec"));


//ACTUALIZAR SUC_ORIG Y SUC_DESTIN EN EL CONTEO
$cid = odbc_connect($dsn, $user, $pass, SQL_CURSOR_FORWARD_ONLY);

$sql="
UPDATE SJ_CONTROL_LOCAL SET SUC_ORIG = $sucOrig, SUC_DESTIN = $sucDestin
WHERE NRO_REMITO = '$rem'
";
odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));



//LIMPIAR TABLA
$sqlBorrar="
DELETE FROM SJ_CONTROL_LOCAL_AUX_REMITO WHERE NRO_REMITO = '$rem'
";
odbc_exec($cid,$sqlBorrar)or die(exit("Error en odbc_exec"));


//DECLARAR FUNCION INSERTAR DATOS DEL REMITO DESDE LOCAL A LA AUDITORIA
function insertarRegistro($cArt, $k, $fechaHora, $codClient,  $fechaRem, $sucOrig, $sucDestin){
	
	$dsn = "1 - CENTRAL";
	$user = "sa";
	$pass = "Axoft1988";
	$rem = $_SESSION['rem'];
	$cid = odbc_connect($dsn, $user, $pass, SQL_CURSOR_FORWARD_ONLY);
	
	$sql4="
	SET DATEFORMAT YMD
	INSERT INTO SJ_CONTROL_LOCAL_AUX_REMITO ( FECHA_CONTROL, COD_CLIENT, FECHA_REM, NRO_REMITO, SUC_ORIG, SUC_DESTIN, COD_ARTICU, CANT_REM )
	VALUES ('$fechaHora', '$codClient', '$fechaRem', '$rem', $sucOrig, $sucDestin, '$cArt', $k)
	";
	odbc_exec($cid,$sql4)or die(exit("Error en odbc_exec"));
}


//INSERTAR DATOS DEL REMITO EN EL LOCAL CON FUNCION	

$sql="
SELECT B.COD_ARTICU, B.CANTIDAD 
FROM STA14 A INNER JOIN STA20 B ON A.NCOMP_IN_S = B.NCOMP_IN_S AND A.TCOMP_IN_S = B.TCOMP_IN_S 
WHERE PROMOCION != 1
AND T_COMP = 'REM' 
AND N_COMP = '$rem'
";


$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result)){
	
	insertarRegistro($v['COD_ARTICU'], $v['CANTIDAD'], $fechaHora, $codClient, $fechaRem, $sucOrig, $sucDestin);
	
}


$sqlAuditoria =
"
SET DATEFORMAT YMD
SELECT COD_CLIENT, COD_ARTICU, CANT_REM, CANT_CONTROL, USUARIO_LOCAL
FROM
(
	SELECT COD_CLIENT, ISNULL(COD1,COD2) COD_ARTICU, 
	CASE WHEN CANT_REM IS NULL THEN 0 ELSE CANT_REM END CANT_REM, 
	CASE WHEN CANT_CONTROL IS NULL THEN 0 ELSE CANT_CONTROL END CANT_CONTROL, ISNULL(USUARIO_LOCAL, '$usuarioLocal')USUARIO_LOCAL
	FROM
	(
		SELECT A.COD_CLIENT, A.COD_ARTICU COD1, B.COD_ARTICU COD2, A.CANT_REM, B.CANT_CONTROL, B.USUARIO_LOCAL FROM SJ_CONTROL_LOCAL_AUX_REMITO A
		FULL JOIN 
		(
			SELECT COD_CLIENT, NRO_REMITO, COD_ARTICU, SUM(CANT_CONTROL)CANT_CONTROL, USUARIO_LOCAL FROM SJ_CONTROL_LOCAL 
			GROUP BY COD_CLIENT, NRO_REMITO, COD_ARTICU, USUARIO_LOCAL
		) B
		ON A.COD_CLIENT = B.COD_CLIENT AND A.NRO_REMITO = B.NRO_REMITO AND A.COD_ARTICU = B.COD_ARTICU
		WHERE ISNULL(A.COD_CLIENT, B.COD_CLIENT) = '$codClient' AND ISNULL(A.NRO_REMITO, B.NRO_REMITO) = '$rem'
	)A
	WHERE ISNULL(COD1,COD2) != '' 
)A
WHERE COD_ARTICU NOT IN (SELECT COD_ARTICU COLLATE Latin1_General_BIN FROM STA03)
";

	$resultAuditoria=odbc_exec($cid,$sqlAuditoria)or die(exit("Error en odbc_exec"));

	while($v=odbc_fetch_object($resultAuditoria)){
		$data[] = array($v);
	};


	foreach ($data as $key => $v) {
		
		
		$codArticu = $v[0]->COD_ARTICU;
		$cantRem = $v[0]->CANT_REM;
		$cantControl = $v[0]->CANT_CONTROL;
		$usuarioLocal = $v[0]->USUARIO_LOCAL;
		
		$sqlInsertarAuditoria = "INSERT INTO SJ_CONTROL_AUDITORIA 
		([FECHA_CONTROL], [COD_CLIENT], [FECHA_REM], [NRO_REMITO], [SUC_ORIG], [SUC_DESTIN], [COD_ARTICU], [CANT_REM], [CANT_CONTROL], [USUARIO_LOCAL], [OBSERVAC_LOGISTICA]) 
		VALUES ('$fechaHora', '$codClient', '$fechaRem', '$rem', $sucOrig, $sucDestin, '$codArticu', $cantRem, $cantControl, '$usuarioLocal', 'PENDIENTE')";

		odbc_exec($cid,$sqlInsertarAuditoria)or die("<p>".odbc_errormsg());
	}


	//ACTUALIZAR ESTADO DEL REMITO SI NO TIENE DIFERENCIAS = ACEPTADO
	$sqlActuaEstado="
	SET DATEFORMAT YMD

	UPDATE SJ_CONTROL_AUDITORIA SET OBSERVAC_LOGISTICA = 'ACEPTADO'
	WHERE NRO_REMITO IN
	(
		SELECT NRO_REMITO FROM
		(
			SELECT 
			FECHA_CONTROL, E.DESC_SUCURSAL, CAST(A.FECHA_REM AS DATE) FECHA_REM, 
			NOMBRE_VEN, A.NRO_REMITO, SUM(A.CANT_CONTROL) CANT_CONTROL, SUM(A.CANT_REM) CANT_REM, 
			SUM(CASE WHEN CANT_REM <> CANT_CONTROL THEN 1 ELSE 0 END) DIF, 
			A.OBSERVAC_LOGISTICA, NRO_AJUSTE
			FROM SJ_CONTROL_AUDITORIA A
			INNER JOIN GVA23 D
			ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
			INNER JOIN SUCURSAL E
			ON A.SUC_ORIG = E.NRO_SUCURSAL
			WHERE A.COD_CLIENT = '$codClient' 
			AND (CAST( A.FECHA_CONTROL AS DATE) BETWEEN CAST(GETDATE()-15 AS DATE) AND CAST(GETDATE() AS DATE))
			AND OBSERVAC_LOGISTICA = 'PENDIENTE'     
			AND NRO_REMITO = '$rem'
			GROUP BY A.NRO_REMITO, A.FECHA_REM, A.FECHA_CONTROL, NOMBRE_VEN, E.DESC_SUCURSAL, A.OBSERVAC_LOGISTICA, NRO_AJUSTE
		)A
		WHERE DIF <> 0 
	)
	";
	odbc_exec($cid,$sqlActuaEstado)or die(exit("Error en odbc_exec"));



}

header("Location: ../controlDetalle.php?rem=$rem&codClient=$codClient");

?>
<title>Procesando..</title>
<link rel="shortcut icon" href="icono.jpg" />
