<?php

try {

    $servidor_locales = 'lakerbis';
    $conexion_locales = array( "Database"=>"LOCALES_LAKERS", "UID"=>"sa", "PWD"=>"Axoft");
    $cid_locales = sqlsrv_connect($servidor_locales, $conexion_locales);
    
} catch (PDOException $e){
        echo $e->getMessage();
}

