<!doctype html>
<html lang="en-US">
<head>
<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

	<meta charset="utf-8">

	<title>Login</title>

</head>


<body>

    <div class="container">

        <h1 align="center">Inicio - Control de ajustes</h1>

        <fieldset>

            <form action="validar.php" method="post">

             

				<div class="form-group row">
				<div class="col-3">
				<input class="form-control" type="text" id="example-text-input" name="user" placeholder="Usuario">
				</div>
				</div>
				
				
               
				
				<div class="form-group row">
				<div class="col-3">
				<input class="form-control" type="password" value="hunter2" id="example-password-input" placeholder="Contraseña" name="pass">
				</div>
				</div>

				<button type="submit" class="btn btn-primary">Ingresar</button>
              

                <footer class="clearfix">

                   

                </footer>

            </form>

        </fieldset>

    </div> 

</body>
</html>