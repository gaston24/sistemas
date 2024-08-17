<?php
class StockPrecio
{
    private $cid;
    private $cidUy;
    
    function __construct() 
    {
        require_once $_SERVER['DOCUMENT_ROOT'] .'/sistemas/class/conexion.php';
        
        $conn = new Conexion();
        $this->cid = $conn->conectar('local');
        $this->cidUy = $conn->conectar('uy');
    }
    
    public function traerMaestroArticulo($codArticulo, $usuarioUy = 0) 
    {   
<<<<<<< HEAD
        $sql = "SELECT A.COD_ARTICU, D.DESCRIPCIO, CAST(A.CANT_STOCK AS INT) AS CANT_STOCK, C.PRECIO, E.DESC_VALOR COLOR 
                FROM STA19 A
                INNER JOIN STA22 B ON A.COD_DEPOSI = B.COD_SUCURS
                LEFT JOIN (SELECT * FROM GVA17 WHERE NRO_DE_LIS = 20) C ON A.COD_ARTICU = C.COD_ARTICU
                LEFT JOIN STA11 D ON A.COD_ARTICU = D.COD_ARTICU
                LEFT JOIN (SELECT COD_ESCALA, COD_VALOR, DESC_VALOR FROM STA33 WHERE COD_ESCALA = '**' OR COD_ESCALA = 'ZZ') E 
                    ON D.ESCALA_1 = E.COD_ESCALA AND D.VALOR1 = E.COD_VALOR      
                WHERE COD_SUCURS LIKE '[0-9]%' 
                AND INHABILITA = 0 
                AND A.COD_ARTICU LIKE '[XO]%'
                AND A.COD_ARTICU = ?";
=======
        $sql = "SELECT A.COD_ARTICU, D.DESCRIPCIO,  CAST(A.CANT_STOCK AS INT) AS CANT_STOCK, C.PRECIO, E.DESC_VALOR COLOR, DESTINO, LIQUIDACION FROM STA19 A
        INNER JOIN (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS LIKE '[0-9]%' AND INHABILITA = 0) B ON A.COD_DEPOSI = B.COD_SUCURS
        LEFT JOIN (SELECT * FROM GVA17 WHERE NRO_DE_LIS = 20) C ON A.COD_ARTICU = C.COD_ARTICU
        LEFT JOIN STA11 D ON A.COD_ARTICU = D.COD_ARTICU
		LEFT JOIN (SELECT COD_ESCALA, COD_VALOR, DESC_VALOR FROM STA33 WHERE COD_ESCALA = '**' OR COD_ESCALA = 'ZZ' ) E ON D.ESCALA_1 = E.COD_ESCALA AND D.VALOR1 = E.COD_VALOR      
		LEFT JOIN (SELECT COD_ARTICU, DESTINO, LIQUIDACION FROM [extralarge.dyndns.biz,5020].LAKER_SA.DBO.MAESTRO_DESTINOS WHERE COD_ARTICU = '$codArticulo') F ON A.COD_ARTICU = F.COD_ARTICU
        WHERE COD_SUCURS LIKE '[0-9]%' 
        AND A.COD_ARTICU LIKE '[XO]%'
        AND A.COD_ARTICU = '$codArticulo'";
>>>>>>> eabf632f0d3edb2c0841bc2a748cdfcf315e9f84

        try {
            $cid = ($usuarioUy == 1) ? $this->cidUy : $this->cid;

            $stmt = sqlsrv_prepare($cid, $sql, array($codArticulo));
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . print_r(sqlsrv_errors(), true));
            }

            $result = sqlsrv_execute($stmt);
            if ($result === false) {
                throw new Exception("Error executing statement: " . print_r(sqlsrv_errors(), true));
            }

            $v = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $v[] = $row;
            }

            return $v;
        } catch (Exception $e) {
            error_log("Error in traerMaestroArticulo: " . $e->getMessage());
            return array('error' => $e->getMessage());
        }
    }

    public function traerVariantes($codArticulo, $usuarioUy = 0)
    {
        $sql = "SELECT A.COD_ARTICU, D.DESCRIPCIO, CAST(A.CANT_STOCK AS INT) AS CANT_STOCK, C.PRECIO, E.DESC_VALOR COLOR 
                FROM STA19 A
                INNER JOIN STA22 B ON A.COD_DEPOSI = B.COD_SUCURS
                LEFT JOIN (SELECT * FROM GVA17 WHERE NRO_DE_LIS = 20) C ON A.COD_ARTICU = C.COD_ARTICU
                LEFT JOIN STA11 D ON A.COD_ARTICU = D.COD_ARTICU
                LEFT JOIN (SELECT COD_ESCALA, COD_VALOR, DESC_VALOR FROM STA33 WHERE COD_ESCALA = '**' OR COD_ESCALA = 'ZZ') E 
                    ON D.ESCALA_1 = E.COD_ESCALA AND D.VALOR1 = E.COD_VALOR      
                WHERE COD_SUCURS LIKE '[0-9]%' 
                AND INHABILITA = 0 
                AND A.COD_ARTICU LIKE '[XO]%' 
                AND A.CANT_STOCK > 0
                AND LEFT(A.COD_ARTICU, 11) = LEFT(?, 11)";

        try {
            $cid = ($usuarioUy == 1) ? $this->cidUy : $this->cid;

            $stmt = sqlsrv_prepare($cid, $sql, array($codArticulo));
            if ($stmt === false) {
                throw new Exception("Error preparing statement: " . print_r(sqlsrv_errors(), true));
            }

            $result = sqlsrv_execute($stmt);
            if ($result === false) {
                throw new Exception("Error executing statement: " . print_r(sqlsrv_errors(), true));
            }

            $v = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $v[] = $row;
            }

            return $v;
        } catch (Exception $e) {
            error_log("Error in traerVariantes: " . $e->getMessage());
            return array('error' => $e->getMessage());
        }
    }
}