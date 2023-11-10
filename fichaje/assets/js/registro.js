
$(document).ready(function() {

    document.querySelector(".swal2-confirm").style.backgroundColor ='white'
    document.querySelector(".swal2-confirm").style.color ='orange'
    document.querySelector(".swal2-confirm").style.border = '1px solid grey'
    document.querySelector(".swal2-confirm").style.borderRadius='20px'
    document.querySelector(".swal2-confirm").style.width='127px'

    document.querySelector('.toggle-password').addEventListener('click', function () {
        const passwordInput = document.querySelector('#password');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            this.classList.add('bi-eye');
            this.classList.remove('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            this.classList.remove('bi-eye');
            this.classList.add('bi-eye-slash');
        }
    });

});

const buscarPorCampo = (div) =>{
    let valor = div.value
    
    $.ajax({
        url : 'controller/FichajeController.php?action=buscarPorCampo',
        type : 'POST',
        data : {campo:valor},
        success : function(response){
            data = JSON.parse(response)[0]

            if(data){

                numero = data['NRO_LEGAJO']
                nombre = data['APELLIDO_Y_NOMBRE']

                document.querySelector("#campo").value = numero + ' - ' + nombre
                document.querySelector("#campo").style.color = '#00C3D8'
            }


        }

    })
}
const login = (numeroLegajo, password) =>{

    $.ajax ({
        url : 'controller/FichajeController.php?action=login',
        type : 'POST',
        data : {numeroLegajo:numeroLegajo.split(' - ')[0], password:password},

        success : function(response){
   
            if(response == 1){

                let sucursal = document.querySelector("#sucursal").textContent

                $.ajax({
                    url: 'controller/FichajeController.php?action=verificarFichaje',
                    type : 'POST',
                    data : {
                        numeroLegajo:numeroLegajo.split(' - ')[0],
                        sucursal:sucursal
                    },
                    success : function(response){
                     

                        if(response == 1){
                            
                            var parts = numeroLegajo.split(',');

                            var segundoElemento = parts[1].trim();

                            var nombreCompleto = segundoElemento.split(' ');

                            let apellido = parts[0].split('-')[1].trim();
                
                            var primerNombre = nombreCompleto[0];

            
                            var nombre = primerNombre + ' ' + apellido[0];

                            Swal.fire({

                                html: `
                                    <div class="circle-icon">
                                        <i class="bi bi-clock" style="font-size: 40px; color: #FF4572;"></i>
                                    </div>
                                    <br>
                                    <div><h3 style="font-style: italic">¡Hola!,${nombre}.!</h3></div>
                                    <div style="margin-top:50px;margin-left:40px">

                                            <i class="fa-solid fa-circle-check" style="color: #06c4f4; font-size: 60px; position: absolute; top: 110px; left: 60px;"></i>
                                    
                                    Has fichado tu ingreso correctamente
                                    
                                    </div>

                                `,
                                showConfirmButton: false, 
                                allowOutsideClick: false, 
                                timer: 2000, 
                            });

                            setTimeout(function () {
                                location.reload();
                            }, 2000); 

                            document.querySelector("#swal2-html-container").style.height = '190px'


                        }else{

                            Swal.fire({

                                html: `
                                    <div class="circle-icon">
                                        <i class="bi bi-exclamation-triangle" style="font-size: 40px; color: orange;"></i>
                                    </div>
                                    <br>
                                    <div>¡ Ya tienes registrado el ingreso !</div>
                                    <div><h2 style="font-style: italic">¿ Cerrar turno ?</h2></div>
                                    
                                    </div>

                                `,
                                showCancelButton: true,
                                cancelButtonText: 'si', // Cambiamos el texto del botón cancelar
                                showConfirmButton: true,
                                confirmButtonText: 'no', // Cambiamos el texto del botón confirmar
                                allowOutsideClick: false,

                            }).then((result) => {

                                if (result.isConfirmed) {

                                    location.reload();
                                   
                                } else if (result.dismiss === Swal.DismissReason.cancel) {

                                    $.ajax({
                                        url : 'controller/FichajeController.php?action=cerrarTurno',
                                        type : 'POST',
                                        data : {numeroLegajo:numeroLegajo.split(' - ')[0]},
                                        success: function(response){

                                            var parts = numeroLegajo.split(',');

                                            var segundoElemento = parts[1].trim();
                
                                            var nombreCompleto = segundoElemento.split(' ');
                
                                            let apellido = parts[0].split('-')[1].trim();
                                
                                            var primerNombre = nombreCompleto[0];
                
                            
                                            var nombre = primerNombre + ' ' + apellido[0];


                                            Swal.fire({

                                                html: `
                                                    <div class="circle-icon">
                                                        <i class="bi bi-clock" style="font-size: 40px; color: #FF4572;"></i>
                                                    </div>
                                                    <br>
                                                    <div><h3 style="font-style: italic">¡Hasta pronto!,${nombre}.!</h3></div>
                                                    <div style="margin-top:50px;margin-left:40px">
                
                                                            <i class="fa-solid fa-person-running" style="color: #06c4f4; font-size: 60px; position: absolute; top: 110px; left: 60px;"></i>
                                                    
                                                    Has fichado tu salida correctamente
                                                    
                                                    </div>
                
                                                `,
                                                showConfirmButton: false, 
                                                allowOutsideClick: false, 
                                                timer: 3000, 
                                                
                                            });
                                            setTimeout(function () {
                                                location.reload();
                                            }, 2000); 
                                            document.querySelector("#swal2-html-container").style.height = '190px'
                                        },

                                    })
                                  
                                }
                            })
                            ;
                            document.querySelector(".swal2-confirm").style.backgroundColor = 'red'
                
                            document.querySelector(".swal2-cancel").style.backgroundColor = 'green'
                            
                        }
                    }   

                })      

            }else if(response == 3){

                Swal.fire({
                    // title: 'Iniciar Sesión',
                    html: `
                        <div class="circle-icon">
                            <i class="bi bi-exclamation-circle" style="font-size: 40px; color: orange;"></i>
                        </div>
                        <br>
                        <div><h3 style="font-style: italic">¡ Usuario No habilitado !</h3></div>
                        <div>Por favor Comuniquese con Recursos Humanos</div>
                        <input type="text" id="campo" class="swal2-input" placeholder="Usuario" onchange="buscarPorCampo(this)" style="width: 261.193182px;height: 52.818182px;font-size:13px">
                        <div class="password-input">
                            <input type="password" id="password" class="swal2-input" placeholder="Contraseña">
                            <i class="bi bi-eye-slash toggle-password"></i>
                        </div>
                    `,
                    allowOutsideClick: false, // Evita que se cierre al hacer clic fuera
                    confirmButtonText: 'Cargar',
                    preConfirm: () => {
                        let campo = document.querySelector('#campo').value;
                        let password = document.querySelector('#password').value;
    
                        login(campo, password);
                    }
                });

                document.querySelector(".toggle-password").style.top = '65%'

                document.querySelector('.toggle-password').addEventListener('click', function () {

                    const passwordInput = document.querySelector('#password');
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.add('bi-eye');
                        this.classList.remove('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('bi-eye');
                        this.classList.add('bi-eye-slash');
                    }

                });


            }else{
                
                Swal.fire({
                    // title: 'Iniciar Sesión',
                    html: `
                        <div class="circle-icon">
                            <i class="bi bi-exclamation-circle" style="font-size: 40px; color: orange;"></i>
                        </div>
                        <br>
                        <div><h3 style="font-style: italic">¡ No hemos podido identificarte correctamente !</h3></div>
                        <div>Vuelve a intentarlo</div>
                        <input type="text" id="campo" class="swal2-input" placeholder="Usuario" onchange="buscarPorCampo(this)" style="width: 261.193182px;height: 52.818182px;font-size:13px">
                        <div class="password-input">
                            <input type="password" id="password" class="swal2-input" placeholder="Contraseña">
                            <i class="bi bi-eye-slash toggle-password"></i>
                        </div>
                    `,
                    allowOutsideClick: false, // Evita que se cierre al hacer clic fuera
                    confirmButtonText: 'Cargar',
                    preConfirm: () => {
                        let campo = document.querySelector('#campo').value;
                        let password = document.querySelector('#password').value;
    
                        login(campo, password);
                    }
                });

                document.querySelector(".toggle-password").style.top = '67%'

                document.querySelector('.toggle-password').addEventListener('click', function () {

                    const passwordInput = document.querySelector('#password');
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        this.classList.add('bi-eye');
                        this.classList.remove('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        this.classList.remove('bi-eye');
                        this.classList.add('bi-eye-slash');
                    }

                });
              
            }
        }
    })

}