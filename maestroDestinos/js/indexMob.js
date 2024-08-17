
document.getElementById('selectArticulo').addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        document.querySelector('.btn-primary').click();
    }
  });
  
  const traerArticulo = (div, usuarioUy = null) => {
    const codArticulo = div.value;
  
    $.ajax({
        url: "Controller/articuloController.php?accion=traerArticulo",
        type: "POST",
        data: {
          codArticulo: codArticulo,
          usuarioUy: usuarioUy
        },
        dataType: 'json',
        success: function (data) {
            if(data.length > 0){
                document.querySelector("#articulo").value = data[0]['COD_ARTICU'];
                document.querySelector("#descripcion").value = data[0]['DESCRIPCION'];
                document.querySelector("#rubro").value = data[0]['RUBRO'];
                document.querySelector("#precio").value = data[0]['PRECIO'] ? "$" + parseNumber(data[0]['PRECIO']) : "N/A";
                document.querySelector("#destino").value = data[0]['DESTINO'] || "N/A";
                document.querySelector("#temporada").value = data[0]['TEMPORADA'] || "N/A";
  
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
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "El articulo es incorrecto!",
                  });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
            alert("Hubo un error al obtener los datos del artículo. Por favor, intente de nuevo.");
            borrar();
        }
    });
  }
  
  const parseNumber = (number) => {
    number = parseFloat(number);
    return number.toLocaleString('es-AR', {
        style: 'decimal',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
  }
  
  const borrar = () => {
    document.querySelector("#selectArticulo").value = "";
    document.querySelector("#articulo").value = "";
    document.querySelector("#descripcion").value = "";
    document.querySelector("#rubro").value = "";
    document.querySelector("#rubro").value = "";
    document.querySelector("#precio").value = "";
    document.querySelector("#destino").value = "";
    document.querySelector("#temporada").value = "";
    document.querySelector("#selectArticulo").focus();
  
    // Ocultar el badge SALE al borrar
    document.querySelector('.estado-badge').style.display = 'none';
  }