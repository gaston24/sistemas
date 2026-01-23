<?php
session_start(); 

if(!isset($_SESSION['username'])){

	header("Location:../../login.php");

}else{
	
		?>
		<!doctype html>
		<html>
		<head>
		<title>Carga de Pedidos - Accesorios</title>
		<link rel="stylesheet" href="css/preloader.css">
		<?php include '../../assets/css/header.php'; ?>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
		<style>
			body {
				padding-top: 95px;
				margin: 0;
				overflow-x: hidden;
				overflow-y: hidden;
			}
			.fixed-header {
				position: fixed;
				top: 0;
				left: 0;
				right: 0;
				z-index: 1000;
				background-color: #f8f9fa;
				padding: 10px 0;
				box-shadow: 0 2px 4px rgba(0,0,0,.1);
				margin-right: 1.5rem;
				margin-left: 1.5rem;
			}
			.search-box {
				position: relative;
			}
			.search-box input {
				height: 31px;
			}
			.clear-search {
				position: absolute;
				right: 10px;
				top: 50%;
				transform: translateY(-50%);
				cursor: pointer;
				color: #6c757d;
			}
			.page-title {
				font-size: 1.2rem;
				margin-bottom: 0.5rem;
			}
			.page-title + a {
				display: block;
				margin-bottom: 0.5rem;
				color: #6c757d;
				text-decoration: none;
			}
			.page-title + a:hover {
				color: #495057;
				text-decoration: underline;
			}
			.fixed-header .input-group-sm {
				height: 31px;
			}
			.fixed-header .input-group-sm .form-control,
			.fixed-header .input-group-sm .input-group-text {
				height: 31px;
				line-height: 1.5;
			}
			.fixed-header .btn-group-sm .btn {
				height: 31px;
				padding: 0.25rem 0.5rem;
				line-height: 1.5;
			}
			.fixed-header .row.align-items-center {
				align-items: center !important;
			}
			#id_tabla {
				margin-right: 1.5rem;
				margin-left: 1.5rem;
				width: calc(100% - 3rem);
				margin-top: 40px;
			}
			#id_tabla thead {
				background-color: #e9ecef;
			}
			#id_tabla thead th {
				background-color: #e9ecef;
			}
			form#formulario {
				margin-right: 1.5rem;
				margin-left: 1.5rem;
				width: calc(100% - 3rem);
			}
			form#formulario > div {
				margin-right: 0;
				margin-left: 0;
				width: 100%;
			}
			html, body {
				width: 100%;
				max-width: 100%;
			}
		</style>
		</head>
		<body>

		<?php

		require_once __DIR__.'/../../class/conexion.php';
		$cid = new Conexion();
		$cid_central = $cid->conectar('central');
		
		if($cid_central === false) {
			die("Error: No se pudo conectar a la base de datos.");
		}
		
		$suc = $_SESSION['numsuc'];
		
		$_SESSION['tipo_pedido'] = 'GENERAL';
		$_SESSION['depo'] = '01';
		
		$codClient = $_SESSION['username'];

		$sql="
		SET DATEFORMAT YMD
		
		EXEC SJ_TIPO_PEDIDO_CORDOBA_2_bis
		
		";

		$result = sqlsrv_query($cid_central, $sql);
		if($result === false) {
			die("Error en sqlsrv_query: " . print_r(sqlsrv_errors(), true));
		}
		
		// Los procedimientos almacenados pueden devolver múltiples conjuntos de resultados
		// Necesitamos obtener el siguiente conjunto de resultados
		$next_result = sqlsrv_next_result($result);
		if($next_result === false && sqlsrv_errors() !== null) {
			// Si hay errores, los mostramos, pero si es null significa que no hay más resultados (normal)
			$errors = sqlsrv_errors();
			if($errors !== null) {
				die("Error en sqlsrv_next_result: " . print_r($errors, true));
			}
		}

		?>

	<div class="fixed-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-auto">
					<h1 class="page-title mb-0">
						<i class="fas fa-shopping-cart"></i> Carga de Pedido
					</h1>
					<div>
						<a href="../../index.php" style="text-decoration: none; color: inherit;">
							<i class="fas fa-home"></i> Inicio
						</a>
					</div>
				</div>
			</div>
			<div class="row align-items-center">
				<div class="col-md-4">
					<div class="search-box">
						<input type="text" id="searchBox" class="form-control form-control-sm" placeholder="Buscar por código, descripción o rubro...">
						<i class="fas fa-times clear-search" id="clearSearch"></i>
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group input-group-sm">
						<span class="input-group-text">Total SKU</span>
						<input type="text" id="totalSKU" class="form-control" value="0" readonly>
					</div>
				</div>
				<div class="col-md-2">
					<div class="input-group input-group-sm">
						<span class="input-group-text">Total unidades</span>
						<input type="text" id="total" class="form-control" value="0" readonly>
					</div>
				</div>
				<div class="col-md-4 text-end">
					<div class="btn-group btn-group-sm" role="group">
						<button type="button" class="btn btn-secondary" id="btnGrabarPedido">
							<i class="fas fa-save"></i> Grabar
						</button>
						<button type="button" class="btn btn-secondary" id="btnCargarPedido">
							<i class="fas fa-upload"></i> Cargar
						</button>
						<button class="btn btn-success" id="btnExport">
							<i class="fas fa-file-excel"></i> Exportar
						</button>
						<button type="submit" class="btn btn-primary" id="btnEnviar">
							<i class="fas fa-cloud-upload-alt"></i> Enviar
						</button>
						<button id="sinConexion" class="btn btn-danger" style="display: none;">SIN CONEXIÓN</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- 	<form method="POST" action="cargarPedidoNuevoCordoba.php" onkeypress = "return pulsar(event)"> -->
	<form id='formulario' method="POST">
		<div style="width:100%">
		  
		<table class="table table-striped table-fh table-12c" id="id_tabla">
		
		<thead>
			
			<?php include 'encabezado.php'; ?>
			
		</thead>
		
		<tbody>
		<?php


		while($v = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

			include 'tabla.php';

		}

		?>

		</tbody>

		
		
		</table>
		</div>
		
		</form>

		<div class="mt-2 text-center fixed fixed-bottom bg-white" style="height: 30px!important; background-color: white;" id="pantalla">

			<a> <strong>Total de articulos:</strong> </a> <input name="total_todo" size="3" id="total" value="0" type="text">

			<a> <strong>Importe total:</strong> </a> <input name="total_precio" size="3" id="totalPrecio" value="0" type="text" onChange="verificarCredito()">
			<a id="cupoCreditoExcedido"></a>
			
			
		</div>

			
		<div class="container">
        <div class="cubo">
            <span style="display: flex; justify-content: center; align-items: center;">XL</span>
            <span style="display: flex; justify-content: center; align-items: center;">XL</span>
            <span style="display: flex; justify-content: center; align-items: center;">XL</span>
            <span></span>
            <span style="display: flex; justify-content: center; align-items: center;">XL</span>
            <span style="display: flex; justify-content: center; align-items: center;">XL</span>
          </div>
          <div>
            <div class="loading">
                <h1>Aguarde un momento...</d>
                <p></p>
            </div>
        </div>
      </div>
		
		
		
	</body>
	<script src="js/main.js"></script>
	<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

		</html>

		<?php

	
}
?>