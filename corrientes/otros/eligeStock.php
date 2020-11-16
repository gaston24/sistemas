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
                <td>910</td>
				<td>FRACAB</td>
				<td>CABILDO</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn2.php?dsn=910'">CABILDO</button></td>
		</tr>
		
		<tr>
                <td>900</td>
				<td>FRREMP</td>
				<td>REMEROS PLAZA</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn2.php?dsn=900'">REMEROS</button></td>
		</tr>
		
		<tr>
                <td>849</td>
				<td>FRSICT</td>
				<td>SAN ISIDRO - CENTRO</td>
				<td><button type="button" class="btn btn-outline-success btn-sm" onclick="window.location='capturaDsn2.php?dsn=849'">SAN ISIDRO</button></td>
		</tr>
		
</table>







</nav>

</div>


</body>
</html>

<?php
}
?>
