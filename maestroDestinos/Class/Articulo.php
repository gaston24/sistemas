<?php

class Articulo
{
    

    private $cid;
    private $cid_central;


    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        
        $this->cid = new Conexion();

        if (session_status() === PHP_SESSION_NONE) {
           
            session_start();

        }
        
        $db = (isset($_SESSION['usuarioUy']) && $_SESSION['usuarioUy'] == 1) ? 'uy' : 'central';

        $this->cid_central = $this->cid->conectar($db);

    } 

    private function retornarArray($sqlEnviado){



        $sql = $sqlEnviado;

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;  

    }

    public function traerArticulos($rubro, $temporada, $liquidacion){


        $sql = " SELECT A.COD_ARTICU, DESCRIPCION, DESTINO, TEMPORADA, B.RUBRO,A.FECHA_MOD, LIQUIDACION FROM MAESTRO_DESTINOS A
                 LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
                 WHERE TEMPORADA LIKE '$temporada' AND RUBRO LIKE '$rubro' 
        ";

        if($liquidacion != '%'){
            $sql .= "AND LIQUIDACION LIKE '$liquidacion'";
        }
       

        $rows = $this->retornarArray($sql);

        return $rows;

    }   
    
    public function traerNovedades(){


        $sql = " 
        SELECT A.COD_ARTICU, DESCRIPCION, DESTINO, TEMPORADA, B.RUBRO,A.FECHA_MOD FROM MAESTRO_DESTINOS A
        LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
        WHERE A.FECHA_MOD = (
        SELECT  MAX(FECHA_MOD) AS FECHA_MOD
        FROM MAESTRO_DESTINOS)
        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }    

    public function buscarArticuloPorCodigo($codArticulo)
    {
        if (empty($codArticulo)) {
            return [];
        }
        
        try {
            // SQL query with ISNULL to handle NULL values
            $sql = "SELECT 
                        A.COD_ARTICU, 
                        ISNULL(A.DESCRIPCION, '') AS DESCRIPCION, 
                        ISNULL(A.DESTINO, '') AS DESTINO, 
                        ISNULL(A.TEMPORADA, '') AS TEMPORADA, 
                        ISNULL(B.RUBRO, '') AS RUBRO, 
                        ISNULL(A.LIQUIDACION, '0') AS LIQUIDACION, 
                        ISNULL(C.PRECIO, 0) AS PRECIO, 
                        ISNULL(CAST((C.PRECIO/(1+(E.PORCENTAJE/100))) AS DECIMAL(10,2)), 0) AS PRECIO_S_IVA
                    FROM MAESTRO_DESTINOS A
                    LEFT JOIN SOF_RUBROS_TANGO B ON A.COD_ARTICU = B.COD_ARTICU
                    LEFT JOIN (SELECT COD_ARTICU, PRECIO FROM GVA17 WHERE NRO_DE_LIS = 20) C ON A.COD_ARTICU = C.COD_ARTICU
                    LEFT JOIN STA11 D ON A.COD_ARTICU = D.COD_ARTICU
                    LEFT JOIN GVA41 E ON D.ID_GVA41_COD_IVA = E.ID_GVA41
                    WHERE A.COD_ARTICU = ?";

            $stmt = sqlsrv_prepare($this->cid_central, $sql, array($codArticulo));
            
            if ($stmt === false) {
                // Log the error and throw with details
                $errors = print_r(sqlsrv_errors(), true);
                error_log("Error preparing SQL statement: $errors");
                throw new Exception("Error preparing SQL statement: $errors");
            }

            $result = sqlsrv_execute($stmt);
            if ($result === false) {
                // Log the error and throw with details
                $errors = print_r(sqlsrv_errors(), true);
                error_log("Error executing SQL statement: $errors");
                throw new Exception("Error executing SQL statement: $errors");
            }

            $rows = array();
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                // Process the row data to ensure it's consistent
                foreach ($row as $key => &$value) {
                    // Convert any remaining NULL values to appropriate defaults
                    if ($value === null) {
                        switch ($key) {
                            case 'PRECIO':
                            case 'PRECIO_S_IVA':
                                $value = 0;
                                break;
                            case 'LIQUIDACION':
                                $value = '0';
                                break;
                            default:
                                $value = '';
                        }
                    }
                    
                    // Format decimal values to prevent floating point issues
                    if (in_array($key, ['PRECIO', 'PRECIO_S_IVA']) && is_numeric($value)) {
                        $value = number_format((float)$value, 2, '.', '');
                    }
                }
                
                $rows[] = $row;
            }

            return $rows;
        } catch (Exception $e) {
            // Log the exception and rethrow
            error_log("Exception in buscarArticuloPorCodigo: " . $e->getMessage());
            throw $e;
        }
    }

}