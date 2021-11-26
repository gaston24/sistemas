<?php

class Remito 
{
    private $dsn = '1 - CENTRAL';
    private $usuario = "sa";
    private $clave="Axoft1988";

    private function getDatos($sql){

        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        ini_set('max_execution_time', 300);
        $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=odbc_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;

    }

    private function insertDatos($sql){
        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);
        ini_set('max_execution_time', 300);
        odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
    }
    
    
    public function traerHistoricos($codClient, $desde, $hasta){

        $sql=

            "
            SET DATEFORMAT YMD

            SELECT 
            
            FECHA_CONTROL, E.DESC_SUCURSAL, CAST(A.FECHA_REM AS DATE) FECHA_REM, 
            NOMBRE_VEN, A.NRO_REMITO, SUM(A.CANT_CONTROL) CANT_CONTROL, SUM(A.CANT_REM) CANT_REM, 
            SUM(CASE WHEN CANT_REM <> CANT_CONTROL THEN 1 ELSE 0 END) DIFERENCIA, 
            A.OBSERVAC_LOGISTICA, NRO_AJUSTE, 
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

        $array = $this->getDatos($sql);    

        return $array;
    }

    public function traerHistoricosDetalle($numRem){

        $sql=
            "
            SET DATEFORMAT YMD

            SELECT 
            COD_CLIENT, SUC_DESTIN, 
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

        $array = $this->getDatos($sql);    

        return $array;
    }    

    public function traerHistoricosAuditoria($desde, $hasta, $estado){

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
            ORDER BY A.FECHA_CONTROL            
            ";

        $array = $this->getDatos($sql);    

        return $array;
    }   

    public function verificacion($user){

        $sql=
        "
        SELECT ISNULL(SUM(CANT_CONTROL), 0)VERIFICACION FROM SJ_CONTROL_LOCAL 
        WHERE COD_CLIENT = '$user'
        ";

        $array = $this->getDatos($sql);    

        return $array;
    } 

    public function buscarSinonimo($codigo){

        $sql=
        "
        SET DATEFORMAT YMD
	    SELECT COD_ARTICU FROM STA11 WHERE COD_ARTICU = '$codigo' OR SINONIMO = '$codigo'
        ";

        $array = $this->getDatos($sql);    

        return $array;
    } 

    public function insertarControlLocal($user, $rem, $codigo, $user_local){

        $sql=
        "
        SET DATEFORMAT YMD
        INSERT INTO SJ_CONTROL_LOCAL
        (FECHA_CONTROL, COD_CLIENT, NRO_REMITO, COD_ARTICU, CANT_CONTROL, USUARIO_LOCAL)
        VALUES (GETDATE(), '$user', '$rem', '$codigo', 1, '$user_local')
        ";
        $this->insertDatos($sql);
        
    } 

    public function wrongCode($codigo){
        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        $sqlValida =
            "
            SET DATEFORMAT YMD
            SELECT COD_ARTICU FROM STA11 WHERE COD_ARTICU = '$codigo'
            ";

        ini_set('max_execution_time', 300);
        $resultValida = odbc_exec($cid, $sqlValida) or die(exit("Error en odbc_exec"));

        if (odbc_num_rows($resultValida) == 0) {
            return '
            <audio src="Wrong.ogg" autoplay></audio>
            </br></br>
            <div class="alert alert-danger" role="alert" style="margin-left:15%; margin-right:15%">
            ATENCION!! El codigo <strong>' . strtoupper($codigo) . '</strong> no existe
            </div>';
        }else{
            return '';
        }
    } 

    public function traerControladoTemporal($user){

        $sql=
            "
            SET DATEFORMAT YMD

				SELECT A.COD_ARTICU, A.CANT_CONTROL, B.DESCRIPCIO 
				FROM
				(
					SELECT COD_ARTICU, SUM(CANT_CONTROL)CANT_CONTROL FROM SJ_CONTROL_LOCAL 
					WHERE COD_CLIENT = '$user'
					GROUP BY COD_ARTICU
				)A
				INNER JOIN STA11 B
				ON A.COD_ARTICU COLLATE Latin1_General_BIN= B.COD_ARTICU COLLATE Latin1_General_BIN
            ";
        $array = $this->getDatos($sql);    

        return $array;
    }  

    public function traerHistorial($user){

        $sql=
            "
            SET DATEFORMAT YMD
            SELECT COD_ARTICU FROM SJ_CONTROL_LOCAL 
            WHERE COD_CLIENT = '$user'
            AND COD_ARTICU IN (SELECT COD_ARTICU COLLATE Latin1_General_BIN FROM STA11)
            ORDER BY ID DESC
            ";

        $array = $this->getDatos($sql);    

        return $array;
    } 

}