<?php

class Remito 
{
    private $dsn = '1 - CENTRAL';
    private $usuario = "sa";
    private $clave="Axoft1988";
    
    
    public function traerHistoricos($codClient, $desde, $hasta){

        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        $sql=
            "
            SET DATEFORMAT YMD

            SELECT 
            
            FECHA_CONTROL, E.DESC_SUCURSAL, CAST(A.FECHA_REM AS DATE) FECHA_REM, 
            NOMBRE_VEN, A.NRO_REMITO, SUM(A.CANT_CONTROL) CANT_CONTROL, SUM(A.CANT_REM) CANT_REM, 
            SUM(A.CANT_CONTROL)-SUM(A.CANT_REM) DIFERENCIA, A.OBSERVAC_LOGISTICA, NRO_AJUSTE, 
            ISNULL((CASE WHEN F.USER_CHAT = 'ramiro' THEN 0 WHEN F.USER_CHAT != 'ramiro' THEN 1 END), 2) ULTIMO_CHAT
                        
            FROM SJ_CONTROL_AUDITORIA A
            
            INNER JOIN GVA23 D
            ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
            INNER JOIN SUCURSAL E
            ON A.SUC_ORIG = E.NRO_SUCURSAL
            
            LEFT JOIN SJ_CONTROL_AUDIRTORIA_CHAT_ULTIMO_MSG F
            ON A.NRO_REMITO = F.NRO_REMITO COLLATE Latin1_General_BIN
            
            WHERE A.COD_CLIENT = '$codClient' 
            AND (CAST( A.FECHA_CONTROL AS DATE) BETWEEN '$desde' AND '$hasta' OR CAST( A.FECHA_REM AS DATE) BETWEEN '$desde' AND '$hasta')
                        
            GROUP BY A.NRO_REMITO, A.FECHA_REM, A.FECHA_CONTROL, NOMBRE_VEN, E.DESC_SUCURSAL, A.OBSERVAC_LOGISTICA, NRO_AJUSTE,
            (CASE WHEN F.USER_CHAT = 'ramiro' THEN 0 WHEN F.USER_CHAT != 'ramiro' THEN 1 END)
            ORDER BY A.FECHA_CONTROL

            ";

        ini_set('max_execution_time', 300);
        $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=odbc_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;
    }


    public function traerHistoricosDetalle($numRem){

        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        $sql=
            "
            SET DATEFORMAT YMD

            SELECT 

            FECHA_CONTROL, CAST(A.FECHA_REM AS DATE) FECHA_REM, 
            NOMBRE_VEN, A.NRO_REMITO, 
            A.COD_ARTICU, B.DESCRIPCIO,
            A.CANT_CONTROL, A.CANT_REM, A.CANT_CONTROL - A.CANT_REM DIFERENCIA
                
            FROM SJ_CONTROL_AUDITORIA A
            LEFT JOIN STA11 B
            ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN
            INNER JOIN GVA23 D
            ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
            WHERE A.NRO_REMITO = '$numRem' 
            ";

        ini_set('max_execution_time', 300);
        $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=odbc_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;
    }    


    public function traerHistoricosAuditoria($desde, $hasta, $estado){

        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        $sql=
            "
            SET DATEFORMAT YMD

            SELECT 
                    
            FECHA_CONTROL, A.SUC_ORIG, A.SUC_DESTIN,  CAST(A.FECHA_REM AS DATE) FECHA_REM, 
            NOMBRE_VEN, A.NRO_REMITO, SUM(A.CANT_CONTROL) CANT_CONTROL, SUM(A.CANT_REM) CANT_REM, 
            SUM(A.CANT_CONTROL)-SUM(A.CANT_REM) DIFERENCIA, A.OBSERVAC_LOGISTICA, NRO_AJUSTE, 
            ISNULL((CASE WHEN E.USER_CHAT = 'ramiro' THEN 0 WHEN E.USER_CHAT != 'ramiro' THEN 1 END), 2) ULTIMO_CHAT
                                        
            FROM SJ_CONTROL_AUDITORIA A
                                
            INNER JOIN GVA23 D
            ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
            
            LEFT JOIN SJ_CONTROL_AUDIRTORIA_CHAT_ULTIMO_MSG E
            ON A.NRO_REMITO = E.NRO_REMITO COLLATE Latin1_General_BIN
                    
            WHERE A.FECHA_REM >= GETDATE()-180
            AND (CAST( A.FECHA_CONTROL AS DATE) BETWEEN '$desde' AND '$hasta' OR CAST( A.FECHA_REM AS DATE) BETWEEN '$desde' AND '$hasta')
            AND A.OBSERVAC_LOGISTICA LIKE '$estado'
                            
            GROUP BY A.NRO_REMITO, A.FECHA_REM, A.FECHA_CONTROL, NOMBRE_VEN, A.COD_CLIENT, 
            A.SUC_ORIG, A.SUC_DESTIN, A.OBSERVAC_LOGISTICA, NRO_AJUSTE, (CASE WHEN E.USER_CHAT = 'ramiro' THEN 0 WHEN E.USER_CHAT != 'ramiro' THEN 1 END)
            ORDER BY A.FECHA_REM            
            ";

        ini_set('max_execution_time', 300);
        $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=odbc_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;
    }   


}