<?php

class Conexion
{

    protected $local;
    protected $DB;
    protected $port;
    protected $User = 'sa';
    protected $Password ;

    function conectarLocal($local, $DB,$port)
    {
        $this->local = $local;
        $this->DB = $DB;
        $this->port=$port;
        $this->Password='Axoft1988';
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" .$this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            echo 'estoy en '.$this->local.' \n';
            $sth = $conn->query("select * from TARJETA_COEFICIENTE_CUOTA");
            /* $sth->execute(); */
          
            foreach($sth as $row){
                print_r($row);
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


   function conexionLocales()
    { 
        $this->local = 'LAKERBIS';
        $this->DB = 'LOCALES_LAKERS';
        $this->port='1433';
        $this->User = 'sa';
        $this->Password = 'Axoft';
       
        //traer los datos de conexión de los locales para luego recorrer cada una con la función conectarLocal()
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port. ";Database=" . $this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sth = $conn->query("SELECT NRO_SUCURSAL,DESC_SUCURSAL,CONEXION_DNS,BASE_NOMBRE  FROM SUCURSALES_LAKERS WHERE NRO_SUCURSAL < 100 AND HABILITADO=1");
          
            foreach($sth as $row)
            {
                $Conexion=explode(',',$row['CONEXION_DNS']);
                $Direccion=$Conexion[0];
                $Puerto=$Conexion[1];
                $this->conectarLocal($Direccion,$row['BASE_NOMBRE'],$Puerto);
              /*   print $row['NRO_SUCURSAL'];
                print $row['DESC_SUCURSAL'];
                print $row['CONEXION_DNS'];
                print $row['BASE_NOMBRE']; */
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
