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
                template += `
                <tr>
                    <td>${response.FECHA_REG}</td>
                    <td>${response.SUCURSAL}</td>
                    <td>${response.LEGAJO}</td>
                    <td>${response.APELLIDO_Y_NOMBRE}</td>
                    <td>${response.ENTRADA}</td>
                    <td>${response.SALIDA}</td>
                    <td>${response.AUSENTE}</td>
                    <td>${response.LLEGA_TARDE}</td>
                    <td>${response['TOTAL TRABAJADO']}</td>
                    
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

 
        $("#tablaReporte").table2excel({
    
            // exclude CSS class
            exclude: ".noExl",
            name: "Worksheet Name",
            filename: "Remitos", //do not include extension
            fileext: ".xls", // file extension
        });
   
}