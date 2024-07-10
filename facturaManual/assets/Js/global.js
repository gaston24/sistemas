$(document).ready(function() {
    console.log('global.js')
    setTimeout(function() {
        
        Swal.fire({
            // title: 'Iniciar Sesión',
            html: `
                <div class="circle-icon">
                 
                </div>
                <br>
                <button id="miBoton" onclick="reproducirSonido()" hidden></button>
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
    }, 1500000);
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




function reproducirSonido() {
    // Crear un contexto de audio
    var audioContext = new (window.AudioContext || window.webkitAudioContext)();
    // Crear un oscilador
    var oscilador = audioContext.createOscillator();
    // Conectar el oscilador al contexto de audio
    oscilador.connect(audioContext.destination);
    // Establecer el tipo de onda del oscilador (en este caso, un tono)
    oscilador.type = 'sine';
    // Establecer la frecuencia del tono (puedes ajustarla según tus preferencias)
    oscilador.frequency.value = 1000; // 1000 Hz

    // Iniciar el oscilador
    oscilador.start();
    // Detener el oscilador después de un breve período (en este caso, 0.5 segundos)
    setTimeout(function() {
        oscilador.stop();
    }, 500); // 0.5 segundos
}

// Luego, puedes llamar a esta función en el lugar adecuado de tu código cuando quieras reproducir el sonido.
