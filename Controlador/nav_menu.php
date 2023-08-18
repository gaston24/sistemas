<?php
include('estado_cuenta.php');
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" onCLick="window.location='index.php'"><i class="fad fa-home" title="INICIO"></i></a>
    <a class="navbar-brand" onCLick="window.location='login.php'"><i class="far fa-times-octagon" title="CERRAR SESION"></i></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <!-- PEDIDOS -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pedidos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">


                    <?php if ($habPedidos == 1 || $_SESSION['numsuc'] < 100) {
                    ?>

                        <!-- GENERALES -->
                        <a class="dropdown-item spinner" href="#" onClick="nuevoPedido('PEDIDO GENERAL', 1)" id="buttonPedidoGeneral">Generales</a>
                        <!-- ACCESORIOS -->
                        <a class="dropdown-item spinner" href="#" onClick="nuevoPedido('PEDIDO ACCESORIOS', 2)" id="buttonPedidoAccesorios">Accesorios</a>


                    <?php
                    }
                    ?>
                    <?php
                    if ($_SESSION['esOutlet'] == 1) {
                    ?>
                        <!-- OUTLET -->
                        <a class="dropdown-item spinner" href="#" onClick="nuevoPedido('PEDIDO OUTLET', 3)" id="buttonPedidoOutlet">Outlet</a>
                    <?php
                    } 

                    if ($deposi != '00') {
                    ?>
                        <!-- DESABASTECIMIENTO -->
                        <a class="dropdown-item" href="#" onclick="location.href='pedidos/desabastecimiento.php'">Desabastecimiento</a>
                    <?php
                    }
                    ?>
                    <!-- HISTORIAL  -->
                    <a class="dropdown-item spinner" href="#" onclick="location.href='pedidos/historial.php'">Historial</a>

                </div>
            </li>

         
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Consultas
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                    <a class="dropdown-item spinner" href="stockYprecios/consultaStockPrecios.php" onclick="location.href='guia_local'">Stock y Precios</a>
                </div>
            </li>
       
            <!-- ECOMMERCE -->
            <?php
            if ($_SESSION['tipo'] != 'MAYORISTA') {
            ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ecommerce
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item spinner" href="#" onclick="location.href='guia_local'">Registrar Pedidos</a>
                        <a class="dropdown-item spinner" href="#" onclick="location.href='guia_local/buscar_factura.php'">Buscar Factura</a>
                        <a class="dropdown-item spinner" href="#" onclick="location.href='../logistica/ecommerce/index.php'">Control pedidos</a>
                    </div>
                </li>
            <?php
            }
            ?>

            <?php
            if ($_SESSION['tipo'] != 'MAYORISTA') {
            ?>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Reportes
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item spinner" href="#" onclick="<?= (substr($codClient, 0, 2) == 'FR') ?  "location.href='guiasf/index.php'" : "location.href='guia/index.php'"; ?>">Guia de Transporte</a>

                        <?php
                        if ($dashboard != 'SIN_URL') {
                        ?>
                            <a class="dropdown-item spinner" href="#" onclick="window.open('<?php echo $dashboard; ?>');">Dashboard</a>
                        <?php
                        }
                        ?>
                        <?php if ($_SESSION['numsuc'] > 100) { ?> <a class="dropdown-item" data-toggle="modal" data-target="#dataFranquiciaModal" style="cursor:pointer">Estado de cuenta</a> <?php } ?>
                        <a class="dropdown-item spinner" href="#" onclick="location.href='maestroDestinos/index.php'">Maestro Destinos</a>
                    </div>
                </li>


                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Operaciones
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">

                        <?php if($_SESSION['connection_db'] != false) {?>      
                            <?php if ($_SESSION['numsuc'] < 100) { ?> <a class="dropdown-item" href="#" onclick="location.href='control/index.php'">Control de remitos</a> <?php } ?>
                        <?php } ?>

                        <?php if ($_SESSION['numsuc'] < 100) { ?> <a class="dropdown-item" href="#" onclick="location.href='remitosLocal/index.php'">Rotulo rotaciones</a> <?php } ?>
                        <?php if ($_SESSION['numsuc'] < 100) { ?> <a class="dropdown-item spinner" href="#" onclick="location.href='talonarioFallas/index.php'">Talonario de fallas</a> <?php } ?>
                        <a class="dropdown-item" href="#" onclick="location.href='barcode/index.html'">Etiq. codigo de barras</a>
                        <?php if ($_SESSION['numsuc'] < 100) { ?> <a class="dropdown-item spinner" href="#" onclick="location.href='controlFallas/seleccionDeSolicitudes.php'">Gestion de fallas</a> <?php } ?>
                        <?php if ($_SESSION['numsuc'] < 100) { ?> <a class="dropdown-item spinner" href="#" onclick="location.href='egresosDeCaja/egresosDeCaja.php'">Egresos de caja</a> <?php } ?>
                    </div>
                </li>


                <?php if ($_SESSION['numsuc'] < 100) { ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Utiles
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#" onclick="location.href='procedimientos'">Procedimientos</a>
                        </div>
                    </li>

                <?php } ?>

                <?php
                if ($_SESSION['numsuc'] > 100 && $_SESSION['habPedidos'] == 1) {
                ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Distribucion Inicial
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <!-- <a class="dropdown-item" href="#" onclick="location.href='inicial/cargaNuevo.php'">Pedido Inicial</a> -->
                            <a class="dropdown-item" href="#" onclick="location.href='DistriCuero/listOrdenesActivas.php'">Nota de pedido</a>
                        </div>
                    </li>
                <?php
                }
                ?>

                <?php
                if ($codClient == 'FRSAL1' || $codClient == 'FRSAL2' || $codClient == 'FRSAL3') {
                ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Estadisticas
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#" onclick="location.href='estadisticas/index.php'">Indices</a>
                            <a class="dropdown-item" href="#" onclick="location.href='estadisticas/ventas.php'">Ventas</a>
                        </div>
                    </li>
            <?php
                }
            }
            ?>
            <!-- spinner -->
		    <div id="boxLoading"></div>


        </ul>
    </div>
</nav>

<script>

    //Spinner listOrdenesActivas.php//
    var btn = document.querySelectorAll('.spinner');
    btn.forEach(el => {
     el.addEventListener("click", ()=>{$("#boxLoading").addClass("loading")});
   })

</script>