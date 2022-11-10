
<?php

class Conexion{

    function __construct(){
        require_once(__DIR__.'/../vars.php');
        
        $this->host_central = $HOST_CENTRAL;
        $this->database_central = $DATABASE_CENTRAL;
        $this->host_locales = $HOST_LOCALES;
        $this->database_locales = $DATABASE_LOCALES;
        $this->user = $USER;
        $this->pass = $PASS;
        $this->character = $CHARACTER;
    }

    private function servidor($nameServer) {
        
        if($nameServer == 'central'){
            return array($this->host_central, $this->database_central);
        }elseif($nameServer == 'locales'){
            return array($this->host_locales, $this->database_locales);
        }else{
            $local = $this->buscarLocal($nameServer);
            return array($local['CONEXION_DNS'], $local['BASE_NOMBRE']);
        }

    }

    public function conectar($nameServer) {
        try {

            $serverDB = $this->servidor($nameServer);

            $params = array( 
                "Database" => $serverDB[1], 
                "UID" => $this->user, 
                "PWD" => $this->pass, 
                "CharacterSet" => $this->character
            );

            $cid = sqlsrv_connect($serverDB[0], $params);

            return $cid;
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function buscarLocal($nameLocal){

        $sql = "select * from [LAKERBIS].locales_lakers.dbo.sucursales_lakers where cod_client = '$nameLocal'";

        $params = array( 
            "Database" => $this->database_central, 
            "UID" => $this->user, 
            "PWD" => $this->pass, 
            "CharacterSet" => $this->character
        );

        $cid = sqlsrv_connect($this->host_central, $params);

        $stmt = sqlsrv_query($cid, $sql);

        try {

            // $next_result = sqlsrv_next_result($stmt);

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }
    
            return $v[0];

        } catch (\Throwable $th) {

            print_r($th);

        }
    }
}
