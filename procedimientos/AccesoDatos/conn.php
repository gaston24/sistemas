<?php

try {

    $servidor = 'SERVIDOR';
    $conexion = array( "Database"=>"LAKER_SA", "UID"=>"sa", "PWD"=>"Axoft1988", "CharacterSet" => "UTF-8");
    $cid = sqlsrv_connect($servidor, $conexion);
    
} catch (PDOException $e){
        echo $e->getMessage();
}

