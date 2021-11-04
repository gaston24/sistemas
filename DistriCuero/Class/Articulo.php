
<?php

class Articulo
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

    public function traerArticulos($codArticu){

        $sql = " 
        
        SELECT A.COD_ARTICU, DESCRIPCIO, B.PRECIO, TEMPORADA FROM STA11 A
        INNER JOIN (SELECT COD_ARTICU, PRECIO FROM GVA17 WHERE NRO_DE_LIS = '20') B ON A.COD_ARTICU = B.COD_ARTICU
        LEFT JOIN MAESTRO_DESTINOS C ON A.COD_ARTICU = C.COD_ARTICU
        INNER JOIN SOF_RUBROS_TANGO D ON A.COD_ARTICU = D.COD_ARTICU
        WHERE A.COD_ARTICU LIKE 'X%' AND D.RUBRO = 'CARTERAS DE CUERO'
        AND A.COD_ARTICU = '$codArticu'

        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }

    public function insertarArticulo($articulo,$descrip,$precio,$temporada){

        require_once 'Conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar();        

        $sql = "
        
        INSERT INTO DBO.RO_MAESTRO_PRECOMPRA_CUERO ([COD_ARTICU], [DESCRIPCIO], [PRECIO_ESTIMADO],[TEMPORADA]) VALUES 
        ('$articulo','$descrip','$precio', '$temporada');

        ";
        $stmt = sqlsrv_query( $cid_central, $sql );

        sqlsrv_execute( $stmt);

        return 'Articulo ingresado correctamente';


    }

    public function traerMaestro($rubro, $temporada){

        $sql = " SELECT * FROM RO_MAESTRO_PRECOMPRA_VIEW WHERE RUBRO LIKE '$rubro' AND TEMPORADA LIKE '$temporada' ";
        
        $rows = $this->retornarArray($sql);

        return $rows;
    }

}    



