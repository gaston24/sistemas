<?php
 
class Conexion
{
    protected $info_locales=[];
    protected $local;
    protected $DB;
    protected $port;
    protected $User = 'sa';
    protected $Password ;
   

    function conectarLocal($nro_Suc,$Nombre,$local, $DB,$port)
    {
        $datos_Local=[];
        $cuotas_local=[];
        array_push($datos_Local,$nro_Suc,$Nombre);
        /* array_push($this->info_locales,$nro_Suc,$Nombre); */
        $this->local = $local;
        $this->DB = $DB;
        $this->port=$port;
        $this->Password='Axoft1988';
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port . ";Database=" .$this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sth = $conn->query("
            SELECT DISTINCT COD_TARJET ,CUOTA FROM TARJETA_COEFICIENTE_CUOTA  A
            
            INNER JOIN TARJETA_VIGENCIA_COEFICIENTE B
            
            ON A.ID_TARJETA_VIGENCIA_COEFICIENTE = B.ID_TARJETA_VIGENCIA_COEFICIENTE
            
            INNER JOIN SBA22 C
            
            ON B.ID_SBA22 = C.ID_SBA22
            
            WHERE C.COD_TARJET IN ('MST','VIS','AME')
            ");

            foreach($sth as $row){
                /* array_push($this->info_locales,$row); */
                array_push($cuotas_local,$row);
            }
            
           
        } catch (PDOException $e) {
            $msj=$e->getMessage() . ' Error en la conexión a ' .$Nombre;
            array_push($cuotas_local,[],$msj);

        }
        array_push($this->info_locales,array($datos_Local,$cuotas_local));
        $sth=null;
        $conn=null;
    }


   function conexionLocales()
    { 
        $this->local = 'LAKERBIS';
        $this->DB = 'LOCALES_LAKERS';
        $this->port='1433';
        $this->User = 'sa';
        $this->Password = 'Axoft';
       
        //traer los datos de conexión de los locales para luego recorrer cada una con la función conectarLocal()
        try {
            $conn = new PDO("sqlsrv:Server=" . $this->local . "," . $this->port. ";Database=" . $this->DB, $this->User, $this->Password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            $sth = $conn->query("SELECT NRO_SUCURSAL,DESC_SUCURSAL,CONEXION_DNS,BASE_NOMBRE  FROM SUCURSALES_LAKERS WHERE NRO_SUCURSAL < 100 AND HABILITADO=1 AND
            TIPO_LOCAL IN ('SHOPPING','CALLE')");
          
            foreach($sth as $row)
            {
                $Conexion=explode(',',$row['CONEXION_DNS']);
                $Direccion=$Conexion[0];
                $Puerto=$Conexion[1];
               $this->conectarLocal($row['NRO_SUCURSAL'],$row['DESC_SUCURSAL'],$Direccion,$row['BASE_NOMBRE'],$Puerto);
            }
           echo (json_encode($this->info_locales));
           /*  echo json_encode($this->info_locales); */
        } catch (PDOException $e) {
            echo $e->getMessage() . 'Error de conexión.' ;
        }
        $sth=null;
        $conn=null;
    }
}
