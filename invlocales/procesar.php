<?php
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$dsn = '1 - CENTRAL';
$usuario = "sa";
$clave="Axoft1988";
$user = $_SESSION['username'];
$rubro = $_SESSION['rubro'];

$conn = odbc_connect($dsn, $usuario, $clave);

$conn2 = odbc_connect($dsn, $usuario, $clave);

function strright($rightstring, $length) {
	  return(substr($rightstring, -$length));
	}
	
$dia = date('Y-m').'-'.strright(('0'.(date('d'))),2);
$hora = (date('G')-5).':'.date('i:s');
$fechaHora = $dia.' '.$hora.':000' ;

		

		
$sql =	"
SELECT * FROM SOF_INVENTARIO WHERE USUARIO = '$user' AND RUBRO = '$rubro' 
";
$result=odbc_exec($conn,$sql)or die(exit("Error en odbc_exec"));

while($v=odbc_fetch_array($result)){
	
	$codigo = $v['COD_ARTICU'];
	$cant = $v['CANT'];
	$rubro = $v['RUBRO'];
	$area = $v['AREA'];
	
	$sql2 =	"
	SET DATEFORMAT YMD
	INSERT INTO SOF_INVENTARIO_FINAL (FECHA, USUARIO, AREA, COD_ARTICU, CANT, RUBRO) VALUES ('$fechaHora', '$user', '$area', '$codigo', '$cant', '$rubro');
	";
	
	odbc_exec($conn2,$sql2);
	
	
	//echo $v['FECHA'].' - '.$v['USUARIO'].' - '.$v['AREA'].' - '.$v['COD_ARTICU'].' - '.$v['CANT'].' - '.$v['RUBRO'].'</br>';
}

}

?>
<script>setTimeout(function () {window.location.href= 'index.php';},1);</script>
