
<?php

require_once '../Class/Conexion.php';
$cid = new Conexion();
$cid_central = $cid->conectar();
if (isset($_GET['datos'])) {
    //si el nro de pedido existe se procede a actualizar los campos. En caso de no existir el pedido en la tabla se realiza un insert.
    if (existe($_GET['datos'])) {
        $datos = json_decode(stripslashes($_GET['datos']));
        foreach ($datos as $dato) {
            $codigo = $dato->codigo;
            $tipoComp = $dato->tipoComp;
            $embalaje = $dato->embalaje;
            $despacho = $dato->despacho;
            $fecha = $dato->fecha;
            try {
                $sql = "UPDATE RO_PEDIDOS_MAYORISTA_ASIGNADOS SET TIPO_COMP = '$tipoComp', EMBALAJE = '$embalaje', DESPACHO = '$despacho', FECHA_DESPACHO = '$fecha' WHERE NRO_PEDIDO = '$codigo'";
                $stmt = sqlsrv_query($cid_central, $sql);
                echo "Datos guardados exitosamente";  
            } catch (Exception $e) {
                echo 'Se produjo un Error:' . $e->getMessage();
            }
        }
    } else {
        echo 'El nro de pedido no existe. Insertar.';
        /* try {
            $sql = "
    INSERT INTO DBO.RO_PEDIDOS_MAYORISTA_ASIGNADOS (FECHA, ESTADO, HORA_INGRESO, COD_CLIENT, RAZON_SOCI, LOCALIDAD, TALON_PED, NRO_PEDIDO, CANT_PEDIDO, CANT_PENDIENTE, IMP_PENDIENTE, 
    COD_VENDED, VENDEDOR, TIPO_COMP, EMBALAJE, DESPACHO, FECHA_DESPACHO)
    VALUES ('$fechaPed', $estado,'$HoraPed','$codClient','$cliente','$localidad', $talonPed, $nroPedido, $cantPedid, $cantPend, $importe, '$codVended', '$vendedor','$tipoComp',
    '$embalaje','$despacho','$fechaDespacho')

    ";
            $stmt = sqlsrv_query($cid_central, $sql);
        } catch (Exception $e) {
            echo 'Se produjo un Error:' . $e->getMessage();
        } */
    }
}

function existe($pedido)
{
    global $cid_central;
    $sql = "select * from RO_PEDIDOS_MAYORISTA_ASIGNADOS where nro_pedido='$pedido'";
    return sqlsrv_query($cid_central, $sql);
}


?>