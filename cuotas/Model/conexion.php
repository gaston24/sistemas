<?php

class Conexion
{

    protected $local;
    protected $DB;
    protected $port;
    protected $User = 'sa';
    protected $Password = 'Axoft1988';

    private function conectarLocal($local, $DB,$port)
    {
        $this->local = $local;
        $this->DB = $DB;
        $this->port=$port;
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" .$this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sth = $conn->query("SELECT * FROM RO_VIEW_INSERT_ML_SOF_AUDITORIA");
            /* $sth->execute(); */
            echo 'sdf' . $sth->rowCount();
            if ($sth->rowCount() > 0) {
                $sth = $conn->prepare("SET NOCOUNT ON; EXEC RO_SP_INSERT_ML_SOF_AUDITORIA");
                /* $sth->bindParam(1, $name);
			$sth->bindParam(2, $lastname);
			$sth->bindParam(3, $age); */
                $sth->execute();
                $sth->nextRowset();

                $conn = null;
            } else {
                echo 'no tiene nada';
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


   function infoSucursales()
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


            $sth = $conn->query("SELECT NRO_SUCURSAL,DESC_SUCURSAL,CONEXION_DNS,BASE_NOMBRE  FROM SUCURSALES_LAKERS WHERE NRO_SUCURSAL < 100");
          
            var_dump($sth);
            foreach($sth as $row)
            {
                print $row['NRO_SUCURSAL'];
                print $row['DESC_SUCURSAL'];
                print $row['CONEXION_DNS'];
                print $row['BASE_NOMBRE'];
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
