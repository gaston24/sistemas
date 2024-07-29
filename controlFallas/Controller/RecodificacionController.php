<?php

require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';
require_once  $_SERVER["DOCUMENT_ROOT"]."/sistemas/controlFallas/Controller/SendEmailController.php";
require_once $_SERVER["DOCUMENT_ROOT"]."/sistemas/ajustes/Class/Ajuste.php";

$accion = $_GET['accion'];

switch ($accion) {

    case 'traerArticulos':
        traerArticulos();

        break;

    case 'traerArticulosEnSolicitud':
        traerArticulosEnSolicitud();

        break;

    case 'eliminarArchivo':
        eliminarArchivo();

        break;
    
    case 'contarImagenes':
        contarFotosEnCarpeta();

        break;

    case 'mostrarFotos':
        mostrarFotos();

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

    case 'traerLocales':
        traerLocales();

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

    $numImg = $_POST['numImg'];

    $numSucursal = $_POST['nroSucursal'];

    $fileName = $numSucursal.$numImg.$codArticulo;

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

    $nroSucursal = $_POST['nroSucursal'];

    $numImg = $_POST['numImg'];


    if(isset($arrayArticulos)){
        
        $contadorFotos = 0;
        $datosDeLosArchivos = [];
        $datosDeLosArchivos['cantidad'] = 0;
        foreach ($arrayArticulos as $key => $codigo) {
            $fileName = $nroSucursal.$numImg.$codigo;
           
            if ($gestor = opendir("../assets/uploads")) {
                
                while (($archivo = readdir($gestor)) !== false) {
                   
                    if ($archivo != "." && $archivo != "..") {
        
                        if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
                            
                            $datosDeLosArchivos['cantidad'] ++;
        
                            $datosDeLosArchivos['nombre'][] = $codigo;
        
                           
                        } 
         
                     
                    }
                }
        
                
                closedir($gestor);
            }

        }

    }else{

  
        $fileName = $nroSucursal.$numImg.$codArticulo;
        $contadorFotos = 0;
        $datosDeLosArchivos = [];
        $datosDeLosArchivos['cantidad'] = 0;
        
        if ($gestor = opendir("../assets/uploads")) {
            
            while (($archivo = readdir($gestor)) !== false) {
                
                if ($archivo != "." && $archivo != "..") {

                    if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
                       
                        $datosDeLosArchivos['cantidad'] ++;

                        $datosDeLosArchivos['nombre'][] = pathinfo($archivo, PATHINFO_FILENAME);

                       
                    } 
    
                
                }
            }

            
            closedir($gestor);
        }
    }

    echo json_encode($datosDeLosArchivos);
}

function mostrarFotos () {
    
    $codArticulo = (isset($_POST['codArticulo'])) ? $_POST['codArticulo'] : "";
    
    if($codArticulo == ""){

        $arrayArticulos = $_POST['codArticulos'];

    }

    $numSolicitud = $_POST['numSolicitud'];


    $fileName = $numSolicitud.$codArticulo;
    $contadorFotos = 0;
    $datosDeLosArchivos = [];
    $datosDeLosArchivos['cantidad'] = 0;
    
    if ($gestor = opendir("../assets/uploads")) {
        
        while (($archivo = readdir($gestor)) !== false) {
           
            if ($archivo != "." && $archivo != "..") {

                if (stripos(pathinfo($archivo, PATHINFO_FILENAME), $fileName) !== false) {
                   
                    $datosDeLosArchivos['cantidad'] ++;

                    $datosDeLosArchivos['nombre'][] = pathinfo($archivo, PATHINFO_FILENAME);

                    
                } 

            
            }
        }

       
        closedir($gestor);
    }
 

    echo json_encode($datosDeLosArchivos);

}

function solicitar () {

    $nroSucursal = $_POST['nroSucursal'];

    $fecha = $_POST['fecha'];
    
    $usuario = $_POST['usuario'];
    $estado = $_POST['estado'];
    $numSolicitud = $_POST['numSolicitud'];
    $numImg = $_POST['numImg'];
    $dataArticulos = $_POST['dataArticulos'];
    $esBorrador = $_POST['esBorrador'];
    $valores = "";

    
    $recodificacion = new Recodificacion();

    if ($esBorrador == 1){

        $solicitud = $numSolicitud;
    }else{

        $solicitud = $numImg;
    }

    $encabezado = $recodificacion->insertarEncabezado($solicitud, $nroSucursal, $fecha, $usuario, 1, $esBorrador );
    
    foreach ($dataArticulos as $key => $articulo) {
        $valores .= "('$encabezado', '$articulo[codArticulo]', '$articulo[descripcion]', '$articulo[precio]', '$articulo[cantidad]', '$articulo[descFalla]'),";


     
        $directorio = '../assets/uploads/';
        $extensionesValidas = array('.jpg', '.jpeg', '.png', '.gif');

    
        $nombreArchivoBusqueda = $nroSucursal . $numImg . $articulo['codArticulo'];
      

        if ($handle = opendir($directorio)) {
     
            while (false !== ($archivo = readdir($handle))) {
                if ($archivo != "." && $archivo != "..") {
    
                    $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                    $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
      

                    if (strpos($nombreArchivo, $nombreArchivoBusqueda) !== false ) {

                        $nuevoNombre = trim($encabezado).trim(substr($nombreArchivo, strpos($nombreArchivo, $numImg) + strlen($numImg)));
                        $nuevoPath = $directorio . $nuevoNombre . ".$extension";
                        rename($directorio . $archivo, $nuevoPath);
                    }
                }
            }
            closedir($handle);
        }
    }
    $valores = substr_replace($valores, ";", -1, 1);


    if($encabezado != '' && $esBorrador != "false"){
    
        $recodificacion->borrarDetalle($encabezado);
    }
    
    $recodificacion->insertarDetalle($valores);

    echo $encabezado;

}

function borrador () {
   
    $nroSucursal = $_POST['nroSucursal'];

    $fecha = $_POST['fecha'];

    $usuario = $_POST['usuario'];
    $estado = $_POST['estado'];
    $valores = "";
    $numSolicitud = $_POST['numSolicitud'];
    $numImg = $_POST['numImg'];
    $dataArticulos = $_POST['dataArticulos'];
    
    $recodificacion = new Recodificacion();

    $buscarBorradorEnc = $recodificacion->buscarBorradorEnc($numSolicitud, "4");


    if(count($buscarBorradorEnc) > 0){
        
        $encabezado = $buscarBorradorEnc[0]['ID'];
        $recodificacion->borrarDetalle($encabezado);

    }else{

        $encabezado = $recodificacion->insertarEncabezado($numImg, $nroSucursal, $fecha, $usuario, 4, "false" );
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
    $arrayArticulosAlta = $_POST['arrayArticulosAlta'];
    $numSucursal = $_POST['numSucursal'];
    $valores = "";

    $recodificacion = new Recodificacion();
    
    foreach ($arrayArticulosAlta as $key => $value) {
  
        $result = $recodificacion->altaArticulo($value);
    }    
 
    if($outlet == "1"){

        $result = $recodificacion->ingresar($numSolicitud);

    }else{

        $result  = $recodificacion->autorizar($numSolicitud);
    }

    
    if ($result == true) {

        foreach ($data as $key => $value) {

            $recodificacion->actualizarDetalle($value['PRECIO'], $value['NUEVO_CODIGO'], $value['DESTINO'], $value['OBSERVACIONES'], $value['ID']);

            if($value['NUEVO_CODIGO'] == '' && $value['DESTINO'] == $numSucursal ) {

                $valores .= "(''$value[COD_ARTICULO]'', ''1''),";

            }
        }

    }
    
    $cadena = rtrim($valores, ',') ;

    $result = $recodificacion->realizarMovimientoDepositoCentral($cadena);
   
    return true;
    
}

function enviar () {

    $data = $_POST['data'];
    $numSolicitud = $_POST['numSolicitud'];
    $destinoCentral = $_POST['destinoCentral'];


    $recodificacion = new Recodificacion();


    if($destinoCentral == "true"){

        $result = $recodificacion->finalizar($numSolicitud);

    }else{

        $result = $recodificacion->enviar($numSolicitud);

    }

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
    // notificarCodigosOulet($arrayResult, $numSolicitud, $nombreSuc);
    echo json_encode($arrayResult);
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
    $nroSolicitud = $_POST['nroSolicitud'];
    foreach ($data as $key => $value) {

        $recodificacion = new Recodificacion();
        $result = $recodificacion->ajustarArticulos($value['id_enc'], $value['codigo']);

    }

    //  estado encabezado 
    $recodificacion->setearAjustadoEnc($nroSolicitud);

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


    $valores = "";
    foreach ($dataArticulos as $key => $articulo) {

        $valores .= "(''$articulo[codArticulo]'', ''$articulo[cantidad]''),";
    }
    $cadena = rtrim($valores, ',') ;

    $result = $recodificacion->realizarMovimientoOu($cadena);
    
    echo $result;

}


function traerLocales () {

    $recodificacion = new Recodificacion();
    $result = $recodificacion->traerLocales(0);
    echo json_encode($result);

}

function traerArticulosEnSolicitud () {
    
    $id = $_POST['id'];

    $recodificacion = new Recodificacion();

    $result = $recodificacion->traerDetalle($id);

    echo json_encode($result);
}

?>