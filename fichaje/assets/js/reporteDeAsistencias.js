const buscar = () => {
    let spinner = document.querySelector('#boxLoading');
    
    spinner.classList.add('loading')

    let desde = document.querySelector('#desde').value;
    let hasta = document.querySelector('#hasta').value;

    if(desde == '' || hasta == ''){
       Swal.fire({

            icon: 'error',
            title: 'Oops...',
            text: 'Debe completar los campos de fecha',
        
        })

        return 1;
    }

    let usuario = document.querySelector('#usuario').value;
    let sucursal = document.querySelector('#sucursal').value;
     

    $.ajax({
        url: 'Controller/FichajeController.php?action=traerReporteAsistencias',
        type: 'POST',
        dataType: 'json',
        data: {
            desde: desde,
            hasta: hasta,
            usuario: usuario,
            sucursal: sucursal
        },
        success: function (response) {

            spinner.classList.remove('loading')
         
         
            let template = '';
       
            response.forEach(response => {
       
                if(response.SUCURSAL.split(" ")[0] != sucursal){
                    return 1;
                }

                if(response.ENTRADA == null){
                    response.ENTRADA = '';
                }
                if(response.SALIDA == null){
                    response.SALIDA = '';
                }
                if(response.AUSENTE == null){
                    response.AUSENTE = '';
                }
                if(response.LLEGA_TARDE == null){
                    response.LLEGA_TARDE = '';
                }
                template += `
                <tr>
                    <td id="tdFecha">${response.FECHA_REG}</td>
                    <td>${response.SUCURSAL}</td>
                    <td>${response.NRO_LEGAJO}</td>
                    <td>${response.APELLIDO_Y_NOMBRE}</td>
                    <td>${response.ENTRADA}</td>
                    <td>${response.SALIDA}</td>
                    <td>${response.AUSENTE}</td>
                    <td>${response.LLEGA_TARDE}</td>
                    <td>${response.TOTAL_TRABAJADO}</td>
                    
                </tr>
                `;
            });
            $('#detalleBody').html(template);
        }

    });
}

const formatoFecha = (fechaOriginal) => {

    let fecha = new Date(fechaOriginal);

    // Obtener los componentes de la fecha
    let dia = fecha.getDate();
    let mes = fecha.getMonth() + 1; // Nota: ¡los meses en JavaScript comienzan desde 0!
    let anio = fecha.getFullYear();

    // Formatear la fecha como 'DD/MM/YYYY'
    let fechaConvertida = dia + '/' + mes + '/' + anio;

    return fechaConvertida;

}


const exportar = () => {

    const tabla = document.getElementById('tablaReporte');
    if(tabla.querySelector("#detalleBody").querySelector("tr") == null){
        Swal.fire({

            icon: 'error',
            title: 'error',
            text: 'No hay datos para exportar',
        
        })
        return 1;
    }
    document.querySelectorAll("#tdFecha").forEach(element => {
        let arrayFecha = element.textContent.split("/")
        let fecha = arrayFecha[1] + "/" + (parseInt(arrayFecha[0]) + 1) + "/" + arrayFecha[2]
        element.textContent = fecha
    });
    
    const workbook = XLSX.utils.table_to_book(tabla);
    XLSX.writeFile(workbook, 'ReporteDeFichadas.xlsx');

    document.querySelectorAll("#tdFecha").forEach(element => {
        let arrayFecha = element.textContent.split("/")
        let fecha = (agregarCero(parseInt(arrayFecha[1]) - 1)) + "/" + agregarCero(arrayFecha[0]) + "/" + arrayFecha[2]
        element.textContent = fecha
    });

    
  };

  const agregarCero = (numero) => {
    // Convertir el número a cadena para verificar su longitud
    const numeroStr = numero.toString();

    // Verificar si la longitud es de una sola cifra
    if (numeroStr.length === 1) {
        // Agregar un cero adelante y devolver la cadena resultante
        return '0' + numeroStr;
    } else {
        // Devolver el número como cadena sin cambios
        return numeroStr;
    }
    
};