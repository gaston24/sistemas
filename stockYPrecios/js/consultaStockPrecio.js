const traerArticulo = (div, usuarioUy = null) => {
  console.log("aca");
  const codArticulo = div.value;

  $.ajax({
      url: "Controller/StockPrecioController.php?accion=traerArticulos",
      type: "POST",
      data: {
        codArticulo: codArticulo,
        usuarioUy: usuarioUy
      },
      dataType: 'json',
      success: function (data) {
          if(data.length > 0){
              document.querySelector("#articulo").value = data[0]['COD_ARTICU'];
              document.querySelector("#descripcion").value = data[0]['DESCRIPCIO'];
              document.querySelector("#stock").value = parseInt(data[0]['CANT_STOCK']);
              document.querySelector("#precio").value = "$" + parseNumber(data[0]['PRECIO']);

              // Mostrar u ocultar el badge SALE
              const badgeElement = document.querySelector('.estado-badge');
              if (data[0]['LIQUIDACION'] === 'SI') {
                  badgeElement.style.display = 'inline-block';
                  badgeElement.textContent = 'SALE';
              } else {
                  badgeElement.style.display = 'none';
              }

              traerVariantes(codArticulo, usuarioUy);
          } else {
              borrar();
              Swal.fire({
                icon: 'info',
                title: 'Sin resultados',
                text: 'No se encontraron datos para este artículo.',
              });
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al obtener los datos del artículo. Por favor, intente de nuevo.',
          });
          borrar();
      }
  });
}

const traerVariantes = (codArticulo, usuarioUy) => {
  $.ajax({
    url: "Controller/StockPrecioController.php?accion=traerVariantes",
    type: "POST",
    data: {
      codArticulo: codArticulo,
      usuarioUy: usuarioUy
    },
    dataType: 'json',
    success: function (variantes) {
      let tbodyStockPrecio = document.querySelector("#tbodyStockPrecio");
      tbodyStockPrecio.innerHTML = '';
      
      variantes.forEach(element => {
        // Solo agregar a la tabla si no es el artículo original
        if (element.COD_ARTICU !== codArticulo) {
          console.log(element);

          let tr = document.createElement("tr");
          let tdArticulo = document.createElement("td");
          let tdColor = document.createElement("td");
          let tdStock = document.createElement("td");
          let tdPrecio = document.createElement("td");

          tdArticulo.textContent = element.COD_ARTICU;
          tdColor.textContent = element.COLOR;
          tdStock.textContent = element.CANT_STOCK;
          tdPrecio.textContent = "$" + parseNumber(element.PRECIO);

          tr.appendChild(tdArticulo);
          tr.appendChild(tdColor);
          tr.appendChild(tdStock);
          tr.appendChild(tdPrecio);

          tbodyStockPrecio.appendChild(tr);
        }
      });
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.error("Error al obtener variantes:", textStatus, errorThrown);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Hubo un error al obtener las variantes del artículo. Por favor, intente de nuevo.',
      });
    }
  });
}

const parseNumber = (number) => {
  number = parseInt(number);

  const newNumber = number.toLocaleString('de-De', {
      style: 'decimal',
      maximumFractionDigits: 0,
      minimumFractionDigits: 0
  });

  return newNumber;
}

const borrar = () => {
  document.querySelector("#selectArticulo").value = "";
  document.querySelector("#articulo").value = "";
  document.querySelector("#descripcion").value = "";
  document.querySelector("#stock").value = "";
  document.querySelector("#precio").value = "";
  document.querySelector("#selectArticulo").focus();

  // Ocultar el badge SALE al borrar
  document.querySelector('.estado-badge').style.display = 'none';

  // Limpiar la tabla de variantes
  let tbodyStockPrecio = document.querySelector("#tbodyStockPrecio");
  if (tbodyStockPrecio) {
    tbodyStockPrecio.innerHTML = '';
  }
}

// Agregar evento de tecla Enter al campo de búsqueda
document.getElementById('selectArticulo').addEventListener('keydown', function(event) {
  if (event.key === 'Enter') {
    event.preventDefault();
    document.querySelector('.btn-primary').click();
  }
});