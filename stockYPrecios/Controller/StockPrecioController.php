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
    $usuarioUy = $_POST['usuarioUy'];

    $result = $articulo->traerMaestroArticulo($codArticulo, $usuarioUy);
     

    echo json_encode($result);


}

?>