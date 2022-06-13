<?php
require_once '../Class/Conexion.php';

if (isset($_POST['articulos'])) {

try {
  
  $articulos = json_decode($_POST['articulos']);

  $fecha = Date('Y-m-d');
  date_default_timezone_set('Etc/GMT+3');
  $Object = new DateTime();
  $hora = $Object->format("G:i");
  $cid = new Conexion();
  $cid_central = $cid->conectar();

} catch (Exception $e) {
  echo 'Ocurrió un error'+$e->getMessage();
}
  foreach ($articulos as $art) {
    $sql = "INSERT INTO SJ_EXCLUIDOS (FECHA, HORA_CARGA, COD_ARTICU, DESCRIPCION, CANT, OBSERVACIONES)
  VALUES ('$fecha','$hora','$art[0]','$art[3]',$art[1],'$art[2]')
";
    $stmt = sqlsrv_query($cid_central, $sql);

    sqlsrv_execute($stmt);
  }
}
/* session_start(); */

/* function insertarArticulo($codArticu, $descripcion, $cantidad, $observaciones)
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
} */
