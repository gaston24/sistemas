<?php 
include_once __DIR__."/../class/remitoEquis.php";

$remitoEquis = new RemitoEquis();

$cobros = $_POST['cobros'];

foreach ($cobros as $cobro) {
    $remitoEquis->rendirCobro($cobro);

}

return true;

?>