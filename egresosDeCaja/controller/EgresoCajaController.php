<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../class/Egreso.php";

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    case 'guardar':
        guardar();
        break;
    case 'eliminarArchivo':
        eliminarArchivo();
        break;
    case 'contarImagenes':
        contarFotosEnCarpeta();
        break;
    case 'contarImagenesMob':
        contarFotosEnCarpetaMob();
        break;
    default:
        echo json_encode(['error' => 'Acción no reconocida']);
        break;
}

function eliminarArchivo() {
    $nComp = $_POST['nComp'];
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = $root.'/Imagenes/egresosCaja/';
    $fileName = $nComp;

    if ($gestor = opendir($targetDir)) {
        while (($archivo = readdir($gestor)) !== false) {
            if ($archivo != "." && $archivo != "..") {
                if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
                    unlink($targetDir.pathinfo($archivo, PATHINFO_FILENAME).".jpg");
                }
            }
        }
        closedir($gestor);
    }

    echo json_encode(true);
}

function contarFotosEnCarpeta() {
    $nComp = isset($_POST['nComp']) ? $_POST['nComp'] : "";
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = $root.'/Imagenes/egresosCaja/';
    
    $datosDeLosArchivos = [
        'cantidad' => 0,
        'nombre' => []
    ];

    if ($gestor = opendir($targetDir)) {
        while (($archivo = readdir($gestor)) !== false) {
            if ($archivo != "." && $archivo != "..") {
                if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $nComp) !== false) {
                    $datosDeLosArchivos['cantidad']++;
                    $datosDeLosArchivos['nombre'][] = pathinfo($archivo, PATHINFO_FILENAME);
                }
            }
        }
        closedir($gestor);
    }

    header('Content-Type: application/json');
    echo json_encode($datosDeLosArchivos);
}

function contarFotosEnCarpetaMob() {
    $nComp = isset($_POST['nComp']) ? $_POST['nComp'] : "";
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = $root . '/Imagenes/egresosCaja/';
    
    $datosDeLosArchivos = [
        'cantidad' => 0,
        'nombre' => []
    ];

    if ($gestor = opendir($targetDir)) {
        while (($archivo = readdir($gestor)) !== false) {
            if ($archivo != "." && $archivo != "..") {
                if (strpos($archivo, $nComp) === 0) {
                    $datosDeLosArchivos['cantidad']++;
                    $datosDeLosArchivos['nombre'][] = $archivo;
                }
            }
        }
        closedir($gestor);
    }

    header('Content-Type: application/json');
    echo json_encode($datosDeLosArchivos);
    exit;
}

function guardar() {
    $egreso = new Egreso();
    $data = $_POST['listaDeComprobantes'];
    $nroSucursal = $_POST['nroSucursal'];
    $arrayNcomp = [];

    foreach ($data as $nComp) {
        if(!in_array($nComp, $arrayNcomp)){
            $arrayNcomp[] = $nComp;
        }
    }

    foreach ($arrayNcomp as $value) {
        switch (strlen($value)) {
            case '21':
                $nComp = substr($value, 0, 15);
                $nComp = substr($nComp, 0, -1);
                $codCta = substr($value, 15);
                break;
            case '22':
                $nComp = substr($value, 0, 16);
                $nComp = substr($nComp, 0, -2);
                $codCta = substr($value, 16);
                break;
            case '23':
                $nComp = substr($value, 0, 17);
                $nComp = substr($nComp, 0, -3);
                $codCta = substr($value, 17);
                break;
        }

        $egreso->existeFoto($nComp, $codCta, $nroSucursal);
    }
}
?>