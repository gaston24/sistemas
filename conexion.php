<?php

class Conexion{

    private $servidor;
    private $database ;
    private $user;
    private $pass;
    private $character;

    public function conectar(){
        require_once(__DIR__.'/vars.php');


        $this->servidor=$HOST_CENTRAL;
        $this->database=$DATABASE;
        $this->user=$USER;
        $this->pass=$PASS;
        $this->character=$CHARACTER;

        try {

            $conexion_central = array( "Database"=>$this->database, "UID"=>$this->user, "PWD"=>$this->pass, "CharacterSet" => $this->character);
            $cid_central = sqlsrv_connect($this->servidor, $conexion_central);

            return $cid_central;
             
        } catch (PDOException $e){
                 echo $e->getMessage();
        }

    }

}