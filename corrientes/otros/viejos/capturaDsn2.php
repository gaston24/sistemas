<?php
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$local = $_GET['dsn'];

$dsn = '1 - CENTRAL';
$user = 'sa';
$pass = 'Axoft1988';

$cid=odbc_connect($dsn, $user, $pass);

$sqlDsn = "SELECT TOP 1 DSN FROM SOF_USUARIOS WHERE NRO_SUCURS = $local";



$resultDsn=odbc_exec($cid,$sqlDsn)or die(exit("Error en odbc_exec"));
while($v=odbc_fetch_array($resultDsn)){
	$_SESSION['dsnLocal'] = $v['DSN'];
}
	
header("Location: stockLocales.php");		

}
?>