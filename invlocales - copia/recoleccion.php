<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
$area = $_GET['area'];
$_SESSION['area'] = $area;
$ubicacion = $_SESSION['area'];

$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);

?>
<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	<title>Inventarios</title>	
	<link rel="shortcut icon" href="XL.png" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	

<div style="margin: 5px">
<h3>Área: <?php echo $area ?></h3>
<button onClick="volver()" class="btn btn-primary">Cerrar Area</button>
</div>

<script>
function volver() {window.location.href= 'area.php?rubro=<?php echo $_SESSION['rubro'];?>';};

$(function(){
	// Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
	$("#agregar").on('click', function(){
		$("#tabla tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla tbody");
	});
 
	// Evento que selecciona la fila y la elimina 
	$(document).on("click",".eliminar",function(){
		var parent = $(this).parents().get(0);
		$(parent).remove();
	});
});


</script>

<form action="" id="sucu" style="margin:20px" align="center" method="POST">
	
<?php
	
	function strright($rightstring, $length) {
	  return(substr($rightstring, -$length));
	}
	
	$dia = date('Y-m').'-'.strright(('0'.(date('d'))),2);
	$hora = (date('G')-5).':'.date('i:s');
	$fechaHora = $dia.' '.$hora.':000' ;



$sqlArea=
	"
	SET DATEFORMAT YMD
	SELECT * FROM SOF_INVENTARIO_FINAL WHERE AREA = '$area'
	";

ini_set('max_execution_time', 300);
$resultArea=odbc_exec($cid,$sqlArea)or die(exit("Error en odbc_exec"));


	while($v=odbc_fetch_array($resultArea)){
		if(odbc_num_rows($resultArea)>0){
			//$areaEscaneada = 1;
			echo '
			<audio src="Wrong.ogg" autoplay></audio>
			</br></br>
			<div class="alert alert-danger" role="alert" style="margin-left:15%; margin-right:15%">
			ATENCION!! Area ya controlada
			</div>';
			
		}
	}
	
	//odbc_close($resultArea);
	
?>

	<div class="form-inline" style="margin-left:20%">
	<label>Leer Codigo: </label>
	<input type="text" name="codigo" class="form-check-input" placeholder="Ingrese codigo" autofocus></input>
	
	
	<label>Cantidad: </label>
	<input type="text" name="cant" value="1" size="1" class="form-check-input"></input>
	
	<input type="submit" value="Ingresar" class="btn btn-primary">
	</div>
	
</form>



<?php

if(isset ($_POST['codigo']) || $_SESSION['conteo'] == 1){
	
$_GET['area'];
if(isset($_POST['codigo'])){
	$codigo = $_POST['codigo'];
}else{
	$codigo = '';
}

if(isset($_POST['cant'])){
	$cant = $_POST['cant'];
}else{
	$cant = '';
}



$rubro = $_SESSION['rubro'];

$sqlInsertar=
	"
	SET DATEFORMAT YMD
	INSERT INTO SOF_INVENTARIO (FECHA, USUARIO, AREA, COD_ARTICU, CANT, RUBRO) VALUES ('$fechaHora', '$user', '$area', '$codigo', '$cant', '$rubro');
	";

ini_set('max_execution_time', 300);
$insert = odbc_exec($cid,$sqlInsertar)or die(exit("Error en odbc_exec"));



$sql=
	"
	SET DATEFORMAT YMD
	SELECT CODIGO, DESCRIPCION, SUM(CANTIDAD) CANTIDAD FROM
	(
	SELECT A.COD_ARTICU CODIGO, B.DESCRIPCIO DESCRIPCION, CANT CANTIDAD FROM SOF_INVENTARIO A
	INNER JOIN STA11 B
	ON A.COD_ARTICU COLLATE Latin1_General_BIN  = B.COD_ARTICU 
	WHERE A.USUARIO = '$user' and AREA = '$area'
	)A
	GROUP BY CODIGO, DESCRIPCION
	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));







?>
<div class="container">
<!--<form action="procesar.php" method="POST">-->
<table class="table table-striped" style="margin-left:20px; margin-right:50px ; margin-bottom:30px" id="tabla">

        <tr >

				<td class="col-"><h4>CODIGO</h4></td>
		
				<td class="col-"><h4>DESCRIPCION</h4></td>
		
                <td class="col-"><h4>CANTIDAD</h4></td>
				
				<td class="col-"><h4></h4></td>
				
				<td></td>
				
        </tr>

		
        <?php

		$sum = 0;
	   
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr class="fila-base">

				<td class="col-"><input name="codigo[]" value="<?php echo $v['CODIGO'] ;?>" size="13" readonly></td>
		
                <td class="col-"><?php echo $v['DESCRIPCION'] ;?></td>
				
				<td class="col-" align="center"><input name="cant[]" value="<?php echo $v['CANTIDAD'] ;?>" size="2" readonly></td>

                <td class="col-"></td>
				
				<td><button type="button" class="btn btn-outline-danger btn-sm" onClick="window.location.href= 'eliminaArticulo.php?codigo=<?php echo $v['CODIGO']; ?>'">Eliminar</button></td>
				
				<?php $sum = $sum + $v['CANTIDAD']; ?>

        </tr>
		
		      
		
        <?php

        }

        ?>

		<tr class="fila-base">

		<td></td>

		<td align="center"><h5>TOTAL DE ARTICULOS</h5></td>

		<td>
			<?php
			echo "<h3>".$sum."</h3>";
			?>
		</td>
		
		<td></td>
		
		<td></td>
		
        </tr>  
		
        		
</table>
</div>

<!--<input type="submit" class="btn btn-primary" style="margin-left:80%; margin-top:20px " value="Procesar"> </form>-->


<script>
function procesar() {window.location.href= 'procesar.php';};
</script>
        <?php


}

}
?>