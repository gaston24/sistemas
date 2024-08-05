<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../class/Egreso.php";

$accion = isset($_GET['accion']) ? $_GET['accion'] : '';

switch ($accion) {
    case 'guardar':
        guardar();
        break;
    case 'guardarMob':
        guardarMob();
        break;
    case 'eliminarArchivo':
        eliminarArchivo();
        break;
    case 'eliminarArchivoMob':
        eliminarArchivoMob();
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

function eliminarArchivo () {

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
    
    $nComp = (isset($_POST['nComp'])) ? $_POST['nComp'] : "";
    $nroSucursal = (isset($_POST['nroSucursal'])) ? $_POST['nroSucursal'] : "";

    $root = $_SERVER["DOCUMENT_ROOT"];

    $targetDir = $root.'/Imagenes/egresosCaja/';
    
    if(isset($_POST['arrayNcomp'])){

        $arrayArticulos = $_POST['arrayNcomp'];

    }
 

    if(isset($arrayArticulos)){
        
        $contadorFotos = 0;
        $datosDeLosArchivos = [];
        $datosDeLosArchivos['cantidad'] = 0;
        foreach ($arrayArticulos as $key => $codigo) {
            $fileName = $codigo;
            // Abre el directorio
            if ($gestor = opendir($targetDir)) {
                // Recorre los archivos en el directorio
                while (($archivo = readdir($gestor)) !== false) {
                    // Ignora las carpetas "." y ".."
                    if ($archivo != "." && $archivo != "..") {
        
                        if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
                            // $contadorFotos++;
                            $datosDeLosArchivos['cantidad'] ++;
        
                            $datosDeLosArchivos['nombre'][] = $codigo;

                            // array_push($nombreArchivo, pathinfo($archivo, PATHINFO_FILENAME));
                        } 
         
                     
                    }
                }
        
                // Cierra el directorio
                closedir($gestor);
            }

        }

    }else{

    
        $fileName = $nComp;
        $contadorFotos = 0;
        $datosDeLosArchivos = [];
        $datosDeLosArchivos['cantidad'] = 0;
        // Abre el directorio
        if ($gestor = opendir($targetDir)) {
            // Recorre los archivos en el directorio
            while (($archivo = readdir($gestor)) !== false) {
                // Ignora las carpetas "." y ".."
                if ($archivo != "." && $archivo != "..") {

                    if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
                        // $contadorFotos++;
                        $datosDeLosArchivos['cantidad'] ++;

                        $datosDeLosArchivos['nombre'][] = pathinfo($archivo, PATHINFO_FILENAME);

                        // array_push($nombreArchivo, pathinfo($archivo, PATHINFO_FILENAME));
                    } 
    
                
                }
            }

            // Cierra el directorio
            closedir($gestor);
        }
    }

    echo json_encode($datosDeLosArchivos);
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

function eliminarArchivoMob() {
    // Verificar que la solicitud se haga mediante POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Método de solicitud inválido']);
        return;
    }

    // Verificar si los parámetros esperados están presentes
    if (!isset($_POST['nComp']) || !isset($_POST['index'])) {
        echo json_encode(['success' => false, 'message' => 'Parámetros faltantes']);
        return;
    }

    $nComp = $_POST['nComp'];
    $index = intval($_POST['index']);
    $root = $_SERVER["DOCUMENT_ROOT"];
    $targetDir = $root.'/Imagenes/egresosCaja/';
    $fileName = $nComp;

    $archivosEncontrados = [];

    if ($gestor = opendir($targetDir)) {
        while (($archivo = readdir($gestor)) !== false) {
            if ($archivo != "." && $archivo != "..") {
                if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
                    $archivosEncontrados[] = $archivo;
                }
            }
        }
        closedir($gestor);
    }

    // Ordenar los archivos para asegurar que el índice corresponda al orden de las imágenes
    sort($archivosEncontrados);

    if ($index >= 0 && $index < count($archivosEncontrados)) {
        $archivoAEliminar = $archivosEncontrados[$index];
        if (unlink($targetDir . $archivoAEliminar)) {
            echo json_encode(['success' => true, 'message' => 'Archivo eliminado correctamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se pudo eliminar el archivo']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Índice de archivo inválido']);
    }
}

function guardarMob() {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => ''];

    if (isset($_POST['nComp']) && isset($_POST['codCta']) && isset($_POST['nroSucursal'])) {
        $egreso = new Egreso();
        $result = $egreso->existeFoto($_POST['nComp'], $_POST['codCta'], $_POST['nroSucursal']);

        if ($result) {
            $response['success'] = true;
            $response['message'] = 'Gasto guardado correctamente';
        } else {
            $response['message'] = 'No se pudo guardar el gasto';
        }
    } else {
        $response['message'] = 'Faltan datos requeridos';
    }

    echo json_encode($response);
    exit;
}

?>