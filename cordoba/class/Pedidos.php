<?php

class Pedido
{


    
    function __construct() 
    {
        
        require_once $_SERVER['DOCUMENT_ROOT'] .'/sistemas/class/conexion.php';
        
        $conn = new Conexion();
        $this->cid = $conn->conectar('central');

    }
    
    public function traerHistorial() 
    {   
        $sql="SET DATEFORMAT YMD
            SELECT CAST(FECHA_PEDI AS DATE)FECHA, A.NRO_PEDIDO, LEYENDA_1, B.CANT FROM GVA21 A
            INNER JOIN
            (
            SELECT NRO_PEDIDO, CAST(SUM(CANT_PEDID) AS FLOAT) CANT FROM GVA03 WHERE TALON_PED IN (96, 97) GROUP BY NRO_PEDIDO
            )B
            ON A.NRO_PEDIDO = B.NRO_PEDIDO
            WHERE COD_CLIENT IN
            (
            'FRBAUD', 
            'FRORCE',
            'FRORIG',
            'FRORNC',
            'FRORSJ',
            'FRPASJ'
            ) 
            AND FECHA_PEDI > (GETDATE()-60) AND A.TALON_PED IN (96, 97)
            ORDER BY 1 desc, 2 desc
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

    public function traerHistorialPedidos ($desde, $hasta , $suc) {
        
        $sql=
        "

        SET DATEFORMAT YMD

        SELECT CAST(FECHA_PEDI AS DATE)FECHA, A.COD_CLIENT, A.NRO_PEDIDO, LEYENDA_1, CAST(B.CANT AS INT)CANT FROM GVA21 A
        INNER JOIN
        (
        SELECT NRO_PEDIDO, CAST(SUM(CANT_PEDID) AS FLOAT) CANT FROM GVA03 GROUP BY NRO_PEDIDO
        )B
        ON A.NRO_PEDIDO = B.NRO_PEDIDO
        WHERE COD_CLIENT IN
        (
        'FRBAUD', 
        'FRORCE',
        'FRORIG',
        'FRORNC',
        'FRORSJ',
        'FRPASJ'
        ) 
        AND FECHA_PEDI > (GETDATE()-60) 
        AND (FECHA_PEDI BETWEEN '$desde' AND '$hasta')
        AND A.COD_CLIENT like '%$suc%'
        ORDER BY 1 desc, 2 desc

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

    public function traerDetallePedido ($pedido , $suc) {
        
        $sql=
        "
        SET DATEFORMAT YMD
    
        SELECT CAST(A.FECHA_PEDI AS DATE)FECHA, B.COD_ARTICU, C.DESCRIPCIO, CAST(B.CANT_PEDID AS FLOAT) CANT FROM GVA03 B
        INNER JOIN GVA21 A
        ON A.NRO_PEDIDO = B.NRO_PEDIDO AND A.TALON_PED = B.TALON_PED
        INNER JOIN STA11 C
        ON B.COD_ARTICU = C.COD_ARTICU
        WHERE A.NRO_PEDIDO = '$pedido'
        AND A.COD_CLIENT = '$suc'
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

    public function ejecutarSpAccesorios () {
        
       		$sql="
                SET DATEFORMAT YMD
                
                EXEC SJ_TIPO_PEDIDO_CORDOBA_2
            
            ";
        
        try {

            $stmt = sqlsrv_query($this->cid, $sql); 
            
            $v = [];

            do{
                while($row=sqlsrv_fetch_array($stmt))
                {
                    $v[] = $row;
                }
            }while(sqlsrv_next_result($stmt));
            
            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
    }

    public function ejecutarSpPedidosGenerales () {
        
            $sql="
            SET DATEFORMAT YMD
            
            EXEC SJ_TIPO_PEDIDO_CORDOBA_1
            
            ";
        
        try {

            $stmt = sqlsrv_query($this->cid, $sql); 
            
            $v = [];

            do{
                while($row=sqlsrv_fetch_array($stmt))
                {
                    $v[] = $row;
                }
            }while(sqlsrv_next_result($stmt));
            
            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
    }
}
