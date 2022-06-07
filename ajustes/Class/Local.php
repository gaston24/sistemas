<?php

class Local 
{
    private $dsn = '1 - CENTRAL';
    private $usuario = "sa";
    private $clave="Axoft1988";

    public function getData($sqlEnviado){

        $cid = odbc_connect($this->dsn, $this->usuario, $this->clave);

        $sql= $sqlEnviado;

        ini_set('max_execution_time', 300);
        $result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
        $data = [];
        while($v=odbc_fetch_object($result)){
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