<?php

function comp_stock($a, $b, $c){
	
	$dsn = "1 - CENTRAL";
    $user = "sa";
    $pass = "Axoft1988";

    $cid = odbc_connect($dsn, $user, $pass);

    if (!$cid){exit("<strong>Ha ocurrido un error tratando de conectarse con el origen de datos.</strong>");}

	$sqlCompStock = "  
    EXEC SJ_COMP_STOCK $a, '$b', '$c'
	";

	ini_set('max_execution_time', 300);
	odbc_exec($cid,$sqlCompStock)or die(exit("Error en odbc_exec"));
}

?>