
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>crear usuario</title>

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
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="NOMBRE DE USUARIO" id="nombre"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Contraseña</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="CONTRASEÑA DEL USUARIO" id="contraseña"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Permisos</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="1-2-3-4-5-6" id="permisos"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Dsn</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="1-central" id="dsn"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Descripcion</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="descripcion"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Cod Cliente</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="codCliente"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Nro Sucursal</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="nroSucursal"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Cod Vendedor</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="codVendedor"></div>
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
                                                    <option value="si">si</option>
                                                    <option value="no">no</option>
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
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="codDeposito"></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                            <div class="row row-space" style="margin-top : 10px;">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="row">
                                            <div class="row"><h5>Tipo</h5></div>
                                            <div class="row"><input class="input--style-1 mayusc" type="text" placeholder="DESC" id="tipo"></div>
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
                                                    <option value="1">si</option>
                                                    <option value="0">no</option>
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
                                                    <option value="1">administracion</option>
                                                    <option value="2">cobranzas</option>
                                                    <option value="3">compras</option>
                                                    <option value="4">proveedores</option>
                                                    <option value="5">comercioExterior</option>
                                                    <option value="6">contabilidad</option>
                                                    <option value="7">logistica</option>
                                                    <option value="8">ecommerce</option>
                                                    <option value="9">abastecimiento</option>
                                                    <option value="10">comercial</option>
                                                    <option value="11">impuestos</option>
                                                    <option value="12">control de Stock</option>
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
                                                    <option value="1">administracion</option>
                                                    <option value="2">Supervision</option>
                                                    <option value="3">Operador</option>
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
            url: "controller/crearUsuario.php",
            method: "POST",
            data: {
                data:data
            },
    
        }).success(function (data) {

            window.location ="index.php";

        })
       
    })
</script>
