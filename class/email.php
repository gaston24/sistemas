<?php

    require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/PHPMailer.php';
    require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/SMTP.php';
    require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

class Email{

    private $Host;
    private $SMTPAuth;
    private $Username;
    private $Password;
    private $SMTPSecure;
    private $Port;
    private $email;
    private $sender;


    function __construct(){

        require_once($_SERVER["DOCUMENT_ROOT"].'/sistemas/class/classEnv.php');
        $vars = new DotEnv($_SERVER["DOCUMENT_ROOT"].'/sistemas/.env');
        $this->envVars = $vars->listVars();

        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();

        $this->Host = $this->envVars['HOST_EMAIL'];
        $this->SMTPAuth = true;
        $this->Username = $this->envVars['USER_EMAIL'];
        $this->Password = $this->envVars['PASS_EMAIL'];
        $this->SMTPSecure = false;
        $this->Port = 26;
        $this->sender = 'XL Extralarge';
        

    }

    public function enviarEmail($email, $asunto, $message){

        try {

            // Detalles del correo electrónico
            $this->mail->setFrom($this->Username,  $this->sender);
            $this->mail->addAddress($email);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $message;
            $this->mail->isHTML(true);

            $this->mail->send();
        }
        catch (Exception $e) {
            echo "Error al enviar el mensaje: {$this->mail->ErrorInfo}";
        }
    }

}