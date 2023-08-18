<?php

$accion = $_GET['accion'];

switch ($accion) {

    case 'traerArticulos':
        traerArticulos();

        break;

    
    default:
        # code...
        break;
}

function traerArticulos () {

    require_once "../class/StockPrecio.php";

    $articulo = new StockPrecio();
    
    $codArticulo = $_POST['codArticulo'];

    $result = $articulo->traerMaestroArticulo($codArticulo);
     

    echo json_encode($result);


}

?>