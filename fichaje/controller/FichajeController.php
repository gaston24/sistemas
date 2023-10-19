<?php 
require_once '../class/fichaje.php';

$accion = $_GET['action'];
$fichaje = new Fichaje;


switch ($accion) {
    case 'buscarPorCampo':
        buscarPorCampo($fichaje);
        break;
    
    case 'login':
        login($fichaje);
        break;
    
    case 'verificarFichaje':
        verificarFichaje($fichaje);
        break;

    case 'cerrarTurno':
        cerrarTurno($fichaje);
        break;
    
    default:
        # code...
        break;
}



function buscarPorCampo ($fichaje) {
    
    $campo = $_POST['campo'];

    $result = $fichaje->buscarPorCampo($campo);

    echo $result;

}

function login ($fichaje){

   $numeroLegajo = $_POST['numeroLegajo'];

   $password = $_POST['password'];

    $result = $fichaje->login($numeroLegajo, $password);


    echo $result;

}

function verificarFichaje($fichaje){

    $numeroLegajo = $_POST['numeroLegajo'];
    $sucursal = $_POST['sucursal'];
 
    $result = $fichaje->verificarFichaje($numeroLegajo);
 
    if($result == 0){
        $fichaje->fichar($numeroLegajo, $sucursal);

        echo true;
    }else{
        echo false;
    }
    
}


function cerrarTurno ($fichaje) {

    $numeroLegajo = $_POST['numeroLegajo'];
 
    $result = $fichaje->cerrarTurno($numeroLegajo);

    return true ;
    
}