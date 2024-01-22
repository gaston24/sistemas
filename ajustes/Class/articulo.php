<?php

class Articulo
{
    
    private function retornarArray($sqlEnviado){

        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';

        $cid = new Conexion();

        $cid_central = $cid->conectar('central');  
        $sql = $sqlEnviado;

        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;  

    }

    public function traerArticulos(){

        $sql = " 
        
        SELECT COD_ARTICU, DESCRIPCIO FROM STA11 WHERE COD_ARTICU LIKE 'O%' AND PERFIL != 'N'

        ";

        $rows = $this->retornarArray($sql);

        return $rows;

    }


    public function traerMaestroArticulo($codArticulo = null, $usuarioUy = 0) 
    {
        $codArt = $codArticulo ? $codArticulo : '%';

        $sqlArt = "SELECT a.*,b.PRECIO FROM RO_MAESTRO_ARTICULOS_TODOS  a
        inner join GVA17 b on a.COD_ARTICU = b.COD_ARTICU
        WHERE a.COD_ARTICU LIKE '%$codArt%' AND B.NRO_DE_LIS = '20'";

        $rows = $this->retornarArray($sqlArt, $usuarioUy);

        return $rows;
    }

    public function calcularNuevoCodigo($codArticulo) 
    {
        $sqlArt = "EXEC RO_SP_RECODIFICAR_OUTLET 'XT2SDC27C0707', 0.9";

        $rows = $this->retornarArray($sqlArt);

        return $rows;
    }

}