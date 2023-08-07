<?php 


require_once "controller/traerUsuarios.php";

$id = $_GET['id'];

$usuario = traerTodosLosUsuarios($id);
$usuario = $usuario[0];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>Editar usuario</title>

    <!-- Librerias Bootstrap -->
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
                        <!------------------------------------------------------------>

    <!-- Icons font CSS-->
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <!-- Vendor CSS-->
    <!-- <link href="assets/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="assets/datepicker/daterangepicker.css" rel="stylesheet" media="all"> -->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Main CSS-->
    
</head>

<body>
    <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
        <div class="wrapper wrapper--w680">
            <div class="card card-1">
                <div class="card-heading"></div>
                <div class="card-body" style="padding-left:900px;">
                    <h2 class="title"><i class="bi bi-folder-check"></i> Datos del Usuario</h2>
                            <div class="row row-space m-t-">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Nombre</h5></div>
                                            <div class="row" id="idUsuario" hidden><?= $usuario['ID'] ?></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="NOMBRE DE USUARIO" id="nombre" value="<?= $usuario['NOMBRE'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Contraseña</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="CONTRASEÑA DEL USUARIO" id="contraseña" required></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Permisos</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="1-2-3-4-5-6" id="permisos" value=" <?= $usuario['PERMISOS'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Dsn</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="1-central" id="dsn" value="<?= $usuario['DSN'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Descripcion</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="descripcion" value="<?= $usuario['DESCRIPCION'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Cod Cliente</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="codCliente" value ="<?= $usuario['COD_CLIENT'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Nro Sucursal</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="nroSucursal" value="<?= $usuario['NRO_SUCURS'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Cod Vendedor</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="codVendedor" value="<?= $usuario['COD_VENDED'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Tango</h5></div>
                                            <div class="row">
                                                <select id="tango">
                                                    <option value="si" <?php if ($usuario['TANGO'] == "SI"){ echo "selected"; }  ?> >si</option>
                                                    <option value="no" <?php if ($usuario['TANGO'] == "NO"){ echo "selected"; }  ?>>no</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Cod deposito</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="codDeposito" value="<?= $usuario['COD_DEPOSI'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Tipo</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="tipo" value="<?= $usuario['TIPO'] ?>"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>

                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Is Oulet</h5></div>
                                            <div class="row">
                                                <select id="outlet">
                                                    <option value="1" <?php if ($usuario['IS_OUTLET'] == "1"){ echo "selected"; }  ?>>si</option>
                                                    <option value="0" <?php if ($usuario['IS_OUTLET'] == "0"){ echo "selected"; }  ?>>no</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Sector</h5></div>
                                            <div class="row">
                                                <select id="sector">
                                                    <option value="1" <?php if ($usuario['sj_users_sectores_id'] == "1"){ echo "selected"; }  ?>>administracion</option>
                                                    <option value="2" <?php if ($usuario['sj_users_sectores_id'] == "2"){ echo "selected"; }  ?>>cobranzas</option>
                                                    <option value="3" <?php if ($usuario['sj_users_sectores_id'] == "3"){ echo "selected"; }  ?>>compras</option>
                                                    <option value="4" <?php if ($usuario['sj_users_sectores_id'] == "4"){ echo "selected"; }  ?>>proveedores</option>
                                                    <option value="5" <?php if ($usuario['sj_users_sectores_id'] == "5"){ echo "selected"; }  ?>>comercioExterior</option>
                                                    <option value="6" <?php if ($usuario['sj_users_sectores_id'] == "6"){ echo "selected"; }  ?>>contabilidad</option>
                                                    <option value="7" <?php if ($usuario['sj_users_sectores_id'] == "7"){ echo "selected"; }  ?>>logistica</option>
                                                    <option value="8" <?php if ($usuario['sj_users_sectores_id'] == "8"){ echo "selected"; }  ?>>ecommerce</option>
                                                    <option value="9" <?php if ($usuario['sj_users_sectores_id'] == "9"){ echo "selected"; }  ?>>abastecimiento</option>
                                                    <option value="10" <?php if ($usuario['sj_users_sectores_id'] == "10"){ echo "selected"; }  ?>>comercial</option>
                                                    <option value="11" <?php if ($usuario['sj_users_sectores_id'] == "11"){ echo "selected"; }  ?>>impuestos</option>
                                                    <option value="12" <?php if ($usuario['sj_users_sectores_id'] == "12"){ echo "selected"; }  ?>>control de Stock</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>ROL</h5></div>
                                            <div class="row">
                                                <select id="rol">
                                                    <option value="1" <?php if ($usuario['sj_users_roles_id'] == "1"){ echo "selected"; }  ?>>administracion</option>
                                                    <option value="2" <?php if ($usuario['sj_users_roles_id'] == "2"){ echo "selected"; }  ?>>Supervision</option>
                                                    <option value="3" <?php if ($usuario['sj_users_roles_id'] == "3"){ echo "selected"; }  ?>>Operador</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        
                            <div class="p-t-20">
                                <button class="btn btn-primary" id="btnSave" style="margin-top : 20px;width:400px" >Guardar <i class="bi bi-cloud-download"></i></button>
                            </div>
                        </div>
                    </div>
        </div>
    </div>

    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   
</body>

</html>


<script>

   const btnSave = document.querySelector('#btnSave');
    btnSave.addEventListener('click', () => {

        const idUsuario = document.querySelector("#idUsuario").textContent;
        const nombre = document.querySelector('#nombre').value;
        const contraseña = document.querySelector('#contraseña').value;
        const permisos = document.querySelector('#permisos').value;
        const dsn = document.querySelector('#dsn').value;
        const descripcion = document.querySelector('#descripcion').value;
        const codCliente = document.querySelector('#codCliente').value;
        const nroSucursal = document.querySelector('#nroSucursal').value;
        const codVendedor = document.querySelector('#codVendedor').value;
        const tango = document.querySelector('#tango').value;
        const codDeposito = document.querySelector('#codDeposito').value;
        const tipo = document.querySelector('#tipo').value;
        const outlet = document.querySelector('#outlet').value;
        const sector = document.querySelector('#sector').value;
        const rol = document.querySelector('#rol').value;

        const data = {
            idUsuario,
            nombre,
            contraseña,
            permisos,
            dsn,
            descripcion,
            codCliente,
            nroSucursal,
            codVendedor,
            tango,
            codDeposito,
            tipo,
            outlet,
            sector,
            rol
        }
  
        $.ajax({
            url: "controller/editarUsuario.php",
            method: "POST",
            data: {
                data:data
            },
    
        }).success(function (data) {

            window.location ="index.php";

        })
       
    })
</script>
