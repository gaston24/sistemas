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

    case 'traerReporteAsistencias':
        traerReporteDeAsistencias($fichaje);
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

   $habilitado = $fichaje->comprobarHabilitado($numeroLegajo);


   if($habilitado == true){

       $result = $fichaje->login($numeroLegajo, $password);
    
    
       echo $result;

   }else{

       echo '3';
   }


}

function verificarFichaje($fichaje){

    $numeroLegajo = $_POST['numeroLegajo'];
    $sucursal = $_POST['sucursal'];
 
    $result = $fichaje->verificarFichaje($numeroLegajo);
 
    if($result == 0){

        $result = $fichaje->fichar($numeroLegajo, $sucursal);
        echo json_encode($result);
        
    }else{
        echo false;
    }
    
}


function cerrarTurno ($fichaje) {

    $numeroLegajo = $_POST['numeroLegajo'];
 
    $result = $fichaje->cerrarTurno($numeroLegajo);

    return true ;
    
}

function traerReporteDeAsistencias ($fichaje) {

    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $usuario = $_POST['usuario'];
    $sucursal = $_POST['sucursal'];
 
    $result = $fichaje->traerReporteDeAsistencias($desde, $hasta, $usuario, $sucursal);

    echo json_encode($result) ;
    
}