<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/email.php';
require_once $_SERVER["DOCUMENT_ROOT"].'/sistemas/controlFallas/class/Recodificacion.php';

$recodificacion = new Recodificacion();

$accion = $_GET['accion'];

switch ($accion ) {
    case 'confirmarSolicitud':
        confirmarSolicitud();
        break;

    case 'autorizarSolicitud':
        autorizarSolicitud();
        break;

    case 'enviarSolicitud':
        enviarSolicitud();
        break;
    
    default:
        # code...
        break;
}


function confirmarSolicitud () {

    $usuarios = $recodificacion->traerUsuariosNotificaSolicitud();
    
    $numSolicitud = $_POST["numSolicitud"];
    $urlEmail = $_POST["urlEmail"];
    $url = $_SERVER["DOCUMENT_ROOT"].'/sistemas/controlFallas/'. $urlEmail;

    $message = file_get_contents($url); // Carga el contenido del archivo HTML
    $message = str_replace('$desc_sucursal', $_SESSION['descLocal'], $message);
    $message = str_replace('$numSolicitud', $numSolicitud , $message);
    $asunto =  "$_SESSION[descLocal] - Cambio de estado en Solicitud N ° $numSolicitud";

    $email = new Email();
    
    try {

        foreach ($usuarios as $key => $usuario) {

            $email->enviarEmail($usuario['MAIL'], $asunto, $message);

        }
        
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

}

function autorizarSolicitud () {

    $usuario = $_SESSION['username'];
    
    $numSuc= $_POST["numSuc"];
    $nombreSuc = $_POST["nombreSuc"];
    $numSolicitud = $_POST["numSolicitud"];
    $urlEmail = $_POST["urlEmail"];
    $url = $_SERVER["DOCUMENT_ROOT"].'/sistemas/controlFallas/'. $urlEmail;

    $message = file_get_contents($url); // Carga el contenido del archivo HTML

    $emailLocal = $recodificacion->traerMailAutorizaSolicitud($numSuc);
    $emailLocal = $emailLocal[0]['MAIL'];

    $message = str_replace('$desc_sucursal', $nombreSuc, $message);
    $message = str_replace('$numSolicitud', $numSolicitud , $message);
    $message = str_replace('$nombreSup', $usuario , $message);

    $mail = new PHPMailer(true);

    $asunto =  "$nombreSuc - Cambio de estado en Solicitud N ° $numSolicitud";

    try {

        $email->enviarEmail($emailLocal, $asunto, $message);
    
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
    
}

function enviarSolicitud () {

    $usuarios = $recodificacion->traerUsuariosNotificaSolicitud();
    
    $numSolicitud = $_POST["numSolicitud"];
    $urlEmail = $_POST["urlEmail"];
    $url = $_SERVER["DOCUMENT_ROOT"].'/sistemas/controlFallas/'. $urlEmail;

    $message = file_get_contents($url); // Carga el contenido del archivo HTML

    $message = str_replace('$desc_sucursal', $_SESSION['descLocal'], $message);
    $message = str_replace('$numSolicitud', $numSolicitud , $message);
    $mail = new PHPMailer(true);
    $asunto =  "$_SESSION[descLocal] - Cambio de estado en Solicitud N ° $numSolicitud";

    try {

        foreach ($usuarios as $key => $usuario) {

            $email->enviarEmail($usuario['MAIL'], $asunto, $message);
            
        }
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

}
?>