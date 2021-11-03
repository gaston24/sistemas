<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{

require_once 'Class/Local.php';
$local = new Local();
$localesOutlet = $local->traerLocales();


?>
<!DOCTYPE HTML>

<html>
<head>

<meta charset="utf-8">
<title>Seleccionar Local</title>	

<link rel="shortcut icon" href="icono.jpg" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

</head>
<body>	
    <div class="container">

        <div align="center" style="margin-top:50px"> 
            <img src="../Controlador/logo.jpg">
        </div>

        <div class="row mt-5">
            <div class="col text-right">Seleccione un local:</div>
            <div class="col">
                
                <form action="ajusteLocal.php" method="post">
            
                    <div class="row">
                        <div class="col">
                            <select class="form form-control" name="localSeleccionado" id="">
                            <?php
                            foreach ($localesOutlet as $key => $value) {
                            ?>
                                <option value="<?=$value[0]->DSN?>"><?=$value[0]->DESCRIPCION?></option>
                            <?php
                            }
                            ?>
                            </select>
                        </div>
                        <div class="col">
                            <input class="btn btn-primary" type="submit" value="Consultar">
                        </div>
                    </div>
                    

            
                </form>

            </div>
            <div class="col"></div>
        </div>



    </div>



</body>
<?php
}
?>

