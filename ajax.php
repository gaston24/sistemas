<?php

$usuario = $_POST['validarUsuario'];

$dsn = '1 - CENTRAL';
$nom = 'sa';
$con = 'Axoft1988';

$sql = 
"
SELECT * FROM SOF_USUARIOS WHERE NOMBRE = '$usuario'
";

$cid = odbc_connect($dsn, $nom, $con);

$result = odbc_exec($cid, $sql);

if(odbc_num_rows($result)==1){
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