
<?php 

if(!isset($_SESSION['codClient'])){

    class Orden

    {
    
        private function retornarArray($sqlEnviado){
    
            require_once 'Conexion.php';
    
            $cid = new Conexion();
            $cid_central = $cid->conectar();  
            $sql = $sqlEnviado;
    
            $stmt = sqlsrv_query( $cid_central, $sql );
    
            $rows = array();
    
            while( $v = sqlsrv_fetch_array( $stmt) ) {
                $rows[] = $v;
            }
    
            return $rows;  
    
        }
    
        public function traerDetalleOrden($a){
    
            $sql = " SELECT * FROM RO_ORDENES_PRECOMPRA WHERE NRO_ORDEN LIKE '$a' ";
    
            $rows = $this->retornarArray($sql);
    
            return $rows;
        }
    
        public function traerOrdenesActivasTodas(){
    
            $sql = "
    
            SELECT CAST(FECHA AS VARCHAR) FECHA, HORA, NRO_ORDEN, COUNT(COD_ARTICU) ARTICULOS FROM RO_ORDENES_PRECOMPRA 
            WHERE ACTIVA = 1
            GROUP BY FECHA, HORA, NRO_ORDEN
            ORDER BY NRO_ORDEN DESC
    
            ";
            
            $rows = $this->retornarArray($sql);
    
            return $rows;
    
        }  
    
        public function traerOrdenesInactivas(){
    
            $sql = "
    
                SELECT CAST(FECHA AS VARCHAR) FECHA, HORA, NRO_ORDEN, COUNT(COD_ARTICU) ARTICULOS FROM RO_ORDENES_PRECOMPRA 
                WHERE ACTIVA IS NULL OR ACTIVA = 0
                GROUP BY FECHA, HORA, NRO_ORDEN, ACTIVA
                ORDER BY NRO_ORDEN DESC
    
            ";
            
            $rows = $this->retornarArray($sql);
    
            return $rows;
    
            }  
    
        }
	
}else{

$codClient = $_SESSION['codClient']; 

class Orden

{

    private function retornarArray($sqlEnviado){

        require_once 'Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();  
        $sql = $sqlEnviado;

        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;  

    }

    public function traerDetalleOrden($a){

        $sql = " SELECT * FROM RO_ORDENES_PRECOMPRA WHERE NRO_ORDEN LIKE '$a' ";

        $rows = $this->retornarArray($sql);

        return $rows;
    }

    public function traerOrdenesActivas($codClient){

        $sql = "

        SELECT CAST(FECHA AS VARCHAR) FECHA, HORA, NRO_ORDEN, COUNT(COD_ARTICU) ARTICULOS FROM RO_ORDENES_PRECOMPRA 
        WHERE ACTIVA = 1
        AND NRO_ORDEN NOT IN (SELECT NRO_ORDEN FROM RO_PEDIDO_PRECOMPRA WHERE COD_CLIENT = '$codClient')
        GROUP BY FECHA, HORA, NRO_ORDEN
        ORDER BY NRO_ORDEN DESC

        ";
        
        $rows = $this->retornarArray($sql);

        return $rows;

        }  
    }
}