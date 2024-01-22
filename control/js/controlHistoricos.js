const actualizarAjusar = (div) => {
    let  nroRemito = div.parentElement.parentElement.querySelectorAll("td")[2].textContent;
    let  value = div.value;
    $.ajax ({
        url: 'controlador/ajustar.php',
        type: 'POST',
        data: {
            nroRemito: nroRemito,
            value: value
        },
        success: function(data){
            console.log(data);
        }
    });
}