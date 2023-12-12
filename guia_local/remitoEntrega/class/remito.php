<?php

class Remito{

    public function traerRemito($nComp){
        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';

        $cid = new Conexion();
        $cid_central = $cid->conectar('central');  

        

        $sql = "
        SET DATEFORMAT YMD

        SELECT 
        CAST(A.FECHA_EMIS AS DATE) FECHA, 
        ISNULL(C.LEYENDA_2, '') NOMBRE, 
        A.N_COMP, ISNULL(C.NRO_PEDIDO, '') PEDIDO , CAST(A.IMPORTE AS DECIMAL(10,2)) IMP_COMPROBANTE, 
        ISNULL(F.CARD_FIRST_DIGITS+'-'+F.CARD_LAST_DIGITS, '') TARJETA , F.ISSUER BANCO, I.N_CUIT, 
        --I.C_POSTAL, I.DIRECCION_ENTREGA, I.DOMICILIO, 
		I.TELEFONO1_ENTREGA TELEFONO
        FROM GVA12 A
        INNER JOIN GVA55 B ON A.T_COMP = B.T_COMP AND A.N_COMP = B.N_COMP
        INNER JOIN GVA21 C ON B.TALON_PED = C.TALON_PED AND B.NRO_PEDIDO = C.NRO_PEDIDO
        LEFT JOIN NEXO_PEDIDOS_ORDEN D ON C.ID_NEXO_PEDIDOS_ORDEN = D.ID_NEXO_PEDIDOS_ORDEN   
        LEFT JOIN GC_ECOMMERCE_ORDER E ON D.order_id_tienda = E.ORDER_ID collate Latin1_General_BIN              
        LEFT JOIN GC_ECOMMERCE_PAYMENT_DETAIL F ON E.ID_GC_ECOMMERCE_ORDER = F.ID_GC_ECOMMERCE_ORDER 
        LEFT JOIN GVA38 I ON I.T_COMP = 'PED' AND C.NRO_PEDIDO = I.N_COMP
        WHERE A.COD_CLIENT = '000000'
        AND A.FECHA_EMIS >= GETDATE()-180
        AND (A.N_COMP = '$nComp')
        ";
        $stmt = sqlsrv_query( $cid_central, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        $sql2 = "
        SET DATEFORMAT YMD

        SELECT 
        G.COD_ARTICU, H.DESCRIPCIO, CAST(G.CANTIDAD AS INT) CANT, CAST(G.IMP_NETO_P * 1.21 AS DECIMAL(10,2)) IMP_ARTICULO
        FROM GVA12 A
        INNER JOIN GVA55 B ON A.T_COMP = B.T_COMP AND A.N_COMP = B.N_COMP
        INNER JOIN GVA21 C ON B.TALON_PED = C.TALON_PED AND B.NRO_PEDIDO = C.NRO_PEDIDO
        INNER JOIN GVA53 G ON A.T_COMP = G.T_COMP AND A.N_COMP = G.N_COMP
        INNER JOIN STA11 H ON G.COD_ARTICU = H.COD_ARTICU
        LEFT JOIN GVA38 I ON I.T_COMP = 'PED' AND C.NRO_PEDIDO = I.N_COMP
        WHERE A.COD_CLIENT = '000000'
        AND A.FECHA_EMIS >= GETDATE()-180
        AND (A.N_COMP = '$nComp')
        ";
        $stmt = sqlsrv_query( $cid_central, $sql2 );

        $art = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $art[] = $v;
        }

        $datos = array($rows, $art);

        return $datos;

    }

}