<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];


$dsn = $_SESSION['sucursal'];
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);





$fecha = date("Y") . "/" . date("m") . "/" . date("d");
$hora = (date("H")-5).date("i").date("s");





for($i=0;$i<count($_POST['cantAjuste']);$i++){
	
	$ajustar = $_POST['ajustar'][$i];
	$renglon = $i+1;
	
	if($ajustar==1){
		
		$codArticu = $_POST['codArticu'][$i];
		$audita = $_POST['audita'][$i];
		$cantAjuste = $_POST['cantAjuste'][$i];
		$id = $_POST['id'][$i];
		

		while($v=odbc_fetch_array($resultBusca)){
				$codConsulta = $v['COD_ARTICU'];
				
				
			//echo $codConsulta;
			if(odbc_num_rows($resultBusca)==1){
				
			//SUMA CANTIDAD
				echo 'suma'.$codConsulta.' '.$cantAjuste;

			}else{
				
				echo 'agrega'.$codConsulta.' '.$cantAjuste;

			}

		}

	
		
		
		if($cantAjuste < 0){
			$cantAjuste = $cantAjuste *-1;
			$tcomp = 'S';
		}else{
			$tcomp = 'E';
		}
		
		//echo $codArticu.' - '.$audita.' - '.$ajustar.' - '.$cantAjuste.'</br>';
		
		//DETALLE SALIDA
		echo 'detalle salida'.$cantAjuste.$codArticu.$renglon.$proxInterno.$tcomp;
				
			
		
	}
	
	
	
}


}

//echo "<script>setTimeout(function () {window.location.href= 'index.php';},1);</script>";

?>