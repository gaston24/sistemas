<!doctype html>
<html lang="en-US">
<head>
<!--<link rel="stylesheet" type="text/css" href="css/css.css">-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

	<meta charset="utf-8">

	<title>Login</title>

	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</head>


<body>

    <div class="container">

        <h1 align="center">Inicio - Control de ajustes</h1>

        <fieldset>

            <form action="validar.php" method="post">

                <!--<input type="text" required name="user" placeholder="User">  JS because of IE support; better: placeholder="Email" -->

				<div class="form-group row">
				<div class="col-3">
				<input class="form-control" type="text" id="example-text-input" name="user" placeholder="Usuario">
				</div>
				</div>
				
				
                <!--<input type="password" required name="pass" placeholder="Contraseña">  JS because of IE support; better: placeholder="Password" -->
				
				<div class="form-group row">
				<div class="col-3">
				<input class="form-control" type="password" value="hunter2" id="example-password-input" placeholder="Contraseña" name="pass">
				</div>
				</div>

				<button type="submit" class="btn btn-primary">Ingresar</button>
                <!--<input type="submit" value="Ingresar">-->

                <footer class="clearfix">

                    <!--<p><span class="info">?</span><a href="#">Forgot Password</a></p>-->

                </footer>

            </form>

        </fieldset>

    </div> <!-- end login-form -->

</body>
</html>