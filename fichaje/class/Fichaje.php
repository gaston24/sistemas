<?php

class Fichaje {

    function __construct(){

        require_once __DIR__.'/../../class/conexion.php';
        $this->conn = new Conexion;
        session_start();
        $db = 'central';

        $this->cid = $this->conn->conectar($db);
        
    }

    public function buscarPorCampo($campo){


        $sql = "SELECT NRO_LEGAJO, APELLIDO_Y_NOMBRE FROM RO_T_LEGAJOS_PERSONAL 
        WHERE NRO_LEGAJO LIKE  '%$campo%'
        OR APELLIDO LIKE '%$campo%'
        OR NOMBRE LIKE '%$campo%'
        ";

        $result = sqlsrv_query($this->cid, $sql);

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


        $sql = "SELECT NRO_LEGAJO, CONTRASEÑA FROM RO_T_LEGAJOS_PERSONAL WHERE NRO_LEGAJO = '$numeroLegajo'";
    
        $result = sqlsrv_query($this->cid, $sql);

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

    
        $sql = "SELECT HABILITADO AS H FROM RO_T_LEGAJOS_PERSONAL WHERE NRO_LEGAJO = '$numeroLegajo'";

       
        $stmt = sqlsrv_query($this->cid, $sql);

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


        $sql = "set dateformat ymd
        SELECT COUNT(*) AS ExisteRegistro FROM SJ_FICHADAS WHERE LEGAJO = '$numeroLegajo' AND CAST(FECHA_REG AS DATE) = CAST(GETDATE() AS DATE)  AND SALIDA IS NULL";
    
        $result = sqlsrv_query($this->cid, $sql);
    
        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        $res = ($row['ExisteRegistro'] > 0) ? 1 : 0;

        return $res;

    }


    public function fichar ($numeroLegajo, $sucursal){
   
        $sql ="EXEC FU_SP_FICHAR '$numeroLegajo', '$sucursal'";
        
        try {

            $result = sqlsrv_query($this->cid, $sql); 
            $next_result = sqlsrv_next_result($result);
            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }
            return $v[0];


        } catch (\Throwable $th) {

            print_r($th);

        }

    }


    public function cerrarTurno ($numeroLegajo) {


        $fechaHoy = date('Y-m-d');

        $sql = "
        set dateformat ymd

        UPDATE SJ_FICHADAS
        SET SALIDA = GETDATE()
        WHERE LEGAJO = '$numeroLegajo' AND SALIDA IS NULL
        AND ENTRADA = (SELECT MAX(ENTRADA) FROM SJ_FICHADAS WHERE LEGAJO = '$numeroLegajo' AND SALIDA IS NULL);";
       
        $result = sqlsrv_query($this->cid, $sql);

        return true;


    }

    public function traerReporteDeAsistencias ($desde, $hasta, $usuario, $sucursal) {


        $sql = "SELECT * 
        FROM Reporte_Fichadas_CALENDAR 
        WHERE CONVERT(DATE, FECHA_REG, 103) BETWEEN '$desde' AND '$hasta'
          AND (NRO_LEGAJO LIKE '%$usuario%' OR APELLIDO_Y_NOMBRE LIKE '%$usuario%')
          AND SUCURSAL LIKE '%$sucursal%'
          AND SUCURSAL != ''
        ORDER BY CONVERT(DATE, FECHA_REG, 103) DESC;
        
        ";
      
  
        $result = sqlsrv_query($this->cid, $sql);

        if($result === false){
            die( print_r( sqlsrv_errors(), true));
        }

        $data = array();

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $data[] = $row;
        }

        return $data;

    }
    public function traerUsuarios () {

        $sql ="SELECT NRO_LEGAJO, APELLIDO_Y_NOMBRE FROM RO_T_LEGAJOS_PERSONAL WHERE HABILITADO = 'S' ORDER BY APELLIDO_Y_NOMBRE ASC";


        $result = sqlsrv_query($this->cid, $sql);

        if($result === false){
            die( print_r( sqlsrv_errors(), true));
        }

        $data = array();

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $data[] = $row;
        }

        return $data;

    }

    public function traerLocales (){

        $sql ="SELECT  * FROM SOF_USUARIOS WHERE TIPO = 'LOCAL_PROPIO' AND NRO_SUCURS NOT IN ('111', '702')  ORDER BY NRO_SUCURS ASC";

        $result = sqlsrv_query($this->cid, $sql);

        if($result === false){
            die( print_r( sqlsrv_errors(), true));
        }

        $data = array();

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $data[] = $row;
        }

        return $data;
    }
    
}