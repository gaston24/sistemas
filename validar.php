<?php
session_start();

$dsn = '1 - CENTRAL';
$nom = 'sa';
$con = 'Axoft1988';

$user = $_POST['user'];
$pass = $_POST['pass'];

$sql = 
"
SELECT A.*, ISNULL(B.URL_DASHBOARD, 'SIN_URL')URL_DASHBOARD, ISNULL(C.EXCLUYE_PEDIDOS, 1) EXCLUYE_PEDIDOS
FROM SOF_USUARIOS A
LEFT JOIN SJ_LOCALES_DASHBOARD B
ON A.NRO_SUCURS = B.NRO_SUCURS 
LEFT JOIN SJ_PPP_EXCLUYE_CLIENTE C
ON A.COD_CLIENT = C.COD_CLIENT COLLATE Latin1_General_BIN
WHERE A.NOMBRE = '$user' AND A.PASS = '$pass';
";

$cid = odbc_connect($dsn, $nom, $con);

$result = odbc_exec($cid, $sql);

if(odbc_num_rows($result)==1){

while($v=odbc_fetch_array($result)){
	
	$_SESSION['username'] = $v['NOMBRE'];
	$_SESSION['permisos'] = $v['PERMISOS'];
	$_SESSION['dsn'] = $v['DSN'];
	$_SESSION['numsuc'] = $v['NRO_SUCURS'];
	$_SESSION['codClient'] = $v['COD_CLIENT'];
	$_SESSION['descLocal'] = $v['DESCRIPCION'];
	$_SESSION['nuevoPedido'] = 1;
	$_SESSION['cargaPedido'] = 1;
	$_SESSION['ajuste'] = 1;
	$_SESSION['vendedor'] = $v['COD_VENDED'];
	$_SESSION['conteo'] = 0;
	$_SESSION['dashboard'] = $v['URL_DASHBOARD'];
	$_SESSION['deposi'] = $v['COD_DEPOSI'];
	$_SESSION['tipo'] = $v['TIPO'];
	$_SESSION['habPedidos'] = $v['EXCLUYE_PEDIDOS'];
	
	
	if($v['COD_VENDED']!='0' && $_SESSION['tipo']!= 'MAYORISTA'){
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: mayoristas/index.php");
	}elseif($_SESSION['tipo'] == 'MAYORISTA'){
		header("Location: index.php");
	}elseif($_SESSION['username']== 'supervisoras'){
		header("Location: supervisoras/index.php");	
	}elseif($_SESSION['username']== 'cordoba'){
		header("Location: cordoba/eliminaPedidoCordoba.php");		
	}elseif($_SESSION['username']== 'dodivani'){
		header("Location: dodivani/eliminaPedidoDodi.php");
	}elseif($_SESSION['username']== 'levi'){
		header("Location: levi/eliminaPedidoLevi.php");	
	}elseif($_SESSION['username']== 'salta'){
		header("Location: salta/eliminaPedidoSalta.php");	
	}elseif($_SESSION['username']== 'ramiro'){
		header("Location: conteos/index.php");			
	}elseif($_SESSION['username']== 'estadisticas'){
		header("Location: ../estadisticas/index.php");	
	}elseif($_SESSION['username']== 'LOGISTICA'){
		header("Location: control/control_logistica.php");	
	}elseif($_SESSION['username']== 'comercial2'){
		header("Location: ../comercial2/index.php");		
	}elseif($_SESSION['username']== 'directores'){
		header("Location: ../ppp/index.php");		
	}elseif($_SESSION['username']== 'rotaciones'){
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: rotaciones/index.php");
	}elseif($_SESSION['dsn']== 'SIN'){
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: index.php");
	}elseif($v['NOMBRE']=='COMERCIAL')
		{
		header("Location: inicial/admin.php");
	}elseif($_POST['conecta']=='no' && $_SESSION['numsuc'] > 104){
		$_SESSION['dsn']= 'SIN';
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: index.php");		
	}else{
		header("Location: eliminaPedido.php");
	}
	
	
	
	
}
}else{
	header("Location: cargaPedido.php");
}
?>