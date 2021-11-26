<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];
$codClient = $_SESSION['codClient'];

$estado = (isset($_GET['estado'])) ? $_GET['estado']: null;

if(!isset($_GET['fechaDesde'])){
	$fechaDesde = date("Y-m-d");
	$fechaHasta = date("Y-m-d");
}else{
	$fechaDesde = $_GET['fechaDesde'];
	$fechaHasta = $_GET['fechaHasta'];
}

include_once 'class/control.php';
$remitos = new Remito();
$remitosHistoricos = $remitos->traerHistoricosAuditoria($fechaDesde, $fechaHasta, $estado);

?>
<!DOCTYPE HTML>

<html>
<head>
<title>Control Remitos</title>	
<meta charset="utf-8">
<link rel="shortcut icon" href="../../../css/icono.jpg" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>

</head>
<body>	
<div class="container-fluid mt-3">

<div >
<form action="" method="GET" >

	<div class="form-group row">
		
		<div class="col">
			<button type="button" class="btn btn-primary btn-sm" onClick="window.location.href= '../conteos/index.php'">Inicio</button>
		</div>
		
		<label class="col">Desde:</label>
		<div class="col">
			<input type="date" class="form-control" name="fechaDesde" value="<?php echo $fechaDesde;?>">
		</div>
		
		<label class="col">Hasta:</label>
		<div class="col">
			<input type="date" class="form-control" name="fechaHasta" value="<?php echo $fechaHasta;?>">
		</div>
		
		<label class="col">Estado:</label>
		<div class="col">
			<select class="form-control"  name="estado" id="">
				<option value="%">Todos</option>
				<option value="PENDIENTE">Pendiente</option>
				<option value="ACEPTADO">Aceptado</option>
				<option value="RECHAZADO">Rechazado</option>
			</select>
		</div>
				
		<div class="col">
			<input type="submit" class="btn btn-primary btn-sm" value="Consultar">
		</div>
		
		<label class="col">Busqueda Rapida:</label>
		<div id="busqueda" >
			<input type="textBox" class="form-control form-control-sm" onkeyup="busquedaRapida()"  id="textBox" name="factura" placeholder="Sobre cualquier campo.." autofocus>
		</div>
		<div class="col"></div>
		
	</div>

</form>

</div>

		<table class="table table-striped " id="id_tabla">
		
		<thead>
			<tr style="font-size:smaller">
				<th >SUC CONTROLÓ</th>
				<th >NRO<br>REMITO</th>
				<th >FECHA<br>CONTROL</th>
				<th >CANT REM</th>
				<th >CANT CONTROL</th>
				<th >CANT DIF</th>
				<th >ESTADO</th>
				<th >NRO AJUSTE</th>
				<th >CHAT</th>
			</tr>
		</thead>
		<tbody id="bodyTable">
        <?php

		foreach($remitosHistoricos as $data){
		$dateControl = date_create($data[0]->FECHA_CONTROL);
		$dateControl = date_format($dateControl, 'Y-m-d H:i');
		$colorChat = 'primary';
		switch ($data[0]->ULTIMO_CHAT) {
			case 0:
				$colorChat = 'success';
				break;
			case 1:
				$colorChat = 'danger';
				break;
		}

		
		?>
		
        <tr class="fila-base" style="font-size:smaller" id="bodyTable">

				<td ><?= $data[0]->SUC_DESTIN ;?></td>
				<td ><a href="controlHistoricosDetalle.php?numRem=<?= $data[0]->NRO_REMITO ;?>">  <?= $data[0]->NRO_REMITO ;?> </a></td>
				<td ><?= $dateControl ;?></td>
				<td ><?= $data[0]->CANT_REM ;?></td>
				<td ><?= $data[0]->CANT_CONTROL ;?></td>
				<td ><?= $data[0]->DIFERENCIA ;?> </td>
								
				<td >
					<select class="form-control form-control-sm" id="select-<?= $data[0]->NRO_REMITO ;?>" onChange="changeStatus('<?= $data[0]->NRO_REMITO ;?>')" id="estadoRemito">
						<option value="PENDIENTE" <?php if($data[0]->OBSERVAC_LOGISTICA == 'PENDIENTE'){echo 'selected'; }?>>Pendiente</option>
						<option value="ACEPTADO" <?php if($data[0]->OBSERVAC_LOGISTICA == 'ACEPTADO'){echo 'selected'; }?>>Aceptado</option>
						<option value="RECHAZADO" <?php if($data[0]->OBSERVAC_LOGISTICA == 'RECHAZADO'){echo 'selected'; }?>>Rechazado</option>
					</select>
				</td>

				<td ><input type="text" size="8" class="form-control form-control-sm" id="nroAjuste" value="<?= $data[0]->NRO_AJUSTE ;?>" onChange="changeNroAjuste('<?= $data[0]->NRO_REMITO ;?>', this), validaAjuste()"> </td>

				<td >
					<button data-toggle="modal" data-target="#chatModal" class="btn btn-<?=$colorChat?> btn-sm" type="button" onClick="getChat('<?= $data[0]->NRO_REMITO ;?>'), actuaNumRemito('<?= $data[0]->NRO_REMITO ;?>')">
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

<script type="text/javascript" src="main.js"></script>
<script src="main.js"></script>
<script src="js/chat.js"></script>


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
        <button type="button" class="btn btn-primary" onClick="sendChat('<?=$user;?>'), actuaNumRemito('<?= $data[0]->FECHA_REM ;?>')">Enviar mensaje</button>
      </div>
    </div>
  </div>
</div>

<!--  -->


</div>
</body>

</html>


<?php
}
?>


