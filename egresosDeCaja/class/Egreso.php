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
  
        $sql = "SELECT a.*, (case when b.FECHA_GUARDADO is not null then 1 else 0 end) guardado
        FROM [Lakerbis].locales_lakers.dbo.RO_V_GASTOS_CAJA_SUCURSALES  a
        LEFT JOIN SJ_EGRESOS_DE_CAJA_GUARDADO b ON a.n_comp = b.n_comp COLLATE Latin1_General_BIN
        WHERE FECHA BETWEEN '$desde' AND '$hasta'";

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

    public function existeFoto ($nComp) {
        
        $sql = "INSERT INTO SJ_EGRESOS_DE_CAJA_GUARDADO (N_COMP,FECHA_GUARDADO) VALUES ('$nComp', GETDATE())";

        try{

            $stmt = sqlsrv_query($this->cid_central, $sql);
            return true;
        
        
        } catch (\Throwable $th){
            print_r($th);
        }


    }
}