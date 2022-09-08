<?php

     $servidor='SERVIDOR';
     $database='LAKER_SA';
     $user='sa';
     $pass='Axoft1988';
     $cod='UTF-8';

 

try {
    $conexion = array( "Database"=>$database, "UID"=>$user, "PWD"=>$pass, "CharacterSet" => $cod);
    $cid = sqlsrv_connect($servidor, $conexion);
    return $cid;
    
} catch (PDOException $e){
        print_r("Error connection:" . $e->getMessage());
}



?>