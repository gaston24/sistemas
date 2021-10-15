<nav class="navbar navbar-expand-lg navbar-light bg-light">
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

            
           
            
            <?php if($habPedidos == 1 || $_SESSION['numsuc'] < 100 and $codClient !='FRMPOU')
            {
            ?>

                <!-- GENERALES -->
                <a class="dropdown-item" href="#" 
                <?php if($deposi != '00' ) 
				{?> 
				onclick="location.href='pedidos/pedidos_ecommerce.php?tipo=1'"<?php 
				}else{ 
				?> onclick="location.href='pedidos/pedidos.php?tipo=1'" <?php  
				} ?>
                >Generales</a>
                <!-- ACCESORIOS -->
                <a class="dropdown-item" href="#"
                <?php if($deposi != '00' )
                {?>
                    onclick="location.href='pedidos/pedidos_ecommerce.php?tipo=2'"<?php
                }else{
                    ?> onclick="location.href='pedidos/pedidos.php?tipo=2'" <?php
                } ?>
                >Accesorios</a>
                
            <?php    
            }
            ?>
            <?php 
		if($_SESSION['esOutlet'] == 1)
		{
        ?>
        <!-- OUTLET -->
        <a class="dropdown-item" href="#" onclick="location.href='pedidos/pedidos.php?tipo=3'">Outlet</a>    
        <?php
        }
        if($deposi != '00')
		{
            ?>
            <!-- DESABASTECIMIENTO -->
			<a class="dropdown-item" href="#" onclick="location.href='pedidos/desabastecimiento.php'">Desabastecimiento</a>   
			<?php
		}
        ?>
            <!-- HISTORIAL  -->
             <a class="dropdown-item" href="#" onclick="location.href='pedidos/historial.php'">Historial</a>   
            
          </div>
        </li>

        <!-- ECOMMERCE -->
        <?php
		if($_SESSION['tipo']!= 'MAYORISTA'){
		?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           Ecommerce
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" onclick="location.href='guia_local'">Seguimiento de Pedidos</a>
            <a class="dropdown-item" href="#" onclick="location.href='guia_local/buscar_factura.php'">Buscar Factura</a>
          </div>
        </li>
        <?php
        }
        ?>

            <?php
				if($_SESSION['tipo']!= 'MAYORISTA'){
			?>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
           Reportes
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" onclick="
                <?php
                if(substr($codClient, 0, 2) == 'FR'){
                    echo "location.href='guiasf/index.php'";
                }else{
                    echo "location.href='guia/index.php'";
                }
                ?>
            ">
            Guia de Transporte</a>
            <?php
            if($dashboard!= 'SIN_URL'){
                ?>
                    <a class="dropdown-item" href="#" onclick="window.open('<?php echo $dashboard ;?>');">Dashboard</a>
                <?php
            }
            ?>
            
          </div>
        </li>

        


        <?php 
		if($_SESSION['numsuc']<100){
        ?>	
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Operaciones
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" onclick="location.href='control/index.php'">Control de remitos</a>
            <a class="dropdown-item" href="#" onclick="location.href='remitosLocal/index.php'">Rotulo rotaciones</a>
            <a class="dropdown-item" href="#" onclick="location.href='talonarioFallas/index.php'">Talonario de fallas</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Utiles
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" onclick="location.href='procedimientos'">Procedimientos</a>
            </div>
        </li>
        <?php
        }
        ?>
        <?php 
		if($_SESSION['numsuc']>100 && $_SESSION['habPedidos']==1){
        ?>	
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Distribucion Inicial
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" onclick="location.href='inicial/cargaNuevo.php'">Pedido Inicial</a>
            </div>
        </li>
        <?php
        }
        ?>
        <?php 
		if(
            $_SESSION['numsuc']== 8 
            || $_SESSION['numsuc']== 16 
            || $_SESSION['numsuc']== 72 
            || $_SESSION['numsuc']== 76 
            || $_SESSION['numsuc']== 60
            || $_SESSION['numsuc']== 78
            || $_SESSION['numsuc']== 79
            || $_SESSION['numsuc']== 80
            ){
        ?>	
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Ajustes
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" onclick="location.href='ajustes/ajusteLocal.php'">Recodificaciones</a>
            </div>
        </li>
        <?php
        }
        ?>
        <?php 
		if($codClient == 'FRSAL1' || $codClient == 'FRSAL2' || $codClient == 'FRSAL3'){
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

        

      </ul>
    </div>
  </nav>