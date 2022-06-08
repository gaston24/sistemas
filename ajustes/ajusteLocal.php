<?php
session_start();
if(!isset($_SESSION['username'])){
	header("Location:index.php");
}elseif($_SESSION['ajuste'] != 1){
	header("Location:index.php");
}else{
//include 'conex.php';
include 'consultas.php';

if(isset($_POST['localSeleccionado'])){
	$_SESSION['dsn'] = $_POST['localSeleccionado'];
}

$dsn = $_SESSION['dsn'];
$user = 'sa';
$pass = 'Axoft1988';

$cid = odbc_connect($dsn, $user, $pass);

odbc_exec($cid, $sqlNuevos);

$result = odbc_exec($cid, $sql) or die (exit("Error en conexion"));

require_once 'Class/articulo.php';

$maestroArticulos = new Articulo();
$todosLosArticulos = $maestroArticulos->traerArticulos();


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
<link rel="stylesheet" type="text/css" href="select2/select2.min.css">

<script src="select2/select2.min.js"></script>
<link rel="stylesheet" href="css/style.css">

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

<div class="container-fluid">

<div class="row">
	<button type="button" class="btn btn-primary" id="btn_back" OnClick="location.href='selecLocal.php'">Volver</button>

	<div class="mt-2" id="busqRapida">
		<label id="textBusqueda">Busqueda rapida:</label>
		<input type="text" id="textBox"  placeholder="Sobre cualquier campo..." onkeyup="myFunction()"  class="form-control form-control-sm"></input>  
	</div>

	<div id="div_total">
        <label id="labelTotal">Total artículos</label> 
		<input name="total_todo" id="total" value="0" type="text" class="form-control" readonly>
	</div>

</div>
<br>
<form method="POST" action="confirmAjus.php">
<div class="table-responsive">
	<table class="table table-striped" id="tableAjuste">

		<thead>
			<tr>
				<th>FECHA</th>
				<th>TIPO COMP</th>
				<th>COMP LOCAL</th>
				<th>CODIGO</th>
				<th>DESCRIPCION</th>
				<th>CANT</th>
				<th>CODIGO NUEVO</th>
			</tr>
		</thead>

		<tbody  id="table">
		
        <?php

		$contador = 1;

		while($v=odbc_fetch_array($result)){


        ?>
		
        	<tr>
                <td><?php echo $v['FECHA_MOV'] ;?></td>
				<td><input class="form-control input" name="tcomp[]" type="text" value="<?php echo $v['NCOMP_ORIG'] ;?>" readonly></td>
                <td><input class="form-control input" name="ncomp[]" type="text" value="<?php echo $v['N_COMP'] ;?>" readonly></a></td>
				<td><input class="form-control input" name="codigo[]" type="text" value="<?php echo $v['COD_ARTICU'] ;?>"  readonly></td>
				<td><?php echo $v['DESCRIPCIO'] ;?></td>
				<td><input class="form-control input" name="cant[]" type="text" value="<?php echo $v['CANT'] ;?>"  readonly></td>
				<td>
				<div class="col">
                    <select id="controlBuscador<?=$contador?>" class="ctr-busc" name="articulo[]" onchange="contar();">
                        <option selected></option>
                        <?php
                    foreach($todosLosArticulos as $articulo => $key){
                    ?>
                    <option value="<?= $key['COD_ARTICU'] ?>"><?= $key['COD_ARTICU'] ?></option>
                    <?php   
                    }
                    ?>
                    </select>
					</div>
				</td>
			</tr>
        <?php
		$contador++;
        	}
        ?>
        </tbody>	
	</table>
</div>

<button type="submit" class="btn btn-success" id="btn_ajustar" onclick="ocultarBoton(); procesar();">Ajustar</button>

</form>

</div>

	<script src="main.js" charset="utf-8"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<script type="text/javascript">
		
	</script>

</body>
<?php
}
?>
</html>