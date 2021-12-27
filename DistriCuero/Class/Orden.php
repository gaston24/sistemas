
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
        
        public function traerOrdenesTodas(){
    
                $sql = "
        
                SELECT CAST(FECHA AS VARCHAR) FECHA, HORA, NRO_ORDEN, COUNT(COD_ARTICU) ARTICULOS, ACTIVA FROM RO_ORDENES_PRECOMPRA 
                GROUP BY FECHA, HORA, NRO_ORDEN, ACTIVA
                ORDER BY NRO_ORDEN DESC
        
                ";
                
                $rows = $this->retornarArray($sql);
        
                return $rows;
        
        }
            
        public function traerOrdenesConNotaPedido($orden){

            $sql = "
    
                    SELECT A.NRO_SUCURSAL, A.COD_CLIENT, A.DESC_SUCURSAL, REPLACE(ISNULL(B.FECHA, ''),'1900-01-01','') FECHA, B.HORA, B.NRO_ORDEN, NRO_NOTA_PEDIDO, TOTAL, CANTIDAD, 
                    CASE WHEN C.FECHA IS NULL THEN 'PENDIENTE' ELSE 'RECHAZADA' END ESTADO
                    FROM [LAKERBIS].LOCALES_LAKERS.DBO.SUCURSALES_LAKERS A
                    LEFT JOIN 
                    (
                    SELECT FECHA, HORA, COD_CLIENT, NRO_ORDEN, NRO_NOTA_PEDIDO,	SUM(PRECIO) TOTAL, SUM(CANTIDAD) CANTIDAD FROM	 
                    (
                    SELECT FECHA, HORA, COD_CLIENT, NRO_ORDEN, NRO_NOTA_PEDIDO, A.COD_ARTICU, (C.PRECIO * CANTIDAD) PRECIO, CANTIDAD FROM RO_PEDIDO_PRECOMPRA A
                    INNER JOIN (SELECT COD_ARTICU, PRECIO FROM GVA17 WHERE NRO_DE_LIS = '30' AND COD_ARTICU LIKE 'X%') C ON C.COD_ARTICU = A.COD_ARTICU COLLATE Latin1_General_BIN
                    WHERE NRO_ORDEN = '$orden'
                    ) A
                    GROUP BY FECHA, HORA, COD_CLIENT, NRO_ORDEN, NRO_NOTA_PEDIDO
                    ) 
                    B ON A.COD_CLIENT = B.COD_CLIENT
                    LEFT JOIN (SELECT * FROM RO_ORDENES_RECHAZADAS WHERE NRO_ORDEN = '$orden') C ON A.COD_CLIENT = C.COD_CLIENT
                    WHERE CANAL = 'FRANQUICIAS' AND HABILITADO = 1 AND NRO_SUC_MADRE IS NULL
                    ORDER BY NRO_SUCURSAL

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
            AND NRO_ORDEN NOT IN (SELECT NRO_ORDEN FROM RO_ORDENES_RECHAZADAS WHERE COD_CLIENT = '$codClient')
            GROUP BY FECHA, HORA, NRO_ORDEN
            ORDER BY NRO_ORDEN DESC

        ";
        
        $rows = $this->retornarArray($sql);

        return $rows;

        }  
    }
}