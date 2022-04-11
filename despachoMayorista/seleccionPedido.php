<?php

require 'Class/pedido.php';

$cliente = $_GET['cliente'];

$pedido = new Pedido();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <meta http-equiv="X-UA-Compatible" content="IE=edge"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selección de pedido</title>
    <link rel="icon" type="image/jpg" href="images/LOGO XL 2018.jpg">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <!-- Including Font Awesome CSS from CDN to show icons -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.1/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <h3 id="title_nota">
        <i class="fa fa-bars"></i> Pedidos de cliente: <?php echo $cliente ?>
    </h3>

    <form class="form-row mt-2">

        <div class="col-1">
            <a type="button" class="btn btn-warning" id="btn_back" href="javascript:history.back(-1)"><i class="fa fa-arrow-left"></i> Volver</a>
        </div>

        <div id="busqRapida2">
            <label id="textBusqueda">Busqueda rapida:</label>
            <input type="text" id="textbusq" placeholder="Sobre cualquier campo..." onkeyup="myFunction()" class="form-control form-control-sm"></input>
        </div>

        <div>
            <button type="button" class="btn btn-primary" id="btn_save">Guardar <i class="fa fa-save"></i></button>
        </div>

    </form>

    <?php

    $todosLosPedidos = $pedido->traerPedidosCliente($cliente);


    ?>

    <div class="table-responsive" id="tablePedidos">
        <table class="table table-hover table-condensed table-striped text-center">
            <thead class="thead-dark" style="font-size: small;">
                <th scope="col" style="width: 8%">FECHA</th>
                <th scope="col" style="width: 1%">HORA</th>
                <th scope="col" style="width: 1%">ESTADO</th>
                <th scope="col" style="width: 1%">TALONARIO</th>
                <th scope="col" style="width: 3%">NRO. PEDIDO</th>
                <th scope="col" style="width: 2%">UNID. PEDIDO</th>
                <th scope="col" style="width: 2%">UNID. PENDIENTE</th>
                <th scope="col" style="width: 2%">IMPORTE PEND.</th>
                <th scope="col" style="width: 4%">TIPO COMPROBANTE</th>
                <th scope="col" style="width: 7%">EMBALAJE</th>
                <th scope="col" style="width: 10%">DESPACHO</th>
                <th scope="col" style="width: 2%">ASIGNAR FECHA</th>
                <th scope="col" style="width: 1%;"></th>
            </thead>

            <tbody id="table" style="font-size: small;">
                <?php
                $todosLosPedidos = json_decode($todosLosPedidos);

                foreach ($todosLosPedidos as $valor => $value) {
                    // var_dump($value->FECHA);
                ?>


                    <tr>
                        <td><?= substr($value->FECHA->date, 0, 10); ?></td>
                        <td><?= $value->HORA_INGRESO; ?></td>
                        <td>
                            <?php if ($value->ESTADO >= 45) { ?><?= $value->ESTADO; ?><i class="fa fa fa-circle text-danger semaforo"></i>
                            <?php }
                            if ($value->ESTADO < 45 && $value->ESTADO >= 30) { ?><?= $value->ESTADO; ?><i class="fa fa fa-circle text-warning semaforo"></i>
                            <?php }
                            if ($value->ESTADO < 30) { ?><?= $value->ESTADO; ?><i class="fa fa fa-circle text-success semaforo"></i>
                        <?php } else {
                            } ?>
                        </td>
                        <td><?= $value->TALON_PED; ?></td>
                        <td><?= $value->NRO_PEDIDO; ?></td>
                        <td><?= $value->CANT_PEDIDO; ?></td>
                        <td><?= $value->CANT_PENDIENTE; ?></td>
                        <td id="importe"><?= $value->IMP_PENDIENTE; ?></td>
                        <td>
                            <?php if ($value->TIPO_COMP != '') { ?>
                                <select name="Comprobante" id="Comprobante" class="form-control form-control-sm edit" style="font-size: small;" disabled>
                                    <option value="" selected id="primerSelect"><?= $value->TIPO_COMP; ?></option>
                                    <option value="REMITO">REMITO</option>
                                    <option value="FACTURA">FACTURA</option>
                                </select>
                            <?php } else { ?>
                                <select name="Comprobante" id="Comprobante" class="form-control form-control-sm" style="font-size: small;" disabled>
                                    <option value="" selected></option>
                                    <option value="REMITO">REMITO</option>
                                    <option value="FACTURA">FACTURA</option>
                                </select>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if ($value->EMBALAJE != '') { ?>
                                <select name="Embalaje" id="Embalaje" class="form-control form-control-sm edit" style="font-size: small;" disabled>
                                    <option value="" disabled selected id="primerSelect"><?= $value->EMBALAJE; ?></option>
                                    <option value="BOLSA">BOLSA</option>
                                    <option value="CAJA">CAJA</option>
                                </select>
                            <?php } else { ?>
                                <select name="Embalaje" id="Embalaje" class="form-control form-control-sm" style="font-size: small;" disabled>
                                    <option value="" selected></option>
                                    <option value="BOLSA">BOLSA</option>
                                    <option value="CAJA">CAJA</option>
                                </select>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($value->DESPACHO != '') { ?>
                                <select name="Despacho" id="Despacho" class="form-control form-control-sm edit" style="font-size: small;" disabled>
                                    <option value="" selected id="primerSelect"><?= $value->DESPACHO; ?></option>
                                    <option value="CLIENTE">CLIENTE</option>
                                    <option value="FLETE">FLETE</option>
                                    <option value="TRANSPORTE">TRANSPORTE</option>
                                </select>
                            <?php } else { ?>
                                <select name="Despacho" id="Despacho" class="form-control form-control-sm" style="font-size: small;" disabled>
                                    <option value="" selected></option>
                                    <option value="CLIENTE">CLIENTE</option>
                                    <option value="FLETE">FLETE</option>
                                    <option value="TRANSPORTE">TRANSPORTE</option>
                                </select>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if (substr($value->FECHA_DESPACHO->date, 0, 10) != '1900-01-01') { ?>
                                <input name="" id="primerSelect" type="date" class="form-control form-control-sm edit" style="width: 90%;" value="<?= substr($value->FECHA_DESPACHO->date, 0, 10); ?>" disabled></input>
                            <?php } else { ?>
                                <input name="" id="" type="date" class="form-control form-control-sm" style="width: 90%;" disabled></input>
                            <?php } ?>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm btnEdit" href="#"><i class="fa fa-edit" style="font-size:20px;"></i></button>
                        </td>
                    </tr>

                <?php
                }
                ?>

            </tbody>
        </table>

    </div>

</body>
<script>
    window.addEventListener('DOMContentLoaded', iniciarEscucha);
    document.getElementById('btn_save').addEventListener('click', actualizarDB);

    let row;
    let datos = [];
    let select = document.querySelectorAll('.form-control');
    select.forEach(el => {
        el.addEventListener('change', guardarDatos)
    });

    function iniciarEscucha() {
        console.log('entraste');
        let edit = document.querySelectorAll(".btnEdit");
        /* let button = document.getElementById("btnEdit"); */
        // input.disabled = true;
        edit.forEach(ele => {
            ele.addEventListener('click', editarPedido);
        });
    }

    function editarPedido(pedido) {
        habilitarInputs(pedido);
        guardarDatos(pedido);

    }

    function habilitarInputs(input) {
        //columna 8 al 11
        let row = [];
        row = input.target.parentNode.parentNode;
        for (let i = 8; i < 12; i++) {
            row.children[i].children[0].disabled = false;
            /*  if(i==11)
             {
                 console.log(row.children[i].children[0].value);
             }else
             {
                 console.log(row.children[i].children[0].children[0].innerHTML);
             } */
            /* datos.push(row.children[i].children[0].children[0].children[0].innerHTML); */

        }

        /* console.log(datos); */
        document.getElementById("primerSelect").disabled = true;
    }

    /* infoPedido; */
    let Pedidos = [];

    function guardarDatos(row) {
        row = row.target.parentNode.parentNode;
        console.log(row);
        /* let indice; */
        if ((Pedidos.find((valor, indice) => {
                return valor.codigo == row.children[4].innerHTML
            })) == undefined) {
            const infoPedido = {
                codigo: row.children[4].innerHTML,
                tipoComp: row.children[8].children[0].children[0].innerHTML,
                embalaje: row.children[9].children[0].children[0].innerHTML,
                despacho: row.children[10].children[0].children[0].innerHTML,
                fechaDespacho: row.children[11].children[0].value
            };
            Pedidos.push(infoPedido);
            console.log(Pedidos);
        } else {
            console.log('actualizando');
            elementIndex = Pedidos.findIndex((pedido => pedido.codigo == row.children[4].innerHTML));
            Pedidos[elementIndex] = {
                codigo: row.children[4].innerHTML,//nro pedido
                fecha:row.children[0].children[0].innerHTML,
                hora:row.children[1].children[0].innerHTML,
                estado:row.children[2].children[0].innerText,
                talonario:row.children[3].children[0].innerHTML,
                unidPedido:row.children[5].children[0].innerHTML,
                unidPendiente:row.children[6].children[0].innerHTML,
                importePendiente:row.children[7].children[0].innerText.slice(2),
                tipoComp: (row.children[8].children[0].value != ''?row.children[8].children[0].value:row.children[8].children[0].children[0].innerHTML),
                embalaje: (row.children[9].children[0].value !=''?row.children[9].children[0].value: row.children[9].children[0].children[0].innerHTML),
                despacho: (row.children[10].children[0].value !=''?row.children[10].children[0].value:row.children[10].children[0].children[0].innerHTML),
                fechaDespacho: row.children[11].children[0].value
            };
            console.log(Pedidos[elementIndex]);
        }
    }

    //Funciones ajax para actualizar DB con los cambios en los pedidos
    let conexion;
    function actualizarDB() {
        conexion = new XMLHttpRequest();
        conexion.onreadystatechange = procesar;
        let datos=JSON.stringify(Pedidos);
        conexion.open('GET', './Controller/updateDatos.php?datos='+datos, true);
       /*    conexion.setRequestHeader('Content-type', 'application/json; charset=UTF-8') */
       
       /*  console.log(datos); */
        conexion.send();
    }


    function procesar() {
        if(conexion.readyState!==4) return; 
        if (conexion.status>=200 && conexion.status<300) {
            /* let nro_pedidos=Pedidos.map(); */
            Swal.fire({
                icon: 'success',
                title: 'Pedido modificado exitosamente!',
                text: "Numero de pedido: ",
                showConfirmButton: true,
                })
            .then(function () {
                location.reload() 
        });         
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Error de carga',
                text: 'No se modificó ningún pedido! '+conexion.responseText
                })
            .then(function () {
            location.reload() 
        }); 
        }

    }
    /*  function verificarCodigoCargadoEnArreglo(codigo) {

     } */
</script>
<script src="main.js" charset="utf-8"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


</html>