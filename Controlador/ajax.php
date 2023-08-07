<?php

$usuario = $_POST['validarUsuario'];


include_once __DIR__.'/../class/conexion.php';

$this->conn = new Conexion;

$cid = $this->conn->conectar('central');


$sql = 
"
SELECT * FROM SOF_USUARIOS WHERE NOMBRE = '$usuario'
";



$result = sqlsrv_query($cid, $sql);

if(sqlsrv_num_rows($result)==1){
//while($v=odbc_fetch_array($result)){
    echo 1;
}else{
    echo 0;
}


class Ajax{
    public $validarUsuario;
    public function validarUsuarioAjax(){

    }
}

?>