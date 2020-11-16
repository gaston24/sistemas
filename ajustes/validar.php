<?php
session_start();

$dsn = '1 - CENTRAL';
$nom = 'Axoft';
$con = 'Axoft';

$user = $_POST['user'];
$pass = $_POST['pass'];

$sql = 
"
SELECT * FROM SOF_USUARIOS WHERE NOMBRE = '$user' AND PASS = '$pass';
";

$cid = odbc_connect($dsn, $nom, $con);

$result = odbc_exec($cid, $sql);

if(odbc_num_rows($result)==1){

while($v=odbc_fetch_array($result)){

    setcookie("idioma","es",time()+60*60*24*3);
	$_SESSION['username'] = $v['NOMBRE'];
	$_SESSION['permisos'] = $v['PERMISOS'];
	$_SESSION['dsn'] = $v['DSN'];
	$_SESSION['ajuste'] = 1;
	header("Location: ajusteLocal.php");
	//echo $_SESSION['username'];
}
}else{
	header("Location: index.php");
}
/*
$nomSoleil = 'xlsoleil@lakerscorp.com.ar';
$passSoleil = 'soleil,123';
$nomGurru = 'xlgurruchaga@lakerscorp.com.ar';
$passGurru = 'gurru,123';


if(isset($_POST['user'])){
	
	$nombre = $_POST['user'];
	$pass = $_POST['pass'];
		
	if($nombre == $nomSoleil && $pass == $passSoleil){
			
		if(isset($_POST['remember'])){
			setcookie('name', $nombre,time()+60*60*7);
		}
		
		session_start();
		$_SESSION['nombre'] = $nombre;
		header("location: ajusteLocal.php?dsn=SOLEIL");		
	}else if($nombre == $nomGurru && $pass == $passGurru){
			
		if(isset($_POST['remember'])){
			setcookie('name', $nombre,time()+60*60*7);
		}
		
		session_start();
		$_SESSION['nombre'] = $nombre;
		header("location: ajusteLocal.php?dsn=GURRU");		
	}else{
		echo "<script>alert('Debe escribir un nombre de usuario y contraseña correctos')
		setTimeout(function () {
		window.location.href= 'index.php'; // the redirect goes here
		},1);
		</script>";
		
	}
}
*/
?>