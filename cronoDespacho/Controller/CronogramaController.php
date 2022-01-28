<?php

require_once '../Class/cronograma.php';


$codClient = $_POST['codClient'];
$tipoCronograma = $_POST['tipoCronograma'];
$nuevoCrono = new Cronograma();


if($_GET['function'] == 'actuaDia' ){
    $nameDia = $_POST['nameDia'];
    $nuevoCrono->updateDia($codClient, $nameDia, $tipoCronograma);
}

if($_GET['function'] == 'actuaPrioridad' ){
    $prioridad = $_POST['prioridad'];
    $nuevoCrono->updatePrioridad($codClient, $prioridad, $tipoCronograma);
}
