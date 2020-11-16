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
                <td>844</td>
				<td>FRSAL1</td>
				<td>ALTO NOA</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn.php?dsn=844'">ALTO NOA</button></td>
		</tr>
		
		<tr>
                <td>845</td>
				<td>FRSAL2</td>
				<td>PORTAL</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn.php?dsn=845'">PORTAL</button></td>
		</tr>
		
		<tr>
                <td>866</td>
				<td>FRSAL3</td>
				<td>PASEO</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn.php?dsn=866'">PASEO</button></td>
		</tr>
		
</table>







</nav>

</div>


</body>
</html>

<?php
}
?>
