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
$rubro = $_SESSION['descrubro'];
$dsn = "1 - CENTRAL";
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);

?>
<!DOCTYPE HTML>

<html>
<head>

	<title>Inventarios</title>	
<?php include '../../css/header_simple.php'; ?>


<body>	

<div style="margin: 5px">
<h3>Rubro: <?php echo $rubro ?> - Área: <?php echo $area ?></h3>
<button onClick="volver()" class="btn btn-primary">Cerrar Area</button>
</div>



<form action="" id="sucu" style="margin:20px" align="center" method="POST">
	
<?php
	
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
	<input type="text" name="codigo" class="form-check-input" placeholder="Ingrese codigo" autofocus required></input>
	
	
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
	$cod_ultimo = $_POST['codigo'];
}else{
	$codigo = '';
	$cod_ultimo = '';
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
				
				<td class="col-"><h4></h4></td>
		
				<td class="col-"><h4>DESCRIPCION</h4></td>
		
                <td class="col-"><h4></h4></td>
				
				<td class="col-"><h4>CANTIDAD</h4></td>
				
				<td class="col-"><h4></h4></td>
				
				<td></td>
				
        </tr>

		
        <?php

		$sum = 0;
	   
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr class="fila-base">

				<td class="col-"><?php echo $v['CODIGO'] ;?></td>
		
				<td class="col-"><input name="codigo[]" value="<?php echo $v['CODIGO'] ;?>" size="13" hidden></td>
		
                <td class="col-"><?php echo $v['DESCRIPCION'] ;?></td>
				
				<td class="col-" align="center"><input name="cant[]" value="<?php echo $v['CANTIDAD'] ;?>" size="2" hidden></td>
				
				<td class="col-" align="center"><?php echo $v['CANTIDAD'] ;?></td>

                <td class="col-"></td>
				
				<td class="col-"><img src="eliminar.png" width="23px" height="23px" align="left" onClick="window.location.href='eliminaArticulo.php?codigo=<?php echo $v['CODIGO'] ;?>'"></img></td>
				
				<?php $sum = $sum + $v['CANTIDAD']; ?>

        </tr>
		
		      
		
        <?php

        }

        ?>

		<tr class="fila-base">

		<td><strong>Ultimo controlado:</strong></td>

		<td><?php echo $cod_ultimo;?></td>
		
		<td></td>
		
		<td align="center"><h5>TOTAL DE ARTICULOS</h5></td>

		<td>
			<?php
			echo "<h3>".$sum."</h3>";
			?>
		</td>
				
		<td></td>
		
		<td><button type="button" class="btn btn-info btn-sm" onClick="historial()">Historial</button></td>
		
        </tr>  
		
        		
</table>

</div>

<script>
function volver() {window.location.href= 'area.php?rubro=<?php echo $_SESSION['rubro'];?>';};


function historial(){

<?php

$sql5=
	"
	SELECT * FROM SOF_INVENTARIO WHERE USUARIO = '$user' AND AREA = '$area' ORDER BY ID DESC
	";

ini_set('max_execution_time', 300);
$cod_ultimo = '';
$result5=odbc_exec($cid,$sql5)or die(exit("Error en odbc_exec"));
?>
alert("Ultimos codigos ingresados :\n<?php while($v=odbc_fetch_array($result5)){ echo $v['COD_ARTICU'].'\n'; if($cod_ultimo==''){$cod_ultimo = $v['COD_ARTICU'] ;} }?>");

};





</script>

</body>

<?php
}
}
?>