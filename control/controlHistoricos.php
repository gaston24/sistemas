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

include_once 'class/control.php';
$remitos = new Remito();
$remitosHistoricos = $remitos->traerHistoricos($codClient, $fechaDesde, $fechaHasta);

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


<div class="container mt-2">


<div >
<form action="" method="GET" >

	<div class="form-group row">
		
		<div class="col-sm-1">
			<button type="button" class="btn btn-primary btn-sm" onClick="window.location.href= 'index.php'">Inicio</button>
		</div>
		
		<label class="col-sm-1 col-form-label">Desde</label>
		<div class="col-sm-2">
			<input type="date" class="form-control" name="fechaDesde" value="<?= $fechaDesde;?>">
		</div>
		
		<label class="col-sm-1 col-form-label">Hasta</label>
		<div class="col-sm-2">
			<input type="date" class="form-control" name="fechaHasta" value="<?= $fechaHasta;?>">
		</div>
				
		<div class="col-sm-2 ">
			<input type="submit" class="btn btn-primary btn-sm" value="Consultar">
		</div>
		
		<label class="col-sm col-form-label">Busqueda Rapida:</label>
		<div id="busqueda" >
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
				<th >DIF</th>
				<th >ESTADO</th>
				<th >NRO AJUSTE</th>
				<th >CHAT</th>
			</tr>
		</thead>
		<tbody id="bodyTable">
        <?php
		foreach($remitosHistoricos as $data){
			$diferencia = $data[0]->DIFERENCIA > 0 ? '<strong>SI</strong>' : 'NO';
			$dateControl = date_create($data[0]->FECHA_CONTROL);
			$dateControl = date_format($dateControl, 'Y-m-d H:i');
			$colorChat = 'primary';
			switch ($data[0]->ULTIMO_CHAT) {
				case 0:
					$colorChat = 'danger';
					break;
				case 1:
					$colorChat = 'success';
					break;
			}
		?>

		
		
        <tr class="fila-base" style="font-size:smaller" >

				<td ><?= $data[0]->FECHA_REM ;?></td>
				<td ><?= $data[0]->DESC_SUCURSAL ;?></td>
				<td ><a href="controlHistoricosDetalle.php?numRem=<?= $data[0]->NRO_REMITO ;?>">  <?= $data[0]->NRO_REMITO ;?> </a></td>
				<td ><?= $dateControl ;?></td>
				<td ><?= $data[0]->NOMBRE_VEN ;?></td>
				<td ><?= $diferencia ;?> </td>
				<td ><?= $data[0]->OBSERVAC_LOGISTICA ;?> </td>
				<td ><?= $data[0]->NRO_AJUSTE ;?> </td>
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


</form>

</div>

<?php

}

}

?>
</div>
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
        <button type="button" class="btn btn-primary" onClick="sendChat('<?=$user;?>'), actuaNumRemito('<?= $data[0]->NRO_REMITO ;?>')">Enviar mensaje</button>
      </div>
    </div>
  </div>
</div>

<!--  -->

</body>
</html>