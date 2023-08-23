<?php


require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/PHPMailer.php';
require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/SMTP.php';
require  $_SERVER["DOCUMENT_ROOT"].'/sistemas/class/PHPMailer/Exception.php';

require_once($_SERVER["DOCUMENT_ROOT"].'/sistemas/class/classEnv.php');
       

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
    private $vars;
    private $envVars;


    public function __construct() {

        try {

            $this->vars = new DotEnv($_SERVER["DOCUMENT_ROOT"].'/sistemas/.env');
            $this->envVars = $this->vars->listVars();

            $this->mail = new PHPMailer(true);

            $this->mail->isSMTP();
            $this->mail->Host = $this->envVars['HOST_EMAIL'];
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->envVars['USER_EMAIL'];
            $this->mail->Password = $this->envVars['PASS_EMAIL'];
            $this->mail->SMTPSecure = false;
            $this->mail->Port = 26;
            $this->mail->setFrom($this->envVars['USER_EMAIL'], 'XL Extralarge');

        } catch (Exception $e) {
            echo "Error al instanciar la clase Email: {$this->mail->ErrorInfo}";
        }
    }

    
    public function enviarEmail($email, $asunto, $message){

        try {
            
            $this->mail->addAddress($email);
            $this->mail->Subject = $asunto;
            $this->mail->Body = $message;
            $this->mail->isHTML(true);
            $this->mail->send();

            return true;

        } catch (\Throwable $th) {
            echo "Error al enviar el correo: {$mail->ErrorInfo}";
        }
        
    }
    
    
}
