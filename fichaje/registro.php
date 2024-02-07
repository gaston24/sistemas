<!DOCTYPE html>
    <html lang="en">
    <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fichaje</title>
       
        <?php 
            require_once "assets/css/css.php";
            session_start();
      
        ?>
     <style>
     </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    </head>

    <body>

        <div class="alert alert-secondary">
           <div hidden id="sucursal"><?= $_SESSION['numsuc']?></div>
        </div>

        <?php 
            require_once "assets/js/js.php"
        ?>
        
        <!-- <input type="password" id="password" class="swal2-input" placeholder="Contraseña"> -->
        <script>
                Swal.fire({
                // title: 'Iniciar Sesión',
                html: `
                    <div class="circle-icon">
                        <i class="bi bi-clock" style="font-size: 40px; color: #FF4572;"></i>
                    </div>
                    <br>
                    <div><h3 style="font-style: italic">¡ Te damos la bienvenida !</h3></div>
                    <div>Por favor</div>
                    <div>Indicanos tu legajo y clave</div>
                    <input type="text" id="campo" class="swal2-input" onKeyup="buscarPorCampo(this)" style="width: 261.193182px;height: 52.818182px;font-size:13px" autocomplete="off" >
                    <div id="suggestions"></div>
                    <div class="password-input" autocomplete="off">
                        <input type="password" id="password" class="swal2-input" placeholder="Contraseña"  autocomplete="off">
                        <i class="bi bi-eye-slash toggle-password"></i>
                    </div>
                  

                `,
                allowOutsideClick: false, // Evita que se cierre al hacer clic fuera
                confirmButtonText: 'Cargar',
                preConfirm: () => {
                    let campo = document.querySelector('#campo').value;
                    let password = document.querySelector('#password').value;

                    login(campo, password);
                }
            });
          
        </script>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script>


        
    </body>

    </html>
