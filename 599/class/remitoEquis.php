<?php

class RemitoEquis {

    function __construct(){

        require_once __DIR__.'/../../class/conexion.php';
        $this->conn = new Conexion;
        
    }

    public function traerTodos($campo = null){

        $cid = $this->conn->conectar('central');

        if($campo){
            $sql = "SET DATEFORMAT YMD SELECT * FROM SJ_EQUIS_TABLE WHERE (FECHA_MOV BETWEEN '2022-02-10 00:00:00.000' AND '2022-02-15 00:00:00.000') AND RAZON_SOCI LIKE '%$campo%' ORDER BY  COD_PRO_CL, N_COMP";

        }else{

            $sql = "SET DATEFORMAT YMD SELECT * FROM SJ_EQUIS_TABLE WHERE (FECHA_MOV BETWEEN '2022-02-10 00:00:00.000' AND '2022-02-15 00:00:00.000') ORDER BY  COD_PRO_CL, N_COMP";
        }

        $stmt = sqlsrv_query($cid, $sql);

     
        try {
            
            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;
    
            }
    
            return $v;
    
        }
        catch (\Throwable $th) {
            die("Error en sqlsrv_exec");
        }
    

    }


    public function traerDetalle($codClient){

        $cid = $this->conn->conectar('central');

        $sql = "SET DATEFORMAT YMD SELECT * FROM SJ_EQUIS_TABLE WHERE (FECHA_MOV BETWEEN '2022-02-10 00:00:00.000' AND '2022-02-15 00:00:00.000')  AND COD_PRO_CL = '$codClient'  ORDER BY  COD_PRO_CL, N_COMP";
        
        $stmt = sqlsrv_query($cid, $sql);

     
        try {
            
            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;
    
            }
    
            return $v;
    
        }
        catch (\Throwable $th) {
            die("Error en sqlsrv_exec");
        }
    


    }
    
    public function cambiarEstado ($remito) {
   
        $cid = $this->conn->conectar('central');
        $date = date('Y-m-d H\:i\:s.v');
        
        $sql = "UPDATE SJ_EQUIS_TABLE SET CHEQUEADO = '1' ,FECHA_CHEQUEADO ='$date' WHERE N_COMP = '$remito'";
        
        try {
            $stmt = sqlsrv_query($cid, $sql);
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }


    }

    public function cargarCheque ($data){

        $cid = $this->conn->conectar('central');

        $numeroDeInterno = $data['numeroDeInterno'];
        $numeroDeCheque = $data['numeroDeCheque'];
        $banco = $data['banco'];
        $chequeMonto = $data['chequeMonto'];
        $fechaCobro = $data['fechaCobro'];
        $codClient = $data['codClient'];

        
        $sql = "INSERT INTO sj_administracion_cheques (cod_client,num_interno, num_cheque, banco, monto, fecha_cheque) VALUES ('$codClient','$numeroDeInterno', '$numeroDeCheque', '$banco', '$chequeMonto', '$fechaCobro') ";
        
        try {
            $stmt = sqlsrv_query($cid, $sql);
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }
    
    public function traerCheque ($codClient){

        $cid = $this->conn->conectar('central');
        
        $sql = "SELECT * FROM sj_administracion_cheques WHERE cod_client = '$codClient' ORDER BY num_cheque DESC";
        
        try {

            $stmt = sqlsrv_query($cid, $sql);

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;
    
            }
            
            return $v;
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }
    

}