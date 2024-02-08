<?php

class Codigos
{

      
    function __construct()
    {   


        require_once $_SERVER['DOCUMENT_ROOT'].'/sistemas/class/conexion.php';
        $this->cid = new Conexion();
        $db= 'central';
        session_start();
        if($_SESSION['usuarioUy'] == 1){
            $db = 'uy';
        }
        $this->cid_central = $this->cid->conectar($db);

    } 

    public function traerArticulo($codigo){
    
        $sql ="
        SELECT COD_ARTICU, DESCRIPCIO FROM STA11 WHERE (COD_ARTICU LIKE '[XO]%' AND COD_ARTICU LIKE '$codigo') AND USA_ESC!='B'
        ";
        $stmt = sqlsrv_query( $this->cid_central, $sql);

       
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