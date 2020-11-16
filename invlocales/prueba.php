<?php

for($i=0;$i<count($_POST['id']);$i++){
	
	
	if(!isset($_POST['ajusta'][$i])){
		$ajusta = 0;
	}else{
		$ajusta = $_POST['ajusta'][$i];
	}
	
	if($ajusta == 1){
	
		$id = $_POST['id'][$i];
		$fecha = $_POST['fecha'][$i];
		$codArt = $_POST['codArticu'][$i];
		$cant = $_POST['cant'][$i];
		$cantStock = $_POST['cantStock'][$i];
		$dif = $_POST['dif'][$i];
		
		
		if(!isset($_POST['observac'][$i])){
			$observac = 'nada';
		}else{
			$observac = $_POST['observac'][$i];
		}
		
		echo $id.' - '.$fecha.' - '.$codArt.' - '.$cant.' - '.$cantStock.' - '.$dif.' - '.$ajusta.' - '.$observac.'</br>';
	}
}


?>