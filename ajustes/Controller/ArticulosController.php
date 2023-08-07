<?php

require_once "../../ajustes/Class/Articulo.php";

$articulo = new Articulo();

$codArticulo = $_POST['term'];

$result = $articulo->traerMaestroArticulo($codArticulo);

$res = [];

foreach ($result as $key => $value) {
    $res[] = [
        'id' => $value['COD_ARTICU'],
        'text' => $value['COD_ARTICU'].'-'.$value['DESCRIPCIO']
    ];
}

header('Content-Type: application/json');
echo json_encode($res);