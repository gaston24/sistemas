<?php session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
?>
<!DOCTYPE HTML>

<html>
<head>
	<meta charset="utf-8">
	<title>Stock por Local</title>	
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<body>	


<button type="button" class="btn btn-primary btn-sm" OnClick="location.href='index.php' " style="margin:5px">Volver</button>

	<form action="" id="sucu" >
	Elija sucursal:
	<select name="sucursal" form="sucu" >
		<option value="TODOS">TODOS</option>
		<option value="1 - CENTRAL">Central</option>
		<option value="2 - NVOUNICENTER">Unicenter</option>
		<option value="3 - ALTO PALERMO">Alto Palermo</option>
		<option value="6 - AVELLANEDA">Avellaneda</option>
		<option value="7 - ABASTO">Abasto</option>
		<option value="8 - MORENO">Moreno</option>
		<option value="10 - SOLAR">Solar</option>
		<option value="11 - TORTUGAS">Tortugas</option>
		<option value="13 - CABILDO">Cabildo</option>
		<option value="29 - SIGLO">ROSARIO - Siglo</option>
		<option value="32 - MAR DEL PLATA">MDQ - Shopping</option>
		<option value="33 - MDQ-PASEO ALDREY">MDQ - Aldrey</option>
		<option value="38 - VILLA DEL PARQUE">Villa del Parque</option>
		<option value="40 - FLORES">Flores</option>
		<option value="48 - ALTO ROSARIO">ROSARIO - Alto Rosario</option>
		<option value="53 - CABALLITO">Caballito</option>
		<option value="54 - GALERIAS">Galerias</option>
		<option value="56 - GUEMES">MDQ - Guemes</option>
		<option value="60 - PORTAL">Portal</option>
		<option value="66 - DOT">DOT</option>
		<option value="70 - PALACE">ROSARIO - Palace</option>
		<option value="72 - GURRUCHAGA">Gurruchaga</option>
		<option value="75 - FLORES2">Flores 2</option>
		<option value="76 - SOLEIL">Soleil</option>
		
	</select >
	
	Elija Rubro
	<select name="rubro" >
		<option value="">TODOS</option>
		<option value="ACCESORIOS DE CUERO">ACCESORIOS DE CUERO</option>
		<option value="ACCESORIOS DE VINILICO">ACCESORIOS DE VINILICO</option>
		<option value="ACCESORIOS OUTLET">ACCESORIOS OUTLET</option>
		<option value="ALHAJEROS">ALHAJEROS</option>
		<option value="BILLETERAS DE CUERO">BILLETERAS DE CUERO</option>
		<option value="BILLETERAS DE VINILICO">BILLETERAS DE VINILICO</option>
		<option value="CALZADOS">CALZADOS</option>
		<option value="CALZADOS OUTLET">CALZADOS OUTLET</option>
		<option value="CAMPERAS">CAMPERAS</option>
		<option value="CAMPERAS OUTLET">CAMPERAS OUTLET</option>
		<option value="CARTERAS DE CUERO">CARTERAS DE CUERO</option>
		<option value="CARTERAS DE VINILICO">CARTERAS DE VINILICO</option>
		<option value="CHALINAS">CHALINAS</option>
		<option value="CINTOS DE CUERO">CINTOS DE CUERO</option>
		<option value="CINTOS DE VINILICO">CINTOS DE VINILICO</option>
		<option value="COSMETICA">COSMETICA</option>
		<option value="CUERO OUTLET">CUERO OUTLET</option>
		<option value="EQUIPAJES">EQUIPAJES</option>
		<option value="LENTES">LENTES</option>
		<option value="LLAVEROS">LLAVEROS</option>
		<option value="PACKAGING">PACKAGING</option>
		<option value="PARAGUAS">PARAGUAS</option>
		<option value="RELOJES">RELOJES</option>
		<option value="SINTETICOS OUTLET">SINTETICOS OUTLET</option>
		<option value="_DISCONTINUO CALZADO">DISCONTINUO CALZADO</option>
		<option value="_DISCONTINUO CUERO">DISCONTINUO CUERO</option>
		<option value="_DISCONTINUO VINILICO">DISCONTINUO VINILICO</option>
		<option value="_KITS">KITS</option>
	</select >
	
	Código
	<input type="text" name="codigo" autofocus></input>
	
	Descripción
	<input type="text" name="art" ></input>
		
	<input type="submit" value="Consultar" class="btn btn-primary btn-sm">
	</form>

<div class="container">

<?php

if(isset ($_GET['sucursal'])){
	
$suc = $_GET['sucursal'];

if($suc <> 'TODOS' ){

$dsn = $_GET['sucursal'];
$rubro = $_GET['rubro'];
$articulo = $_GET['art'];
$codigo = $_GET['codigo'];
$usuario = "Axoft";
$clave="Axoft";

$cid=odbc_connect($dsn, $usuario, $clave);

if (!$cid){
	echo 'Error en la conexion con '.$dsn;
	//continue;
}



$sql=
	"
	
	SELECT TOP 300 SUBSTRING('$dsn', 5,15) SUCURSAL, D.DESCRIP RUBRO, A.COD_ARTICU CODIGO, B.DESCRIPCIO DESCRIPCION, CAST(A.CANT_STOCK AS INT)STOCK 
	FROM STA19 A
	INNER JOIN STA11 B
		ON A.COD_ARTICU = B.COD_ARTICU
	INNER JOIN STA11ITC C
		ON A.COD_ARTICU = C.CODE
	INNER JOIN STA11FLD D
		ON C.IDFOLDER = D.IDFOLDER
	WHERE B.PERFIL <> 'N'
	AND A.CANT_STOCK <> 0
	AND D.DESCRIP LIKE '%$rubro%'
	AND B.DESCRIPCIO LIKE '%$articulo%'
	AND A.COD_ARTICU LIKE '%$codigo%'
	ORDER BY 1, 2

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));



echo '<h2 align="center">'.$_GET['sucursal'].'</h2>';



?>

<table class="table table-striped">

        <tr >

				<td align="center">SUCURSAL</td>
		
                <td align="center">RUBRO</td>

                <td align="center">CODIGO</td>

                <td align="center">DESCRIPCION</td>

                <td align="center">STOCK</td>

        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

                <td><?php echo $v['SUCURSAL'] ;?></a></td>
				
				<td><?php echo $v['RUBRO'] ;?></a></td>

                <td><?php echo $v['CODIGO'] ;?></td>

                <td align="center"><?php echo $v['DESCRIPCION'] ;?></td>

                <td align="center"><?php echo $v['STOCK'] ;?></td>

        </tr>

		
        <?php

        }

        ?>

		
        <tr >

                <!--<td colspan="13"><font face="arial" size="2">Total registros: <?php echo odbc_num_rows($result); ?></font></td>-->

        </tr>

		
</table>

<?php

}

else{
	
	
$sucursales = array(
					"1 - CENTRAL",
					"2 - NVOUNICENTER",
					"3 - ALTO PALERMO",
					"6 - AVELLANEDA",
					"7 - ABASTO",
					"8 - MORENO",
					"10 - SOLAR",
					"11 - TORTUGAS",
					"13 - CABILDO",
					"29 - SIGLO",
					"32 - MAR DEL PLATA",
					"33 - MDQ-PASEO ALDREY",
					//"38 - VILLA DEL PARQUE",
					"40 - FLORES",
					"48 - ALTO ROSARIO",
					"53 - CABALLITO",
					"54 - GALERIAS",
					"56 - GUEMES",
					"60 - PORTAL",
					"66 - DOT",
					"70 - PALACE",
					"72 - GURRUCHAGA",
					"75 - FLORES2",
					"76 - SOLEIL"
				);


?>


<table  class="table table-striped">

        <tr bgcolor="#efc789">

				<td align="center">SUCURSAL</td>
		
                <td align="center">RUBRO</td>

                <td align="center">CODIGO</td>

                <td align="center">DESCRIPCION</td>

                <td align="center">STOCK</td>

             

                

        </tr>


<?php

				
for($i=0;$i<count($sucursales);$i++){


		
$dsn = $sucursales[$i];
$rubro = $_GET['rubro'];
$articulo = $_GET['art'];
$codigo = $_GET['codigo'];
$usuario = "Axoft";
$clave="Axoft";

ini_set('max_execution_time', 300);
$cid=odbc_connect($dsn, $usuario, $clave);

if (!$cid){
	echo 'Error en la conexion con '.$dsn;
	continue;
}



$sql=
	"
	
	SELECT TOP 300 SUBSTRING('$dsn', 5,15) SUCURSAL, D.DESCRIP RUBRO, A.COD_ARTICU CODIGO, B.DESCRIPCIO DESCRIPCION, CAST(A.CANT_STOCK AS INT)STOCK 
	FROM STA19 A
	INNER JOIN STA11 B
		ON A.COD_ARTICU = B.COD_ARTICU
	INNER JOIN STA11ITC C
		ON A.COD_ARTICU = C.CODE
	INNER JOIN STA11FLD D
		ON C.IDFOLDER = D.IDFOLDER
	WHERE B.PERFIL <> 'N'
	AND A.CANT_STOCK <> 0
	AND D.DESCRIP LIKE '%$rubro%'
	AND B.DESCRIPCIO LIKE '%$articulo%'
	AND A.COD_ARTICU LIKE '%$codigo%'
	ORDER BY 1, 2

	";

ini_set('max_execution_time', 300);
$result=odbc_exec($cid,$sql) or die(exit("Error en odbc_exec"));





?>



		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr >

                <td><?php echo $v['SUCURSAL'] ;?></a></td>
				
				<td><?php echo $v['RUBRO'] ;?></a></td>

                <td><?php echo $v['CODIGO'] ;?></td>

                <td align="center"><?php echo $v['DESCRIPCION'] ;?></td>

                <td align="center"><?php echo $v['STOCK'] ;?></td>

               

        </tr>

		
        <?php

        }

        ?>

		
        <tr >

                <!--<td colspan="13"><font face="arial" size="2">Total registros: <?php echo odbc_num_rows($result); ?></font></td>-->

        </tr>

		


<?php

}
echo '</table>	';
}

}




?>
</div>

</html>
<?php
}
?>