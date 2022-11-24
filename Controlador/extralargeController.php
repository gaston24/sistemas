<?php

include __DIR__.'\..\class\extralarge.php';
$xl = new Extralarge();

if(isset($_GET['action'])){
    
    if($_GET['action'] == 'limitePedidos'){
        $pedidos = $xl->limitePedidos($_GET['codClient']);
        echo json_encode($pedidos);
    }

}