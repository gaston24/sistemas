<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/email.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/Recodificacion.php';

$recodificacion = new Recodificacion();

$accion = $_GET['accion'];

switch ($accion ) {

    case 'confirmarSolicitud':
        confirmarSolicitud($recodificacion);
        break;

    case 'autorizarSolicitud':
        autorizarSolicitud($recodificacion);
        break;

    case 'enviarSolicitud':
        enviarSolicitud($recodificacion);
        break;
    
}


function confirmarSolicitud ($recodificacion) {

    $usuarios = $recodificacion->traerUsuariosNotificaSolicitud();
    
    $numSolicitud = $_POST["numSolicitud"];

    $asunto =  "$_SESSION[descLocal] - Cambio de estado en Solicitud Nro. $numSolicitud";

    $arrayData = [

        'tipo' => 1,
        'numSolicitud' => $numSolicitud,
        'descSucursal' => $_SESSION['descLocal'],

    ];

    $email = new Email();
    
    try {
        $arrayEmail = array();
        foreach ($usuarios as $key => $usuario) {
           

            $arrayEmail[] = $email->enviarEmail($usuario['MAIL'], $asunto, $arrayData);
            
        }
        
        return $arrayEmail;

    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

}

function autorizarSolicitud ($recodificacion) {

    $usuario = $_SESSION['descLocal'];
    
    $numSuc= $_POST["numSuc"];
    $nombreSuc = $_POST["nombreSuc"];
    $numSolicitud = $_POST["numSolicitud"];
   
    $emailLocal = $recodificacion->traerMailAutorizaSolicitud($numSuc);
    $emailLocal = $emailLocal[0]['MAIL'];

    $arrayData = [

        'tipo' => 2,
        'numSolicitud' => $numSolicitud,
        'descSucursal' => $_SESSION['descLocal'],
        'nombreSup' => $usuario,

    ];

    $email = new Email();

    $asunto =  "$nombreSuc - Cambio de estado en Solicitud Nro. $numSolicitud";

    try {

        $email->enviarEmail($emailLocal, $asunto, $arrayData);
    
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

    
}

function enviarSolicitud ($recodificacion) {

    $usuarios = $recodificacion->traerUsuariosNotificaSolicitud();
    
    $numSolicitud = $_POST["numSolicitud"];

    $arrayData = [

        'tipo' => 3,
        'numSolicitud' => $numSolicitud,
        'descSucursal' => $_SESSION['descLocal'],

    ];

    $email = new Email();
    $asunto =  "$_SESSION[descLocal] - Cambio de estado en Solicitud Nro. $numSolicitud";

    try {

        foreach ($usuarios as $key => $usuario) {

            $email->enviarEmail($usuario['MAIL'], $asunto, $arrayData);
            
        }
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

}

function notificarCodigosOulet ($data, $numSolicitud, $nombreSuc) {
   
    $recodificacion = new Recodificacion();

    $usuarios = ["florencia.bocchicchio@xl.com.ar","valeria.villarreal@xl.com.ar"];

    $arrayData = [

        'tipo' => 4,
        'numSolicitud' => $numSolicitud,
        'descSucursal' => $nombreSuc,
        'data' => $data,

    ];

    $email = new Email();
    $asunto =  "$nombreSuc - Solicitud de alta de articulos para Solicitud Nro. $numSolicitud";

    try {

        foreach ($usuarios as $key => $usuario) {
   
            $email->enviarEmail($usuario, $asunto, $arrayData);
            
        }
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

    

}
?>