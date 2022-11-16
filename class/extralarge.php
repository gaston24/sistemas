<?php

class Extralarge {

    function __construct(){

        require_once __DIR__.'/conexion.php';
        $this->conn = new Conexion;
        
    }

    public function login($user, $pass){

        $cid = $this->conn->conectar('central');
        

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

        SELECT COD_ARTICU, CANT_STOCK, VENDIDO FROM
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

}