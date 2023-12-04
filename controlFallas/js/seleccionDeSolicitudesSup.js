$(document).ready(function() {
    
    comprobarIngresados()


})

const comprobarIngresados = () => {

    let solicitudes = document.querySelectorAll('table tbody tr');
    let arraySolicitudes = [];
    solicitudes.forEach(element => {
        arraySolicitudes.push(element.childNodes[1].textContent);
    });

    $.ajax({
        type: "POST",
        url: "Controller/RecodificacionController.php?accion=comprobarIngresados",
        data: {solicitudes: arraySolicitudes},
        success: function (response) {

            
          
        }
    });

}