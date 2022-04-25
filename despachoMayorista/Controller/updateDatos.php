
<?php

require_once '../Class/Conexion.php';
$cid = new Conexion();
$cid_central = $cid->conectar();
$fechaModif= Date('Y-m-d');

date_default_timezone_set('Etc/GMT+3');
$Object = new DateTime();  
$horaModif = $Object->format("G:i");

if (isset($_GET['datos'])) {
    //si el nro de pedido existe se procede a actualizar los campos. En caso de no existir el pedido en la tabla se realiza un insert.
    $datos = json_decode(stripslashes($_GET['datos']));
    foreach ($datos as $dato){
        $codigo = $dato->codigo;
        $tipoComp = $dato->tipoComp;
        $embalaje = $dato->embalaje;
        $despacho = $dato->despacho;
        $arreglo = $dato->arreglo;
        $prioridad = $dato->prioridad;
        $fecha_despacho = $dato->fechaDespacho;
    if (existe($dato->codigo)) {
        {
            try {
                $sql = "UPDATE RO_PEDIDOS_MAYORISTA_ASIGNADOS SET TIPO_COMP = '$tipoComp', EMBALAJE = '$embalaje', DESPACHO = '$despacho', 
                        ARREGLO = $arreglo, PRIORIDAD = $prioridad, FECHA_DESPACHO = '$fecha_despacho', FECHA_MODIF = '$fechaModif', HORA_MODIF = '$horaModif' 
                        WHERE NRO_PEDIDO = '$codigo'";
                sqlsrv_query($cid_central, $sql);
                // echo "Datos guardados exitosamente";  
            } catch (Exception $e) {
                echo 'Se produjo un Error:' . $e->getMessage();
            }
        }
    } else {
                $codClient=$dato->cod_client;
                $cliente=$dato->razon_soci;
                $localidad=$dato->localidad;
                $codVended=$dato->cod_vended;
                $vendedor=$dato->vendedor;
                $fecha=$dato->fecha;
                $hora=$dato->hora;
                $estado=$dato->estado;
                $talonario=$dato->talonario;
                $unidPedido=$dato->unidPedido;
                $unidPendiente=$dato->unidPendiente;
                $importePendiente=str_replace(".","",$dato->importePendiente);
               /*  $cantPend=5; */
                /* $importe=10000; */
                //faltan codClient, cliente, localidad,,codVendedor,vendedor
      /*   echo 'El nro de pedido no existe. Insertar.'; */
 
        try {
            $sql = "
    INSERT INTO DBO.RO_PEDIDOS_MAYORISTA_ASIGNADOS (FECHA, ESTADO, HORA_INGRESO, COD_CLIENT, RAZON_SOCI, LOCALIDAD, TALON_PED, NRO_PEDIDO, CANT_PEDIDO, CANT_PENDIENTE, IMP_PENDIENTE, 
    COD_VENDED, VENDEDOR, TIPO_COMP, EMBALAJE, DESPACHO, ARREGLO, PRIORIDAD, FECHA_MODIF, HORA_MODIF, FECHA_DESPACHO)
    VALUES ('$fecha', $estado,'$hora','$codClient','$cliente','$localidad', $talonario,'$codigo', $unidPedido, $unidPendiente, $importePendiente, '$codVended', '$vendedor','$tipoComp',
    '$embalaje','$despacho', $arreglo, $prioridad, '$fechaModif', '$horaModif','$fecha_despacho')

    ";
            $stmt=sqlsrv_query($cid_central, $sql);
            if( $stmt === false ) {
                die( print_r( sqlsrv_errors(), true));
           }
        } catch (Exception $e) {
            echo 'Se produjo un Error:' . $e->getMessage();
        }
    }
}
}

function existe($pedido)
{
    global $cid_central;
    $sql = "select * from RO_PEDIDOS_MAYORISTA_ASIGNADOS where nro_pedido='$pedido'";
    return sqlsrv_has_rows( sqlsrv_query($cid_central, $sql));

}

echo $codigo;

?>