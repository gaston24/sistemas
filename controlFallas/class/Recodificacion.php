<?php


class Recodificacion
{

    private $consulta;
    
    function __construct() 
    {
        
        require_once $_SERVER['DOCUMENT_ROOT'] .'/sistemas/class/conexion.php';
        
        $conn = new Conexion();
        $this->cid = $conn->conectar('central');

    }
    
    public function traerNumSolicitud() 
    {   
        $sql = "SELECT MAX(ID) AS ultimo_id FROM sj_reco_locales_enc";
        try {

            $result = sqlsrv_query($this->cid, $sql); 
            
            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function insertarEncabezado($numSolicitud, $nroSucursal, $fecha, $usuario, $estado ) 
    {   

        $sql = "DELETE FROM sj_reco_locales_enc WHERE ID = $numSolicitud AND ESTADO = '4';";

        $result = sqlsrv_query($this->cid, $sql);

        $sql = "SET DATEFORMAT YMD INSERT INTO sj_reco_locales_enc (NUM_SUC, FECHA, USUARIO_EMISOR, ESTADO) VALUES ($nroSucursal, '$fecha', '$usuario', $estado )";

        
        try {

            $result = sqlsrv_query($this->cid, $sql); 

            $sql = "SELECT SCOPE_IDENTITY() AS ultimo_id;";

            $result = sqlsrv_query($this->cid, $sql);

            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

            $ultimo_id = $row['ultimo_id'];

            return $ultimo_id;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function insertarEncabezadoTemp($id, $nroSucursal, $fecha, $usuario, $estado ) 
    {   

        $sql="DELETE FROM sj_reco_locales_enc_temp WHERE ID = $id;";
        $result = sqlsrv_query($this->cid, $sql);

        $sql = "INSERT INTO sj_reco_locales_enc_temp (ID, NUM_SUC, FECHA, USUARIO_EMISOR, ESTADO) VALUES ($id, $nroSucursal, '$fecha', '$usuario', '4')";

        try {

            $result = sqlsrv_query($this->cid, $sql); 

            $sql = "SELECT MAX(ID) AS ultimo_id
            FROM sj_reco_locales_enc_temp;";

            $result = sqlsrv_query($this->cid, $sql);

            $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
          
            $ultimo_id = $row['ultimo_id'];

            return $ultimo_id;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function insertarDetalle($valores ) 
    {   

        $sql = "INSERT INTO sj_reco_locales_det (ID_ENC, COD_ARTICU, DESCRIPCION, PRECIO, CANTIDAD, DESC_FALLA) VALUES $valores";
        try {

            $result = sqlsrv_query($this->cid, $sql); 

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function insertarDetalleTemp($valores, $id) 
    {   

        $sql="DELETE FROM sj_reco_locales_det_temp WHERE ID_ENC = $id;";
        $result = sqlsrv_query($this->cid, $sql);

        $sql = "INSERT INTO sj_reco_locales_det_temp (ID_ENC, COD_ARTICU, DESCRIPCION, PRECIO, CANTIDAD, DESC_FALLA) VALUES $valores";
        try {

            $result = sqlsrv_query($this->cid, $sql); 

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function buscarBorradorEnc($numSolicitud,$estado) 
    {   
        $sql = "SELECT * FROM sj_reco_locales_enc where ID = $numSolicitud AND ESTADO = '$estado'";

        try {

            $result = sqlsrv_query($this->cid, $sql); 

            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th); 

        }
    }

    public function buscarBorradorDet($numSolicitud) 
    {   
        $sql = "SELECT * FROM sj_reco_locales_det where ID_ENC = $numSolicitud";
        try {

            $result = sqlsrv_query($this->cid, $sql); 

            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th); 

        }
    }


    public function traerSolicitudes($nroSucursal, $desde, $hasta, $estado) 
    {   

        $sql = "
        SET DATEFORMAT YMD
        SELECT 
        enc.ID ,
        enc.FECHA,
        enc.USUARIO_EMISOR,
        enc.ESTADO,
        enc.NUM_SUC,
        enc.UPDATED_AT,
        SUM(det.cantidad) AS cantidad_total_articulos
        FROM sj_reco_locales_enc AS enc
        JOIN sj_reco_locales_det AS det ON enc.id = det.ID_ENC
        WHERE enc.NUM_SUC ='$nroSucursal' 
        AND enc.FECHA BETWEEN '$desde' AND '$hasta'
        AND enc.ESTADO LIKE '%$estado%'
        GROUP BY enc.ID, enc.FECHA, enc.USUARIO_EMISOR, enc.ESTADO, enc.NUM_SUC, enc.UPDATED_AT";

        try {

            $result = sqlsrv_query($this->cid, $sql); 
            
            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

  

}