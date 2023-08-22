<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/PHPMailer.php';
require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/SMTP.php';
require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/Exception.php';


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

    session_start();

    require_once '../class/Recodificacion.php';
    $recodificacion = new Recodificacion();

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
        // Configuración del servidor SMTP
        foreach ($usuarios as $key => $usuario) {

            $mail->isSMTP();
            $mail->Host = 'mail.xl.com.ar';
            $mail->SMTPAuth = true;
            $mail->Username = 'notificaciones@xl.com.ar';
            $mail->Password = '%3xtr44415_';
            $mail->SMTPSecure = false;
            $mail->Port = 26;

            // Detalles del correo electrónico
            $mail->setFrom('notificaciones@xl.com.ar', 'XL Extralarge');
            $mail->addAddress($usuario['MAIL']);
            $mail->Subject = $asunto;
            $mail->Body = $message;
            $mail->isHTML(true);

            $mail->send();

        }
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

}

function autorizarSolicitud () {

    require_once '../class/Recodificacion.php';
    $recodificacion = new Recodificacion();
    session_start();

    $usuario = $_SESSION['username'];
    
    $numSuc= $_POST["numSuc"];
    $nombreSuc = $_POST["nombreSuc"];
    $numSolicitud = $_POST["numSolicitud"];
    $urlEmail = $_POST["urlEmail"];
    $url = $_SERVER["DOCUMENT_ROOT"].'/sistemas/controlFallas/'. $urlEmail;

    $message = file_get_contents($url); // Carga el contenido del archivo HTML


    $mailLocal = $recodificacion->traerMailAutorizaSolicitud($numSuc);
    $mailLocal = $mailLocal[0]['MAIL'];

    $message = str_replace('$desc_sucursal', $nombreSuc, $message);
    $message = str_replace('$numSolicitud', $numSolicitud , $message);
    $message = str_replace('$nombreSup', $usuario , $message);

    $mail = new PHPMailer(true);

    $asunto =  "$nombreSuc - Cambio de estado en Solicitud N ° $numSolicitud";

    try {
        // Configuración del servidor SMTP

            $mail->isSMTP();
            $mail->Host = 'mail.xl.com.ar';
            $mail->SMTPAuth = true;
            $mail->Username = 'notificaciones@xl.com.ar';
            $mail->Password = '%3xtr44415_';
            $mail->SMTPSecure = false;
            $mail->Port = 26;

            // Detalles del correo electrónico
            $mail->setFrom('notificaciones@xl.com.ar', 'XL Extralarge');
            $mail->addAddress($mailLocal);
            $mail->Subject = $asunto;
            $mail->Body = $message;
            $mail->isHTML(true);

            $mail->send();

    
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
    
}

function enviarSolicitud () {

    session_start();

    require_once '../class/Recodificacion.php';
    $recodificacion = new Recodificacion();

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
        // Configuración del servidor SMTP
        foreach ($usuarios as $key => $usuario) {

            $mail->isSMTP();
            $mail->Host = 'mail.xl.com.ar';
            $mail->SMTPAuth = true;
            $mail->Username = 'notificaciones@xl.com.ar';
            $mail->Password = '%3xtr44415_';
            $mail->SMTPSecure = false;
            $mail->Port = 26;

            // Detalles del correo electrónico
            $mail->setFrom('notificaciones@xl.com.ar', 'XL Extralarge');
            $mail->addAddress($usuario['MAIL']);
            $mail->Subject = $asunto;
            $mail->Body = $message;
            $mail->isHTML(true);

            $mail->send();

        }
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }

}
?>