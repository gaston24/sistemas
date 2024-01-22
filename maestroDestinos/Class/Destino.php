<?php

class Destino
{
    
    private $cid;
    private $cid_central;

    
    function __construct()
    {

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->cid = new Conexion();
        session_start();
        $db = isset($_SESSION['entorno']) ? $_SESSION['entorno'] : 'central';
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

    public function traerArticulos($rubro, $temporada, $novedades){

        $sql = " 
            SELECT A.* FROM MAESTRO_DESTINOS A
            LEFT JOIN MAESTRO_TEMPORADAS B ON A.TEMPORADA = B.NOMBRE_TEMP
            WHERE EXCLUIR IS NULL
            AND TEMPORADA LIKE '$temporada' AND FAMILIA LIKE '$rubro'

        ";

        if($novedades == 1){

            $sql .= " AND FECHA_MOD = (
                SELECT  MAX(FECHA_MOD) AS FECHA_MOD
                FROM MAESTRO_DESTINOS)
            ;";

        }

        $rows = $this->retornarArray($sql);

        return $rows;

    }  

    public function liquidar($codArticuString, $liquidacion ){

        $sql = " UPDATE MAESTRO_DESTINOS SET LIQUIDACION = '$liquidacion' WHERE COD_ARTICU IN ($codArticuString)";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return $rows;

    }  

    public function cambiarDestino ($codArticuString, $destino ){

        $sql = " UPDATE MAESTRO_DESTINOS SET DESTINO = '$destino' WHERE COD_ARTICU = '$codArticuString'";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return true;;

    }

    public function cambiarLiquidacion ($codArticuString, $liquidacion ){

        $sql = " UPDATE MAESTRO_DESTINOS SET LIQUIDACION = '$liquidacion' WHERE COD_ARTICU = '$codArticuString'";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return true;;

    }

    public function actualizarMaestro ($articulo, $descripcion, $destino, $liquidacion ){

        $sql = " UPDATE MAESTRO_DESTINOS  SET DESCRIPCION = '$descripcion', DESTINO = '$destino', LIQUIDACION = '$liquidacion' WHERE COD_ARTICU = '$articulo'";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return true;

    }

    public function comprobarArticulo($articulo) {

        $sql = 'SELECT CASE WHEN EXISTS (SELECT 1 FROM STA11 WHERE COD_ARTICU = \''.$articulo.'\') THEN 1 ELSE 0 END AS resultado';
        
 
    
    
        $stmt = sqlsrv_query($this->cid_central, $sql);
    
        // Verificar el resultado de la consulta
        if ($stmt !== false) {
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            
            return $result['resultado'];
           
        } else {
            // Error en la consulta
            return false;
        }
    }
    
    public function traerDescripcion ($codArticulo) {

        $sql = "SELECT DESCRIPCION FROM MAESTRO_DESTINOS WHERE COD_ARTICU = '$codArticulo'";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        $result = '';

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $result = $v['DESCRIPCION'];
        }

        return $result;
    }

    public function traerTemporadas () {

        $sql = 'SELECT NOMBRE_TEMP, COD_TEMP, EXCLUIR FROM MAESTRO_TEMPORADAS
        WHERE EXCLUIR IS NULL';

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }
      
    public function excluirTemporada ($nombreTemp, $val) {

    
        $sql = "UPDATE MAESTRO_TEMPORADAS SET EXCLUIR = $val WHERE NOMBRE_TEMP = '$nombreTemp'";
   
        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return true;

    }

    public function guardarTemporada ($string) {
            
        $sql = "INSERT INTO MAESTRO_TEMPORADAS (NOMBRE_TEMP, COD_TEMP, EXCLUIR) VALUES $string";

        $stmt = sqlsrv_query( $this->cid_central, $sql );

        return true;
        
    }
}    
