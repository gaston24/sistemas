<?php
include_once __DIR__."/../class/remitoEquis.php";

$remitos = $_POST['remitos'];
$cobroEfectivo = $_POST['cobroEfectivo'] > 0 ? $_POST['cobroEfectivo'] : 0;
$cobroCheque = $_POST['cobroCheque'] > 0 ? $_POST['cobroCheque'] : 0;
$saldoCobrar = $_POST['saldoCobrar'] > 0 ? $_POST['saldoCobrar'] : 0;
$montoACobrar = $_POST['montoACobrar'];
$codClient = $_POST['codClient'];
$postCheques = $_POST['idCheque'];
$nombreCliente = $_POST['nombreCliente'];

$idCheques = explode(",", $postCheques);

$importeTotal = $cobroEfectivo + $cobroCheque; 
$montoTotal = $montoACobrar - $saldoCobrar;
$remitoEquis = new RemitoEquis();

$remitosParceados = "";


$idCobro = $remitoEquis->guardarCobro($codClient, $cobroEfectivo, $cobroCheque, $importeTotal,$nombreCliente);

foreach ($idCheques as $value) {

    $remitoEquis->asignarCobroPorCheque($idCobro,$value);
    # code...
}


foreach ($remitos as $num => $remito) {

    if($num == 0){
        $remitosParceados = $remitosParceados."'$remito'";
    } 
    
    $remitosParceados = $remitosParceados.",'$remito'";
}



$remitosDetalle = $remitoEquis->traerRemito($remitosParceados);

foreach ($remitosDetalle as $detalle) {
    
    if($montoTotal >= $detalle['IMPORTE_TO'] ){
       

            $remitoEquis->cambiarEstado($detalle['N_COMP']);

            $remitoEquis->guardarCobroRemito($idCobro, $detalle['N_COMP']);

            $montoTotal = $montoTotal - $detalle['IMPORTE_TO'];
            
        }
        
        
    }
    try {

    return true;

} catch (\Throwable $th) {
    
    echo $th;
}



 


?>