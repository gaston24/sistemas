<?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";
$suc = $_SESSION['numsuc'];


switch ($_GET['tipo']) {
    case 1:
        $_SESSION['tipo_pedido'] = 'GENERAL';
        break;
    case 2:
        $_SESSION['tipo_pedido'] = 'ACCESORIOS';
        break;
    case 3:
        $_SESSION['tipo_pedido'] = 'OUTLET';
        break;
}

$_SESSION['depo'] = '01';

$codClient = $_SESSION['username'];
$tipo_cli = $_SESSION['tipo'];

include 'Controlador/tipoPedido.php';

switch ($_GET['tipo']) {
    case 1:
        $sql = $sql1;
        break;
    case 2:
        $sql = $sql2;
        break;
    case 3:
        $sql = $sql3;
        break;
}

$cid = odbc_connect($dsn, $user, $pass);

ini_set('max_execution_time', 300);
$result = odbc_exec($cid, $sql) or die(exit("Error en odbc_exec"));