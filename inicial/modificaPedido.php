<?php
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{


$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$codArt = $_SESSION['codArt'];
$contenedor = $_SESSION['contenedor'];

$cid = odbc_connect($dsn, $user, $pass);

$codArt = $_SESSION['codArt'];



foreach($_POST['pedido'] as $valor => $index){
	
	if($_POST['cant'][$valor]<>$_POST['modificar'][$valor]){
	
		$sql="
		SELECT COD_ARTICU, COD_INSUMO, CANTIDAD FROM STA03 WHERE COD_ARTICU = '$codArt'
		";

		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
		
		while($v=odbc_fetch_array($result)){
			echo $index.' '.$codArt.' '.$_POST['cant'][$valor].' '.$_POST['modificar'][$valor].$v['COD_INSUMO'].' '.$v['CANTIDAD'].' CANTIDAD --> '.$_POST['modificar'][$valor]*$v['CANTIDAD'].'</br>';
			
			$cantModificar = $_POST['modificar'][$valor];
			
			$sqlModifica="
			UPDATE GVA03 SET 
			CANT_A_DES = $cantModificar, 
			CANT_A_FAC = $cantModificar, 
			CANT_PEDID = $cantModificar, 
			CANT_PEN_D = $cantModificar, 
			CANT_PEN_F = $cantModificar
			WHERE NRO_PEDIDO = '$index' AND TALON_PED = 96
			AND 
			(
			COD_ARTICU IN
			(SELECT COD_INSUMO FROM STA03 WHERE COD_ARTICU = '$codArt')
			OR COD_ARTICU = '$codArt'
			)
			";

			odbc_exec($cid,$sqlModifica)or die(exit("Error en odbc_exec"));
						
		}
		
		$cantModificar = $_POST['modificar'][$valor];
		
		$sqlModifica2="
		UPDATE SOF_DISTRIBUCION_INICIAL_RELACION SET CANT = $cantModificar WHERE COD_ARTICU = '$codArt'
		";

		odbc_exec($cid,$sqlModifica2)or die(exit("Error en odbc_exec"));
	
	}
	
}


}
?>

<script>setTimeout(function () {window.location.href= 'detallePedidos.php?codArt=<?php echo $codArt ?>&contenedor=<?php echo $contenedor ?>';},1000);</script>