<?php

require_once "../class/Recodificacion.php";
$accion = $_GET['accion'];

switch ($accion) {

    case 'traerArticulos':
        traerArticulos();

        break;

    case 'eliminarArchivo':
        eliminarArchivo();

        break;
    
    case 'contarImagenes':
        contarFotosEnCarpeta();

        break;

    case 'solicitar':
        solicitar();

        break;

    case 'borrador':
        borrador();

        break;

    case 'autorizar':
        autorizar();

        break;

    case 'enviar':
        enviar();

        break;

    case 'traerCodigoRecodificacion':
        traerCodigoRecodificacion();

        break;
    
    default:
        # code...
        break;
}

function traerArticulos () {
    require_once "../../ajustes/Class/Articulo.php";
    $articulo = new Articulo();

    $result = $articulo->traerMaestroArticulo();


    echo json_encode($result);

}

function eliminarArchivo () {

    $codArticulo = $_POST['codArticulo'];

    $numSolicitud = $_POST['numSolicitud'];

    $fileName = $numSolicitud.$codArticulo;

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
    
    $codArticulo = $_POST['codArticulo'];
    $numSolicitud = $_POST['numSolicitud'];
    $fileName = $numSolicitud.$codArticulo;
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

    echo json_encode($datosDeLosArchivos);
}

function solicitar () {

    $nroSucursal = $_POST['nroSucursal'];
    $fecha_objeto = DateTime::createFromFormat('Y-d-m', $_POST['fecha']);
    $fecha = $fecha_objeto->format('Y-m-d');
    $usuario = $_POST['usuario'];
    $estado = $_POST['estado'];
    $numSolicitud = $_POST['numSolicitud'];
    $dataArticulos = $_POST['dataArticulos'];
    $valores = "";

    foreach ($dataArticulos as $key => $articulo) {
        $valores .= "('$numSolicitud', '$articulo[codArticulo]', '$articulo[descripcion]', '$articulo[precio]', '$articulo[cantidad]', '$articulo[descFalla]'),";
    }

    $valores = substr_replace($valores, ";", -1, 1);

    $Recodificacion = new Recodificacion();
    $encabezado = $Recodificacion->insertarEncabezado($numSolicitud, $nroSucursal, $fecha, $usuario, 1 );

    if($encabezado == true){
        $Recodificacion->insertarDetalle($valores, $encabezado);
    }

    return true;

}

function borrador () {
   
    $nroSucursal = $_POST['nroSucursal'];

    $fecha_objeto = DateTime::createFromFormat('Y-d-m', $_POST['fecha']);
    $fecha = $fecha_objeto->format('Y-m-d');

    $usuario = $_POST['usuario'];
    $estado = $_POST['estado'];
    $numSolicitud = $_POST['numSolicitud'];
    $dataArticulos = $_POST['dataArticulos'];
    $valores = "";

    
    
    $Recodificacion = new Recodificacion();

    $encabezado = $Recodificacion->insertarEncabezado($numSolicitud, $nroSucursal, $fecha, $usuario, 4 );
    
    foreach ($dataArticulos as $key => $articulo) {
        $valores .= "('$encabezado', '$articulo[codArticulo]', '$articulo[descripcion]', '$articulo[precio]', '$articulo[cantidad]', '$articulo[descFalla]'),";
    }
    $valores = substr_replace($valores, ";", -1, 1);
    
    if($encabezado == true){
        $Recodificacion->insertarDetalle($valores);
    }

    return true;

}

function traerCodigoRecodificacion () {
    
    $valor = $_POST['valor'];
    $codArticulo = $_POST['codArticulo'];
    $recodificacion = new Recodificacion();
    $result = $recodificacion->traerCodigoRecodificacion($valor, $codArticulo);
    echo json_encode($result);

}


function autorizar () {


    $data = ($_POST['data']);
    $numSolicitud = $_POST['numSolicitud'];
    $Recodificacion = new Recodificacion();

 
    $result  = $Recodificacion->autorizar($numSolicitud);
    
    if ($result == true) {

        foreach ($data as $key => $value) {

            $Recodificacion->actualizarDetalle($value['PRECIO'], $value['NUEVO_CODIGO'], $value['DESTINO'], $value['OBSERVACIONES'], $value['ID']);

        }

    }
    
   
    return true;
}

function enviar () {
    $data = $_POST['data'];
    $numSolicitud = $_POST['numSolicitud'];

    $Recodificacion = new Recodificacion();

    $result = $Recodificacion->enviar($numSolicitud);

    if ($result == true) {

        foreach ($data as $key => $value) {

            $Recodificacion->cargarRemito($value['id'], $value['remito']);

        }

    }
    return true;
}
?>