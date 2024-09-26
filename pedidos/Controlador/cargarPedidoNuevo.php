<?php


require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
$cid = new Conexion();
$cid_central = $cid->conectar('central');

require 'fecha.php';



$suc = $_POST['numsuc'];
$codClient = $_POST['codClient'];
$t_ped = $_POST['tipo_pedido'];
$depo = $_POST['depo'];
$talon_ped = 97;
$stringParaSql = '(';

if( substr($codClient, 0, 2) == 'MA' ){
	$cantidadPedida = 8;
}else{
	$cantidadPedida = 8;
}


foreach ($_POST['matriz'] as $key => $value) {

	$stringParaSql .= '"'.$value[1].'","'.$value[$cantidadPedida].'","'.$value[3].'","'.$value[4].'";';
}


$stringParaSql = substr($stringParaSql, 0, -1);
$stringParaSql .= ')';

// Cargar variables de entorno
require_once('../../class/classEnv.php');
$dotEnv = new DotEnv('../../.env');
$vars = $dotEnv->listVars();

$serverName = escapeshellarg($vars["HOST_CENTRAL"]);
$database = escapeshellarg($vars['DATABASE_CENTRAL']);
$user = escapeshellarg($vars['USER']);
$password = escapeshellarg($vars['PASS']);


// Crear un archivo temporal para la consulta
$tempFile = tempnam(sys_get_temp_dir(), 'sqlcmd_' . uniqid());
$query = "EXEC FU_PEDIDOS $suc, '$codClient', '$t_ped', '$depo', $talon_ped, '$stringParaSql'";
file_put_contents($tempFile, $query); // Guarda la consulta en un archivo

// Ruta donde quieres guardar el log
$logFile = $_SERVER['DOCUMENT_ROOT'].'/LOGSFU/LOG.log';

// Un mensaje de error o información que quieres registrar
$message = "query:$query fecha:" . date('Y-m-d H:i:s') . PHP_EOL;

// Escribe el mensaje en el archivo de log
error_log($message, 3, $logFile);
  

$command = "sqlcmd -S $serverName -d $database -U $user -P $password -i $tempFile";


// Ejecutar el comando
try {
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        throw new Exception("El comando sqlcmd falló con el código de retorno $returnVar. Salida: " . implode("\n", $output));
    }

} catch (\Throwable $th) {
    error_log("Error al ejecutar el SP: " . $th->getMessage());
    echo 'Error: ' . $th->getMessage();
}

// Eliminar el archivo temporal
unlink($tempFile);


$sql = "SELECT TOP 1 NRO_PEDIDO
FROM GVA21 
WHERE COD_CLIENT = '$codClient' 
ORDER BY ID_GVA21 DESC;
";

$result = sqlsrv_query($cid_central, $sql);

$result = sqlsrv_fetch_array($result);

$result = $result['NRO_PEDIDO'];

echo $result;


?>
