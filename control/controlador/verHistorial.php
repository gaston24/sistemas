
<?php 

require_once '../class/control.php';

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
$user = $data['user']; 

$control = new Remito();
$array = $control->traerHistorial($user);

echo json_encode($array);