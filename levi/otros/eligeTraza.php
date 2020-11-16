<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

$vendedor = $_SESSION['vendedor'];

?>
<!doctype html>
<head>
<title>Carga Inicial</title>
<?php include '../../../css/header.php'; ?>


</head>
<body>

</br>
<div class="container-fluid">

<h2 align="center">Seleccionar Sucursales</h2></br>

<nav style="margin-left:20%; margin-right:20%">


<table class="table table-striped" id="id_tabla">

        <tr>
			<td ><strong>NUM SUC</strong></td>
			<td ><strong>CODIGO</strong></td>
			<td ><strong>NOMBRE</strong></td>
			<td ></td>
        </tr>
	
        <tr>
                <td>851</td>
				<td>FRSMI2</td>
				<td>SAN MIGUEL</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn.php?dsn=851'">SAN MIGUEL</button></td>
		</tr>
		
		<tr>
                <td>903</td>
				<td>FRBALL</td>
				<td>VILLA BALLESTER</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn.php?dsn=903'">VILLA BALLESTER</button></td>
		</tr>
		
		<tr>
                <td>833</td>
				<td>FRMAL2</td>
				<td>MALVINAS</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn.php?dsn=903'">MALVINAS</button></td>
		</tr>
		
</table>







</nav>

</div>


</body>
</html>

<?php
}
?>
