<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}
$ahora = date('Y-m').'-'.strright(('0'.(date('d'))),2);	


$rubro = $_SESSION['rubro'];
	
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Inventarios</title>
<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head>
<body>


<div style="margin-left: 10px ; margin-top:5px ">

<form action="exportar.php" id="exportar" method="GET" >
EXPORTAR
Desde <input type="date" name="desde" value="<?php echo $ahora ?>"></input>
Hasta <input type="date" name="hasta" value="<?php echo $ahora ?>"></input>
<input type="submit" value="Exportar" class="btn btn-primary btn-sm">
<?php
if($_SESSION['username']=='ramiro'){
//echo 'm'.$_SESSION['username'].'m';
?>
<button type="button" class="btn btn-outline-danger btn-sm" onClick="location.href='vaciar.php'">Limpiar Historial</button>
<?php
}

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);

$sql=
	"
	SET DATEFORMAT YMD
	SELECT DESCRIP FROM STA11FLD WHERE IDFOLDER = '$rubro'
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

</form>

</br>
<button type="button" class="btn btn-outline-danger" onClick="location.href='procesar.php'">Cerrar Rubro</button>


</div>
</br>
<?php
while($v=odbc_fetch_array($result)){ 
echo '<h2 align="center">RUBRO: '.$v['DESCRIP'].'</h2>';
}
?>
<div style="margin-top: 80px">
<form action="recoleccion.php" id="pedidos" style="margin:20px" align="center" method="GET" >
INGRESAR EL AREA A CONTROLAR</br></br>
<input type="text" name="area" placeholder="Area" id="caja">
<input type="submit" value="Comenzar" class="btn btn-primary"></br>
</form>



<div align="right" style="margin-right:40%; margin-top:10%; margin-left:40%">
<button type="button" class="btn btn-outline-success btn-lg btn-block" onClick="location.href='enviarHistorico.php'">Finalizar Rubro</button>
</div>

</div>


<script>
window.onload = function() {
  var input = document.getElementById("caja").focus();
}
</script>

<?php
}
?>
</body>
</html>