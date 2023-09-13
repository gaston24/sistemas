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

            $sql = "UPDATE sj_reco_locales_enc SET ESTADO = '$estado', UPDATED_AT = GETDATE() WHERE ID = '$numSolicitud' AND ESTADO = '4';";

        }else{

            $sql = "SET DATEFORMAT YMD INSERT INTO sj_reco_locales_enc (NUM_SUC, FECHA, USUARIO_EMISOR, ESTADO) VALUES ('$nroSucursal', '$fecha', '$usuario', '$estado' )";
            
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
            SELECT NRO_SUCURSAL, DESC_SUCURSAL, COD_CLIENT FROM LAKERBIS.LOCALES_LAKERS.DBO.SUCURSALES_LAKERS WHERE CANAL = 'PROPIOS' AND HABILITADO = 1 AND OUTLET = 1
            UNION ALL
            SELECT NRO_SUCURSAL, DESC_SUCURSAL, COD_CLIENT FROM LAKERBIS.LOCALES_LAKERS.DBO.SUCURSALES_LAKERS WHERE NRO_SUCURSAL = '16' OR COD_CLIENT = 'GTCENT'
            ORDER BY DESC_SUCURSAL
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

    
    public function traerUsuariosNotificaSolicitud () 
    {
        $sql = " SELECT DESCRIPCION, MAIL FROM SOF_USUARIOS WHERE TIPO = 'SUPERVISION' AND NOTIFICA = 1";

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

    public function traerMailAutorizaSolicitud ($numSucursal) 
    {

        $sql = "select MAIL from RO_MAILS_LOCALES_PROPIOS WHERE NRO_SUCURS = '$numSucursal'";

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

    public function comprobarStock ($articulo) 
    {

        $sql="SELECT CASE 
                    WHEN A.CANT_STOCK >= $articulo[cantidad] THEN 'True'
                    ELSE 'False'
                END AS TieneStock
            FROM STA19 A
            INNER JOIN (
            SELECT COD_SUCURS 
            FROM STA22 
            WHERE COD_SUCURS LIKE '[0-9]%' 
                AND INHABILITA = 0
            ) B ON A.COD_DEPOSI = B.COD_SUCURS
            WHERE A.COD_ARTICU = '$articulo[articulo]' ;";


            $stmt = sqlsrv_query($this->cid, $sql);

            if ($stmt === false) {
                die("Error en la consulta: " . sqlsrv_errors());
            }
        
            $row = sqlsrv_fetch_array($stmt);
    
            if( $row == null || $row['TieneStock'] == 'False'){


                $tieneStock = false ;
                
            }else{
                
                $tieneStock = true ;
            }
        
            return $tieneStock;
    }


    public function validarCodigosOulet ($articulo)
    {

        $sql="SELECT CASE WHEN EXISTS (
            SELECT 1
            FROM STA19 A
            INNER JOIN (
                SELECT COD_SUCURS
                FROM STA22
                WHERE COD_SUCURS LIKE '[0-9]%' AND INHABILITA = 0
            ) B ON A.COD_DEPOSI = B.COD_SUCURS
            WHERE CANT_STOCK > 0 AND COD_ARTICU LIKE 'O%' and COD_ARTICU = '$articulo'
        ) THEN 'true' ELSE 'false' END AS ArticuloExiste;";


        $stmt = sqlsrv_query($this->cid, $sql);

        if ($stmt === false) {
            die("Error en la consulta: " . sqlsrv_errors());
        }
    
        $row = sqlsrv_fetch_array($stmt);
 
        if( $row == null || $row['ArticuloExiste'] == 'false'){


            $existeArticulo = false ;
            
        }else{
            
            $existeArticulo = true ;
        }
    
        return $existeArticulo;
    }


    public function comprobarArticuloEnRemito ($nComp, $articulo) 
    {

        $conn = new Conexion();
        $cid = $conn->conectar('local');

        $sql="SELECT CASE WHEN EXISTS (
            SELECT 1 FROM STA14 A 
            INNER JOIN STA20 B 
            ON A.ID_STA14 = B.ID_STA14
            WHERE A.FECHA_MOV >= GETDATE()-60 
            AND T_COMP = 'REM'
            AND A.COD_PRO_CL LIKE 'GT%'
            AND A.N_COMP ='$nComp'
            AND B.COD_ARTICU ='$articulo'
        ) THEN 'true' ELSE 'false' END AS ArticuloExiste;";
    

        $stmt = sqlsrv_query($cid, $sql);

        if ($stmt === false) {
            die("Error en la consulta: " . sqlsrv_errors());
        }

        $row = sqlsrv_fetch_array($stmt);
   
        if( $row == null || $row['ArticuloExiste'] == 'false'){


            $existeArticulo = false ;
            
        }else{
            
            $existeArticulo = true ;
        }

        return $existeArticulo;

    }

    public function comprobarArticuloRecodifica ($nComp, $articulo, $cantidad) 
    {

        $conn = new Conexion();
        $cid = $conn->conectar('local');

        $sql="SELECT CASE WHEN EXISTS (
          	 SELECT 1 FROM STA14 A 
            INNER JOIN STA20 B ON A.ID_STA14 = B.ID_STA14
            WHERE A.NCOMP_ORIG ='$nComp'
            AND B.COD_ARTICU ='$articulo'
            AND B.CANTIDAD = '$cantidad'
        ) THEN 'true' ELSE 'false' END AS ArticuloExiste;";
    
         
        $stmt = sqlsrv_query($cid, $sql);

        if ($stmt === false) {
            die("Error en la consulta: " . sqlsrv_errors());
        }

        $row = sqlsrv_fetch_array($stmt);
   
        if( $row == null || $row['ArticuloExiste'] == 'false'){


            $existeArticulo = false ;
            
        }else{
            
            $existeArticulo = true ;
        }

        return $existeArticulo;

    }

    


    public function traerRecodificacionDeArticulos ()
    {

        $sql = "SELECT A.NUM_SUC, CAST(A.FECHA AS DATE) FECHA, B.N_COMP, B.COD_ARTICU, B.DESCRIPCION, B.CANTIDAD, B.NUEVO_CODIGO FROM sj_reco_locales_enc A
        INNER JOIN sj_reco_locales_det B ON A.ID = B.ID_ENC";

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
    
    public function traerRemitosEnElLocal($remitos)
    {
        $conn = new Conexion();
        $cid = $conn->conectar('local');

        $remitosList = implode("', '", $remitos);

        $sql = "SELECT NCOMP_ORIG FROM STA14 WHERE NCOMP_ORIG IN ('$remitosList')";

        $stmt = sqlsrv_query($cid, $sql);
    
        $resultados = [];
    
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $resultados[] = $row["NCOMP_ORIG"];

            }
        } else {

            die(print_r(sqlsrv_errors(), true));
        }
    
        return $resultados;
    }
    
}