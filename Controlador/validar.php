<?php
session_start();
require_once __DIR__.'/../Class/extralarge.php';

$user = $_POST['user'];
$pass = $_POST['pass'];

$login = new Extralarge();

$loginRes = $login->login($user, $pass);


if( count($loginRes) == 0 ){

	header('Location:../login.php');

}else{
	
	$_SESSION['usuarioUy'] = $loginRes['IS_USER_UY'];
	$_SESSION['username'] = $loginRes['NOMBRE'];
	$_SESSION['permisos'] = $loginRes['PERMISOS'];
	$_SESSION['dsn'] = $loginRes['DSN'];
	$_SESSION['numsuc'] = $loginRes['NRO_SUCURS'];
	$_SESSION['codClient'] = $loginRes['COD_CLIENT'];
	$_SESSION['descLocal'] = $loginRes['DESCRIPCION'];
	$_SESSION['conexion_dns'] = $loginRes['CONEXION_DNS'];
	$_SESSION['base_nombre'] = $loginRes['BASE_NOMBRE'];
	$_SESSION['nuevoPedido'] = 1;
	$_SESSION['cargaPedido'] = 1;
	$_SESSION['ajuste'] = 1;
	$_SESSION['vendedor'] = $loginRes['COD_VENDED'];
	$_SESSION['conteo'] = 0;
	$_SESSION['dashboard'] = $loginRes['URL_DASHBOARD'];
	$_SESSION['deposi'] = $loginRes['COD_DEPOSI'];
	$_SESSION['tipo'] = $loginRes['TIPO'];
	$_SESSION['habPedidos'] = $loginRes['EXCLUYE_PEDIDOS'];
	$_SESSION['esOutlet'] = $loginRes['IS_OUTLET'];
	// esto hay que revisarlo, esta mal
	$_SESSION['connection_db'] = $_SESSION['cid'] != false ? true : false;
	
	// datos de credito del cliente
	// cupoCredi es lo real disponible para pedidos
	$_SESSION['cupoCredi'] = $loginRes['CUPO_CREDI'];
	// cupo de credito dispuesto por la empresa
	$_SESSION['cupoCrediCliente'] = $loginRes['CUPO_CREDITO_CLIENTE'];
	// esto es al pedo
	$_SESSION['totalDisponible'] = isset($loginRes['TOTAL_DISPONIBLE']) ? $loginRes['TOTAL_DISPONIBLE'] : 0;
	// pedidos abiertos
	$_SESSION['pedidos'] = $loginRes['PEDIDOS'];
	// total de deuda
	$_SESSION['totalDeuda'] = $loginRes['TOTAL_DEUDA'];

	$_SESSION['pantallas'] = true;

	
	if($loginRes['NRO_SUCURS'] == 202){
            
		$_SESSION['conexion_dns'] = 'DESKTOP-K8EK5EV\AXSQLEXPRESS';
		$_SESSION['base_nombre'] = 'XL__NUEVOCENTRO';
		header("Location: eliminaPedido.php");

	}
	
	if($loginRes['NRO_SUCURS'] == 201){
	
		$_SESSION['conexion_dns'] = 'DESKTOP-L6VOQPJ\AXSQLEXPRESS_1';
		$_SESSION['base_nombre'] = 'TRES_CRUCES';
		header("Location: eliminaPedido.php");

		
	}
	
	if($loginRes['COD_VENDED']!='0' && $_SESSION['tipo']!= 'MAYORISTA'){
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: ../mayoristas/index.php");
	}elseif($_SESSION['tipo'] == 'MAYORISTA'){
		header("Location: ../index.php");
	}elseif($_SESSION['username']== 'supervisoras'){
		header("Location: ../supervisoras/index.php");	
	}elseif($_SESSION['username']== 'cordoba'){
		header("Location: ../cordoba/eliminaPedidoCordoba.php");		
	}elseif($_SESSION['username']== 'levi'){
		header("Location: ../levi/eliminaPedidoLevi.php");	
	}elseif($_SESSION['username']== 'salta'){
		header("Location: ../salta/eliminaPedidoSalta.php");	
	}elseif($_SESSION['permisos']== '6'){
		header("Location: ../conteos/index.php");
	}elseif($_SESSION['permisos']== '5'){
		header("Location: ../conteos/index.php");			
	}elseif($_SESSION['permisos']== '4' && $_SESSION['tipo']=='SUPERVISION'){
		header("Location: ../../estadisticas/index.php");	
	}elseif($_SESSION['username']== 'LOGISTICA'){
		header("Location: ../control/control_logistica.php");	
	}elseif($_SESSION['username']== 'comercial2'){
		header("Location: ../../comercial2/index.php");		
	}elseif($_SESSION['username']== 'directores'){
		header("Location: ../../ppp/index.php");		
	}elseif($_SESSION['username']== 'rotaciones'){
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: ../rotaciones/index.php");
	}elseif($_SESSION['dsn']== 'SIN'){
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: ../index.php");
	}elseif($loginRes['NOMBRE']=='COMERCIAL'){
		header("Location: ../inicial/admin.php");
	}elseif($_POST['conecta']=='no' && $_SESSION['numsuc'] > 104){
		$_SESSION['dsn']= 'SIN';
		$_SESSION['nuevoPedido']=0; 
		$_SESSION['cargaPedido']=1;
		header("Location: ../index.php");		
	}elseif( in_array($_SESSION['tipo'], ['LOCAL_PROPIO', 'FRANQUICIA']) ){
		header("Location: eliminaPedido.php");
	}else{
		header('Location:../login.php');
	}
	
}

?>