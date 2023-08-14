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


    if($codArticulo != null ){


        $result = $articulo->traerMaestroArticulo($codArticulo);
     
    }else{

        $result = $articulo->traerMaestroArticulo();
    }


    echo json_encode($result);


}

?>