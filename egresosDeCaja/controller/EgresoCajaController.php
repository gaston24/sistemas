<?php

require_once "../class/Egreso.php";

$accion = $_GET['accion'];

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

    default:
        # code...
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

function guardar ()  {

    $egreso = new Egreso();
    $data = $_POST['listaDeComprobantes'];
    $nroSucursal = $_POST['nroSucursal'];
    $arrayNcomp = [];

    foreach ($data as $key => $nComp) {

        if(!in_array($nComp, $arrayNcomp)){
            $arrayNcomp[] = $nComp;
        }
        

    }

    foreach ($arrayNcomp as $key => $value) {
        
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