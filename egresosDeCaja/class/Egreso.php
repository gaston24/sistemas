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
        $db= 'central';
        if($_SESSION['usuarioUy'] == 1){
            $db = 'uy';
        }
        $this->cid_central = $this->cid->conectar($db);

    } 

    public function traerGastos($desde, $hasta, $nroSucursal)
    {
  
        $sql = "SELECT a.*, (case when b.FECHA_GUARDADO is not null then 1 else 0 end) guardado
        FROM [Lakerbis].locales_lakers.dbo.RO_V_GASTOS_CAJA_SUCURSALES  a
        LEFT JOIN SJ_EGRESOS_DE_CAJA_GUARDADO b ON a.n_comp = b.n_comp COLLATE Latin1_General_BIN AND a.cod_cta = b.COD_CTA  AND  b.NRO_SUCURSAL = '$nroSucursal'
        WHERE FECHA BETWEEN '$desde' AND '$hasta'
        AND a.NRO_SUCURS = '$nroSucursal'";
    var_dump($sql);
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

    public function existeFoto ($nComp, $codCta, $nroSucursal) {
        
        $sql = "INSERT INTO SJ_EGRESOS_DE_CAJA_GUARDADO (N_COMP, FECHA_GUARDADO, COD_CTA, NRO_SUCURSAL)
        SELECT '$nComp', GETDATE(), '$codCta', '$nroSucursal'
        WHERE NOT EXISTS (
            SELECT 1
            FROM SJ_EGRESOS_DE_CAJA_GUARDADO
            WHERE N_COMP = '$nComp' AND COD_CTA = '$codCta' AND NRO_SUCURSAL = '$nroSucursal'
        );";

        try{

            $stmt = sqlsrv_query($this->cid_central, $sql);
            return true;
        
        
        } catch (\Throwable $th){
            print_r($th);
        }


    }
}