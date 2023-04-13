<?php
include_once __DIR__."/../class/remitoEquis.php";

$codClient = $_POST['codClient'];

$remitoEquis = new RemitoEquis();

$result = $remitoEquis->traerCheque($codClient);

echo json_encode($result);


 


?>