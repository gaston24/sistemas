<?php 

include_once '../class/usuario.php';

$data = $_POST['data'];

$usuario = new Usuario;

$result = $usuario->editarUsuario($data);

return $result;







?>