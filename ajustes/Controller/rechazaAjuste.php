<?php
if (!isset($_SESSION['username'])) {
    header("Location:../index.php");
} else {
    include 'consultas.php';

    if (isset($_POST['localSeleccionado'])) {
        $_SESSION['dsn'] = $_POST['localSeleccionado'];
    }
}
$matriz = $_POST['matriz'];

// print_r($matriz);
session_start();


$dsn = $_SESSION['dsn'];
$user = 'sa';
$pass = 'Axoft1988';

foreach ($matriz as $id) {
try {
    $cid = odbc_connect($dsn, $user, $pass);
    
    $sql = "UPDATE SOF_CONFIRMA SET RECHAZADO = 1 WHERE ID_STA20 = $id";
    odbc_exec($cid, $sql);
} catch (Exception $e) {
    echo 'Excepción capturada: ',  $e->getMessage(), "\n";
    }
}


