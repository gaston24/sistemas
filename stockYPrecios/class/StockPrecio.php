<?php


class StockPrecio
{

    private $consulta;
    
    function __construct() 
    {
        
        require_once $_SERVER['DOCUMENT_ROOT'] .'/sistemas/class/conexion.php';
        
        $conn = new Conexion();
        $this->cid = $conn->conectar('local');

    }
    
    public function traerMaestroArticulo($codArticulo) 
    {   
        $sql = "SELECT A.COD_ARTICU, D.DESCRIPCIO,  CAST(A.CANT_STOCK AS INT) AS CANT_STOCK, C.PRECIO FROM STA19 A
        INNER JOIN STA22 B ON A.COD_DEPOSI = B.COD_SUCURS
        LEFT JOIN (SELECT * FROM GVA17 WHERE NRO_DE_LIS = 20) C ON A.COD_ARTICU = C.COD_ARTICU
        LEFT JOIN STA11 D ON A.COD_ARTICU = D.COD_ARTICU
        WHERE COD_SUCURS LIKE '[0-9]%' 
        AND INHABILITA = 0 
        AND A.COD_ARTICU LIKE '[XO]%' 
        AND A.COD_ARTICU = '$codArticulo'";

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