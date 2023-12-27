<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';
require_once  $_SERVER["DOCUMENT_ROOT"]."/sistemas/controlFallas/Controller/SendEmailController.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/sistemas/ajustes/Class/Ajuste.php";

$accion = $_GET['accion'];

switch ($accion) {

    case 'traerArticulos':
        traerArticulos();

        break;

    case 'traerArticuloss':
        traerArticuloss();

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

    case 'comprobarStock':
        comprobarStock();

        break;


    case 'validarCodigosOulet':
        validarCodigosOulet();

        break;


    case 'comprobarStockArticulos':
        comprobarStockArticulos();

        break;


    case 'comprobarArticuloEnRemito':
        comprobarArticuloEnRemito();

        break;

    case 'ajustarArticulos':
        ajustarArticulos();

        break;

    case 'realizarMovimientoOu':
        realizarMovimientoOu();

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
function traerArticuloss () {
    $campo = $_GET['q'];
    
    require_once "../../ajustes/Class/Articulo.php";
    $articulo = new Articulo();

    $result = $articulo->traerMaestroArticulo($campo);
    $data = [];
    
    foreach ($result as $key => &$value) {
        $value['id'] = $key;
        $value['text'] = $value['COD_ARTICU']." - ".$value['DESCRIPCIO'];
        $data['items'][] = $value;

    }
    $data['total_count'] = 3;
    echo json_encode($data);

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
    
    $codArticulo = (isset($_POST['codArticulo'])) ? $_POST['codArticulo'] : "";
    
    if($codArticulo == ""){

        $arrayArticulos = $_POST['codArticulos'];

    }

    $numSolicitud = $_POST['numSolicitud'];
 
    if(isset($arrayArticulos)){
        
        $contadorFotos = 0;
        $datosDeLosArchivos = [];
        $datosDeLosArchivos['cantidad'] = 0;
        foreach ($arrayArticulos as $key => $codigo) {
            $fileName = $numSolicitud.$codigo;
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
    }

    echo json_encode($datosDeLosArchivos);
}

function solicitar () {

    $nroSucursal = $_POST['nroSucursal'];

    $fecha = $_POST['fecha'];
    
    $usuario = $_POST['usuario'];
    $estado = $_POST['estado'];
    $numSolicitud = $_POST['numSolicitud'];
    $dataArticulos = $_POST['dataArticulos'];
    $esBorrador = $_POST['esBorrador'];
    $valores = "";
    foreach ($dataArticulos as $key => $articulo) {
        $valores .= "('$numSolicitud', '$articulo[codArticulo]', '$articulo[descripcion]', '$articulo[precio]', '$articulo[cantidad]', '$articulo[descFalla]'),";
    }

    $valores = substr_replace($valores, ";", -1, 1);

    $recodificacion = new Recodificacion();
    $encabezado = $recodificacion->insertarEncabezado($numSolicitud, $nroSucursal, $fecha, $usuario, 1, $esBorrador );

    if($encabezado == true && $esBorrador == "false"){
        $recodificacion->insertarDetalle($valores);
    }

    return true;

}

function borrador () {
   
    $nroSucursal = $_POST['nroSucursal'];


    $fecha = $_POST['fecha'];

    $usuario = $_POST['usuario'];
    $estado = $_POST['estado'];
    $numSolicitud = $_POST['numSolicitud'];
    $dataArticulos = $_POST['dataArticulos'];
    $valores = "";

    
    
    $recodificacion = new Recodificacion();

    $buscarBorradorEnc = $recodificacion->buscarBorradorEnc($numSolicitud, "4");


    if(count($buscarBorradorEnc) > 0){
        
        $encabezado = $buscarBorradorEnc[0]['ID'];
        $recodificacion->borrarDetalle($encabezado);

    }else{

        $encabezado = $recodificacion->insertarEncabezado($numSolicitud, $nroSucursal, $fecha, $usuario, 4, "false" );
    }

    
    foreach ($dataArticulos as $key => $articulo) {

        $valores .= "('$encabezado', '$articulo[codArticulo]', '$articulo[descripcion]', '$articulo[precio]', '$articulo[cantidad]', '$articulo[descFalla]'),";
        
    }
    $valores = substr_replace($valores, ";", -1, 1);
    
    if($encabezado == true){
        $recodificacion->insertarDetalle($valores);
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
    $outlet = $_POST['outlet'];

    $recodificacion = new Recodificacion();
 
    if($outlet == "1"){

        $result = $recodificacion->ingresar($numSolicitud);

    }else{

        $result  = $recodificacion->autorizar($numSolicitud);
    }

    
    if ($result == true) {

        foreach ($data as $key => $value) {

            $recodificacion->actualizarDetalle($value['PRECIO'], $value['NUEVO_CODIGO'], $value['DESTINO'], $value['OBSERVACIONES'], $value['ID']);

        }

    }
    
   
    return true;
}

function enviar () {

    $data = $_POST['data'];
    $numSolicitud = $_POST['numSolicitud'];

    $recodificacion = new Recodificacion();

    $result = $recodificacion->enviar($numSolicitud);

    if ($result == true) {

        foreach ($data as $key => $value) {

            $recodificacion->cargarRemito($value['id'], $value['remito']);

        }

    }
    return true;

}

function comprobarStock () {

    $codArticulos = $_POST['codArticulos'];


    $recodificacion = new Recodificacion();
    $arrayResult = [];

    foreach ($codArticulos as $key => $articulo) {
        $result = $recodificacion->comprobarStock($articulo);


        if($result == false){

            $arrayResult[] = $articulo['articulo'];
            
        }
    }
    
    echo json_encode($arrayResult);
}


function validarCodigosOulet () {

    $codigosOulet = $_POST['codigosOulet'];
    $numSolicitud = $_POST['numSolicitud'];
    $nombreSuc = $_POST['nombreSuc'];

    $recodificacion = new Recodificacion();
    $arrayResult = [];

    foreach ($codigosOulet as $key => $articulo) {
    
        if($articulo != ""){

            $result = $recodificacion->validarCodigosOulet($articulo);
 
            if($result == false){

                $arrayResult[] = $articulo;
            
            }

        }

    
    }
    notificarCodigosOulet($arrayResult, $numSolicitud, $nombreSuc);
    echo true;
}


function comprobarArticuloEnRemito () {


    if(isset($_POST['arrayRemitos'])){

        $arrayRemitos = $_POST['arrayRemitos'];
        
    }else{
        
        $nComp = $_POST['nComp'];
        $articulo = $_POST['articulo'];

    }


    $recodificacion = new Recodificacion();
    
    if(isset($arrayRemitos)){

        $arrayResponse = [];

        foreach ($arrayRemitos as $key => $remito) {
            
            $result = $recodificacion->comprobarArticuloRecodifica($remito['nComp'], $remito['articulo'], $remito['cantidad']);
            $arrayResponse[] = ["remito"=>$remito['nComp'], "articulo"=>$remito['articulo'] , "respuesta"=>$result];
        }
        
        echo json_encode($arrayResponse);

    }else{

        $result = $recodificacion->comprobarArticuloEnRemito($nComp, $articulo);
    
        echo $result;

    }

}

function ajustarArticulos () {

    $data = $_POST['arrayDeArticulos'];

    foreach ($data as $key => $value) {

        $recodificacion = new Recodificacion();
        $result = $recodificacion->ajustarArticulos($value['id_enc'], $value['codigo']);

    }

    echo true;
}

function comprobarStockArticulos () {

    $data = json_decode($_POST['data'],true);

    $arrayDeArticulosSinStock = [];
 
    foreach ($data as $key => $value) {
   
        $recodificacion = new Recodificacion();

        $codAnterior = $recodificacion->comprobarStockMaestroDeArticulos($value['codigo']);

        if($codAnterior == false){

            $arrayDeArticulosSinStock[] = ['1',$value['codigo']];

        }else{

            $stockSuficiente = $recodificacion->comprobarStockDepositoLocal($value['codigo'], $value['cant']);

            if($stockSuficiente == false){

                $arrayDeArticulosSinStock[] = ['2',$value['codigo']];

            }
        }


        $nuevoCodigo = $recodificacion->comprobarStockMaestroDeArticulos($value['articulo']);

        if($nuevoCodigo == false){

            $arrayDeArticulosSinStock[] = ['1',$value['articulo']];

        }


    }
    
    echo json_encode($arrayDeArticulosSinStock);

}


function realizarMovimientoOu () {


    $dataArticulos = $_POST['dataArticulos'];

    $ajuste = new Ajuste();

    $recodificacion = new Recodificacion();

    $fecha = date("Y") . "/" . date("m") . "/" . date("d");
    
    $hora = (date("H")-5).date("i").date("s");


    foreach ($dataArticulos as $key => $value) {

        $proximo = $ajuste->setearProximoRemito();

        $ajuste->updateRemitoEnTalonario();
  
        $proxInterno = $ajuste->traerProximoInterno();
 
        $recodificacion->insertarEncabezadoTango($fecha, $proximo, $proxInterno, $hora);

        $result = $recodificacion->darBajaArticuloOriginal($value['codArticulo'], $value['cantidad']);
        
        $recodificacion->insertarDetalleSalidaOu($value['cantidad'], $value['codArticulo'], $fecha, $proxInterno);
  
        
        $result = $recodificacion->darAltaEnDepositoOu($value['codArticulo'], $value['cantidad']);

        
        $recodificacion->insertarDetalleEntradaOu($value['cantidad'], $value['codArticulo'], $fecha, $proxInterno);
 

    }

    echo true;

}

?>