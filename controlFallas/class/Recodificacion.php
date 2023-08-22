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

    public function insertarEncabezado($numSolicitud, $nroSucursal, $fecha, $usuario, $estado ,$borrador = false) 
    {   

        if($borrador != "false"){

            $sql = "UPDATE sj_reco_locales_enc SET ESTADO = $estado, UPDATED_AT = GETDATE() WHERE ID = $numSolicitud AND ESTADO = '4';";

        }else{

            $sql = "SET DATEFORMAT YMD INSERT INTO sj_reco_locales_enc (NUM_SUC, FECHA, USUARIO_EMISOR, ESTADO) VALUES ($nroSucursal, '$fecha', '$usuario', $estado )";
            
        }
 
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


    public function insertarDetalle($valores) 
    {   

        $sql = "INSERT INTO sj_reco_locales_det (ID_ENC, COD_ARTICU, DESCRIPCION, PRECIO, CANTIDAD, DESC_FALLA) VALUES $valores";
        try {

            $result = sqlsrv_query($this->cid, $sql); 

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function borrarDetalle($idEnc) 
    {   

        $sql = "DELETE FROM sj_reco_locales_det WHERE ID_ENC = $idEnc";

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


    public function traerSolicitudes($nroSucursal = null, $desde, $hasta, $estado, $sup = null) 
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
        WHERE enc.FECHA BETWEEN '$desde' AND '$hasta'
        AND enc.ESTADO LIKE '%$estado%'";

        if($nroSucursal != null){
            $sql .= "AND enc.NUM_SUC = '$nroSucursal'";
        }

        if($sup == 1){
            $sql .= "AND enc.ESTADO != '4'";
        }
        $sql .= "
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
    
    public function traerLocales() 
    {   

        $sql = "
            SELECT NRO_SUCURSAL, DESC_SUCURSAL, COD_CLIENT FROM LAKERBIS.LOCALES_LAKERS.DBO.SUCURSALES_LAKERS WHERE CANAL = 'PROPIOS' AND HABILITADO = 1
            UNION ALL
            SELECT NRO_SUCURSAL, DESC_SUCURSAL, COD_CLIENT FROM LAKERBIS.LOCALES_LAKERS.DBO.SUCURSALES_LAKERS WHERE NRO_SUCURSAL = '16' OR COD_CLIENT = 'GTCENT'
            ORDER BY NRO_SUCURSAL
        ";

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

    public function traerEncabezado ($numSolicitud) {

        $sql = "SELECT * FROM sj_reco_locales_enc where ID = $numSolicitud";

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

    public function traerDetalle ($numSolicitud) {
            
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

    public function traerCodigoRecodificacion($valor, $codArticulo) 
    {   
        $sql = "EXEC RO_SP_RECODIFICAR_OUTLET '$codArticulo', $valor";
 
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

    public function traerRemitos($nroSucursal,$destino) 
    {   
        $sql = "SET DATEFORMAT YMD 
		SELECT FECHA_MOV, NRO_SUCURS, N_COMP, COD_PRO_CL FROM [LAKERBIS].locales_lakers.dbo.CTA09 
        WHERE T_COMP = 'REM' AND COD_PRO_CL LIKE 'GT%'
        AND FECHA_MOV >= GETDATE()-60 AND NRO_SUCURS = '$nroSucursal' AND COD_PRO_CL LIKE '%$destino%'";
 
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

    public function autorizar ($numSolicitud)
    {
        $sql = "UPDATE sj_reco_locales_enc SET ESTADO = '2' WHERE ID = $numSolicitud";

        try {
    
            $result = sqlsrv_query($this->cid, $sql);

            return true;

        } catch (\Throwable $th) {

            print_r($th); 

        }
    }


    public function actualizarDetalle ($precio, $nuevoCodigo, $destino, $observaciones, $id)
    {
      
        $sql = "UPDATE sj_reco_locales_det SET PRECIO = '$precio', NUEVO_CODIGO = '$nuevoCodigo', UPDATED_AT = GETDATE(), DESTINO = '$destino', OBSERVACIONES = '$observaciones' WHERE ID = $id";
        
        try {

    
            $result = sqlsrv_query($this->cid, $sql);

            return true;

        } catch (\Throwable $th) {

            print_r($th); 

        }
    }

    public function enviar ($numSolicitud)
    {
        $sql = "UPDATE sj_reco_locales_enc SET ESTADO = '3' WHERE ID = $numSolicitud";

        try {
    
            $result = sqlsrv_query($this->cid, $sql);

            return true;

        } catch (\Throwable $th) {

            print_r($th); 

        }
    }

    public function cargarRemito ($id, $numRemito)
    {
      
        $sql = "UPDATE sj_reco_locales_det SET N_COMP = '$numRemito' WHERE ID = $id";
        
        try {

    
            $result = sqlsrv_query($this->cid, $sql);

            return true;

        } catch (\Throwable $th) {

            print_r($th); 

        }
    }

    
   
}