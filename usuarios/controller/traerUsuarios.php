<?php 

include_once './class/usuario.php';

function traerTodosLosUsuarios($id = null){

    $usuario = new Usuario;
    
    if($id){

        $result = $usuario->traerUsuarios($id);

    }else{

        $result = $usuario->traerUsuarios();

    }
    
    return $result;

}






?>