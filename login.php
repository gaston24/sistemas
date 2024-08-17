<?php
session_start();

if(isset($_SESSION['username'])){
	session_destroy();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>XL Gestion - Login</title>
	<link rel="shortcut icon" href="assets/css/icono.jpg" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            height: 80vh;
			margin-top:7rem;
        }
        .login-container {
            max-width: 400px;
            width: 100%;
            padding: 15px;
        }
        .form-control-icon {
            position: absolute;
            z-index: 2;
            display: block;
            width: 2.375rem;
            height: 2.375rem;
            line-height: 2.375rem;
            text-align: center;
            pointer-events: none;
            color: #aaa;
        }
        .form-control {
            padding-left: 2.375rem;
        }
        .input-group-append .btn {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .connection-row {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        .connection-row label {
            margin-right: 10px;
            margin-bottom: 0;
        }
        .connection-row select {
			flex-grow: 1;
			width: auto;
		}
		/* Media queries para responsividad */
		@media (max-width: 480px) {
		body {
			margin-top:2px;
		}
		}
    </style>
</head>
<body>
    <div class="login-container">
        <h1 class="text-center mb-4">XL GESTION</h1>
        <div class="text-center mb-4">
            <img src="Controlador/logo.jpg" alt="XL EXTRA LARGE" class="img-fluid" style="max-width: 200px;">
        </div>
        <form action="Controlador/validar.php" method="post">
            <div class="form-group position-relative">
                <i class="fas fa-user form-control-icon"></i>
                <input type="text" class="form-control" id="usuarioRegistrado" name="user" placeholder="Usuario" required autofocus>
            </div>
            <div class="form-group position-relative">
                <i class="fas fa-lock form-control-icon"></i>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="pass" placeholder="Contraseña" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
            <div class="connection-row">
                <label for="inputState">Conectar con local:</label>
                <select class="form-control" id="inputState" name="conecta">
                    <option selected value="si">Si</option>
                    <option value="no">No</option>
                </select>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
      
    </script>
</body>

<script>

document.getElementById('togglePassword').addEventListener('click', function (e) {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });


//Spinner listOrdenesActivas.php//
var btn = document.querySelectorAll('.login-button');
   btn.forEach(el => {
     el.addEventListener("click", ()=>{$("#boxLoading").addClass("loading")});
   })

</script>

</html>

