<?php

// require_once "../class/Recodificacion.php";
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


    $fileName = $nComp;

    if ($gestor = opendir("../assets/uploads")) {
    
        while (($archivo = readdir($gestor)) !== false) {
      
            if ($archivo != "." && $archivo != "..") {

                if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
             
                  
                    unlink("../assets/uploads/".pathinfo($archivo, PATHINFO_FILENAME).".jpg");
                 
                } 
 
             
            }
        }

        closedir($gestor);
    }

    echo json_encode(true);


}

function contarFotosEnCarpeta() {
    
    $nComp = (isset($_POST['nComp'])) ? $_POST['nComp'] : "";
    
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
            if ($gestor = opendir("../assets/uploads")) {
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
        if ($gestor = opendir("../assets/uploads")) {
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
    $data = $_POST['listaDeComprobantes'];
    foreach ($data as $key => $nComp) {
        var_dump($nComp);
        

    }

}





?>