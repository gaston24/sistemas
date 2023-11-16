<?php

class Fichaje {

    function __construct(){

        require_once __DIR__.'/../../class/conexion.php';
        $this->conn = new Conexion;
        
    }

    public function buscarPorCampo($campo){

        $cid = $this->conn->conectar('central');

        $sql = "SELECT NRO_LEGAJO, APELLIDO_Y_NOMBRE FROM RO_T_LEGAJOS_PERSONAL 
        WHERE NRO_LEGAJO LIKE  '%$campo%'
        OR APELLIDO LIKE '%$campo%'
        OR NOMBRE LIKE '%$campo%'
        ";

        $result = sqlsrv_query($cid, $sql);

        if($result === false){
            die( print_r( sqlsrv_errors(), true));
        }

        $data = array();

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $data[] = $row;
        }

        return json_encode($data);

       
    }

    public function login ($numeroLegajo, $password){


        $cid = $this->conn->conectar('central');

        $sql = "SELECT NRO_LEGAJO, CONTRASEÑA FROM RO_T_LEGAJOS_PERSONAL WHERE NRO_LEGAJO = '$numeroLegajo'";

       
        $result = sqlsrv_query($cid, $sql);

        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $data = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        if ($data) {
 
            $dbPassword = $data['CONTRASEÑA'];

            if ($password == $dbPassword) {
                // Contraseña válida
                return true;
            } else {
                // Contraseña incorrecta
                return false;
            }
        } else {
            // Usuario no encontrado en la base de datos
            return false;
        }

    }



    public function comprobarHabilitado ($numeroLegajo) {

        $cid = $this->conn->conectar('central');

        $sql = "SELECT HABILITADO AS H FROM RO_T_LEGAJOS_PERSONAL WHERE NRO_LEGAJO = '$numeroLegajo'";

       
        $stmt = sqlsrv_query($cid, $sql);

        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
 
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
 
    
        $habilitado = $row['H'];

        if($habilitado == 'S'){

            return true ;

        }else{

            return false ;

        }


    }

    public function verificarFichaje ($numeroLegajo) {

        $cid = $this->conn->conectar('central');

        $fechaHoy = date('Y-m-d'); 
    
        $sql = "SELECT COUNT(*) AS ExisteRegistro FROM SJ_FICHADAS WHERE LEGAJO = '$numeroLegajo' AND CONVERT(DATE, FECHA_REG) = '$fechaHoy'";
        
        $result = sqlsrv_query($cid, $sql);
    
        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    
        return $row['ExisteRegistro'] > 0 ? 1 : 0;
        

    }


    public function fichar ($numeroLegajo, $sucursal){

        $cid = $this->conn->conectar('central');
    
        $sql = "SET DATEFORMAT YMD
        INSERT INTO SJ_FICHADAS (FECHA_REG, LEGAJO, ENTRADA, SUCURSAL) VALUES ( GETDATE(), '$numeroLegajo', GETDATE(), '$sucursal')";
       
        $result = sqlsrv_query($cid, $sql);

        return true ;

    }


    public function cerrarTurno ($numeroLegajo) {

        $cid = $this->conn->conectar('central');

        $fechaHoy = date('Y-m-d');

        $sql = "
        UPDATE SJ_FICHADAS
        SET SALIDA = GETDATE()
        WHERE LEGAJO = '$numeroLegajo' AND SALIDA IS NULL
        AND ENTRADA = (SELECT MAX(ENTRADA) FROM SJ_FICHADAS WHERE LEGAJO = '$numeroLegajo' AND SALIDA IS NULL);";

        $result = sqlsrv_query($cid, $sql);

        return true;


    }
}