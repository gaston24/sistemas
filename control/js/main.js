let user = '<?=$user?>'

// CONTROL REMITOS

let form = document.querySelector("#formControlRemito");

document.addEventListener("DOMContentLoaded", ()=>{
    localStorage.removeItem('ultimosChequeados')
})

form.addEventListener("submit", (e)=>{

    e.preventDefault();

    let codigo = e.target.querySelector("#codigoName").value;

    if(codigo.length > 5){

        verificarCodigo(codigo);

        sumarTotal();

        e.target.querySelector("#codigoName").value = '';

        document.querySelector("#codigoName").focus();

    }else{
        document.querySelector("audio").play();
        
        alert('El codigo no existe!');

        e.target.querySelector("#codigoName").value = '';

        document.querySelector("#codigoName").focus();
    }

})

let buttonHistorial = document.querySelector("#buttonHistorial");

buttonHistorial.addEventListener("click", ()=>{

    let ultimosChequeados = localStorage.getItem('ultimosChequeados');
    ultimosChequeados = JSON.parse(ultimosChequeados);

    let ultimos = '';

    ultimosChequeados.forEach(x=>{
        ultimos += x+'\n';
    })

    alert(ultimos)


})

const verificarCodigo = (codigo) =>{

    checkVisibility();

    let maestroArt = localStorage.getItem('maestroArt')

    maestroArt = maestroArt.replaceAll(/(\r\n|\n|\r)/gm, '');

    maestroArt = JSON.parse(maestroArt);

    let flag = false;

    maestroArt.forEach((x)=>{
        if(x.COD_ARTICU == codigo || x.SINONIMO == codigo){

            flag = true;

            addArticulo(x)
            
            updateUltimoCodigo(x.COD_ARTICU);

            ultimosChequeados(x.COD_ARTICU)
        }

    })

            
    if(!flag){
        document.querySelector("audio").play();
        alert('El codigo no existe!');
    }
}

const checkVisibility = ()=>{
    let bodyControl = document.querySelector("#bodyControl");
    bodyControl.style.display = "block";
}

const addArticulo = (articulo) =>{
    let table = document.querySelector("#table");

    if(buscarExistente(articulo.COD_ARTICU)){

        table.insertAdjacentHTML('beforeend',
            `
            <tr class="fila-base">
                <td class="col-" style="width:6em">${articulo.COD_ARTICU}</td>
                <td class="col-" style="width:5em">${articulo.DESCRIPCIO}</td>
                <td class="col-" style="width:3em" align="center">1</td>
                <td class="col-"><img src="eliminar.png" width="17rem" height="17rem" align="left" onClick="eliminarArticulo(this)"></img></td>
            </tr>
            `
        )

    }


}

const buscarExistente = (codigo)=>{
    let codigos = document.querySelectorAll(".fila-base");
    let flag = true;
    codigos.forEach(x=>{
        if(x.querySelectorAll("td")[0].innerHTML == codigo){
            x.querySelectorAll("td")[2].innerHTML = parseInt(x.querySelectorAll("td")[2].innerHTML)+1;
            flag = false;
        }
    })
    return flag;
}

const sumarTotal = () =>{
    let codigos = document.querySelectorAll(".fila-base");
    let total = 0;

    codigos.forEach(x=>{
        total += parseInt(x.querySelectorAll("td")[2].innerHTML)
    })

    document.querySelector("#totalArt").innerHTML = total;

}

const updateUltimoCodigo = (codigo) => {
    let ultimo = document.querySelectorAll("#lastCodigoControlado");
    ultimo.forEach(x=>{
        x.innerHTML = codigo;
    })
}

const eliminarArticulo = (e) => {
    e.parentElement.parentElement.remove();
    sumarTotal();
}

const ultimosChequeados = (codigo) => {

    let ultimosChequeados = localStorage.getItem('ultimosChequeados');

    let array = [];

    if(ultimosChequeados){

        array = JSON.parse(ultimosChequeados);
        array.push(codigo);
    
    }else{

        array.push(codigo);
    
    }

    localStorage.setItem('ultimosChequeados', JSON.stringify(array))
}



// PROCESAR CONTROLADOS

let btnProcesar = document.querySelector("#btnProcesar");

btnProcesar.addEventListener("click", ()=>{


    let codigos = document.querySelectorAll(".fila-base");
    let controlados = [];

    codigos.forEach(x=>{
        controlados.push(
            [
                x.querySelectorAll("td")[0].innerHTML,
                x.querySelectorAll("td")[2].innerHTML
            ]
        )
    })


    procesarRemito(controlados, remito, codClient);

})


const procesarRemito = (articulosControlados, remito, codClient) => {

    let server = window.location.href.split('/sistemas')[0];

    fetch(server+'/sistemas/control/controlador/procesar.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({data: articulosControlados}) 
      })
        .then((response) => response.json())
        .then((data) => { 
            if(data == 1){
                window.location.href= 'controlDetalle.php?rem='+remito+'&codClient='+codClient
            }else{
                console.log("HA OCURRIDO UN ERROR EN EL SERVER")
            }
         } );

}


