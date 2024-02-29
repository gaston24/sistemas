$(document).ready(function() {
    console.log('global.js')
    setTimeout(function() {
        Swal.fire({
            // title: 'Iniciar Sesión',
            html: `
                <div class="circle-icon">
                 
                </div>
                <br>
                <div><h3 style="font-style: italic">Tu sesion expirará en <span id='contador'></span></br>min</h3></div>
                <div>¿Quieres extender la sesion?</div>

            `,
            allowOutsideClick: false, // Evita que se cierre al hacer clic fuera
            showCancelButton: true,
            cancelButtonColor: '#d33',
            confirmButtonColor: '#3085d6',
            cancelButtonText: 'NO',
            confirmButtonText: 'SI',
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {

                window.location.href = `${window.location.origin}/sistemas/login.php`;

            } else if (result.isConfirmed) {
                
                location.reload();
                
            }
            

        });
        actualizarContador();
        setInterval(actualizarContador, 1000);
    }, 1800000);
})



let segundos = 5 * 60; 


function actualizarContador() {

    if(segundos == 0){
        console.log(window.location.origin+'/Controlador/Logout.php')
       
           $.ajax({
            url : `${window.location.origin}/sistemas/Controlador/Logout.php`,
            type: "POST",
            success: function(response){
                console.log(response)
                window.location.href = `${window.location.origin}/sistemas/login.php`;
            }

       })
    }

    let minutos = Math.floor(segundos / 60);
    let segundosRestantes = segundos % 60;

  
    let formatoMinutos = minutos < 10 ? "0" + minutos : minutos;
    let formatoSegundos = segundosRestantes < 10 ? "0" + segundosRestantes : segundosRestantes;


    document.querySelector("#contador").textContent =   formatoMinutos + ":" + formatoSegundos

    segundos--;


 
}



