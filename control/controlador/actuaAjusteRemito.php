
<?php 

$ncomp = $_POST['ncomp'];
$ajuste = $_POST['ajuste'];


include_once __DIR__.'/../../class/remito.php';

$remito = new Remito();
$remito->ajusteRemitoNumero($ajuste, $ncomp);

echo json_encode('Ajuste modificado');
