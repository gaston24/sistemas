<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}
$ahora = date('Y-m').'-'.strright(('0'.(date('d'))),2);	
$user = $_SESSION['username'];

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);


$sqlHistorico=
	"
	SET DATEFORMAT YMD

	INSERT INTO SOF_INVENTARIO_HISTORICO (FECHA, USUARIO, AREA, COD_ARTICU, CANT, RUBRO )
	(
		SELECT '$ahora', '$user', AREA, CODIGO, SUM(CANTIDAD) CANTIDAD, RUBRO FROM 
		(
			SELECT A.COD_ARTICU CODIGO, CANT CANTIDAD, B.RUBRO, AREA FROM SOF_INVENTARIO A 
			INNER JOIN SOF_RUBROS_TANGO B
			ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU
			WHERE A.USUARIO = '$user' 
		)A 
		GROUP BY CODIGO, RUBRO, AREA
	)
	
	";

ini_set('max_execution_time', 300);
odbc_exec($cid,$sqlHistorico)or die(exit("Error en odbc_exec"));


//HASTA ACA CONTROLADO - TODO OK

function insertarRubro ($a, $b, $c, $d){
	$dsn = "1 - CENTRAL";
	$usuario = "sa";
	$clave="Axoft1988";
	$cid=odbc_connect($dsn, $usuario, $clave);
	$sqlInsertarRubro = "SET DATEFORMAT YMD INSERT INTO SJ_CONTEOS_RUBROS ([USER], FECHA_MOV, RUBRO, CANT) VALUES ('$a', '$b', '$c', $d)";
	odbc_exec($cid, $sqlInsertarRubro);
}



//VACIAR TABLA DE ARTICULOS DEL LOCAL EN CENTRAL

$sqlVaciarRubroLocal = "DELETE FROM SJ_CONTEOS_RUBROS WHERE [USER] = '$user'";

odbc_exec($cid, $sqlVaciarRubroLocal) or die(exit("Error en odbc_exec"));



//ENVIAR ARTICULOS CON STOCK DEL LOCAL A CENTRAL

$dsnLocal = $_SESSION['dsn'];

$cidLocal = odbc_connect($dsnLocal, $usuario, $clave);

$sqlRubroLocal = "SELECT SUM(A.CANT_STOCK)CANT_STOCK, B.RUBRO FROM (
SELECT COD_ARTICU, SUM(CANT_STOCK)CANT_STOCK FROM STA19 
WHERE CANT_STOCK > 0 GROUP BY COD_ARTICU )A
INNER JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU GROUP BY B.RUBRO";

$result5 = odbc_exec($cidLocal, $sqlRubroLocal) or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result5)){
	insertarRubro ($user, $ahora, $v['RUBRO'], $v['CANT_STOCK']);
}



$sqlRubro=
	"
	SET DATEFORMAT YMD

	INSERT INTO SOF_INVENTARIO_RUBRO (FECHA, USUARIO, RUBRO, CANT, STOCK, DIF ) 
	(
			SELECT FECHA_MOV, [USER], RUBRO, ISNULL(A.CANT, 0) RUBRO_STOCK, ISNULL(B.CANT, 0)CANT, ISNULL(A.CANT, 0)-ISNULL(B.CANT, 0)DIF
			FROM SJ_CONTEOS_RUBROS A
			INNER JOIN 
			(
			SELECT CAST(A.FECHA AS DATE)FECHA, USUARIO, AREA, SUM(CANT)CANT, B.DESCRIP FROM SOF_INVENTARIO A
			INNER JOIN STA11FLD B
			ON A.RUBRO = B.IDFOLDER
			WHERE A.USUARIO = '$user'
			GROUP BY CAST(A.FECHA AS DATE), USUARIO, AREA, B.DESCRIP
			) B
			ON A.RUBRO = B.DESCRIP
	)
	";

ini_set('max_execution_time', 300);
odbc_exec($cid,$sqlRubro)or die(exit("Error en odbc_exec"));











$sqlSuperHistorico=
	"
	SET DATEFORMAT YMD

	INSERT INTO SOF_INVENTARIO_SUPER_HISTORICO SELECT * FROM 
	(
	SELECT A.ID, A.FECHA, A.USUARIO, A.AREA, A.COD_ARTICU, A.CANT, C.RUBRO, B.DESCRIP RUBRO_CONTROLADO FROM SOF_INVENTARIO A 
	INNER JOIN STA11FLD B ON A.RUBRO = B.IDFOLDER INNER JOIN SOF_RUBROS_TANGO C ON A.COD_ARTICU = C.COD_ARTICU
	)A
	
	";

ini_set('max_execution_time', 300);
odbc_exec($cid,$sqlSuperHistorico)or die(exit("Error en odbc_exec"));









}
?>
<script>setTimeout(function () {window.location.href= 'index.php';},1);</script>