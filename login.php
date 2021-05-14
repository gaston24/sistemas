<?php
session_start();

if(isset($_SESSION['username'])){
		session_destroy();
}

include 'checkBrowser.php';
?>

<!doctype html>
<html charset="UTF-8">
<head>
<?php include '../css/header.php'; ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" href="css/style.css">
<title>Login</title>

</head>




<body>
	<div id="contenedor">

	

	<header>
		<h1 align="center">Gestión de Locales</h1>

	</header>

	<?php 
		// if(get_the_browser() == 'Google Chrome'){
		if(1==1){
	?>
	
	<section id="contenido">
		<article>
			<form action="Controlador/validar.php" method="post">
				<div class="form-group row">

				<div class="col-7">

					<input class="form-control" type="text" id="usuarioRegistrado" name="user" placeholder="Usuario" required autofocus>

				</div>

				</div>

				<div class="form-group row">

				<div class="col-7">

					<input class="form-control" type="password" value="hunter2" id="example-password-input" placeholder="Contraseña" name="pass" required>
					
				</div>
				
				</br>
				
				

				
				
				
				</div>
								
				
				</br>
				Conexión con el local
				
				
				<select id="inputState" name="conecta" >
				<option selected value="si">Si</option>
				<option value="no">No</option>
				</select>
				
				</br>
				</br>
				
				
				
				<button type="submit" class="btn btn-primary">Ingresar</button>



				<div class="alert alert-danger mt-2" role="alert" id="label" style="display:none" >
				<a > No existe el usuario! </a>
				</div>


			</form>
		</article>
	</section>
	
	</br>
	</br>
	</br>

	<div class="row">
	
	<div class="col"> 
	</div>
	
	<div class="col-md-12">
	
		<img src="Controlador/logo nuevo.jpg">
	
	</div>

	<?php
		}else{
	?>
		<div class="row">
			<h1 align="center">Para un correcto funcionamiento de la plataforma de carga de pedidos, por favor usar el navegador Google Chrome</h1>
		</div>
		<div class="row">
		<div class="col"></div>
		<div class="col">
			<img src="Controlador/chrome.jpg">
		</div>
		<div class="col"></div>
		</div>
	<?php
		}
	?>
	
		
	</div>
	
</body>

<script src="Controlador/validar_usuario.js"></script>


</html>

