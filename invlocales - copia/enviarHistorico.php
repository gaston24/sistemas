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

	INSERT INTO SOF_INVENTARIO_HISTORICO (FECHA, USUARIO, COD_ARTICU, CANT, RUBRO )
	(
		SELECT '$ahora', '$user', CODIGO, SUM(CANTIDAD) CANTIDAD, RUBRO FROM 
		(
			SELECT A.COD_ARTICU CODIGO, CANT CANTIDAD, B.RUBRO FROM SOF_INVENTARIO A 
			INNER JOIN SOF_RUBROS_TANGO B
			ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU
			WHERE A.USUARIO = '$user' 
		)A 
		GROUP BY CODIGO, RUBRO
	)
	";

ini_set('max_execution_time', 300);
odbc_exec($cid,$sqlHistorico)or die(exit("Error en odbc_exec"));


$sqlRubro=
	"
	SET DATEFORMAT YMD

	INSERT INTO SOF_INVENTARIO_RUBRO (FECHA, USUARIO, RUBRO, CANT, STOCK, DIF ) 
	(
		SELECT '$ahora', '$user', RUBRO, SUM(CANTIDAD) CANTIDAD, CANT_STOCK, (SUM(CANTIDAD)-CANT_STOCK)DIF  FROM 
		(
			SELECT CODIGO, CANTIDAD, CASE WHEN CANT_STOCK IS NULL THEN 0 ELSE CANT_STOCK END CANT_STOCK, RUBRO FROM
			(
				SELECT A.COD_ARTICU CODIGO, CANT CANTIDAD, CAST(C.CANT_STOCK AS INT)CANT_STOCK, B.RUBRO FROM SOF_INVENTARIO A 
				INNER JOIN SOF_RUBROS_TANGO B
				ON A.COD_ARTICU = B.COD_ARTICU
				INNER JOIN 
				(
					SELECT B.RUBRO,  SUM(CANT_STOCK)CANT_STOCK FROM STA19 A
					INNER JOIN 
					(SELECT COD_ARTICU, RUBRO FROM SOF_RUBROS_TANGO WHERE RUBRO IN
					(SELECT B.RUBRO FROM SOF_INVENTARIO A 
					INNER JOIN SOF_RUBROS_TANGO B
					ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN)
					)B 
					ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN
					GROUP BY B.RUBRO
				)C
				ON B.RUBRO COLLATE Latin1_General_BIN = C.RUBRO COLLATE Latin1_General_BIN
				WHERE A.USUARIO = '$user' 
			)A
		)A 
		GROUP BY RUBRO, CANT_STOCK
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
<script>setTimeout(function () {window.location.href= 'index.php';},1000);</script>