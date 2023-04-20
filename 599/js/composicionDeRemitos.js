
const parseNumber = ()=>{

    let allDeuda = document.querySelectorAll("#colDeuda");
    let allCobrado = document.querySelectorAll("#colCobrado");

    allDeuda.forEach(deuda => {
        let valor = parseFloat(deuda.textContent);
        valor = valor.toLocaleString('de-De', {
            style: 'decimal',
            maximumFractionDigits: 2,
            minimumFractionDigits: 2
        });
        deuda.textContent = "$ "+valor;
    });

    allCobrado.forEach(cobrado => {
        let valor = parseFloat(cobrado.textContent);
        valor = valor.toLocaleString('de-De', {
            style: 'decimal',
            maximumFractionDigits: 2,
            minimumFractionDigits: 2
        });
        cobrado.textContent = "$ "+valor;
    });


}

const verDetalle = (rem) =>{
        
    codClient = rem.parentElement.parentElement.childNodes[0].getAttribute("attr-codClient") ;
   
    window.location.href = "remitosPendientesDeCobro.php?codClient="+codClient;
}

$("#btnExport").click(function() {

    $('input[type=number]').each(function(){
        this.setAttribute('value',$(this).val());
    });

    $("table").table2excel({

        // exclude CSS class
        exclude: ".noExl",
        name: "Worksheet Name",
        filename: "Remitos", //do not include extension
        fileext: ".xls", // file extension
    });
});