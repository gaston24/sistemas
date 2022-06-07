<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Control de Remitos</title>
<?php include '../../css/header.php'; ?>
</head>
<body>

<button type="button" class="btn btn-primary" onclick="location.href='../index.php'" style="margin:5px">Inicio</button>
<button type="button" class="btn btn-primary" onclick="location.href='controlHistoricos.php'" style="margin:5px">Historial</button>


<div align="center" style="margin-top:10%">
<form action="limpiar.php" id="pedidos" method="get">

<br><br>
<div>
	
	<div style="display: inline-block">
		<label>Remito</label>
		<input type="text" name="rem" placeholder="Numero de Remito" class="form-control form-control-sm col-sm-13"  required autofocus >
	</div>
	
	
	
	<div class="form-group col-sm-3" style="display: inline-block">
      <label for="inputState">Usuario</label>
      <select id="inputState" class="form-control form-control-sm" name="usuario" required>
        <option value="" selected >Usuario</option>
<?php
$dsn = '1 - CENTRAL'; $user = 'sa'; $pass = 'Axoft1988';  $nroSucurs = $_SESSION['numsuc'];
$sql = "SELECT C.NOMBRE, C.APELLIDO, 
		COD_VENDED BLOQUE, B.DESC_SUCURSAL, CA_1118_NUM_SUCURSAL_M NRO_SUCURSAL FROM GVA23 A
		INNER JOIN [LAKERBIS].LOCALES_LAKERS.DBO.SUCURSALES_LAKERS B ON A.CA_1118_NUM_SUCURSAL_M = B.NRO_SUCURSAL
		INNER JOIN [TANGO-SUELDOS].LAKERS_CORP_SA.DBO.LEGAJO C ON A.COD_VENDED = C.BLOQUE COLLATE Latin1_General_BIN
		WHERE CA_1118_NUM_SUCURSAL_M IS NOT NULL AND CA_1118_NUM_SUCURSAL_M = $nroSucurs
		ORDER BY APELLIDO
";
$cid = odbc_connect($dsn, $user, $pass ); $result = odbc_exec($cid, $sql);
while($v=odbc_fetch_array($result)){
	echo '<option value="'.$v['BLOQUE'].'">'.$v['APELLIDO'].' '.$v['NOMBRE'].'</option>';
}


?>
	       
      </select>
    </div>
	
	

	
	
</div>
<br>
<input type="submit" value="CONSULTAR" class="btn btn-primary btn-sm"></br>
</form>
</div>


<?php
if($_SESSION['conteo'] == 1){
	echo '</br></br><div class="alert alert-danger" role="alert" style="margin-left:15%; margin-right:15%">	El remito no existe en este local!</div>';
	$_SESSION['conteo'] = 0;
}elseif($_SESSION['conteo'] == 2){
	echo '</br></br><div class="alert alert-danger" role="alert" style="margin-left:15%; margin-right:15%">	El remito ya fue controlado</div>';
	$_SESSION['conteo'] = 0;
}


}
?>
</body>
</html>