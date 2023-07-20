<?php

class Remito {

    function __construct(){

        require_once __DIR__.'/conexion.php';
        $this->conn = new Conexion;
        
    }

    private function getArray($sql){

        $cid = $this->conn->conectar('central');

        try {
            $stmt = sqlsrv_query($cid, $sql);

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;
                
            }

            return $v;

        }
        catch (\Throwable $th) {
            return ("Error en sqlsrv_exec");
        };

    }

    private function insertDatos($sql){
        $cid = $this->conn->conectar('central');
        try{
            sqlsrv_exec($cid,$sql);
        } catch (\Throwable $th) {
            die("Error en sqlsrv_exec");
        }
    }
    
    public function listarUsuarios($nroSucurs){

        $sql = " SELECT C.NOMBRE, C.APELLIDO, A.BLOQUE, B.DESC_SUCURSAL, A.XML_CA_1118_NUM_SUCURSAL NRO_SUCURSAL FROM 
            (
            SELECT COD_VENDED BLOQUE,
            GVA23_CAMPOS_ADICIONALES.XML_CA.value('CA_1118_NUM_SUCURSAL', 'VARCHAR(6)') XML_CA_1118_NUM_SUCURSAL FROM GVA23
            OUTER APPLY GVA23.CAMPOS_ADICIONALES.nodes('CAMPOS_ADICIONALES') as GVA23_CAMPOS_ADICIONALES(XML_CA)
            ) A
            INNER JOIN (SELECT * FROM [LAKERBIS].LOCALES_LAKERS.DBO.SUCURSALES_LAKERS WHERE CANAL = 'PROPIOS') B ON A.XML_CA_1118_NUM_SUCURSAL = B.NRO_SUCURSAL
            INNER JOIN [TANGO-SUELDOS].LAKERS_CORP_SA.DBO.LEGAJO C ON A.BLOQUE = C.BLOQUE COLLATE Latin1_General_BIN
            WHERE XML_CA_1118_NUM_SUCURSAL IS NOT NULL AND XML_CA_1118_NUM_SUCURSAL = $nroSucurs
            ORDER BY APELLIDO
        ";

        $result = $this->getArray($sql);
        return $result;

    }

    public function traerHistoricos($codClient, $desde, $hasta){
       
        $sql=

        "
        SET DATEFORMAT YMD

        SELECT FECHA_CONTROL, DESC_SUCURSAL, FECHA_REM, NOMBRE_VEN, NRO_REMITO, SUM(CANT_CONTROL) CANT_CONTROL, SUM(CANT_REM) CANT_REM, SUM(DIFERENCIA) DIFERENCIA, OBSERVAC_LOGISTICA, NRO_AJUSTE, ULTIMO_CHAT FROM 
		(
			SELECT CAST(STUFF(STUFF(CONVERT(VARCHAR(10), FECHA_CONTROL, 112),5,0,'-'),8,0,'-') AS DATE) FECHA_CONTROL, D.DESC_SUCURSAL, CAST(A.FECHA_REM AS DATE) FECHA_REM, NOMBRE_VEN, A.NRO_REMITO, A.SUC_ORIG, A.SUC_DESTIN, 
			A.CANT_CONTROL, A.CANT_REM, A.CANT_CONTROL-A.CANT_REM DIFERENCIA, A.USUARIO_LOCAL, A.OBSERVAC_LOGISTICA, NRO_AJUSTE,
			ISNULL((CASE WHEN C.USER_CHAT IN ('ramiro','eduardo','Agustinal') THEN 0 WHEN C.USER_CHAT NOT IN ('ramiro','eduardo','Agustinal') THEN 1 END), 2) ULTIMO_CHAT
			FROM SJ_CONTROL_AUDITORIA A
			INNER JOIN GVA23 B ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = B.COD_VENDED
			LEFT JOIN SJ_CONTROL_AUDIRTORIA_CHAT_ULTIMO_MSG C ON A.NRO_REMITO = C.NRO_REMITO COLLATE Latin1_General_BIN
			LEFT JOIN SUCURSAL D ON A.SUC_ORIG = D.NRO_SUCURSAL
			WHERE A.COD_CLIENT LIKE '$codClient' --AND A.NRO_REMITO = 'R0014500024535'
		) A
		WHERE FECHA_REM >= GETDATE()-180 AND (CAST( A.FECHA_CONTROL AS DATE) BETWEEN '$desde' AND '$hasta')
		GROUP BY FECHA_CONTROL, DESC_SUCURSAL, FECHA_REM, NOMBRE_VEN, NRO_REMITO, OBSERVAC_LOGISTICA, NRO_AJUSTE, ULTIMO_CHAT
		ORDER BY FECHA_CONTROL

        ";

        $array = $this->getArray($sql);    

        return $array;

    }

    public function traerHistoricosDetalle($numRem){

        $sql=
            "
            SET DATEFORMAT YMD

            SELECT A.*, B.DESCRIPCIO FROM
            (
                SELECT ISNULL(COD_CLIENT, COD_PRO_CL) COD_CLIENT, ISNULL(A.SUC_DESTIN, B.SUC_DESTIN) SUC_DESTIN, FECHA_CONTROL, ISNULL(FECHA_REM, B.FECHA_MOV) FECHA_REM, NOMBRE_VEN, A.NRO_REMITO, ISNULL(A.COD_ARTICU, B.COD_ARTICULO) COD_ARTICU, ISNULL(A.CANT_CONTROL, 0) CANT_CONTROL, 
                ISNULL(A.CANT_REM, B.CANTIDAD) CANT_REM, ISNULL(DIFERENCIA, 0-B.CANTIDAD) DIFERENCIA, ISNULL(PARTIDA, '') PARTIDA, CASE WHEN A.COD_ARTICU IS NULL THEN 0 ELSE 1 END AUDITADO  FROM 
                (
                    SELECT COD_CLIENT, SUC_DESTIN, FECHA_CONTROL, FECHA_REM, NOMBRE_VEN, NRO_REMITO, COD_ARTICU, MAX(CANT_CONTROL) CANT_CONTROL, SUM(CANT_REM) CANT_REM, MAX(CANT_CONTROL) - SUM(CANT_REM) DIFERENCIA, MAX(PARTIDA) PARTIDA
                    FROM
                    (
                        SELECT 
                        COD_CLIENT, SUC_DESTIN, 
                        FECHA_CONTROL, CAST(A.FECHA_REM AS DATE) FECHA_REM, 
                        NOMBRE_VEN, A.NRO_REMITO, 
                        A.COD_ARTICU, 
                        A.CANT_CONTROL, A.CANT_REM, A.CANT_CONTROL - A.CANT_REM DIFERENCIA, ISNULL(E.ULTIMA_PARTIDA, '') PARTIDA
                                                                
                        FROM SJ_CONTROL_AUDITORIA A
                                    
                        INNER JOIN GVA23 D
                        ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = D.COD_VENDED
                        LEFT JOIN SOF_PARTIDAS E
                        ON A.COD_ARTICU = E.COD_ARTICU COLLATE Latin1_General_BIN
                        WHERE A.NRO_REMITO = '$numRem' 
                        AND A.COD_ARTICU LIKE '[XO]%'
                    ) A
                    GROUP BY COD_CLIENT, SUC_DESTIN, FECHA_CONTROL, FECHA_REM, NOMBRE_VEN, NRO_REMITO, COD_ARTICU
                ) A
                FULL OUTER JOIN 
                (
                    SELECT DISTINCT B.FECHA_MOV, A.N_COMP, A.COD_PRO_CL, A.SUC_DESTIN, B.COD_ARTICU COD_ARTICULO, B.CANTIDAD from STA14 A
                    INNER JOIN STA20 B ON A.TCOMP_IN_S = B.TCOMP_IN_S AND A.NCOMP_IN_S = B.NCOMP_IN_S
                    INNER JOIN (SELECT COD_ARTICU, DESCRIPCIO FROM STA11 WHERE PROMO_MENU != 'P') C ON B.COD_ARTICU = C.COD_ARTICU
                    WHERE A.N_COMP = '$numRem'
                    AND B.COD_ARTICU LIKE '[XO]%'
                ) B
                ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICULO COLLATE Latin1_General_BIN 
            ) A
                LEFT JOIN STA11 B
            ON A.COD_ARTICU COLLATE Latin1_General_BIN = B.COD_ARTICU COLLATE Latin1_General_BIN
            ";

        $array = $this->getArray($sql);    

        return $array;
    }    

    public function verificacion($user){

        $cid = $this->conn->conectar('central');

        
        $sql=
        "
        SELECT ISNULL(SUM(CANT_CONTROL), 0)VERIFICACION FROM SJ_CONTROL_LOCAL 
        WHERE COD_CLIENT = '$user'
        ";

        try {
            $stmt = sqlsrv_query($cid, $sql);

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;
                
            }

            return $v;

        }
        catch (\Throwable $th) {
            return ("Error en sqlsrv_exec");
        };

    } 
    
    public function buscarSinonimo($codigo){

        $sql=
        "
        SET DATEFORMAT YMD
	    SELECT COD_ARTICU FROM STA11 WHERE COD_ARTICU = '$codigo' OR SINONIMO = '$codigo'
        ";

        $array = $this->getArray($sql);    

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
        
        $cid = $this->conn->conectar('central');

        $sqlValida =
            "
            SET DATEFORMAT YMD
            SELECT COD_ARTICU FROM STA11 WHERE COD_ARTICU = '$codigo'
            ";

       
        $resultValida = sqlsrv_exec($cid, $sqlValida);

        if (sqlsrv_num_rows($resultValida) == 0) {
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
        $array = $this->getArray($sql);    

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

        $array = $this->getArray($sql);    

        return $array;
    } 

    // NEWS

    public function buscarRemitoPorLocal($rem){
        $cid = $this->conn->conectar('local');

        if(!$cid) return false;

        $sql = "SELECT * FROM CTA115 WHERE N_COMP = '$rem'";

        try {
            $stmt = sqlsrv_query($cid, $sql);

            try {
    
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
                    $v[] = $row;
    
                }
    
                sqlsrv_close($cid);
        
                return $v;
    
            } catch (\Throwable $th) {
    
                print_r($th);
    
            }
    
        }
        catch (\Throwable $th) {
            return ("Error en sqlsrv_exec");
        };
    }

    public function deleteControlRemitoTables($user){

        $cid = $this->conn->conectar('central');
        

        $sql = "EXEC SJ_DELETE_CONTROL_LOCAL_TABLES '$user'";

        try {

            $stmt = sqlsrv_prepare($cid, $sql);
            $stmt = sqlsrv_execute($stmt);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }
    
            
    }

    public function traerMaestroDeArticulos(){

        $cid = $this->conn->conectar('central');

        $sql = "SELECT COD_ARTICU, SINONIMO, DESCRIPCIO 
                FROM STA11 
                WHERE COD_ARTICU LIKE '[XO]%'
                AND USA_ESC != 'B'
                AND PERFIL != 'N'
                ";

        try {
            $stmt = sqlsrv_query($cid, $sql);

            try {
    
                $rows = array();

                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
                    $rows[] = array(
                        'COD_ARTICU' => $row['COD_ARTICU'],
                        'SINONIMO' => $row['SINONIMO'],
                        'DESCRIPCIO' => str_replace('"','',$row['DESCRIPCIO']),
                    );
    
                }
    
                sqlsrv_close($cid);
        
                return json_encode($rows);
    
            } catch (\Throwable $th) {
    
                print_r($th);
    
            }
    
        }
        catch (\Throwable $th) {
            return ("Error en sqlsrv_exec");
        };
    }

    // CONTROL REMITOS

    public function marcarRemitoRegistrado($rem){

        $cid = $this->conn->conectar('local');
        

        $sql = "UPDATE CTA115 SET TALONARIO = 1 WHERE N_COMP = '$rem'";

        try {

            $stmt = sqlsrv_prepare($cid, $sql);
            $stmt = sqlsrv_execute($stmt);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }
    
            
    }

    public function traerTodosLosArticulosRemito($rem){

        $cid = $this->conn->conectar('local');
        

        $sql = "SELECT B.COD_ARTICU, CAST(SUM(B.CANTIDAD) AS FLOAT) CANTIDAD FROM STA14 A 
                INNER JOIN STA20 B ON A.ID_STA14 = B.ID_STA14
                INNER JOIN STA11 C ON B.COD_ARTICU = C.COD_ARTICU
                WHERE A.NCOMP_ORIG = '$rem' AND C.PROMO_MENU != 'P'
                GROUP BY B.COD_ARTICU
        ";

        try {

            $stmt = sqlsrv_query($cid, $sql);

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;

            }

            sqlsrv_close($cid);
    
            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
    
            
    }

    public function insertarAuditoria($fechaRem, $codClient, $rem, $sucOrig, $sucDestin, $codArticu, $cantRem, $cantControl, $vendedor, $status){
        $cid = $this->conn->conectar('central');

        $sql = " SET DATEFORMAT YMD
         INSERT INTO SJ_CONTROL_AUDITORIA
        (
            FECHA_CONTROL, COD_CLIENT, FECHA_REM, NRO_REMITO, SUC_ORIG, 
            SUC_DESTIN, COD_ARTICU, CANT_REM, CANT_CONTROL, USUARIO_LOCAL, 
            OBSERVAC_LOGISTICA
        )
        VALUES
        (
            getdate(), '$codClient', '$fechaRem', '$rem', $sucOrig,
            $sucDestin, '$codArticu', $cantRem, $cantControl, '$vendedor', 
            '$status'
        )";

        try {
    
    
            
            $stmt = sqlsrv_prepare($cid, $sql);
            $stmt = sqlsrv_execute($stmt);

            return true;

        } catch (\Throwable $th) {
            print_r($th);

        }

        
    } 

    public function traerHistoricosAuditoria($desde, $hasta, $estado){

        $cid = $this->conn->conectar('central');
        

        $sql = 
        "
        SELECT FECHA_CONTROL, MAX(HORA) HORA, COD_CLIENT, SUC_ORIG, SUC_DESTIN, FECHA_REM, NOMBRE_VEN, NRO_REMITO, SUM(CANT_CONTROL) CANT_CONTROL, SUM(CANT_REM) CANT_REM, SUM(DIFERENCIA) DIFERENCIA, OBSERVAC_LOGISTICA,
		NRO_AJUSTE, ULTIMO_CHAT FROM 
		(
			SELECT CAST(STUFF(STUFF(CONVERT(VARCHAR(10), FECHA_CONTROL, 112),5,0,'-'),8,0,'-') AS DATE) FECHA_CONTROL, CONVERT(CHAR(5), FECHA_CONTROL, 108) HORA,
			A.COD_CLIENT, A.SUC_ORIG, A.SUC_DESTIN,  CAST(A.FECHA_REM AS DATE) FECHA_REM, NOMBRE_VEN, A.NRO_REMITO,
			A.CANT_CONTROL, A.CANT_REM, A.CANT_CONTROL-A.CANT_REM DIFERENCIA, A.OBSERVAC_LOGISTICA, NRO_AJUSTE,
			ISNULL((CASE WHEN C.USER_CHAT IN ('ramiro','eduardo','Agustinal') THEN 0 WHEN C.USER_CHAT NOT IN ('ramiro','eduardo','Agustinal') THEN 1 END), 2) ULTIMO_CHAT
			FROM SJ_CONTROL_AUDITORIA A
			INNER JOIN GVA23 B ON A.USUARIO_LOCAL COLLATE Latin1_General_BIN = B.COD_VENDED
			LEFT JOIN SJ_CONTROL_AUDIRTORIA_CHAT_ULTIMO_MSG C ON A.NRO_REMITO = C.NRO_REMITO COLLATE Latin1_General_BIN
			--WHERE A.NRO_REMITO = 'R0014500024543'
		) A
		WHERE OBSERVAC_LOGISTICA LIKE '$estado' AND FECHA_REM >= GETDATE()-180 AND (CAST( A.FECHA_CONTROL AS DATE) BETWEEN '$desde' AND '$hasta')
		GROUP BY FECHA_CONTROL, COD_CLIENT, SUC_ORIG, SUC_DESTIN, FECHA_REM, NOMBRE_VEN, NRO_REMITO, OBSERVAC_LOGISTICA, NRO_AJUSTE, ULTIMO_CHAT
		ORDER BY FECHA_CONTROL, HORA  
        ";
        
        try {

            $v = array();

            $stmt = sqlsrv_query($cid, $sql);

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;

            }

            sqlsrv_close($cid);
    
            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
    }   

    // HISTORIAL REMITOS

    public function ajusteRemitoNumero($ajuste, $ncomp){

        $cid = $this->conn->conectar('central');
       

        $sql = "	
        SET DATEFORMAT YMD
        UPDATE SJ_CONTROL_AUDITORIA SET NRO_AJUSTE = '$ajuste' WHERE NRO_REMITO = '$ncomp'
        ";

        try {

            $stmt = sqlsrv_prepare($cid, $sql);
            $stmt = sqlsrv_execute($stmt);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

        
    } 

    public function ajusteRemitoStatus($ncomp){

        $cid = $this->conn->conectar('central');


        $sql = 
        "
        SELECT distinct OBSERVAC_LOGISTICA FROM SJ_CONTROL_AUDITORIA where NRO_REMITO = '$ncomp'
        ";

        try {

            $data = array();

            $stmt = sqlsrv_query($cid, $sql);

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
                $data[] = $row;

            }


        } catch (\Throwable $th) {

            print_r($th);

        }

        $estados = array();

        foreach($data as $value){
            $estados[] = $value['OBSERVAC_LOGISTICA'];
        } 
       
        if(in_array("PENDIENTE", $estados)){

            $sql = "	
            SET DATEFORMAT YMD
    
            UPDATE SJ_CONTROL_AUDITORIA SET OBSERVAC_LOGISTICA = 'PENDIENTE' WHERE NRO_REMITO = '$ncomp'
            ";
    
            try {
    
                $stmt = sqlsrv_prepare($cid, $sql);
                $stmt = sqlsrv_execute($stmt);
    
            } catch (\Throwable $th) {
    
                print_r($th);
    
            }

        }

        $sql2 = "	
        DELETE FROM SJ_CONTROL_AUDITORIA
        WHERE ID IN
        (
            SELECT MAX(ID) ID
            FROM SJ_CONTROL_AUDITORIA
            WHERE NRO_REMITO = '$ncomp'
            GROUP BY NRO_REMITO, SUC_ORIG, SUC_DESTIN, COD_ARTICU 
            HAVING COUNT(COD_ARTICU) > 1
        )
        ";

        try {

            $stmt = sqlsrv_prepare($cid, $sql2);
            $stmt = sqlsrv_execute($stmt);

        } catch (\Throwable $th) {

            print_r($th);

        }

      

        
    } 

    public function ajusteRemitoStatusDirecto($status, $ncomp){

        $cid = $this->conn->conectar('central');

        $sql = "	
        UPDATE SJ_CONTROL_AUDITORIA SET OBSERVAC_LOGISTICA = '$status' WHERE NRO_REMITO = '$ncomp'
        ";


        try {

            $stmt = sqlsrv_prepare($cid, $sql);
            $stmt = sqlsrv_execute($stmt);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }
        
    } 

    public function borrarRemitoControlado($numRemito){

        $cid = $this->conn->conectar('central');

        $sql = "
        DELETE FROM SJ_CONTROL_AUDITORIA WHERE NRO_REMITO = $numRemito
        ";

        try {

            $stmt = sqlsrv_query($cid, $sql);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }


}
