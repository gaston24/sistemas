<table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Número de Sucursal</th>
        <th>Tipo de Factura</th>
        <th>Número de Factura</th>
        <th>Imagen de Factura</th>
        <th>Fecha de Registro</th>
      </tr>
    </thead>
    <tbody id="registros-tbody">
      <!-- Aquí se insertarán dinámicamente los registros -->
    </tbody>
</table>
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
</script>
<script src="../facturaManual/assets/Js/listado.js"></script>
