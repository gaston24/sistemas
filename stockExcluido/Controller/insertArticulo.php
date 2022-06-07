
<?php
session_start();
require_once '../Class/Conexion.php';
function insertarArticulo($codArticu, $descripcion, $cantidad, $observaciones)
{
  $fecha = Date('Y-m-d');
  date_default_timezone_set('Etc/GMT+3');
  $Object = new DateTime();
  $hora = $Object->format("G:i");

  $cid = new Conexion();
  $cid_central = $cid->conectar();

  $sql = "INSERT INTO SJ_EXCLUIDOS (FECHA, HORA_CARGA, COD_ARTICU, DESCRIPCION, CANT, OBSERVACIONES)
          VALUES ('$fecha','$hora','$codArticu','$descripcion',$cantidad,'$observaciones')

  ";
  $stmt = sqlsrv_query($cid_central, $sql);

  sqlsrv_execute($stmt);
}

if (isset($_GET['save'])) {
  $articulo_nuevo->insertarArticulo($_GET['articulo'], $_GET['descrip'], $_GET['cantidad'], $_GET['observaciones']);
}
?>