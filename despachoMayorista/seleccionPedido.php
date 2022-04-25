<?php

require 'Class/pedido.php';

$cliente = $_GET['cliente'];
$razon_soci = $_GET['razon_soci'];
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
        <i class="fa fa-bars"></i> Pedidos de cliente: <?php echo $cliente . ' - ' . $razon_soci ?>
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
                <th scope="col" style="width: 1%">UNID. PEDIDO</th>
                <th scope="col" style="width: 1%">UNID. PENDIENTE</th>
                <th scope="col" style="width: 2%">IMPORTE PEND.</th>
                <th scope="col" style="width: 4%">TIPO COMPROBANTE</th>
                <th scope="col" style="width: 7%">EMBALAJE</th>
                <th scope="col" style="width: 10%">DESPACHO</th>
                <th scope="col" style="width: 5%">ARREGLO</th>
                <th scope="col" style="width: 5%">PRIORIDAD</th>
                <th scope="col" style="width: 2%">ASIGNAR FECHA</th>
                <th scope="col" style="width: 1%;"></th>
            </thead>

            <tbody id="table" style="font-size: small;">
                <?php
                $todosLosPedidos = json_decode($todosLosPedidos);
                ?>
                <input type="text" name="" id="cod_client" value="<?php echo $todosLosPedidos[0]->COD_CLIENT ?>" hidden>
                <input type="text" name="" id="razon_soci" value="<?php echo $todosLosPedidos[0]->RAZON_SOCI ?>" hidden>
                <input type="text" name="" id="localidad" value="<?php echo $todosLosPedidos[0]->LOCALIDAD ?>" hidden>
                <input type="text" name="" id="cod_vended" value="<?php echo $todosLosPedidos[0]->COD_VENDED ?>" hidden>
                <input type="text" name="" id="vendedor" value="<?php echo $todosLosPedidos[0]->VENDEDOR ?>" hidden>
                <?php
                foreach ($todosLosPedidos as $valor => $value) {

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
                                    <!--si value es igual a remito entonces mostrar remito
                                    si value es dintinto a remito entonces mostrar factura-->
                                    <option value="<?= $value->TIPO_COMP == 'REMITO' ? 'REMITO' : 'FACTURA' ?>" selected id="primerSelect"><?= $value->TIPO_COMP == 'REMITO' ? 'REMITO' : 'FACTURA' ?></option>
                                    <option value="<?= $value->TIPO_COMP == 'FACTURA' ? 'REMITO' : 'FACTURA' ?>"><?= $value->TIPO_COMP == 'FACTURA' ? 'REMITO' : 'FACTURA'  ?></option>
                                    <!--  <option value="otro" >otro</option> -->
                                    <!--  <option value="REMITO">REMITO</option> -->
                                    <!-- <option value="FACTURA">FACTURA</option> -->
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

                                    <option value="<?= $value->EMBALAJE == 'CAJA' ? 'CAJA' : 'BOLSA' ?>" selected id="primerSelect"><?= $value->EMBALAJE == 'CAJA' ? 'CAJA' : 'BOLSA' ?></option>
                                    <option value="<?= $value->EMBALAJE == 'BOLSA' ? 'CAJA' : 'BOLSA' ?>"><?= $value->EMBALAJE == 'BOLSA' ? 'CAJA' : 'BOLSA'  ?></option>
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
                                    <option value="<?= $value->DESPACHO == 'FLETE' ? 'FLETE' : ($value->DESPACHO == 'CLIENTE' ? 'CLIENTE' : 'TRANSPORTE') ?>" selected id="primerSelect"><?= $value->DESPACHO == 'FLETE' ? 'FLETE' : ($value->DESPACHO == 'CLIENTE' ? 'CLIENTE' : 'TRANSPORTE') ?></option>
                                    <option value="<?= $value->DESPACHO == 'FLETE' ? 'CLIENTE' : ($value->DESPACHO == 'TRANSPORTE' ? 'CLIENTE' : 'TRANSPORTE') ?>"><?= $value->DESPACHO == 'FLETE' ? 'CLIENTE' : ($value->DESPACHO == 'TRANSPORTE' ? 'CLIENTE' : 'TRANSPORTE')  ?></option>
                                    <option value="<?= $value->DESPACHO == 'TRANSPORTE' ? 'FLETE' : ($value->DESPACHO == 'FLETE' ? 'TRANSPORTE' : 'FLETE') ?>"><?= $value->DESPACHO == 'TRANSPORTE' ? 'FLETE' : ($value->DESPACHO == 'FLETE' ? 'TRANSPORTE' : 'FLETE') ?></option>

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
                            <?php if (isset($value->ARREGLO)) { ?>
                                <select name="Arreglo" id="Arreglo" class="form-control form-control-sm edit" style="font-size: small;" disabled>
                                    <option value="<?= $value->ARREGLO == 0 ? 0 : 1 ?>" selected id="primerSelect"><?= $value->ARREGLO == '0' ? 'NO' : 'SI' ?></option>
                                    <option value="<?= $value->ARREGLO == 0 ? 1 : 0 ?>"><?= $value->ARREGLO == '1' ? 'NO' : 'SI'  ?></option>
                                </select>
                            <?php } else { ?>
                                <select name="Arreglo" id="Arreglo" class="form-control form-control-sm" style="font-size: small;" disabled>
                                    <option value="null" selected></option>
                                    <option value="0">NO</option>
                                    <option value="1">SI</option>
                                </select>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if ($value->PRIORIDAD != '') { ?>
                                <select name="Prioridad" id="Prioridad" class="form-control form-control-sm edit" style="font-size: small;" disabled>
                                    <option value="<?= $value->PRIORIDAD == '1' ? '1' : ($value->PRIORIDAD == '2' ? '2' : '3') ?>" selected id="primerSelect"><?= $value->PRIORIDAD == '1' ? '1' : ($value->PRIORIDAD == '2' ? '2' : '3') ?></option>
                                    <option value="<?= $value->PRIORIDAD == '1' ? '2' : ($value->PRIORIDAD == '3' ? '2' : '3') ?>"><?= $value->PRIORIDAD == '1' ? '2' : ($value->PRIORIDAD == '3' ? '2' : '3')  ?></option>
                                    <option value="<?= $value->PRIORIDAD == '3' ? '1' : ($value->PRIORIDAD == '1' ? '3' : '1') ?>"><?= $value->PRIORIDAD == '3' ? '1' : ($value->PRIORIDAD == '1' ? '3' : '1') ?></option>   
                                </select>
                            <?php } else { ?>
                                <select name="Prioridad" id="Prioridad" class="form-control form-control-sm" style="font-size: small;" disabled>
                                    <option value="null" selected></option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            <?php } ?>
                        </td>

                        <td>
                            <?php if (substr($value->FECHA_DESPACHO->date, 0, 10) != '1900-01-01') { ?>
                                <input name="" id="primerSelect" type="date" class="form-control form-control-sm edit" style="width: 90%;" value="<?= substr($value->FECHA_DESPACHO->date, 0, 10); ?>" disabled></input>
                            <?php } else { ?>
                                <input name="" id="campoFecha" type="date" class="form-control form-control-sm" style="width: 90%;" disabled></input>
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
    establecerMinFecha(); //setear min en los campos fecha

    let row;
    let datos = [];
    let select = document.querySelectorAll('.form-control'); //almacenar select 
    select.forEach(el => {
        //guardar los cambios de un select junto con los datos del resto de la fila
        el.addEventListener('change', (el) => {
            guardarDatos(el.target.parentNode.parentNode)
        });
    });

    function iniciarEscucha() {
        let edit = document.querySelectorAll(".fa-edit");
        edit.forEach(ele => {
            ele.addEventListener('click', editarPedido, false);
        });
    }

    function editarPedido(pedido) {
        habilitarInputs(pedido);
        pedido = pedido.target.parentNode.parentNode.parentNode;
        guardarDatos(pedido);
    }

    function habilitarInputs(input) {
        let row = [];
        row = input.target.parentNode.parentNode.parentNode;
        for (let i = 8; i < 14; i++) {
            row.children[i].children[0].disabled = false;

            if (row.children[i].children[0].constructor != HTMLInputElement) {
                row.children[i].children[0].children[0].disabled = true;
            }
        }
    }


    let Pedidos = [];

    function guardarDatos(row) {
        //row es la fila con el cambios en el pedido
        /* row = row.target.parentNode.parentNode; */
        console.log(row);
        if ((Pedidos.find((valor, indice) => {
                return valor.codigo == row.children[4].innerHTML
            })) == undefined) {
            //si el pedido no se encuentra en el array pedidos entonce almaceno los valores en el objeto 
            const infoPedido = {
                codigo: row.children[4].innerHTML,
                fecha: row.children[0].innerHTML,
                hora: row.children[1].innerHTML,
                cod_client: document.getElementById('cod_client').value,
                razon_soci: document.getElementById('razon_soci').value,
                localidad: document.getElementById('localidad').value,
                cod_vended: document.getElementById('cod_vended').value,
                vendedor: document.getElementById('vendedor').value,
                estado: row.children[2].innerText,
                talonario: row.children[3].innerHTML,
                unidPedido: row.children[5].innerHTML,
                unidPendiente: row.children[6].innerHTML,
                importePendiente: row.children[7].innerText.slice(2),
                tipoComp: row.children[8].children[0].children[0].innerHTML,
                embalaje: row.children[9].children[0].children[0].innerHTML,
                despacho: row.children[10].children[0].children[0].innerHTML,
                arreglo: row.children[11].children[0].children[0].innerHTML,
                prioridad: row.children[12].children[0].children[0].innerHTML,
                fechaDespacho: row.children[13].children[0].value
            };
            //guardar el pedidos editado en el array Pedidos
            Pedidos.push(infoPedido);
            /* console.log(Pedidos); */
        } else {
            //si el pedido ya se editó, y se vuelve a editar, se realiza un update de los valores dentro del objeto en el array Pedidos
            console.log('actualizando');
            elementIndex = Pedidos.findIndex((pedido => pedido.codigo == row.children[4].innerHTML));
            Pedidos[elementIndex].tipoComp = (row.children[8].children[0].value != '' ? row.children[8].children[0].value : row.children[8].children[0].children[0].innerHTML);
            Pedidos[elementIndex].embalaje = (row.children[9].children[0].value != '' ? row.children[9].children[0].value : row.children[9].children[0].children[0].innerHTML);
            Pedidos[elementIndex].despacho = (row.children[10].children[0].value != '' ? row.children[10].children[0].value : row.children[10].children[0].children[0].innerHTML),
            Pedidos[elementIndex].arreglo = (row.children[11].children[0].value != '' ? row.children[11].children[0].value : row.children[11].children[0].children[0].innerHTML),
            Pedidos[elementIndex].prioridad = (row.children[12].children[0].value != '' ? row.children[12].children[0].value : row.children[12].children[0].children[0].innerHTML),
            Pedidos[elementIndex].fechaDespacho = row.children[13].children[0].value;
        }
        console.log(Pedidos);
    }

    let conexion;

    function actualizarDB() {
        // si el arreglo Pedidos se encuentra vacio entonces no hubo ningun pedido editado. 
        if (Pedidos.length > 0) {
            //verificar si los campos en el pedido editado no están vacios 
            checkInpustNoVacios();
            if (b == 0) {
                conexion = new XMLHttpRequest();
                conexion.onreadystatechange = procesar;
                let datos = JSON.stringify(Pedidos);
                conexion.open('GET', './Controller/updateDatos.php?datos=' + datos, true);
                conexion.send();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Campos vacios',
                    text: 'Deben completar los datos'
                })
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'No se modificaron datos',
                text: 'Cargue datos y haga click en guardar'
            })

        }
    }


    function procesar() {
        if (conexion.readyState !== 4) return;
        if (conexion.status >= 200 && conexion.status < 300) {
            Swal.fire({
                    icon: 'success',
                    title: 'Pedido modificado exitosamente!',
                    text: "Numero de pedido: "+ conexion.responseText,
                    showConfirmButton: true,
                })
                .then(function() {
                    location.reload()
                });
        } else {
            Swal.fire({
                    icon: 'error',
                    title: 'Error de carga',
                    text: 'No se modificó ningún pedido! ' + conexion.responseText
                })
                .then(function() {
                    location.reload()
                });
        }

    }
    //bandera que indica si el pedido se encuentra con campos vacios o no. 1 indica que existen campos sin completar. 
    let b;

    function checkInpustNoVacios() {
        b = 0;
        let select = document.querySelectorAll('select');
        let date = dia = document.querySelectorAll('[type=date]');
        select.forEach(el => {
            if(el.parentElement.innerHTML.includes('Prioridad')==false && el.parentElement.innerHTML.includes('Arreglo')==false )
            {
            if (el.disabled == false) {
                if (el.value == '' ) {
                    el.style.borderColor = 'red';
                    b = 1;
                } else {
                    el.style.borderColor = '';
                }
            }
        }});
        date.forEach(el => {
            if ((el.disabled == false) && (el.value == '')) {
                el.style.borderColor = 'red';
                b = 1;
            } else {
                el.style.borderColor = '';
            }
        })
    }


    function establecerMinFecha() {
        let today = new Date();
        let dd = today.getDate();
        let mm = today.getMonth() + 1;
        let yyyy = today.getFullYear();
        if (dd < 10) {
            dd = '0' + dd
        }
        if (mm < 10) {
            mm = '0' + mm
        }
        today = yyyy + '-' + mm + '-' + dd;
        let date = document.querySelectorAll('[type=date]');
        date.forEach(el => {
            el.setAttribute("min", today);
        });
    }
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