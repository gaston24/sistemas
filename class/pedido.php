<?php

class Pedido {

    function __construct(){

        require_once __DIR__.'/conexion.php';
        $this->conn = new Conexion;
        
    }

    public function listarPedido($tipoPedido, $tipo_cli, $suc, $codClient, $esOutlet = null){

        $cid = $this->conn->conectar('central');

            switch ($tipoPedido) {
                case 1:
                    $_SESSION['tipo_pedido'] = 'GENERAL';
                    break;
                case 2:
                    $_SESSION['tipo_pedido'] = 'ACCESORIOS';
                    break;
                case 3:
                    $_SESSION['tipo_pedido'] = 'OUTLET';
                    break;
            }
            
        try{

            if($esOutlet == null){

                $sql="EXEC SJ_TIPO_PEDIDO_".$tipoPedido." $suc, '$codClient'";
                
            }else {

                $sql="EXEC SJ_TIPO_PEDIDO_".$tipoPedido."_OUTLET $suc, '$codClient'";

            }

            ini_set('max_execution_time', 300);

            $stmt = sqlsrv_query($cid, $sql);

            $next_result = sqlsrv_next_result($stmt);

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        }
        catch (\Throwable $th) {
            die("Error en sqlsrv_exec");
        };
    }

    public function traerHistorial($codClient){
        
        $cid = $this->conn->conectar('central');
        
        $sql=
        "
        SET DATEFORMAT YMD

        SELECT CAST(FECHA_PEDI AS DATE)FECHA, A.NRO_PEDIDO, LEYENDA_1, B.CANT FROM GVA21 A
        INNER JOIN
        (
            SELECT NRO_PEDIDO, CAST(SUM(CANT_PEDID) AS FLOAT) CANT FROM GVA03 WHERE TALON_PED IN (96, 97) GROUP BY NRO_PEDIDO
        )B
        ON A.NRO_PEDIDO = B.NRO_PEDIDO
        WHERE COD_CLIENT = '$codClient' AND FECHA_PEDI > (GETDATE()-60) AND A.TALON_PED IN (96, 97)
        ORDER BY 1 desc, 2 desc

        ";
            
        
        try {
            $stmt = sqlsrv_query($cid, $sql);
            
            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        }
        catch (\Throwable $th) {
            die("Error en sqlsrv_exec");
        };


        
    }

    public function traerDetallePedido($pedido, $suc){

        $cid = $this->conn->conectar('central');
        
        $sql=
        "
        SET DATEFORMAT YMD

        SELECT CAST(A.FECHA_PEDI AS DATE)FECHA, B.COD_ARTICU, C.DESCRIPCIO, CAST(B.CANT_PEDID AS FLOAT) CANT FROM GVA03 B
        INNER JOIN GVA21 A
        ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED
        INNER JOIN STA11 C
        ON B.COD_ARTICU = C.COD_ARTICU
        WHERE A.TALON_PED IN (96, 97) AND A.NRO_PEDIDO = '$pedido'
        AND A.COD_CLIENT = '$suc'
        ";

        try{

            $stmt = sqlsrv_query($cid, $sql);
            
            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;
        } 
        catch(\Throwable $th){
            die("Error en sqlsrv_exec");
        };
    }
}