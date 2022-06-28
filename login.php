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
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style2.css">
<title>Login</title>

</head>




<body>
	<div id="contenedor">

	

	<header id="encabezado">
		<h1 align="center">XL GESTION</h1>

	</header>

	<aside id="logo">
		<img src="Controlador/logo.jpg">
	</aside>

	<?php 
		// if(get_the_browser() == 'Google Chrome'){
		if(1==1){
	?>
	
	<section id="contenido">
		<article>
			<form action="Controlador/validar.php" method="post">	
				<div class="container login-form">
					<h2 class="login-title">- Please Login -</h2>
					<div class="panel panel-default">
						<div class="panel-body">
							<form>
								<div class="input-group login-userinput">
									<span class="input-group-addon"><span class="fa fa-user mr-3" id="userIcon"></span></span>
									<input class="form-control" type="text" id="usuarioRegistrado" name="user" placeholder="Usuario" required autofocus>
								</div>
								<div class="input-group">
									<span class="input-group-addon"><span class="fa fa-lock mr-3" id="userIcon"></span></span>
									<input class="form-control" type="password" value="hunter2" id="example-password-input" placeholder="Contraseña" name="pass" required>
									<span id="showPassword" class="input-group-btn">
							<button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
						</span>  
								</div>
								<button class="btn btn-primary btn-block login-button" type="submit"><i class="fa fa-sign-in"></i> Ingresar</button>
								<div class="checkbox login-options mt-2">
									<label for="">Conexión con el local</label>
									<select id="inputState" name="conecta" >
										<option selected value="si">Si</option>
										<option value="no">No</option>
									</select>	
								</div>		
							</form>			
						</div>
					</div>
				</div>
										
				<div class="alert alert-danger mt-2" role="alert" id="label" style="display:none" >
				<a > No existe el usuario! </a>
				</div>


			</form>
		</article>
	</section>

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

<script>

window.onload = function(){$("#showPassword").hide();}

$("#txtPassword").on('change',function() {  
		if($("#txtPassword").val())
		{
			$("#showPassword").show();
		}
		else
		{
			$("#showPassword").hide();
		}
});

$(".reveal").on('click',function() {
    var $pwd = $("#txtPassword");
    if ($pwd.attr('type') === 'password') 
		{
        $pwd.attr('type', 'text');
    } 
		else 
		{
        $pwd.attr('type', 'password');
    }
});

</script>

</html>

