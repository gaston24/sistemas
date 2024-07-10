<head>
    <meta charset="UTF-8">
    <title>Crear Factura Manual</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <!-- <div class="overlay-wrapper">
                  <div class="overlay dark" id="spiner"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>
                </div> -->
              </div>
              <div class="row">
                <!-- left column -->
                <div class="col-3 align-self-start" id="col-1">
                  </div>
                  <div class="col-6 align-self-center" id="col-2">
                    <div class="card text-bg-light">
                      <div class="card-header border-0">
                        <div class="d-flex justify-content-between">
                          <a id="carga" href="" class="btn">
                            <img src="../facturaManual/assets/img/uploadFoto.png" alt="Imagen de carga" data-bs-toogle="tooltip" data-bs-placement="right" title="Cargar imagen">
                          </a>                        
                          <h3 class="card-title">Facturas manuales</h5>
                          <a id="" href="../index.php" class="btn"><i class="fa fa-times"></i></a>
                        </div>
                      </div>
                      <div class="card-body table-responsive p-0">
                        <table class="table table-striped table-hover" id="registros">
                          <thead>
                            <tr>
                              <!-- <th>ID</th>
                              <th>Número de Sucursal</th> -->
                              <th>*</th>
                              <th>Tipo</th>
                              <th>Número de Factura</th>
                              <th>Fecha de alta</th>
                            </tr>
                          </thead>
                          <tbody id="registros-tbody">
                            <!-- Aquí se insertarán dinámicamente los registros -->
                          </tbody>
                        </table>
                      </div>                      
                    </div>
                    <div class="overlay-wrapper">
                      <div class="overlay dark" id="spiner"><i class="fas fa-3x fa-sync-alt fa-spin"></i><div class="text-bold pt-2">Loading...</div></div>
                    </div>
                    <div class="col-3 align-self-end" id="col-3">
                  </div>
                </div>
              </div>
          </section>
  </div>
</body>
<script>
        if (window.innerWidth < 768) {
            // El ancho de la ventana es menor que 768 píxeles (posiblemente un dispositivo móvil)
            // Eliminamos los div con id igual a "col-1" y "col-3"
            const col1 = document.getElementById('col-1');
            const col3 = document.getElementById('col-3');
            col1.remove();
            col3.remove();
            // Cambiamos el ancho del div con id "col-2" a 12 columnas
            const col2 = document.getElementById('col-2');
            col2.classList.remove('col-8'); // Eliminamos la clase "col-8"
            col2.classList.add('col-12'); // Agregamos la clase "col-12"
        }
</script>
<script src="../facturaManual/assets/css/bootstrap/jquery-3.5.1.slim.min.js"></script>
<script src="../facturaManual/assets/css/bootstrap//popper.min.js"></script>
<script src="../facturaManual/assets/css/bootstrap/bootstrap.bundle.js"></script>
<script>
  function getParameterByName(name) {
    name = name.replace(/[\\[]/, "\\\\[").replace(/[\\]]/, "\\\\]");
    var regex = new RegExp("[\\\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\\+/g, " "));
  }
  
  var parametro = getParameterByName('suc'); // Obtén el valor del parámetro desde la URL
  var numSuc = parseInt(parametro); // Convierte a entero

  // Detecta si el dispositivo es un dispositivo móvil
  function esDispositivoMovil() {
    const mediaQuery = window.matchMedia("(max-width: 768px)");
    return mediaQuery.matches;
  }
  
  // Función para realizar las acciones necesarias
  function cambiarEstructuraHTML() {
    if (esDispositivoMovil()) {
      // Borrar los div con id col-1 y col-3
      document.getElementById("col-1").remove();
      document.getElementById("col-3").remove();
  
      // Cambiar el valor de class="col-8" por "col12" en el div con id col-2
      const col2 = document.getElementById("col-2");
      col2.classList.remove("col-8");
      col2.classList.add("col-12");
    }
  }
  
  // Llama a la función cambiarEstructuraHTML cuando se carga la página
  window.addEventListener("load", cambiarEstructuraHTML);

</script>
<script src="../facturaManual/assets/Js/listado.js"></script>
