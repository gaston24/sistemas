<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}	

$fecha1 = date('Y-m').'-'.strright(('0'.(date('d'))),2);	
$fecha2 = date('Y-m').'-'.strright(('0'.(date('d'))),2);

	
?>
<!doctype html>
<html>
<head>
<title>EXPORTA</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</head>
<body>
<p>

<button type="button" class="btn btn-primary" OnClick="location.href='index.php' " style="margin:5px">Volver</button>

</br>
<div align="center" style="margin-top:10px"> 

<img src="../logo.jpg">
</div>
<form action="exportar.php" align="center" method="get">
Desde
<input type="date" name="desde" value="<?php echo $fecha1 ?>">
Hasta
<input type="date" name="hasta" value="<?php echo $fecha2 ?>">

<input type="submit" value="Exportar" class="btn btn-outline-success"> 
</form>
</p>
</body>
</html>
<?php
}
?>