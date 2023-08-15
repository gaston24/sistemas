<?php

class Egreso
{
    private $cid;
    private $cid_central;
    private $cid_locales;

    
    function __construct()
    {

        require_once __DIR__.'/../../class/conexion.php';
        $this->cid = new Conexion();
        $this->cid_central = $this->cid->conectar('central');

    } 

    public function traerGastos($desde, $hasta)
    {
  
        $sql = "SELECT * FROM [Lakerbis].locales_lakers.dbo.RO_V_GASTOS_CAJA_SUCURSALES WHERE FECHA BETWEEN '$desde' AND '$hasta'";

        $stmt = sqlsrv_query($this->cid_central, $sql);

        try{
            
            $rows = array();
    
            while ($v = sqlsrv_fetch_array($stmt)) {
                $rows[] = $v;
            }
    
            return $rows;
        
        } catch (\Throwable $th){
            print_r($th);
        }

    }
}