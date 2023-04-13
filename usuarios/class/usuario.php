<?php

class Usuario {

    function __construct(){

        require_once __DIR__.'/../../class/conexion.php';
        $this->conn = new Conexion;
        
    }

    public function borrarUsuario($id){

        $cid = $this->conn->conectar('central');

        $sql = "DELETE FROM SOF_USUARIOS WHERE id = $id";

        try {

            $stmt = sqlsrv_prepare($cid,$sql);
            $stmt = sqlsrv_execute($stmt);
            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }


    }

    public function crearUsuario($data){

        $cid = $this->conn->conectar('central');

        $sql=
			"
			INSERT INTO SOF_USUARIOS (NOMBRE,PASS,PERMISOS,DSN,DESCRIPCION,COD_CLIENT,NRO_SUCURS,COD_VENDED,TANGO,COD_DEPOSI,TIPO,IS_OUTLET,sj_users_roles_id,sj_users_sectores_id) 
            VALUES ('".$data['nombre']."','".$data['contraseña']."','".$data['permisos']."','".$data['dsn']."','".$data['descripcion']."','".$data['codCliente']."','".$data['nroSucursal']."','".$data['codVendedor']."','".$data['tango']."','".$data['codDeposito']."','".$data['tipo']."','".$data['outlet']."','".$data['rol']."','".$data['sector']."')
			"
			;
        try {

            $stmt = sqlsrv_prepare($cid,$sql);
            $stmt = sqlsrv_execute($stmt);
            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }


    }
    public function editarUsuario($data){

        $cid = $this->conn->conectar('central');

        $sql= " UPDATE SOF_USUARIOS SET NOMBRE = '".$data['nombre']."', PASS = '".$data['contraseña']."', PERMISOS = '".$data['permisos']."', DSN = '".$data['dsn']."', DESCRIPCION = '".$data['descripcion']."', COD_CLIENT = '".$data['codCliente']."', NRO_SUCURS = '".$data['nroSucursal']."', COD_VENDED = '".$data['codVendedor']."', TANGO = '".$data['tango']."', COD_DEPOSI = '".$data['codDeposito']."', TIPO = '".$data['tipo']."', IS_OUTLET = '".$data['outlet']."',sj_users_roles_id = '".$data['rol']."' ,sj_users_sectores_id = '".$data['sector']. "' WHERE ID = ".$data['idUsuario']." ";

        try {

            $stmt = sqlsrv_prepare($cid,$sql);
            $stmt = sqlsrv_execute($stmt);
            return true;

        } catch (\Throwable $th) {

            print_r($th);

        }


    }

    public function traerUsuarios($id = null){

        $cid = $this->conn->conectar('central');
        

        $sql = " SELECT * FROM SOF_USUARIOS ";

        if($id) {

            $sql = " SELECT * FROM SOF_USUARIOS where id = $id ";

        }

        $stmt = sqlsrv_query($cid, $sql);

        try {

            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                $v[] = $row;

            }

            sqlsrv_close($cid);
    
            return $v;

        } catch (\Throwable $th) {

            print_r($th);

        }



    }

  

}