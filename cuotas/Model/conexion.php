<?php

use function PHPSTORM_META\type;

class Conexion
{
    protected $info_locales = [];
    protected $local;
    protected $DB;
    protected $port;
    protected $User = 'sa';
    protected $Password;
    protected $msjError=[];

    function conectarLocal($nro_Suc, $Nombre, $local, $DB, $port)
    {
        $datos_Local = [];
        $cuotas_local = [];
        array_push($datos_Local, $nro_Suc, $Nombre);
        /* array_push($this->info_locales,$nro_Suc,$Nombre); */
        $this->local = $local;
        $this->DB = $DB;
        $this->port = $port;
        $this->Password = 'Axoft1988';
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" . $this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sth = $conn->query("
            SELECT DISTINCT COD_TARJET ,CUOTA FROM TARJETA_COEFICIENTE_CUOTA  A
            
            INNER JOIN TARJETA_VIGENCIA_COEFICIENTE B
            
            ON A.ID_TARJETA_VIGENCIA_COEFICIENTE = B.ID_TARJETA_VIGENCIA_COEFICIENTE
            
            INNER JOIN SBA22 C
            
            ON B.ID_SBA22 = C.ID_SBA22
            
            WHERE C.COD_TARJET IN ('MST','VIS','AME')
            ");

            foreach ($sth as $row) {
                /* array_push($this->info_locales,$row); */
                array_push($cuotas_local, $row);
            }
        } catch (PDOException $e) {
            $msj = $e->getMessage() . ' Error en la conexión a ' . $Nombre;
            array_push($cuotas_local, [], $msj);
        }
        array_push($this->info_locales, array($datos_Local, $cuotas_local));
        $sth = null;
        $conn = null;
    }


    function conexionLocales()
    {
        $this->local = 'LAKERBIS';
        $this->DB = 'LOCALES_LAKERS';
        $this->port = '1433';
        $this->User = 'sa';
        $this->Password = 'Axoft';

        //traer los datos de conexión de los locales para luego recorrer cada una con la función conectarLocal()
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" . $this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sth = $conn->query("SELECT NRO_SUCURSAL,DESC_SUCURSAL,CONEXION_DNS,BASE_NOMBRE  FROM SUCURSALES_LAKERS WHERE NRO_SUCURSAL < 100 AND HABILITADO=1 AND
            TIPO_LOCAL IN ('SHOPPING','CALLE')");

            foreach ($sth as $row) {
                $Conexion = explode(',', $row['CONEXION_DNS']);
                $Direccion = $Conexion[0];
                $Puerto = $Conexion[1];
                $this->conectarLocal($row['NRO_SUCURSAL'], $row['DESC_SUCURSAL'], $Direccion, $row['BASE_NOMBRE'], $Puerto);
            }
            echo (json_encode($this->info_locales));
            /*  echo json_encode($this->info_locales); */
        } catch (PDOException $e) {
            echo $e->getMessage() . 'Error de conexión.';
        }
        $sth = null;
        $conn = null;
    }

    function buscarConexionLocal($sucursal)
    {
        $this->local = 'LAKERBIS';
        $this->DB = 'LOCALES_LAKERS';
        $this->port = '1433';
        $this->User = 'sa';
        $this->Password = 'Axoft';
        $Direccion_DNS='';
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" . $this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sth = $conn->prepare("SELECT NRO_SUCURSAL,DESC_SUCURSAL,CONEXION_DNS,BASE_NOMBRE  FROM SUCURSALES_LAKERS WHERE NRO_SUCURSAL = $sucursal");

            $sth->execute();

            while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                $Conexion = explode(',', $row['CONEXION_DNS']);
                $Direccion_DNS = $Conexion[0];/*direcion dns */
                $Puerto = $Conexion[1];
                $baseNombre = $row['BASE_NOMBRE'];
            }

            /*  echo json_encode($this->info_locales); */
        } catch (PDOException $e) {
            echo $e->getMessage() . 'Error de conexión.';
        }
        $sth = null;
        $conn = null;
        return [$Direccion_DNS, $Puerto, $baseNombre];
    }

    function update($sucursal, $tarjeta, $cuota, $estado)
    {
        $datos_de_conexion = $this->buscarConexionLocal($sucursal);
        $this->local = $datos_de_conexion[0];
        $this->DB = $datos_de_conexion[2];
        $this->port = $datos_de_conexion[1];
        $this->User = 'sa';
        $this->Password = 'Axoft1988';


        if ($estado === true) {
            $query = "INSERT INTO TARJETA_COEFICIENTE_CUOTA (ID_TARJETA_VIGENCIA_COEFICIENTE, CUOTA, COEFICIENTE)
            SELECT ID_TARJETA_VIGENCIA_COEFICIENTE, CUOTA, 1
            FROM TARJETA_VIGENCIA_COEFICIENTE A
            INNER JOIN SBA22 C
            ON A.ID_SBA22 = C.ID_SBA22,
            (
                   SELECT CUOTA FROM            
                   (
                         SELECT :valor1 CUOTA
                   )B
            )B
            WHERE C.COD_TARJET= :valor2 ";
        } else {
            $query = "DELETE TARJETA_COEFICIENTE_CUOTA  FROM TARJETA_COEFICIENTE_CUOTA A
            INNER JOIN TARJETA_VIGENCIA_COEFICIENTE B
            ON A.ID_TARJETA_VIGENCIA_COEFICIENTE = B.ID_TARJETA_VIGENCIA_COEFICIENTE
            INNER JOIN SBA22 C
            ON B.ID_SBA22 = C.ID_SBA22
            WHERE C.COD_TARJET = :valor2
            AND CUOTA= :valor1 ";
        }

        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" . $this->DB, $this->User, $this->Password);
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':valor1', $cuota);
            $stmt->bindParam(':valor2', $tarjeta);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo "La actualización se realizó con éxito.";
            } else {
                echo "No se realizó ninguna actualización.";
            }
        } catch (PDOException $e) {
            echo $e->getMessage() . 'Error de conexión.';
        }
        $stmt = null;
        $conn = null;
    }

    function updateLocales($nro_Suc, $Nombre, $local, $DB, $port,$tarjetas,$cuotas,$estado)
    {
       /*  echo $Nombre.' '.$tarjetas.' y '.$cuotas.' y '.$estado;
        echo gettype($cuotas); */
        //$estado->eliminar o insertar cuota
        $datos_Local = [];
        $cuotas_local = [];
        $tarjetas="'" . str_replace(",", "','", $tarjetas) . "'";
        array_push($datos_Local, $nro_Suc, $Nombre);
        /* array_push($this->info_locales,$nro_Suc,$Nombre); */
        $this->local = $local;
        $this->DB = $DB;
        $this->port = $port;
        $this->Password = 'Axoft1988';
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" . $this->DB, $this->User, $this->Password);
            /* $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); */
            if($estado==1)
            {
                $query="INSERT INTO TARJETA_COEFICIENTE_CUOTA (ID_TARJETA_VIGENCIA_COEFICIENTE, CUOTA, COEFICIENTE)
                SELECT ID_TARJETA_VIGENCIA_COEFICIENTE, CUOTA, 1
                FROM TARJETA_VIGENCIA_COEFICIENTE A
                INNER JOIN SBA22 C
                ON A.ID_SBA22 = C.ID_SBA22,
                (
                       SELECT CUOTA FROM
                       (
                             ".$cuotas."
                       )B
                )B
                WHERE C.COD_TARJET IN
                (
                $tarjetas
                )";
            }else{
                $query="DELETE TARJETA_COEFICIENTE_CUOTA  FROM TARJETA_COEFICIENTE_CUOTA A

                INNER JOIN TARJETA_VIGENCIA_COEFICIENTE B
                
                ON A.ID_TARJETA_VIGENCIA_COEFICIENTE = B.ID_TARJETA_VIGENCIA_COEFICIENTE
                
                INNER JOIN SBA22 C
                
                ON B.ID_SBA22 = C.ID_SBA22
                
                WHERE C.COD_TARJET IN ($tarjetas)
                
                AND CUOTA IN ($cuotas)";
            }
            echo $query;
            $stmt = $conn->prepare($query);
          /*   $stmt->bindParam(':valor1', $tarjetas);
            $stmt->bindParam(':valor2', $cuotas); */
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                echo "La actualización se realizó con éxito.";
            } else {
                echo "No se realizó ninguna actualización.";
            }

        } catch (PDOException $e) {
            $msj = $e->getMessage() . ' Error en la conexión a ' . $Nombre;
            echo $msj;
            array_push($this->msjError, [], $msj);
        }
        $stmt = null;
        $conn = null;
    }


    function conexionLocal_porProvincia($provincia,$tarjetas,$cuotas,$estado)
    {
        $this->local = 'LAKERBIS';
        $this->DB = 'LOCALES_LAKERS';
        $this->port = '1433';
        $this->User = 'sa';
        $this->Password = 'Axoft';

        //traer los datos de conexión de los locales para luego recorrer cada una con la función conectarLocal()
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" . $this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sth = $conn->query("SELECT NRO_SUCURSAL,DESC_SUCURSAL,CONEXION_DNS,BASE_NOMBRE  FROM SUCURSALES_LAKERS WHERE NRO_SUCURSAL < 100 AND HABILITADO=1 AND
            PROVINCIA = '$provincia'");

            foreach ($sth as $row) {
                $Conexion = explode(',', $row['CONEXION_DNS']);
                $Direccion = $Conexion[0];
                $Puerto = $Conexion[1];
                $this->updateLocales($row['NRO_SUCURSAL'], $row['DESC_SUCURSAL'], $Direccion, $row['BASE_NOMBRE'], $Puerto,$tarjetas,$cuotas,$estado);
            }
            if(!empty($this->msjError))
            {
                echo (json_encode($this->msjError));//si algun local presento error a la hora de actulizar se guarda en este arreglo. 
            }
            
            /*  echo json_encode($this->info_locales); */
        } catch (PDOException $e) {
            echo $e->getMessage() . 'Error de conexión.';
        }
        $sth = null;
        $conn = null;
    }

 
}
/* 
$a = new Conexion();

$a->update(2, 'VIS', 5, 'false');
 */