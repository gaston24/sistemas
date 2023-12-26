<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
$codClient = $_SESSION['codClient'];

if(!isset($_GET['fechaDesde'])){
	$fechaDesde = date("Y-m-d");
	$fechaHasta = date("Y-m-d");
}else{
	$fechaDesde = $_GET['fechaDesde'];
	$fechaHasta = $_GET['fechaHasta'];
}

include_once __DIR__.'/../class/remito.php';
$remitos = new Remito();
$remitosHistoricos = $remitos->traerHistoricos($codClient, $fechaDesde, $fechaHasta);

?>
<!DOCTYPE HTML>

<html>
<head>
<title>Control Remitos</title>	
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="shortcut icon" href="../../../css/icono.jpg" />
<?php require_once __DIR__.'/../ajustes/css/headers/include_remito.php' ?>
</head>
<body>	


<div class="container mt-2">


<div >
<form action="" method="GET" >

	<div class="form-group row" style="display: flex; ">
		
		<div class="col- col-sm-1">
			<button type="button" class="btn btn-primary btn-sm" onClick="window.location.href= 'index.php'">Inicio</button>
		</div>
		
		<label class="col- col-form-label mr-1 ml-3">Desde</label>
		<div class="col-">
			<input type="date" class="form-control" name="fechaDesde" value="<?= $fechaDesde;?>">
		</div>
		
		<label class="col- col-form-label mr-1 ml-2">Hasta</label>
		<div class="col-">
			<input type="date" class="form-control" name="fechaHasta" value="<?= $fechaHasta;?>">
		</div>
				
		<div class="col-sm-2 ">
			<input type="submit" class="btn btn-primary btn-sm" value="Consultar">
		</div>
		
		<label class="col- col-form-label mr-1">Busqueda Rapida:</label>
		<div id="busqueda">
			<input type="textBox" class="form-control form-control-sm" onkeyup="busquedaRapida()"  id="textBox" name="factura" placeholder="Sobre cualquier campo.." autofocus>
		</div>
		
	</div>

</form>

</div>


<?php

if(isset($_GET['fechaDesde'])){

?>
<div >

<form method="get" action="diferencias_procesar.php">

<table class="table table-striped"  id="tabla">

        
		<thead>
			<tr style="font-size:smaller">
				<th >FECHA<br>REMITO</th>
				<th >SUC<br>ORIGEN</th>
				<th >NRO<br>REMITO</th>
				<th >FECHA<br>CONTROL</th>
				<th >USUARIO</th>
				<!-- <th >DIF</th> -->
				<th >ESTADO</th>
				<th >AJUSTAR</th>
				<th >NRO AJUSTE</th>
				<th >CHAT</th>
			</tr>
		</thead>
		<tbody id="bodyTable">
        <?php
		foreach($remitosHistoricos as $data){
			$diferencia = $data['DIFERENCIA'] <> 0 ? '<strong>SI</strong>' : 'NO';
			$dateControl =$data['FECHA_CONTROL']->format('d/m/Y'); 
			$colorChat = 'primary';
			switch ($data['ULTIMO_CHAT']) {
				case 0:
					$colorChat = 'danger';
					break;
				case 1:
					$colorChat = 'success';
					break;
			}
		?>

		
	
        <tr class="fila-base" style="font-size:smaller" >

				<td ><?= $data['FECHA_REM']->format('d/m/Y');?></td>
				<td ><?= $data['DESC_SUCURSAL'] ;?></td>
				<td ><a href="controlHistoricosDetalle.php?numRem=<?= $data['NRO_REMITO'] ;?>">  <?= $data['NRO_REMITO'] ;?> </a></td>
				<td ><?= $dateControl ;?></td>
				<td ><?= $data['NOMBRE_VEN'] ;?></td>
				<!-- <td ><?= $diferencia ;?> </td> -->
				<td ><?= $data['OBSERVAC_LOGISTICA'] ;?> </td>
				<td ><?php if($data['OBSERVAC_LOGISTICA'] == 'PENDIENTE'){
					
					echo '
						<select id="inputAjustar" name="inputAjustar" onchange="actualizarAjusar(this)" class="form-select" aria-label="Default select example" style="text-align:center;width: 62.22222px;height: 32.22222px;"';
							if($data['NRO_AJUSTE'] != NULL)echo 'disabled';
						echo '>' ;
						echo'
							<option '; 
								if ($data['AJUSTAR'] == NULL)  echo "selected";
						echo ' value="" disabled></option>

							<option  value="SI"';
							if ($data['AJUSTAR'] == 'SI')  echo "selected"; 	
						echo'>Si</option>
							<option value="NO"';
							if ($data['AJUSTAR'] == 'NO')  echo "selected";
						echo'>No</option>
						</select>';

				}else{
					echo $data['AJUSTAR'];
				} ;?> </td>
				<td ><?= $data['NRO_AJUSTE'] ;?> </td>
				<td >
					<button data-toggle="modal" data-target="#chatModal" class="btn btn-<?=$colorChat?> btn-sm" type="button" onClick="getChat('<?= $data['NRO_REMITO'] ;?>'), actuaNumRemito('<?= $data['NRO_REMITO'] ;?>')">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-right-text" viewBox="0 0 16 16">
							<path d="M2 1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h9.586a2 2 0 0 1 1.414.586l2 2V2a1 1 0 0 0-1-1H2zm12-1a2 2 0 0 1 2 2v12.793a.5.5 0 0 1-.854.353l-2.853-2.853a1 1 0 0 0-.707-.293H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12z"/>
							<path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
						</svg>
					</button>
				</td>
				
        </tr>
		
        <?php

        }

        ?>
		</tbody>
     		
</table>


</form>

</div>

<?php

}

}

?>
</div>
<script src="main.js"></script>
<script src="js/chat.js"></script>
<script src="js/controlHistoricos.js"></script>
<script>

</script>
<!-- MODAL CHAT -->

<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Chat auditoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="numRemito" style="display:none"></div>
        <div id="chatShow"></div>
      </div>
      <div class="modal-footer">
		<input type="text" class="form-control mb-2" id="chatNew">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onClick="sendChat('<?=$user;?>'), actuaNumRemito('<?= $data['NRO_REMITO'] ;?>')">Enviar mensaje</button>
      </div>
    </div>
  </div>
</div>

<!--  -->

</body>
</html>