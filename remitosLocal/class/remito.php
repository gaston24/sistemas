<?php

class Remito{

    function __construct() 
    {
        
        require_once $_SERVER['DOCUMENT_ROOT'] .'/sistemas/class/conexion.php';
        
        $conn = new Conexion();
        
        $this->cidLocal = $conn->conectar('local');

    }
    
   
    public function traerRemitos($suc){

        $codProCl= 'GT%';

        if(isset($_SESSION['usuarioUy']) && $_SESSION['usuarioUy'] == '1'){
            $codProCl = 'UR%';
        }

        $sql = "
        SET DATEFORMAT YMD
        SELECT N_COMP, NCOMP_IN_S, COD_PRO_CL
        FROM STA14 WHERE FECHA_MOV >= GETDATE()-30 AND N_COMP LIKE 'R%'
        AND COD_PRO_CL LIKE  '$codProCl'
        ORDER BY N_COMP DESC
        ";
       
        $stmt = sqlsrv_query( $this->cidLocal, $sql );

        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }


    public function traerRemito($suc, $ncompInS){


        $sql = "
        SET DATEFORMAT YMD

        SELECT FECHA, NRO_SUCURS, SUCURSAL_ORIGEN, SUC_DESTIN, SUCURSAL_DESTINO, N_COMP, SUM(CANTIDAD) CANTIDAD FROM
        (
        SELECT CAST(A.FECHA_MOV AS DATE) FECHA,
        (SELECT NRO_SUCURSAL FROM EMPRESA A INNER JOIN SUCURSAL B ON A.ID_SUCURSAL= B.ID_SUCURSAL) NRO_SUCURS,
        (SELECT DESC_SUCURSAL FROM EMPRESA A INNER JOIN SUCURSAL B ON A.ID_SUCURSAL= B.ID_SUCURSAL) SUCURSAL_ORIGEN, A.SUC_DESTIN, C.DESC_SUCURSAL SUCURSAL_DESTINO, A.N_COMP, CAST(CANTIDAD AS INT) CANTIDAD
        FROM STA14 A
        INNER JOIN STA20 B ON A.ID_STA14 = B.ID_STA14
        INNER JOIN SUCURSAL C ON A.SUC_DESTIN = C.NRO_SUCURSAL
        WHERE A.FECHA_MOV >= GETDATE()-30 AND A.N_COMP LIKE 'R%' AND A.NCOMP_IN_S = '$ncompInS'
        ) A
        GROUP BY FECHA, NRO_SUCURS, SUCURSAL_ORIGEN, SUC_DESTIN, SUCURSAL_DESTINO, N_COMP

        ";


       

        $stmt = sqlsrv_query( $this->cidLocal, $sql );


        $rows = array();

        while( $v = sqlsrv_fetch_array( $stmt) ) {
            $rows[] = $v;
        }

        return $rows;

    }

}