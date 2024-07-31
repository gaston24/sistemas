
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Factura Manual</title>
    <!-- Spiner -->
    <link rel="stylesheet" href="../facturaManual/assets/css/spiner.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="../facturaManual/assets/css/bootstrap/bootstrap.css">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../facturaManual/assets/plugins/fontawesome-free/css/all.min.css">
</head>
<body>
<div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-3 align-self-start" id="aviso-1"></div>
                    <div class="col-6 align-self-start" id="aviso-2">
                        <div class="alert alert-info alert-dismissible" role="alert">
                        <strong><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">¡Aviso!</font></font></strong>
                        Se debe cargar el numero de factura completo.
                        </div>
                    </div>
                    <div class="col-3 align-self-start" id="aviso-3"></div>
                </div>
                <div class="row">
                    <!-- left column -->
                    <div class="col-3 align-self-start" id="col-1">
                    </div>
                    <div class="col-6 align-self-center" id="col-2">
                        <div class="card card-primary">
                            <div class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">CARGAR FACTURA MANUAL</h3>
                                    <a id="cerrar" href="" class="btn"><i class="fa fa-times"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data" action="../sistemas/facturaManual/carga.php">
                                    <!-- {% csrf_token %} -->
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="numeroSucursal" class="form-label">Número de Sucursal:</label>
                                            <input type="number" id="numeroSucursal" name="numeroSucursal" class="form-control" placeholder="0" disabled>
                                        </div>
                                        
                                        <label for="tipoFactura" class="form-label">Tipo de Factura:</label>
                                        <select id="tipoFactura" name="tipoFactura" class="form-select" required>
                                            <option value="0">Factura-A</option>
                                            <option value="1">Factura-B</option>
                                        </select>
                                        <div class="form-group">
                                            <label for="numeroFactura" class="form-label">Número de Factura:</label>
                                            <input type="text" id="numeroFactura" name="numeroFactura" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="fechaVencimiento" class="form-label">Vencimiento CAI:</label>
                                            <input type="date" id="fechaVencimiento" name="fechaVencimiento" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="imgFactura" class="form-label">Imagen de Factura:</label>
                                            <input type="file" id="imgFactura" name="imgFactura" accept="image/*" capture="camera" class="form-control" required>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer">
                                        <button class="btn btn-primary ladda-button expand-right" type="submit">Cargar</span> <span class="spinner"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-3 align-self-end" id="col-3">
                    </div>
                </div>
            </div>
        </section>
    </div>
<script src="../facturaManual/assets/js/spiner.js"></script>
<script src="../facturaManual/assets/css/bootstrap/jquery-3.5.1.slim.min.js"></script>
<script src="../facturaManual/assets/css/bootstrap//popper.min.js"></script>
<script src="../facturaManual/assets/css/bootstrap/bootstrap.bundle.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.5/jquery.inputmask.min.js"></script>
<script>
        if (window.innerWidth < 768) {
            // El ancho de la ventana es menor que 768 píxeles (posiblemente un dispositivo móvil)
            // Eliminamos los div
            const col1 = document.getElementById('col-1');
            const col3 = document.getElementById('col-3');
            const col4 = document.getElementById('aviso-3');
            col1.remove();
            col3.remove();
            col4.remove();
            // Cambiamos el ancho del div con id "col-2" a 12 columnas
            const col2 = document.getElementById('col-2');
            col2.classList.remove('col-6'); // Eliminamos la clase "col-8"
            col2.classList.add('col-12'); // Agregamos la clase "col-12"
            // Cambiamos el ancho del div con id "aviso-1" a 2 columnas
            const aviso1 = document.getElementById('aviso-1');
            aviso1.classList.remove('col-3'); 
            aviso1.classList.add('col-1');
            // Cambiamos el ancho del div con id "aviso-2" a 12 columnas
            const aviso2 = document.getElementById('aviso-2');
            aviso2.classList.remove('col-6'); 
            aviso2.classList.add('col-10'); 
        }
</script>
<script>
    function getParameterByName(name) {
        name = name.replace(/[\\[]/, "\\\\[").replace(/[\\]]/, "\\\\]");
        var regex = new RegExp("[\\\\?&]" + name + "=([^&#]*)"),
            results = regex.exec(location.search);
        return results === null ? "" : decodeURIComponent(results[1].replace(/\\+/g, " "));
    }
    var parametro = getParameterByName('suc'); // Obtén el valor del parámetro desde la URL
    var numSuc = parseInt(parametro); // Convierte a entero

    let inputElement = document.getElementById('numeroSucursal');
    inputElement.value = numSuc;

    $(document).ready(function() {
        $('#numeroFactura').inputmask({
            mask: '99999-99999999',
            placeholder: 'X'
        });
    });
</script>    
<script src="../facturaManual/assets/Js/carga.js"></script>
</body>
</html>