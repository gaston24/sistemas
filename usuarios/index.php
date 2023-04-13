<?php
require_once "controller/traerUsuarios.php";
$usuarios = traerTodosLosUsuarios();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control Gastos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="select2/select2.min.css">

    <script src="select2/select2.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    </link>

</head>

<body>

    <div class="alert alert-secondary">
        <div class="row">
            <div class="col">
            <div id="titlePrincipal" class="col-md-auto">
                <h3 class="title"><i class="bi bi-ui-checks"></i> Lista de usuarios</h3>
            </div>
            <div class="form-row">
                <form>
                    <div class="contenedor">
                    
                    </div>

                </form>
            </div>
            </div>
            <div class="col">
            <button id="btnCrear" >Crear Nuevo Usuario</button>    
            
            </div>
        </div>
    </div>




        <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
            <thead class="thead-dark">
                <tr>
                    <th style="position: sticky; top: 0; z-index: 10; width: 200px;" class="col-1">ID</th>
                    <th style="position: sticky; top: 0; z-index: 10;">CODIGO</th>
                    <th style="position: sticky; top: 0; z-index: 10;">NOMBRE</th>
                    <th style="position: sticky; top: 0; z-index: 10;">DESCRIPCION</th>
                    <th style="position: sticky; top: 0; z-index: 10;">TIPO</th>
                    <th style="position: sticky; top: 0; z-index: 10;"></th>
                    <th style="position: sticky; top: 0; z-index: 10;"></th>
                </tr>
            </thead>
            <tbody>
              
            <?php
                foreach ($usuarios as $valor => $key) {
   
            ?>
                    <tr>
                        <td><?= $key['ID'] ?></td>
                        <td><?= $key['COD_CLIENT'] ?></td>
                        <td><?= $key['NOMBRE'] ?></td>
                        <td><?= $key['DESCRIPCION'] ?></td>
                        <td><?= $key['TIPO']?></td>
                        <td><button value="delete" onclick='borrar(this)'>Delete</button></td>
                        <td><button value="delete" onclick='modificar(this)'>Editar</button></td>
                    </tr>

            <?php
                }
            ?>

            </tbody>
        </table>

    <script src="js/functions.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
<script>
    const btnCrear= document.querySelector("#btnCrear");
        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
            });
            
        });

        const borrar = (e)=>{
            let id = e.parentNode.parentNode.children[0].innerHTML;

            $.ajax({
                url: 'controller/borrarUsuario.php',
                method: 'POST',
                data:{
                    "id": id, 
                },
                });

        }

        const modificar = (e)=>{

            let id = e.parentNode.parentNode.children[0].innerHTML;

            window.location ="modificar.php?id="+id+"";
            
           
        }

    btnCrear.addEventListener("click", ()    => {

    window.location ="crear.php";
 
    });
</script>
