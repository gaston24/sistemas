
<?php 

$ncomp = $_POST['ncomp'];
$status = $_POST['status'];


include_once __DIR__.'/../../class/remito.php';

$remito = new Remito();
$remito->ajusteRemitoStatusDirecto($status, $ncomp);

echo json_encode('Ajuste modificado');