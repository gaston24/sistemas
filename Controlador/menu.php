<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Pedidos</li>
		<?php
		if($codClient != 'FRFUL4' && $codClient != 'FRREM2' && $codClient != 'FRMPO')
		{
			?>
			<button type="button" class="btn btn-secondary" 
				<?php if($deposi != '00' ) 
				{?> 
				onclick="location.href='pedidos/pedidos_ecommerce.php?tipo=1'"<?php 
				}else{ 
				?> onclick="location.href='pedidos/pedidos.php?tipo=1'" <?php  
				} ?>
			>Generales</button>
            <button type="button" class="btn btn-secondary"
                <?php if($deposi != '00' )
                {?>
                    onclick="location.href='pedidos/pedidos_ecommerce.php?tipo=2'"<?php
                }else{
                    ?> onclick="location.href='pedidos/pedidos.php?tipo=2'" <?php
                } ?>
            >Accesorios</button>

			<?php
		}
		?>
		<?php 
		if(
		($_SESSION['numsuc']== 8 || $_SESSION['numsuc']== 11 || $_SESSION['numsuc']== 16 || $_SESSION['numsuc']== 72 || $_SESSION['numsuc']== 76 || $_SESSION['numsuc']== 60 || 
		$_SESSION['numsuc']== 700 || $_SESSION['numsuc']== 701 || $_SESSION['numsuc']== 808 || $_SESSION['numsuc']== 900 || $_SESSION['numsuc']== 912
		|| $_SESSION['numsuc']== 885)
		&&($codClient != 'FRMDP')
		)
		{
			?>
			<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/pedidos.php?tipo=3'">Outlet</button>
			<?php
		}
		if($deposi != '00')
		{
			?>
			<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/desabastecimiento.php'">Desabastecimiento</button>
			<?php
		}
		?>
		<button type="button" class="btn btn-secondary" onclick="location.href='pedidos/historial.php'">Historial</button>
		
	</div>
</ul>

<br>

<?php
		if($_SESSION['numsuc']<100){
			?>
			<ul class="nav justify-content-center">
				<div class="btn-group" role="group" aria-label="Basic example">
				<li class="list-group-item">Ecommerce</li>
				<button type="button" class="btn btn-secondary" onclick="location.href='guia_local'">Seguimiento Pedidos</button>
				<button type="button" class="btn btn-secondary" onclick="location.href='guia_local'">Buscar Factura</button>
				<div>
			</ul>	
			<?php
		}
		?>

</br>

<ul class="nav justify-content-center">
	<div class="btn-group" role="group" aria-label="Basic example">
		<li class="list-group-item">Reportes</li>
		
		<button type="button" class="btn btn-secondary" onclick="
		<?php
		if(substr($codClient, 0, 2) == 'FR'){
			echo "location.href='guiasf/index.php'";
		}else{
			echo "location.href='guia/index.php'";
		}
		?>">Guias de Transporte</button>
		<?php
		if($dashboard!= 'SIN_URL'){
			?>
				<button type="button" class="btn btn-secondary" onclick="window.open('<?php echo $dashboard ;?>');">Dashboard</button>
			<?php
		}
		?>
		
		
	</div>
</ul>
</br>

<?php 
		if($_SESSION['numsuc']>104){
?>		
		
			<ul class="nav justify-content-center">
				<div class="btn-group" role="group" aria-label="Basic example">
					<li class="list-group-item">Distribucion Inicial</li>
					<button type="button" class="btn btn-secondary" onclick="location.href='inicial/cargaNuevo.php'">Pedidos</button>
				</div>
			</ul>
			</br>
<?php
		}elseif($_SESSION['numsuc']== 8 || $_SESSION['numsuc']== 16 || $_SESSION['numsuc']== 72 || $_SESSION['numsuc']== 76 || $_SESSION['numsuc']== 60){
?>
			<ul class="nav justify-content-center">
				<div class="btn-group" role="group" aria-label="Basic example">
					<li class="list-group-item">Ajustes</li>
					<button type="button" class="btn btn-secondary" onclick="location.href='ajustes/ajusteLocal.php'">Recodificaciones</button>
				</div>
			</ul>
			</br>
<?php
		}
?>




<?php 
		if($_SESSION['numsuc']<100){
?>		
		
			<ul class="nav justify-content-center">
				<div class="btn-group" role="group" aria-label="Basic example">
					<li class="list-group-item">Control Stock</li>
					<button type="button" class="btn btn-secondary" onclick="location.href='control/index.php'">Remitos</button>
				</div>
			</ul>
			</br>
<?php
		}
?>


<?php 
		if($codClient == 'FRSAL1' || $codClient == 'FRSAL2' || $codClient == 'FRSAL3'){
?>		
		
			<ul class="nav justify-content-center">
				<div class="btn-group" role="group" aria-label="Basic example">
					<li class="list-group-item">Estadisticas</li>
					<button type="button" class="btn btn-secondary" onclick="location.href='estadisticas/index.php'">Indices</button>
					<button type="button" class="btn btn-secondary" onclick="location.href='estadisticas/ventas.php'">Ventas</button>
				</div>
			</ul>
			</br>
<?php
		}
?>

</ul>


