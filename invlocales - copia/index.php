<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}
$ahora = date('Y-m').'-'.strright(('0'.(date('d'))),2);	
	
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


<div style="margin-left: 10px ; margin-top:5px">


<button onClick="volver()" class="btn btn-primary">Inicio</button>
<button onClick="historial()" class="btn btn-outline-info">Informar Ajustes</button>
<button onClick="historialRubro()" class="btn btn-outline-info">Historial Rubros</button>
<button onClick="historialDetalle()" class="btn btn-outline-info">Historial Detalle</button>

</br></br>
<script>
	function volver() {window.location.href= '../index.php';};
	function historial() {window.location.href= 'ajustes.php';};
	function historialRubro() {window.location.href= 'historialRubro.php';};
	function historialDetalle() {window.location.href= 'historialDetalle.php';};
</script>

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
	SELECT * FROM STA11FLD WHERE IDFOLDER < 28
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>

</form>



</div>

</br></br></br>

<div style="margin-left:20%;margin-right:20%">

<table class="table table-striped" >

        <tr >

				<td ><h4>RUBRO</h4></td>
									
        </tr>
		
        <?php

		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

			<td ><a href="limpiar.php?rubro=<?php echo $v['IDFOLDER']; ?>"><?php echo $v['DESCRIP'] ;?></a></td>
		
        </tr>
				
        <?php

        }

        ?>
        		
</table>

</div>



</div>




<?php
$_SESSION['conteo'] = 0;

}
?>
</body>
</html>