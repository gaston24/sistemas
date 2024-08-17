
<?php
session_start();

require 'Class/Articulo.php';
$maestroArticulos = new Articulo();

?> 


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Consulta Destino</title>
    <link rel="shortcut icon" href="../assets/css/icono.jpg" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/indexMob.css" class="rel">
 
</head>
<body>
    <div class="container py-1">
        <nav class="navbar navbar-dark mb-1">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1">
                    <i class="bi bi-search"></i> Consulta Destino
                </span>
            </div>
        </nav>
        
        <div class="card">
            <div class="card-body">
                <div class="mb-2">
                    <div class="input-group">
                        <input type="text" class="form-control" id="selectArticulo" placeholder="Ingrese código">
                        <span class="input-group-text" onclick="borrar()">
                            <i class="bi bi-x-circle"></i>
                        </span>
                        <button class="btn btn-primary" type="button" onclick="traerArticulo(document.getElementById('selectArticulo'), <?= json_encode($_SESSION['usuarioUy'] ?? null) ?>)">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <div id="resultadoArticulo" class="mt-3">
                    <h6 class="card-title mb-1">Información del Artículo
                    <h5 class="card-title">
                        <span class="estado-badge badge" style="display: none;">SALE</span>
                    </h5>
                    </h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Artículo
                            <input type="text" class="form-control-plaintext text-end" id="articulo" readonly>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Descripción
                            <input type="text" class="form-control-plaintext text-end" id="descripcion" readonly>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Rubro
                            <input type="text" class="form-control-plaintext text-end" id="rubro" readonly>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Precio
                            <input type="text" class="form-control-plaintext text-end" id="precio" readonly>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Destino
                            <input type="text" class="form-control-plaintext text-end" id="destino" readonly>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Temporada
                            <input type="text" class="form-control-plaintext text-end" id="temporada" readonly>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/indexMob.js"></script>
 
</body>
</html>