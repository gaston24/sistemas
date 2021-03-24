<?php

try {

    $servidor = 'servidor';
    $conexion = array( "Database"=>"LAKER_SA", "UID"=>"sa", "PWD"=>"Axoft1988");
    $cid = sqlsrv_connect($servidor, $conexion);
    
} catch (PDOException $e){
        echo $e->getMessage();
}

