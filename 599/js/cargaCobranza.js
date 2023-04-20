const completarModal = (codClient)=>{

    traerCheques(codClient);
    let saldo = document.querySelector("#modalSaldo");
    let montoACobrar = document.querySelector("#montoACobrar").getAttribute("attr-valorReal"); 
    let cobroEfectivo = document.querySelector("#cobroEfectivo").getAttribute("attr-valorreal");

    console.log(cobroEfectivo,parseFloat(cobroEfectivo))
    saldo.value = montoACobrar -  cobroEfectivo;
    
}

const  confirmarCobro = (codClient) => {

    let cobroEfectivo = document.querySelector("#cobroEfectivo").getAttribute("attr-valorReal");
    let cobroCheque = document.querySelector("#cobroCheque").getAttribute("attr-valorReal");
    let montoACobrar = document.querySelector("#montoACobrar").getAttribute("attr-valorReal");
    let saldoCobrar = document.querySelector("#saldoCobrar").getAttribute("attr-valorReal");
    let nombreCliente = document.querySelector("#cliente").getAttribute("attr-cliente")
    let idCheques = localStorage.getItem("idCheques");

    if(parseInt(montoACobrar) != parseInt(saldoCobrar)){
        Swal.fire({
            icon: 'warning',
            title: 'El importe cobrado no coincide con el importe a cobrar ¿Desea continuar?',
            showDenyButton: true,
            confirmButtonText: 'Aceptar',
            denyButtonText: 'Cancelar',
            }).then((result) => {
                / Read more about isConfirmed, isDenied below /
                if (result.isConfirmed) {
                    // let remitosEnCadena = sessionStorage.getItem("Remitos");
                    // var arrayDeRemitos = remitosEnCadena.split("-");
                    // // espacio
    
                    // $.ajax({
                    //     url: "controller/ejecutarCobroController.php",
                    //     type: "POST",
                    //     data: {
                    //         remitos: arrayDeRemitos,
                    //         cobroEfectivo:cobroEfectivo,
                    //         cobroCheque:cobroCheque,
                    //         saldoCobrar: saldoCobrar,
                    //         montoACobrar: montoACobrar,
                    //         codClient:codClient
                                                    
                    //     },
                    //     success: function(data) {
                    //         console.log(data)
                    //         Swal.fire('Saved!', '', 'success')
                            
                    //     }
                    // });

                    Swal.fire('Saved!', '', 'success')
                } else if (result.isDenied) {
                    Swal.fire('El proceso fue cancelado', '', 'info')
            }
        })
    }
    
    if(cobroEfectivo < 1 && cobroCheque < 1 ){
        Swal.fire({
            icon: 'error',
            title: 'Error de carga',
            text: 'Debe cargar el importe a cobrar!'
        })
    }   

    if(saldoCobrar == 0){

        Swal.fire({
            icon: 'warning',
            title: '¿Desea registrar el cobro?',
            showDenyButton: true,
            confirmButtonText: 'Aceptar',
            denyButtonText: 'Cancelar',
        }).then((result) => {
            / Read more about isConfirmed, isDenied below /
            if (result.isConfirmed) {
                let remitosEnCadena = sessionStorage.getItem("Remitos");
                var arrayDeRemitos = remitosEnCadena.split("-");

                // espacio

                $.ajax({
                    url: "controller/ejecutarCobroController.php",
                    type: "POST",
                    data: {
                        remitos: arrayDeRemitos,
                        cobroEfectivo:cobroEfectivo,
                        cobroCheque:cobroCheque,
                        saldoCobrar: saldoCobrar,
                        montoACobrar: montoACobrar,
                        codClient:codClient,
                        idCheque:idCheques,
                        nombreCliente:nombreCliente
                                                
                    },
                    success: function(data) {

                        window.location.href = "composicionDeRemitos.php";
                        Swal.fire('Saved!', '', 'success');

                    }
                });

            } else if (result.isDenied) {
                Swal.fire('El cobro fue cancelado', '', 'info')
            }
        })
    }

}

const calcularSaldo = ()=>{
    
    let montoACobrar = document.querySelector("#montoACobrar").getAttribute("attr-valorReal");
    let saldoCobrar = document.querySelector("#saldoCobrar");

    efectivo = document.querySelector("#cobroEfectivo");




    let cheque = document.querySelector("#cobroCheque");



    
    if(efectivo.getAttribute("valorreal") == ""){
        efectivo.value = 0;
    }
    
    if(cheque.getAttribute("valorreal") == ""){
        cheque.value = 0;
    }

    let total = parseInt(montoACobrar) - (parseFloat(efectivo.getAttribute("attr-valorreal")) + parseFloat(cheque.getAttribute("attr-valorreal")));
    console.log(total,"totalFinal")
    saldoCobrar.value =`$ ${total} `
    saldoCobrar.setAttribute("attr-valorReal", total);

}


const traerCheques = (codClient) =>{

    $.ajax({

        url: "controller/traerChequeController.php",
        type: "POST",
        data: {
        codClient:codClient
        },
        success: function(result) {

            let tableBody = $("#tableCheques").html();
            let date = new Date()
            let getYear = date.toLocaleString("default", { year: "numeric" });
            let getMonth = date.toLocaleString("default", { month: "2-digit" });
            let getDay = date.toLocaleString("default", { day: "2-digit" });
            let dateFormat = getYear + "-" + getMonth + "-" + getDay;
       
            let nuevaFila = 
            `<tr id="tdCheques">
                <td style="width:40px;text-align:center" id="numInterno">${(parseInt(result)+1)}</td>
                <td style="text-align:center"><input type="text" style="width:120px" onchange="comprobarSumFila(this)"></td>
                <td style="text-align:center"><input type="text" style="width:120px" onchange="comprobarSumFila(this)"></td>
                <td style="text-align:center"><input type="text" style="width:120px" onchange="comprobarSumFila(this)"></td>
                <td style="text-align:center"><input type="date" style="width:120px" onchange="comprobarSumFila(this)" value="${dateFormat}"></td>
                <td style="text-align:center"><button value="+" onclick="agregarFila(${(parseInt(result)+1)},this)" hidden id="btnAgregarFila">+</button></td>


            </tr>
            `
            ;
        
            $("#tableCheques").html(tableBody+nuevaFila);

        }
    });
}

const agregarFila = ( nroInterno, fila) => {

    fila.setAttribute("hidden", "true")
    let tableBody = $("#tableCheques").html();

    var date = new Date()
    let getYear = date.toLocaleString("default", { year: "numeric" });
    let getMonth = date.toLocaleString("default", { month: "2-digit" });
    let getDay = date.toLocaleString("default", { day: "2-digit" });
    let dateFormat = getYear + "-" + getMonth + "-" + getDay;

    let nuevaFila = 
    `<tr id="tdCheques">
        <td style="width:40px;text-align:center" id="numInterno">${(nroInterno+1)}</td>
        <td style="text-align:center"><input type="text" style="width:120px" onchange="comprobarSumFila(this)"></td>
        <td style="text-align:center"><input type="text" style="width:120px" onchange="comprobarSumFila(this)"></td>
        <td style="text-align:center"><input type="text" style="width:120px" onchange="comprobarSumFila(this)"></td>
        <td style="text-align:center"><input type="date" style="width:120px" onchange="comprobarSumFila(this)" value="${dateFormat}"></td>
        <td style="text-align:center"><button value="+" onclick="agregarFila(${(nroInterno+1)},this)" hidden>+</button></td> 
    </tr>
    `;

    $("#tableCheques").append(nuevaFila);
 
}

const calcularMontoCheques = () => {

    let total = 0
    document.querySelectorAll("#tdCheques").forEach(element => {

        let chequeMonto = element.childNodes[5].childNodes[0].value;
        total += parseFloat(chequeMonto);

    })

    let cobroCheque = document.querySelector("#cobroCheque");
    cobroCheque.setAttribute("attr-valorreal",parseFloat( total));
    cobroCheque.value = parseFloat(total);
    calcularSaldo();

}


const registrarCheque =  (codClient) =>{
    let idCheques = document.querySelectorAll("#numInterno");
    document.querySelectorAll("#numInterno")[0].textContent;

    let todosLosIdCheques = "";

    idCheques.forEach(element => {

        if(todosLosIdCheques != ""){

            todosLosIdCheques = todosLosIdCheques + "," + element.textContent 
        }else{
            todosLosIdCheques = element.textContent 
        }

    });
    localStorage.removeItem("idCheques");
    localStorage.setItem("idCheques", todosLosIdCheques);


    document.querySelectorAll("#tdCheques").forEach(element =>{

        let fechaCobro = element.childNodes[9].childNodes[0].value;
        let banco = element.childNodes[3].childNodes[0].value;
        let numeroDeCheque = element.childNodes[7].childNodes[0].value;
        let numeroDeInterno = element.childNodes[1].textContent;
        let chequeMonto = element.childNodes[5].childNodes[0].value;
        let cod = codClient;
        
        document.querySelector("#idCheque").value = numeroDeInterno;

        $.ajax({

            url: "controller/registrarChequeController.php",
            type: "POST",
            data: {
                fechaCobro: fechaCobro,
                banco: banco,
                numeroDeCheque: numeroDeCheque,
                numeroDeInterno: numeroDeInterno,
                chequeMonto: chequeMonto,
                codClient: cod
            },
            success: function(data) {
                calcularMontoCheques();
            }
        });
 
    })
        
}

const comprobarSumFila = (fila) => {

    let banco = fila.parentElement.parentElement.childNodes[3].childNodes[0].value
    let monto = fila.parentElement.parentElement.childNodes[5].childNodes[0].value
    let nroCheque = fila.parentElement.parentElement.childNodes[7].childNodes[0].value
    let fecha = fila.parentElement.parentElement.childNodes[9].childNodes[0].value
    

    if(banco != "" && monto != "" && nroCheque != "" && fecha != ""){
        fila.parentElement.parentElement.childNodes[11].childNodes[0].removeAttribute("hidden");
    }
}

const parseNumber = (number) => {
    number = parseFloat(number);

    newNumber = number.toLocaleString('de-De', {
        style: 'decimal',
        maximumFractionDigits: 2,
        minimumFractionDigits: 2
    }); 
    
    return newNumber;
}

const setearValores =()=>{

    let efectivo = document.querySelector("#cobroEfectivo");
    let cheque = document.querySelector("#cobroCheque");


    if(efectivo.value == ""){
        efectivo.value = 0;
    }
    if(cheque.value == "" ){

        cheque.value = 0;
    }



    efectivo.setAttribute("attr-valorreal", efectivo.value);
    cheque.setAttribute("attr-valorreal", cheque.value);

    nuevoValor = parseNumber(efectivo.value);
    efectivo.value = nuevoValor;

    nuevoValorCheque = parseNumber(cheque.value);
    cheque.value = nuevoValorCheque;

    calcularSaldo();
}
