<?php

class Local 
{

    public function getData($sqlEnviado){
 
        require_once '../class/conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar('central');  

        $sql= $sqlEnviado;

        ini_set('max_execution_time', 300);
        $result=sqlsrv_query($cid_central,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=sqlsrv_fetch_object($result)){
            $data[] = array($v);
        };
        return $data;
    }
    
    
    public function traerLocales(){

        $sql= "
            SET DATEFORMAT YMD
            SELECT * FROM SOF_USUARIOS WHERE IS_OUTLET = 1 AND NRO_SUCURS < 100 AND NOMBRE LIKE 'GT%'
            ORDER BY DESCRIPCION
            ";
     
        return $this->getData($sql);
    }

}