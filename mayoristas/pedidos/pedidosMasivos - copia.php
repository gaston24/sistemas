<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}

if(isset ($_GET['desde'])){
	$desde = $_GET['desde'];
	$hasta = $_GET['hasta'];	
}else{
	$desde = date('Y-m').'-'.strright(('0'.((date('d'))-15)),2);
	$hasta = date('Y-m').'-'.strright(('0'.((date('d')))),2);
}	

if(isset($_GET['orden'])){
	$orden = $_GET['orden'];
}else{
	$orden = '';
}

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Pendientes E-Commerce</title>
<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</head>
<body>




</br></br>
<div class="container-fluid">
<?php

$dsn = "1 - CENTRAL";
$user = "Axoft";
$pass = "Axoft";

$cid = odbc_connect($dsn, $user, $pass);











$sql="
SET DATEFORMAT YMD

SELECT A.*, B.DESCRIPCIO FROM SOF_PRUEBA A
INNER JOIN STA11 B
ON A.COD_ARTICU = B.COD_ARTICU
";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));







$clientes = array(
	'MAARG',
	'MAAIBA',
	'MABRA2',
	'MAANN',
	'MACMA3',
	'MABAAN',
	'MABAJU',
	'MACMA3',
	'MABAAN',
	'MABAJU'
);



?>





<table class="table table-striped">

        <tr style="font-size:smaller">

				<td align="left" ><strong>CONT</strong></td>
		
				<td></td>
		
                <td align="left" ><strong>CODIGO</strong></td>
				
				<td></td>

				<td align="left"><strong>DESCRIPCION</strong></td>
				
				<?php	

				for($i = 0; $i < count($clientes); $i++)
				{
					echo '<td align="left" size="1"><strong>'.$clientes[$i].'</strong></td>';
				}
				
				?>
				
        </tr>

		
        <?php

       
		while($v=odbc_fetch_array($result)){

        ?>

		
        <tr style="font-size:smaller">

                <td><?php echo $v['CONTENEDOR'] ;?></td>
				
				<td><input value="<?php echo $v['CONTENEDOR'] ;?>" hidden></td>
				
				<td><?php echo $v['COD_ARTICU'] ;?></td>
				
				<td><input value="<?php echo $v['COD_ARTICU'] ;?>" hidden></td>
				
				<td><?php echo $v['DESCRIPCIO'] ;?></td>
				
				<?php for($i = 0; $i < count($clientes); $i++)
				{
					echo "<td><input size='1' value='".$v[$clientes[$i]]."'></td>";
				}
				?>

		</tr>

		
        <?php

        }

        ?>
		
        		
</table>


<?php
//}
?>

</div>
<script>
window.onload = function() {
  var input = document.getElementById("caja").focus();
}
</script>
</body>
</html>

<?php
}
?>
