
<?php

    class nuevo_articulo{
  
    public function insertarArticulo($articulo,$descrip,$precio,$temporada){

      require_once 'Conexion.php';

      $cid = new Conexion();
      $cid_central = $cid->conectar();        

      $sql = "
      
      INSERT INTO DBO.RO_MAESTRO_PRECOMPRA_CUERO ([COD_ARTICU], [DESCRIPCIO], [PRECIO_ESTIMADO],[TEMPORADA]) VALUES 
      ('$articulo','$descrip','$precio', '$temporada');

      ";
      $stmt = sqlsrv_query( $cid_central, $sql );

      sqlsrv_execute( $stmt);
/* 
      return 'Articulo ingresado correctamente'; */


  }

}    



$articulo_nuevo = new nuevo_articulo();

$articulo_nuevo->insertarArticulo( $_GET['articulo'],$_GET['descrip'],$_GET['precio'], $_GET['temporada']);


  ?>
