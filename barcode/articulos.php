<?php

class Codigos
{

    public function traerArticulo($codigo){
        try {

            $servidor_central = 'servidor';
            $conexion_central = array( "Database"=>"LAKER_SA", "UID"=>"sa", "PWD"=>"Axoft1988", "CharacterSet" => "UTF-8");
            $cid_central = sqlsrv_connect($servidor_central, $conexion_central);
             
         } catch (PDOException $e){
                 echo $e->getMessage();
         }
        $sql ="
        SELECT COD_ARTICU, DESCRIPCIO FROM STA11 WHERE (COD_ARTICU LIKE '[XO]%' AND COD_ARTICU LIKE '$codigo') AND USA_ESC!='B'
        ";
        $stmt = sqlsrv_query( $cid_central, $sql);

       
       if($row=sqlsrv_fetch_array($stmt))
       {
         echo $row['DESCRIPCIO'];
        }else{
            echo 'error';
        }
    }
     
    }

  
//'

if(isset($_GET['codigo']))
{
$r=new Codigos();

$r->traerArticulo($_GET['codigo']);
}