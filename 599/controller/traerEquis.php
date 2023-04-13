<?php
include_once __DIR__."/../class/remitoEquis.php";

function traerTodos ($campo = null){

    $remitoEquis = new RemitoEquis();
    $result = $remitoEquis->traerTodos($campo);
    return $result;

}    

function traerDetalle($codCliente){
    
    $remitoEquis = new RemitoEquis();
    $result = $remitoEquis->traerDetalle($codCliente);
    return $result;

}



?>