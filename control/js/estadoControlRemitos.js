


const mostrarSpiner = () => {

    let spinner = document.querySelector("#boxLoading");

    spinner.className += " loading";

}

$('#tablaControl').DataTable({
    "bLengthChange": true,
    "language": {
                "lengthMenu": "mostrar _MENU_ registros",
                "info":           "Mostrando registros del _START_ al _END_ de un total de  _TOTAL_ registros",
                "paginate": {
                    "next":       "Siguiente",
                    "previous":   "Anterior"
                },

    },

    
    "bInfo": true,
    "aaSorting": false,
    'columnDefs': [
        {
            "targets": "_all", 
            "className": "text-center",
            "sortable": false,
     
        },
    ],
    "oLanguage": {

        "sSearch": "Busqueda rapida:",
        "sSearchPlaceholder" : "Sobre cualquier campo"
        

    },
});
$('[data-toggle="tooltip"]').tooltip()