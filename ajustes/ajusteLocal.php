<?php
session_start();
if(!isset($_SESSION['username'])){
	header("Location:index.php");
}elseif($_SESSION['ajuste'] != 1){
	header("Location:index.php");
}else{
//include 'conex.php';
include 'consultas.php';
$dsn = $_SESSION['dsn'];
$user = 'sa';
$pass = 'Axoft1988';

$cid = odbc_connect($dsn, $user, $pass);


odbc_exec($cid, $sqlNuevos);

$result = odbc_exec($cid, $sql) or die (exit("Error en conexion"));

?>

<!doctype html>
<html>
<head>

<title>Ajuste Locales</title>
<link rel="shortcut icon" href="../../css/icono.jpg" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<style>
td{
	font-size: 11px;
}
.form-control{
	font-size: 11px;
}
</style>
</head>
<body>

<!--<input type="button" value="Salir" name="B4" OnClick="location.href='index.php' ">-->

<div class="container-fluid">
<button type="button" class="btn btn-primary" OnClick="location.href='../index.php' " style="margin:5px">Salir</button>
</br></br>
<form method="POST" action="confirmAjus.php">
<table class="table table-striped">

		<thead>
			<tr>
				<th>FECHA</th>
				<th>COMP ORIGEN</th>
				<th>COMP LOCAL</th>
				<th>CODIGO</th>
				<th>DESCRIPCION</th>
				<th>CANT</th>
				<th>CODIGO NUEVO</th>
			</tr>
		</thead>
		
        <?php
       
		while($v=odbc_fetch_array($result)){

        ?>
		
        <tr>
                <td><?php echo $v['FECHA_MOV'] ;?></td>
				<td><input class="form-control" name="ncomp[]" type="text" value="<?php echo $v['NCOMP_ORIG'] ;?>" readonly></td>
                <td><?php echo $v['N_COMP'] ;?></a></td>
				<td><input class="form-control" name="codigo[]" type="text" value="<?php echo $v['COD_ARTICU'] ;?>"  readonly></td>
				<td><?php echo $v['DESCRIPCIO'] ;?></td>
				<td><input class="form-control" name="cant[]" type="text" value="<?php echo $v['CANT'] ;?>"  readonly></td>
				<td><input class="form-control" name="nuevo[]" type="text" value="<?php echo $v['COD_NUEVO'] ;?>" ></td>
		</tr>
		
        <?php

        }

        ?>
        		
</table>


<button type="submit" class="btn btn-primary">Ajustar</button>
</form>

</div>

</body>
<?php
}
?>
</html>