<?php
include_once __DIR__."/../class/remitoEquis.php";

$remitos = $_POST['remitos'];

$remitoEquis = new RemitoEquis();

try {
    foreach ($remitos as $remito) {
        $remitoEquis->cambiarEstado($remito);
    }
    return true;

} catch (\Throwable $th) {
    
    echo $th;
}



 


?>