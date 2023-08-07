<?php 
require_once '../Class/Recodificacion.php';
$accion = $_GET['accion'];


switch ($accion) {
    case 'listarDetalle':
        listarDetalle();
        break;
    
    case 'traerCodigoRecodificacion':
        traerCodigoRecodificacion();
        break;
    
    case 'traerSucursales':
        traerSucursales();
        break;

    case 'validarArticulos':
        validarArticulos();
        break;

    case 'calcularSaldoPartidas':
        calcularSaldoPartidas();
        break;
    
    default:
        # code...
        break;
}

function listarDetalle () {

    $nroTarea = $_POST['nroDeTarea'];
    $recodificacion = new Recodificacion();
    $result = $recodificacion->listarDetalle($nroTarea);
    echo json_encode($result);
}

function traerCodigoRecodificacion () {

    $valor = $_POST['valor'];
    $codArticulo = $_POST['codArticulo'];
    $recodificacion = new Recodificacion();
    $result = $recodificacion->traerCodigoRecodificacion($valor, $codArticulo);
    echo json_encode($result);
}

function traerSucursales () {
    
        $recodificacion = new Recodificacion();
        $result = $recodificacion->traerSucursales();

        echo(json_encode($result));

}

function validarArticulos () {

        $articulo = json_decode($_POST['articulo']);
        $recodificacion = new Recodificacion();
        $result = [];

        foreach ($articulo as $key => $value) {
            $result[$key]["articulo"]   = $value;    
            $result[$key]["respuesta"]   = $recodificacion->validarArticulo($value);    
      
        }

        echo json_encode($result);

}

function calcularSaldoPartidas () {

    $articulos = json_decode($_POST['articulo']);

    $filteredArray = array_filter($articulos, "filtrarElementos");
    $result = [];
    foreach ($filteredArray as  $articulo) {
        $recodificacion = new Recodificacion();
        $result[$articulo] = $recodificacion->calcularSaldoPartidas($articulo);
      
    }

    echo json_encode($result);

}

function filtrarElementos($elemento) {
    return substr($elemento, 0, 1) !== "O";
}


?>