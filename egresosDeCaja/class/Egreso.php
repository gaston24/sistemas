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

    public function traerGastos($desde, $hasta, $nroSucursal)
    {
  
        $sql = "SELECT a.*, (case when b.FECHA_GUARDADO is not null then 1 else 0 end) guardado
        FROM [Lakerbis].locales_lakers.dbo.RO_V_GASTOS_CAJA_SUCURSALES  a
        LEFT JOIN SJ_EGRESOS_DE_CAJA_GUARDADO b ON a.n_comp = b.n_comp COLLATE Latin1_General_BIN AND a.cod_cta = b.COD_CTA 
        WHERE FECHA BETWEEN '$desde' AND '$hasta'
        AND a.NRO_SUCURS = '$nroSucursal'";
  


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

    public function existeFoto ($nComp, $codCta) {
        
        $sql = "INSERT INTO SJ_EGRESOS_DE_CAJA_GUARDADO (N_COMP, FECHA_GUARDADO, COD_CTA)
        SELECT '$nComp', GETDATE(), '$codCta'
        WHERE NOT EXISTS (
            SELECT 1
            FROM SJ_EGRESOS_DE_CAJA_GUARDADO
            WHERE N_COMP = '$nComp' AND COD_CTA = '$codCta'
        );";

        try{

            $stmt = sqlsrv_query($this->cid_central, $sql);
            return true;
        
        
        } catch (\Throwable $th){
            print_r($th);
        }


    }
}