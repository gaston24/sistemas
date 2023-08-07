<?php


class Recodificacion
{

    private $consulta;
    
    function __construct() 
    {

        require_once '../../class/conexion.php';
        
        $conn = new Conexion();
        $this->cid = $conn->conectar('central');

    }
    
    public function listarDetalle() 
    {   
        $sql = "DECLARE @numTarea INT
        SET @numTarea=63998
        SELECT * FROM [192.168.0.226,1433].UbicacionesStockMvc.dbo.RO_MOVIMIENTOS_WMS
        WHERE TIPO_MOVIMIENTO LIKE 'Transferencia M'
        AND DEPO_DESTINO LIKE '10'
        AND NRO_TAREA=@numTarea
        order by NRO_TAREA DESC";

        try {

            $result = sqlsrv_query($this->cid, $sql); 
            
            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function traerCodigoRecodificacion($valor, $codArticulo) 
    {   
        $sql = "EXEC RO_SP_RECODIFICAR_OUTLET '$codArticulo', $valor";

        try {

            $result = sqlsrv_query($this->cid, $sql); 
            
            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

    }

    public function traerSucursales () 
    {
        $sql = "SELECT NRO_SUCURSAL,COD_CLIENT,DESC_SUCURSAL FROM LAKERBIS.LOCALES_LAKERS.DBO.SUCURSALES_LAKERS WHERE CANAL = 'PROPIOS' AND HABILITADO = 1";
        
        try {

            $result = sqlsrv_query($this->cid, $sql); 
            
            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }
            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
    }

    public function validarArticulo ($codArticulo) 
    {
        $sql = "SELECT 1 AS respuesta FROM RO_MAESTRO_ARTICULOS_TODOS WHERE COD_ARTICU = '$codArticulo' ";
        
        try {

            $result = sqlsrv_query($this->cid, $sql); 
            
            if (sqlsrv_has_rows($result)) {

                // Hay al menos un registro en el resultado
                return "1";

            } else {

                // No hay registros en el resultado
                return "1";

            }

        } catch (\Throwable $th) {

            print_r($th);

        }
    }

    public function calcularSaldoPartidas ($codArticulo) 
    {
        $sql = "SELECT * FROM sta10 WHERE cod_articu = '$codArticulo' AND COD_DEPOSI = 10";
        
          
        try {

            $result = sqlsrv_query($this->cid, $sql); 
            
            $v = [];
            
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }
            
            if ($v[0]['CANTIDAD'] < 0) {

                return "0";

            }else{

                return "1";

            }


        } catch (\Throwable $th) {

            print_r($th);

        }
    }



}