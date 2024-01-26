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
            // response = JSON.parse(response);
         
            let template = '';
       
            response.forEach(response => {
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
                    <td>${response.FECHA_REG}</td>
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
    const workbook = XLSX.utils.table_to_book(tabla);
    XLSX.writeFile(workbook, 'ReporteDeFichadas.xlsx');

  };