const btnConfirmar = document.querySelector("#btnConfirmar");

const parseNumber = ()=>{

    let allMontos = document.querySelectorAll("#monto");

    allMontos.forEach(monto => {
        let valor = parseFloat(monto.textContent);
        valor = valor.toLocaleString('de-De', {
            style: 'decimal',
            maximumFractionDigits: 0,
            minimumFractionDigits: 0
        });
        monto.setAttribute("attr-realValue", monto.textContent)
        monto.textContent = "$ "+valor;
    });

}

const checkMonto = ()=>{

    const todosLosCheck = document.querySelectorAll('input[type="checkbox"]');
    let totalMontosCheck = 0;

    todosLosCheck.forEach(e => {
        if(e.checked){

            totalMontosCheck = totalMontosCheck + parseFloat(e.parentElement.parentElement.childNodes[2].getAttribute("attr-realValue"))
        }
    });
    let descuento = document.querySelector("#descuento").value;
    descuento = descuento.replace("%","");

    if(descuento != "" && descuento != 0){
     
        
        let porcentaje = 0;
        
        porcentaje = ( parseFloat(totalMontosCheck)  *  parseFloat(descuento)  ) / 100;

        totalMontosCheck = parseFloat(totalMontosCheck) - parseFloat(porcentaje);
        
        document.querySelector("#importeConDescuento").value = totalMontosCheck;
        
    }else{
        document.querySelector("#importeAbonar").value = totalMontosCheck;
    }


    // document.querySelector("#importeAbonar").value = totalMontosCheck

}

btnConfirmar.addEventListener("click",function (){

    const todosLosCheck = document.querySelectorAll('input[type="checkbox"]');
    let totalMontosCheck = ""

    todosLosCheck.forEach(e => {

        if(e.checked){
        if(totalMontosCheck == ""){
        totalMontosCheck = e.parentElement.parentElement.childNodes[1].textContent
        }else{

        totalMontosCheck =  totalMontosCheck + "-" +e.parentElement.parentElement.childNodes[1].textContent
        }
        }

    });

    sessionStorage.setItem("Remitos", totalMontosCheck);

    if( document.querySelector("#descuento").value == "" || document.querySelector("#descuento").value < 0 ){

        Swal.fire({
            icon: 'warning',
            title: 'No se cargó % de descuento, desea continuar?',
            showDenyButton: true,
            confirmButtonText: 'Aceptar',
            denyButtonText: 'Cancelar',
        }).then((result) => {

            if (result.isConfirmed) {

                Swal.fire('Guardado!', '', 'success').then((result)=>{
                    let montoTotalDeuda = document.querySelector("#totalDeuda").value
                    let importeAbonar = document.querySelector("#importeAbonar").value
                    let codCliente = document.querySelector("#codClient").textContent

                    window.location.href = "cargaCobranza.php?montoTotal="+montoTotalDeuda+"&importeAbonar="+importeAbonar.replace(/[$.]/g, "")+"&codCliente="+codCliente;

                })


            } else if (result.isDenied) {
                Swal.fire('El proceso fue cancelado', '', 'info')
            }
        })

    }else{

        Swal.fire('Guardado!', '', 'success').then((result)=>{
            
            let montoTotalDeuda = document.querySelector("#totalDeuda").value;
            let importe = document.querySelector("#importeAbonar").value.replace(/[$.]/g, "");
            let descuento = document.querySelector("#descuento").value;
            descuento = descuento.replace("%","");

            let porcentaje = 0;

            if(descuento != "" && descuento != 0){
                porcentaje = ( parseFloat(importe)  *  parseFloat(descuento)  ) / 100;
            }

            let importeAbonar = parseFloat(importe) - parseFloat(porcentaje);

            let codCliente = document.querySelector("#codClient").textContent

            window.location.href = "cargaCobranza.php?montoTotal="+montoTotalDeuda+"&importeAbonar="+importeAbonar+"&codCliente="+codCliente;

        })


    }
})
const calcularDescuento = ()=>{

    let montoTotalDeuda = document.querySelector("#totalDeuda").value;
    let descuento = document.querySelector("#descuento").value;
    descuento = descuento.replace("%","");
    let importe = document.querySelector("#importeAbonar").value;

    let porcentaje = 0;

    if(descuento != "" && descuento != 0){
        document.querySelector("#divImporteAabonar").hidden = true;
        document.querySelector("#divImporteConDescuento").hidden = false;

        porcentaje = ( parseFloat(importe)  *  parseFloat(descuento)  ) / 100;
    }else{

        const todosLosCheck = document.querySelectorAll('input[type="checkbox"]');
        let totalMontosCheck = 0;
    
        todosLosCheck.forEach(e => {
            if(e.checked){
    
                totalMontosCheck = totalMontosCheck + parseFloat(e.parentElement.parentElement.childNodes[2].getAttribute("attr-realValue"))
            }
        });
        document.querySelector("#importeAbonar").value = totalMontosCheck;
        document.querySelector("#divImporteAabonar").hidden = false;
        document.querySelector("#divImporteConDescuento").hidden = true;
    }

    let importeAbonar = parseFloat(importe) - parseFloat(porcentaje);

    document.querySelector("#importeConDescuento").value = importeAbonar;


}