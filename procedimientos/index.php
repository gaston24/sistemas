<?php 

session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
    header("Content-Type: text/html;charset=utf-8");
include 'class/procedimiento.php';
$proce = new Procedimiento();
$list = $proce->traerProcedimientos();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="ISO-8859-1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../assets/css/icono.jpg" />
    <title>Procedimientos</title>
    <link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/brands.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/duotone.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/fontawesome.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/light.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/regular.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/solid.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/svg-with-js.min.css">
	<link rel="stylesheet" type="text/css" href="../../../../../sistemas/assets/css/fontawesome/css/v4-shims.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div>
            <button type="button" class="btn btn-primary" onclick="location.href='../index.php'" style="margin-top:1%">Inicio</button>
        </div>
    <h1 class="text-center">Procedimientos</h1>

<div class="row">
<div class="col-1"></div>
<div class="col-10">

<table class="table table-responsive table-hover">
<thead>
    <th>NOMBRE</th>
    <th>DESCRIPCION</th>
    <th>DESCARGAR</th>
</thead>
<tbody>
<?php
foreach ($list as $value) {
    ?>
    <tr>
    <td><?=$value['NOMBRE_PROCEDIMIENTO']; ?></td>
    <td><?=$value['DESC_PROCEDIMIENTO']; ?></td>
    <td>
    <div class="row">
        <div class="col"></div>
        <div class="col"><a href="archivos/<?=$value['NOMBRE_ARCHIVO'];?>"> <i class="fas fa-file-download"></i></a></div>
        <div class="col"></div>
    </div>
    
    </td>
    </tr>
    <?php
}
?>
</tbody>
</table>


</div>
<div class="col-1"></div>
</div>


    

    
    </div>

<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/all.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/brands.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/conflict-detection.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/duotone.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/fontawesome.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/light.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/regular.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/solid.min.js"></script>
<script type="text/javascript" src="../../../../../sistemas/assets/css/fontawesome/js/v4-shims.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>
</html>

<?php
}
?>