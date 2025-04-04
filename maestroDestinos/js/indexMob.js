
const traerArticulo = (div) => {
  const codArticulo = div.value.trim();
  
  if (!codArticulo) {
    Swal.fire({
      icon: 'warning',
      title: 'Atención',
      text: 'Por favor ingrese un código de artículo',
    });
    return;
  }
  
  // Show loading indicator
  Swal.fire({
    title: 'Buscando...',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    }
  });

  $.ajax({
    url: "Controller/ArticuloController.php?accion=traerArticulo",
    type: "POST",
    data: {
      codArticulo: codArticulo
    },
    dataType: 'json',
    success: function (data) {
      Swal.close();
      
      if(data.length > 0){
        // Extract article data, ensuring defaults for missing values
        const article = data[0];
        
        // Basic article info
        document.querySelector("#articulo").value = article['COD_ARTICU'] || '';
        document.querySelector("#descripcion").value = article['DESCRIPCION'] || '';
        document.querySelector("#rubro").value = article['RUBRO'] || 'N/A';
        
        // Price calculations
        const precioConIva = article['PRECIO'] !== null ? parseFloat(article['PRECIO']) : 0;
        const precioSinIva = article['PRECIO_S_IVA'] !== null ? parseFloat(article['PRECIO_S_IVA']) : 0;
        
        // Display prices
        document.querySelector("#precio_sin_iva").value = precioSinIva ? "$" + parseNumber(precioSinIva, 2) : "N/A";
        
        // Calculate and display IVA (VAT)
        if (precioConIva && precioSinIva) {
          const ivaValor = precioConIva - precioSinIva;
          document.querySelector("#iva").value = "$" + parseNumber(ivaValor, 2);
        } else {
          document.querySelector("#iva").value = "N/A";
        }
        
        // Display full price
        document.querySelector("#precio").value = precioConIva ? "$" + parseNumber(precioConIva, 0) : "N/A";
        
        // Article metadata
        document.querySelector("#destino").value = article['DESTINO'] || 'N/A';
        document.querySelector("#temporada").value = article['TEMPORADA'] || 'N/A';

        // Handle SALE badge display
        const badgeElement = document.querySelector('.estado-badge');
        if (article['LIQUIDACION'] === 'SI' || article['LIQUIDACION'] === '1' || article['LIQUIDACION'] === 1) {
          badgeElement.style.display = 'inline-block';
          badgeElement.textContent = 'SALE';
          badgeElement.classList.add('bg-danger');
        } else {
          badgeElement.style.display = 'none';
        }

        // Handle article image
        const imageName = article['COD_ARTICU'].substring(0, 13);
        const imageUrl = `../../Imagenes/${imageName}.jpg`;
        const imgElement = document.querySelector("#imagenArticulo");
        
        // Verify if image exists using fetch
        fetch(imageUrl, { method: 'HEAD' })
          .then(res => {
            if (res.ok) {
              imgElement.src = imageUrl;
              imgElement.style.display = 'block';
            } else {
              imgElement.style.display = 'none';
            }
          })
          .catch(() => {
            imgElement.style.display = 'none';
          });
      } else {
        borrar();
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'No se encontraron datos para este artículo.',
        });
      }
    },
    error: function(jqXHR, textStatus, errorThrown) {
      Swal.close();
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

// Format number with locale-specific formatting
const parseNumber = (number, decimals = 0) => {
  // If it's a string, convert to number
  number = typeof number === 'string' ? parseFloat(number) : parseFloat(number);
  
  return number.toLocaleString('es-AR', {
    style: 'decimal',
    minimumFractionDigits: decimals,
    maximumFractionDigits: decimals
  });
}

// Clear form fields
const borrar = () => {
  document.querySelector("#selectArticulo").value = "";
  document.querySelector("#articulo").value = "";
  document.querySelector("#descripcion").value = "";
  document.querySelector("#rubro").value = "";
  document.querySelector("#precio").value = "";
  document.querySelector("#precio_sin_iva").value = "";
  document.querySelector("#iva").value = "";
  document.querySelector("#destino").value = "";
  document.querySelector("#temporada").value = "";
  document.querySelector("#selectArticulo").focus();

  // Hide SALE badge and image when clearing
  document.querySelector('.estado-badge').style.display = 'none';
  document.querySelector('#imagenArticulo').style.display = 'none';
}

// Add Enter key event to search field
document.getElementById('selectArticulo').addEventListener('keydown', function(event) {
  if (event.key === 'Enter') {
    event.preventDefault();
    traerArticulo(this);
  }
});