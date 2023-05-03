<?php

class RemitoEquis {

    function __construct(){

        require_once __DIR__.'/../../class/conexion.php';
        $this->conn = new Conexion;
        
    }

    public function traerTodos($campo = null){

        $cid = $this->conn->conectar('central');

        if($campo){
            $sql = "SET DATEFORMAT YMD SELECT A.*,C.rendido FROM SJ_EQUIS_TABLE A
            LEFT JOIN sj_administracion_cobros_por_remito B ON B.num_rem = A.N_COMP COLLATE Latin1_General_BIN
            LEFt JOIN sj_administracion_cobros C ON C.id = B.id_cobro WHERE (FECHA_MOV BETWEEN '2022-02-10 00:00:00.000' AND '2022-02-15 00:00:00.000') AND RAZON_SOCI LIKE '%$campo%'  AND C.rendido != 1 ORDER BY  COD_PRO_CL, N_COMP";

        }else{

            $sql = "SET DATEFORMAT YMD SELECT A.*,C.rendido FROM SJ_EQUIS_TABLE A
            LEFT JOIN sj_administracion_cobros_por_remito B ON B.num_rem = A.N_COMP COLLATE Latin1_General_BIN
            LEFt JOIN sj_administracion_cobros C ON C.id = B.id_cobro 
            WHERE (FECHA_MOV BETWEEN '2022-02-10 00:00:00.000' AND '2022-02-15 00:00:00.000') AND C.rendido != 1
            ORDER BY  COD_PRO_CL, N_COMP";
        }

        $stmt = sqlsrv_query($cid, $sql);

        $v = [];
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

        
        $sql = "UPDATE SJ_EQUIS_TABLE SET CHEQUEADO = '1' ,FECHA_CHEQUEADO =getdate() WHERE N_COMP = '$remito'";

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

        
        $sql = "INSERT INTO sj_administracion_cheques (cod_client, num_cheque, banco, monto, fecha_cheque) VALUES ('$codClient', '$numeroDeCheque', '$banco', '$chequeMonto', '$fechaCobro') ";
        
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

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;
    
            }
            
            return $v;
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }

    public function traerChequeNumInterno (){

        $cid = $this->conn->conectar('central');
        
        $sql = "SELECT MAX(ID) FROM sj_administracion_cheques";
        
        try {

            $stmt = sqlsrv_query($cid, $sql);

           $row = sqlsrv_fetch_array($stmt);
    
   
            return $row[0];
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }

    public function guardarCobro ($cod_client, $importe_efectivo, $importe_cheque, $importe_total,$nombreCliente) {
        
        $cid = $this->conn->conectar('central');
        
        $sql = "INSERT INTO sj_administracion_cobros (cod_client, importe_efectivo, importe_cheque, importe_total,rendido,nombre_cliente) VALUES ('$cod_client', '$importe_efectivo', '$importe_cheque', '$importe_total','0','$nombreCliente')SELECT SCOPE_IDENTITY()";

        try {

            $stmt = sqlsrv_query($cid, $sql);

            $next_result = sqlsrv_next_result($stmt);

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            return ( $v[0][""]);
    
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }

    public function guardarCobroRemito ($idCobro, $remito ) {

        $cid = $this->conn->conectar('central');
        
        $sql = "INSERT INTO sj_administracion_cobros_por_remito (id_cobro, num_rem,rendido,fecha_rendido) VALUES ('$idCobro', '$remito','0',getdate())";
   
        try {

            $stmt = sqlsrv_query($cid, $sql);

            return true;
    
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }

    public function traerRemito ($numRemito){

        $cid = $this->conn->conectar('central');
        
        $sql = "SELECT * FROM SJ_EQUIS_TABLE WHERE N_COMP in ($numRemito) ORDER BY FECHA_MOV ASC";

        try {

            $stmt = sqlsrv_query($cid, $sql);

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;
    
            }
            
            return $v;
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }

    public function traerTodosLosCheques (){

        $cid = $this->conn->conectar('central');
        
        $sql = "SELECT * FROM sj_administracion_cobros WHERE rendido = 0 ";

        try {

            $stmt = sqlsrv_query($cid, $sql);

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;
    
            }
            
            return $v;
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }

    public function rendirCobro ($id){

        $cid = $this->conn->conectar('central');
        
        $sql = "UPDATE sj_administracion_cobros SET rendido = 1,fecha_cobro = getdate() where id = '$id'";
   
        try {

            $stmt = sqlsrv_query($cid, $sql);
            
            return true;
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }

    public function asignarCobroPorCheque ($idCobro,$idCheque){

        $cid = $this->conn->conectar('central');
        
        $sql = "INSERT INTO sj_administracion_cobros_por_cheque (id_cobro, id_cheque) VALUES ('$idCobro', '$idCheque')";
        
   
        try {

            $stmt = sqlsrv_query($cid, $sql);
            
            return true;
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }


    public function traerReporteTodosLosCheques ($inputBuscar, $selectEstado, $desde, $hasta){

        $cid = $this->conn->conectar('central');

        $sql ="SET dateformat YMD SELECT * from sj_administracion_cobros A
        INNER JOIN sj_administracion_cheques B ON B.cod_client = A.cod_client COLLATE Latin1_General_BIN
        INNER JOIN sj_administracion_cobros_por_cheque E ON E.id_cobro = A.id AND E.id_cheque = B.id 
        WHERE A.rendido LIKE '%$selectEstado%' AND (A.nombre_cliente LIKE '%$inputBuscar%' OR B.id LIKE '%$inputBuscar%' OR B.banco LIKE '%$inputBuscar%' OR B.monto LIKE '%$inputBuscar%' OR B.num_cheque LIKE '%$inputBuscar%') AND A.fecha_cobro BETWEEN '$desde' AND '$hasta' ";
        try {

            $stmt = sqlsrv_query($cid, $sql);
   
            $v = [];

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {
    
                $v[] = $row;
    
            }
            
            return $v;
    
        }
        catch (\Throwable $th) {

            die("Error en sqlsrv_exec");

        }

    }
    
    public function traerEfectivoCheque () {

        $cid = $this->conn->conectar('central');

        $sql = " SELECT distinct a.* from sj_administracion_cobros a
        INNER JOIN sj_administracion_cobros_por_remito B ON B.id_cobro = a.id 
        where a.rendido = 0
        and b.num_rem in 
        (
        select distinct N_COMP collate Latin1_General_BIN from SJ_EQUIS_TABLE where CHEQUEADO = 1
        )";

        try {

            $stmt = sqlsrv_query($cid, $sql);

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }
            
            return $v;

        }
            catch (\Throwable $th) {

                die("Error en sqlsrv_exec");

        }
    }

    public function traerBancos () {

        $cid = $this->conn->conectar('central');

        $sql = "SELECT * FROM RO_T_BANCOS ";
        
        try {

            $stmt = sqlsrv_query($cid, $sql);

            $v = [];

            while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }
            
            return $v;

        }
            catch (\Throwable $th) {

                die("Error en sqlsrv_exec");

        }
    }
    
    public function listarDetalleRemitos ($desde, $hasta, $selectEstado, $inputBuscar, $selectTalonario ) {
        
        $cid = $this->conn->conectar('central');

        $sql = "SET DATEFORMAT YMD SELECT * FROM (
        SELECT A.*,estado =
        CASE
            WHEN D.ESTADO_MOV = 'A' THEN 'ANULADO'
            when A.CHEQUEADO =  1 AND C.rendido = 0 THEN 'COBRADO'
            when A.CHEQUEADO = 1 AND C.rendido = 1 THEN 'RENDIDO'
            WHEN A.CHEQUEADO = 0 THEN 'DEBE'
        END
        FROM SJ_EQUIS_TABLE A 
        left  join sj_administracion_cobros_por_remito B on a.N_COMP = B.num_rem collate Latin1_General_BIN
        left  join sj_administracion_cobros C on C.id = B.id_cobro
        left  join	STA14 D on D.N_COMP = A.N_COMP  
        )A
        WHERE (A.FECHA_MOV BETWEEN '$desde' AND '$hasta') AND (A.N_COMP LIKE '%$inputBuscar%' OR A.RAZON_SOCI LIKE '%$inputBuscar%' OR A.IMPORTE_TO LIKE '%$inputBuscar%' OR A.CANT_ART LIKE '%$inputBuscar%' OR A.GC_GDT_NUM_GUIA LIKE '%$inputBuscar%')
        AND A.estado LIKE '%$selectEstado%' AND A.N_COMP LIKE '%$selectTalonario%'";

try {
    
    $stmt = sqlsrv_query($cid, $sql);


            $v = [];

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