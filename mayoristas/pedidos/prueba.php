<?php

$dsn = "1 - CENTRAL";
$user = "Axoft";
$pass = "Axoft";

$cid = odbc_connect($dsn, $user, $pass);

$sqlClientes="
SET DATEFORMAT YMD

SELECT TOP 10 COD_CLIENT FROM GVA14 WHERE COD_VENDED = 'Z4'
";

$resultClientes=odbc_exec($cid,$sqlClientes)or die(exit("Error en odbc_exec"));

//$clientes = array();
/*
while($clientes=odbc_fetch_array($resultClientes)){
	$clientes['clientes'];
}
*/

//$nConfig = odbc_num_rows($resultClientes);

//if ($nConfig > 0)  
//{  
//	for ($i=0; $i<$nConfig; $i++)  
//	{  
	
	while($v = odbc_fetch_array($resultClientes)){
		$clientes[] = $v['COD_CLIENT'];
	}  
//	}  
//} 

for($i = 0; $i < count($clientes); $i++)
				{
					echo $clientes[$i].'</br>';
				}

?>


