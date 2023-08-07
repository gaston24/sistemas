<?php
require '../MODEL/conexion.php';
$conexion=new Conexion();

$inputJSON = file_get_contents('php://input');
$datosLocal=json_decode($inputJSON, TRUE);

$local=$datosLocal['local'];
$cuota=$datosLocal['cuota'];
$estado=$datosLocal['estado'];//true->agregar cuota, false->eliminar cuota
$tarjeta=$datosLocal['tarjeta'];


$conexion->update($local,$tarjeta,$cuota,$estado);



?>