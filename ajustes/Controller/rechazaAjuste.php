<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location:../index.php");
}

require_once '../class/Ajuste.php';
require_once '../../class/Conexion.php';

$ajuste = new Ajuste();

$matriz = $_POST['matriz'];
foreach ($matriz as $id) {
    
    $ajuste->rechazarAjuste($id);

}


