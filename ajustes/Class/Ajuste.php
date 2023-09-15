<?php


class Ajuste
{

    private $consulta;
    
    function __construct() 
    {

        require_once 'Consulta.php';
        
        $conn = new Conexion();
        $this->cid = $conn->conectar();

        $this->consulta = new Consulta();

    }
    
    public function traerAjustes() 
    {

        sqlsrv_query($this->cid, $this->consulta->sqlNuevos);
        
        try {

            $result = sqlsrv_query($this->cid, $this->consulta->sql); 
            
            $v = [];

            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }
        

        return $rows;

    }

    public function ejecutarSqlNuevos() 
    {
        try {
            
            sqlsrv_query($this->cid, $this->consulta->sqlNuevos);
            
            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function traerArticulo($nuevo) 
    {
        $sqlArt = "
		SELECT * FROM SJ_VIEW_STA11 WHERE COD_ARTICU = '$nuevo'
		";
        try {
            
        	$resultArt = sqlsrv_query($this->cid, $sqlArt);
               
            $v = [];

            while ($row = sqlsrv_fetch_array($resultArt, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return $v;


        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function actualizarCodigoNuevo($nuevo, $codigo, $ncomp) 
    {
        $sqlUpdate = "UPDATE SOF_CONFIRMA SET COD_NUEVO = '$nuevo' WHERE COD_ARTICU = '$codigo' AND N_COMP = '$ncomp';";

        
        try {
            sqlsrv_query($this->cid, $sqlUpdate);
            return true;


        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function setearProximoRemito() 
    {
        $sqlProx = " SELECT ' ' +
        RIGHT('00000' + CAST((SELECT SUCURSAL FROM STA17 WHERE TALONARIO = 850)AS VARCHAR), 5) + 
        RIGHT(('00000000'+ CAST((SELECT PROXIMO FROM STA17 WHERE TALONARIO = 850)AS VARCHAR)),8) 
        PROXIMO
        ";
    
        try {
            $resultProx = sqlsrv_query($this->cid, $sqlProx);
            
            while($v=sqlsrv_fetch_array($resultProx)){

                $proximo = $v['PROXIMO'];

            }
            return $proximo;


        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function updateRemitoEnTalonario() 
    {
        $sqlUpdateProx = "UPDATE STA17 SET PROXIMO = PROXIMO+1 WHERE TALONARIO = 850;";
        
        try {

            $resultUpdateProx = sqlsrv_query($this->cid, $sqlUpdateProx);
           
            return true;


        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function traerProximoInterno() 
    {
        $sqlProxInterno = "SELECT RIGHT(('00100'+CAST((SELECT MAX(NCOMP_IN_S)+1 NCOMP_IN_S FROM STA14 WHERE TALONARIO = 850)AS VARCHAR)),8) PROXINTERNO;";
        try {

            $resultProxInterno = sqlsrv_query($this->cid, $sqlProxInterno);

            while($v=sqlsrv_fetch_array($resultProxInterno)){
                
                $proxInterno = $v['PROXINTERNO'];
            }
            
            return $proxInterno;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function insertarEncabezado($fecha, $proximo, $proxInterno, $hora) 
    {
        $sqlEncabezado = "
        INSERT INTO STA14 
        (
        COD_PRO_CL, COTIZ, EXPORTADO, EXP_STOCK, FECHA_ANU, FECHA_MOV, HORA, 
        LISTA_REM, LOTE, LOTE_ANU, MON_CTE, N_COMP, NCOMP_IN_S, 
        NRO_SUCURS, T_COMP, TALONARIO, TCOMP_IN_S, USUARIO, HORA_COMP,
        ID_A_RENTA, DOC_ELECTR, IMP_IVA, IMP_OTIMP, IMPORTE_BO, IMPORTE_TO, 
        DIFERENCIA, SUC_DESTIN, DCTO_CLIEN, FECHA_INGRESO, HORA_INGRESO, 
        USUARIO_INGRESO, TERMINAL_INGRESO, IMPORTE_TOTAL_CON_IMPUESTOS, 
        CANTIDAD_KILOS
        )
        VALUES
        (
        '', 4.5, 0, 0, '1800/01/01', '$fecha', '0000', 0, 0, 0, 1, '$proximo', '$proxInterno', 0, 'AJU', 850, 'AJ', 'AJUSTES', 
        '$hora', 0, 0, 0, 0, 0, 0, 'N', 0, 0, '$fecha', '$hora', 'AJUSTES', (SELECT host_name()), 0, 0
        )
        ;";

        try {

            $resultEncabezado = sqlsrv_query($this->cid, $sqlEncabezado);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function insertarDetalleSalida($cant, $codigo, $fecha, $proxInterno) 
    {
        $sqlDetSalida = "
        INSERT INTO STA20
        (
        CAN_EQUI_V, CANT_DEV, CANT_OC, CANT_PEND, CANT_SCRAP, CANTIDAD, COD_ARTICU, COD_DEPOSI, DEPOSI_DDE, EQUIVALENC, 
        FECHA_MOV, N_RENGL_OC, N_RENGL_S, NCOMP_IN_S, PLISTA_REM, PPP_EX, PPP_LO, PRECIO, PRECIO_REM, TCOMP_IN_S, TIPO_MOV,
        CANT_FACTU, DCTO_FACTU, CANT_DEV_2, CANT_PEND_2, CANTIDAD_2, CANT_FACTU_2, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, 
        PRECIO_REMITO_VENTAS, CANT_OC_2, RENGL_PADR, PROMOCION, PRECIO_ADICIONAL_KIT, TALONARIO_OC
        )
        VALUES
        (
        1, 0, 0, 0, 0, '$cant', '$codigo', (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS LIKE '[0-9]%' AND INHABILITA = 0),'', 1, '$fecha', 0, 1, '$proxInterno', 0, 0, 0, 0, 0, 'AJ', 
        'S', 0, 0, 0, 0, 0, 0, 6, 'P', 0, 0, 0, 0, 0, 0
        );";
        

        try {
            
            $resultDetSalida = sqlsrv_query($this->cid, $sqlDetSalida);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function restarCantidad($cant, $codigo) 
    {
        $sqlResta = "UPDATE STA19 SET CANT_STOCK = (CANT_STOCK - $cant) WHERE COD_ARTICU = '$codigo' AND COD_DEPOSI = (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS LIKE '[0-9]%' AND INHABILITA = 0)";

        try {
            
            $resultResta = sqlsrv_query($this->cid, $sqlResta);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function insertarDetalleEntrada($cant, $nuevo, $fecha, $proxInterno) 
    {
        $sqlDetEntrada = "
        INSERT INTO STA20
        (
        CAN_EQUI_V, CANT_DEV, CANT_OC, CANT_PEND, CANT_SCRAP, CANTIDAD, COD_ARTICU, COD_DEPOSI, DEPOSI_DDE, EQUIVALENC, 
        FECHA_MOV, N_RENGL_OC, N_RENGL_S, NCOMP_IN_S, PLISTA_REM, PPP_EX, PPP_LO, PRECIO, PRECIO_REM, TCOMP_IN_S, TIPO_MOV,
        CANT_FACTU, DCTO_FACTU, CANT_DEV_2, CANT_PEND_2, CANTIDAD_2, CANT_FACTU_2, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, 
        PRECIO_REMITO_VENTAS, CANT_OC_2, RENGL_PADR, PROMOCION, PRECIO_ADICIONAL_KIT, TALONARIO_OC
        )
        VALUES
        (
        1, 0, 0, 0, 0, '$cant', '$nuevo', (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS LIKE '[0-9]%' AND INHABILITA = 0),'', 1, 
        '$fecha', 0, 2, '$proxInterno', 0, 0, 0, 0, 0, 'AJ', 'E', 0, 0, 0, 0, 0, 0, 6, 'P', 0, 0, 0, 0, 0, 0
        );";
     
        try {
            
            $resultDetEntrada = sqlsrv_query($this->cid, $sqlDetEntrada);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function sumarStock($nuevo, $cant) 
    {
        $sqlConsulta19 = "EXEC SP_SJ_ARTICULO_OUTLET '$nuevo', $cant";
        
        try {
            
            sqlsrv_query($this->cid, $sqlConsulta19);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function actualizarRegistrosPendientes($ncomp, $codigo) 
    {
        $sqlActuaPend = "UPDATE SOF_CONFIRMA SET N_ORDEN_CO = '1' WHERE N_ORDEN_CO = '' AND N_COMP = '$ncomp' AND COD_ARTICU = '$codigo'";
	
        try {
            
            $resultActuaPend = sqlsrv_query($this->cid, $sqlActuaPend);

            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }

    }

    public function rechazarAjuste($id) 
    {
        $sql = "UPDATE SOF_CONFIRMA SET RECHAZADO = 1 WHERE ID_STA20 = $id";
        try {
            
            sqlsrv_query($this->cid, $sql);
            return true;

        } catch (Exception $e) {

            echo 'Excepción capturada: ',  $e->getMessage(), "\n";

        }

    }


}