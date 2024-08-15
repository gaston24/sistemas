document.getElementById('selectArticulo').addEventListener('keydown', function(event) {
  if (event.key === 'Enter') {
      event.preventDefault(); // Prevenir comportamiento por defecto
      document.querySelector('.btn-primary').click(); // Hacer clic en el botón de buscar
  }
});

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
      success: function (response) {
          const data = JSON.parse(response);
  
          if(data.length > 0){
              document.querySelector("#articulo").value = data[0]['COD_ARTICU'];
              document.querySelector("#descripcion").value = data[0]['DESCRIPCIO'];
              document.querySelector("#stock").value = parseInt(data[0]['CANT_STOCK']);
              document.querySelector("#precio").value = "$" + parseNumber(data[0]['PRECIO']);
              document.querySelector("#destino").value = data[0]['DESTINO'];

              // Mostrar u ocultar el badge SALE
              const badgeElement = document.querySelector('.estado-badge');
              if (data[0]['LIQUIDACION'] === 'SI') {
                  badgeElement.style.display = 'inline-block';
                  badgeElement.textContent = 'SALE';
              } else {
                  badgeElement.style.display = 'none';
              }
          } else {
              borrar();
          }

          $.ajax({
            url: "Controller/StockPrecioController.php?accion=traerVariantes",
            type: "POST",
            data: {
              codArticulo: codArticulo,
              usuarioUy: usuarioUy
            },
            success: function (response) {
              let tbodyStockPrecio = document.querySelector("#tbodyStockPrecio");
              tbodyStockPrecio.innerHTML = '';
              let variantes = JSON.parse(response);
              
              variantes.forEach(element => {
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
              });
              div.value = '';
            }
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
  document.querySelector("#destino").value = "";
  document.querySelector("#selectArticulo").focus();

  // Ocultar el badge SALE al borrar
  document.querySelector('.estado-badge').style.display = 'none';
}