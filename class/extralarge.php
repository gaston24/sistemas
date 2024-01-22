<?php

class Extralarge {

    function __construct(){

        require_once __DIR__.'/conexion.php';
        $this->conn = new Conexion;
        
    }

    public function esUsuarioUy ($user) {

        $sql = "SELECT
        CASE
          WHEN EXISTS (SELECT 1 FROM SOF_USUARIOS_UY WHERE COD_CLIENT = '$user')
          THEN '1'
          ELSE '0'
        END as result;";

        $cid = $this->conn->conectar('uy');
        
        $stmt = sqlsrv_query($cid, $sql);
        
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        return $row['result'];


    }

    
    public function login($user, $pass, $db = 'central'){

        $cid = $this->conn->conectar($db);
        

        $sql = "EXEC SJ_APP_LOGIN '$user', '$pass'";

        $stmt = sqlsrv_query($cid, $sql);

        try {

            $next_result = sqlsrv_next_result($stmt);

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v[0];

        } catch (\Throwable $th) {

            print_r($th);

        }



    }
    
    public function traerDatosDeConexionPorLocal($local){

        $cid = $this->conn->conectar('central');
        

        $sql = "SELECT CONEXION_DNS,BASE_NOMBRE FROM [LAKERBIS].locales_lakers.dbo.SUCURSALES_LAKERS WHERE NRO_SUC_MADRE is NULL AND NRO_SUCURSAL = '$local'";

        $stmt = sqlsrv_query($cid, $sql);

        try {

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }



    }

    public function deletePedidos($numSuc){

        $cid = $this->conn->conectar('central');

        $sql = "DELETE FROM SOF_PEDIDOS_CARGA WHERE NUM_SUC = $numSuc";
        try {

            $stmt = sqlsrv_prepare($cid,$sql);
            $stmt = sqlsrv_execute($stmt);
            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }


    }

    public function insertarPedidos($codArticu, $cantStock, $cantVend, $suc){

        $cid = $this->conn->conectar('central');

        $sql=
			"
			INSERT INTO SOF_PEDIDOS_CARGA (NUM_SUC, COD_ARTICU, CANT_STOCK, VENDIDO) VALUES ($suc, '$codArticu', $cantStock, $cantVend);
			"
			;
        try {

            $stmt = sqlsrv_prepare($cid,$sql);
            $stmt = sqlsrv_execute($stmt);
            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }


    }

    public function traerDatosArticulos($localName){

        $cid = $this->conn->conectar($localName);
        

        $sql = "
        SET DATEFORMAT YMD

        SELECT COD_ARTICU, CAST(CANT_STOCK AS INT) CANT_STOCK, CAST(VENDIDO AS INT) VENDIDO FROM
        (

            SELECT COD_ARTICU, CANT_STOCK, CASE WHEN VENDIDO IS NULL THEN 0 ELSE VENDIDO END VENDIDO  FROM
            (
                SELECT A.COD_ARTICU, A.CANT_STOCK, B.VENDIDO FROM STA19 A
                LEFT JOIN 
                (
                    SELECT COD_ARTICU, SUM(CASE T_COMP WHEN 'NCR' THEN CANTIDAD*-1 ELSE CANTIDAD END)VENDIDO FROM GVA53 WHERE FECHA_MOV > (GETDATE()-30) GROUP BY COD_ARTICU
                )B
                ON A.COD_ARTICU = B.COD_ARTICU
                WHERE A.COD_ARTICU IN 
                (
                    SELECT DISTINCT(COD_ARTICU) FROM GVA53 WHERE FECHA_MOV > (GETDATE()-180)
                    UNION 
                    SELECT COD_ARTICU FROM 
                    ( 
                        SELECT A.COD_ARTICU, A.CANT_STOCK, B.VENDIDO FROM STA19 A
                        LEFT JOIN ( SELECT COD_ARTICU, SUM(CASE T_COMP WHEN 'NCR' THEN CANTIDAD*-1 ELSE CANTIDAD END ) VENDIDO 
                        FROM GVA53 WHERE FECHA_MOV > (GETDATE()-30) GROUP BY COD_ARTICU
                    )B
                    ON A.COD_ARTICU = B.COD_ARTICU WHERE COD_DEPOSI LIKE '[0-9]%' 
                    )A WHERE CANT_STOCK > 0 AND VENDIDO IS NULL 
                )
                AND COD_DEPOSI LIKE '[0-9]%'
                GROUP BY A.COD_ARTICU, A.CANT_STOCK, B.VENDIDO
            )A

        )A

        WHERE (CANT_STOCK != 0 OR VENDIDO != 0)
        ";

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

    public function traerDatosMayorista($codClient, $ven){

        $cid = $this->conn->conectar('central');
        

        $sql = "EXEC SJ_TRAER_DATOS_MAYORISTA '$codClient', '$ven'";

        $stmt = sqlsrv_query($cid, $sql);

        try {

            $next_result = sqlsrv_next_result($stmt);

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }
    
            return $v[0];

        } catch (\Throwable $th) {

            print_r($th);

        }

        
    }

    public function getEnv(){

        $envVars = $this->conn->envVars;
        return $envVars['ENV'];


    }

    public function limitePedidos($cliente){

        $cid = $this->conn->conectar('central');

        $tiposPedidos = ['PEDIDO GENERAL', 'PEDIDO ACCESORIOS', 'PEDIDO OUTLET'];

        $sql = "SELECT LEYENDA_1 AS TIPO, COD_CLIENT, COUNT(NRO_PEDIDO) CANT_PEDIDOS 
        FROM GVA21
        WHERE COD_CLIENT = 'GTSHSO' 
        AND FECHA_PEDI BETWEEN DATEADD(wk,DATEDIFF(wk,0,GETDATE()),0) AND DATEADD(wk,DATEDIFF(wk,0,GETDATE()),6) 
        AND TALON_PED = '97' 
        AND LEYENDA_1 IN ('PEDIDO GENERAL', 'PEDIDO ACCESORIOS', 'PEDIDO OUTLET')
        GROUP BY COD_CLIENT, LEYENDA_1 ;";

        $stmt = sqlsrv_query($cid, $sql);

        $v = [];

        try {

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            sqlsrv_close($cid);

            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }

        sqlsrv_close($cid);

        return $result;

    }

}