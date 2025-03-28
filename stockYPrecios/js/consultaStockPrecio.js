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
              document.querySelector("#precio_sin_iva").value = "$" + parseNumber(data[0]['PRECIO_S_IVA'], 2);

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
          let tdPrecioSinIva = document.createElement("td");

          tdArticulo.textContent = element.COD_ARTICU;
          tdColor.textContent = element.COLOR;
          tdStock.textContent = element.CANT_STOCK;
          tdPrecio.textContent = "$" + parseNumber(element.PRECIO);
          tdPrecioSinIva.textContent = "$" + parseNumber(element.PRECIO_S_IVA, 2);

          tr.appendChild(tdArticulo);
          tr.appendChild(tdColor);
          tr.appendChild(tdStock);
          tr.appendChild(tdPrecio);
          tr.appendChild(tdPrecioSinIva);

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

const parseNumber = (number, decimals = 0) => {
  // Si es un string, convertir a número
  number = typeof number === 'string' ? parseFloat(number) : number;
  
  const newNumber = number.toLocaleString('de-De', {
      style: 'decimal',
      maximumFractionDigits: decimals,
      minimumFractionDigits: decimals
  });

  return newNumber;
}

const borrar = () => {
  document.querySelector("#selectArticulo").value = "";
  document.querySelector("#articulo").value = "";
  document.querySelector("#descripcion").value = "";
  document.querySelector("#stock").value = "";
  document.querySelector("#precio").value = "";
  document.querySelector("#precio_sin_iva").value = "";
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