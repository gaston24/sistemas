let user = '<?=$user?>'

// CONTROL REMITOS

let form = document.querySelector("#formControlRemito");
let btnBorrador = document.querySelector("#btnBorrador");
let btnTraerBorrador = document.querySelector("#btnTraerBorrador");

document.addEventListener("DOMContentLoaded", ()=>{
    localStorage.removeItem('ultimosChequeados')
    existeBorrador()    
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

        ultimos += `<tr><td class="col-" style="width:3.5em">${x}</td></tr>`;

    })

 
    // alert(ultimos);
    Swal.fire({
        position: 'top-center',
        showConfirmButton: true,
        html: `<h5>Detalle escaneo</h5>
        <table class="table table-striped mt-2 ml-2">
			<thead class="thead-dark mt-1">
				<tr>
					<td class="col-" style="width:3.5em">CODIGO</td>
				</tr>
			</thead>
			<tbody id="table">
                ${ultimos}
			</tbody>

		</table>
               <br> `,
      })

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
    let cantidad = (articulo.CANTIDAD) ? articulo.CANTIDAD : 1 ;
    if(buscarExistente(articulo.COD_ARTICU)){

        table.insertAdjacentHTML('beforeend',
            `
            <tr class="fila-base">
                <td class="col-" style="width:6em">${articulo.COD_ARTICU}</td>
                <td class="col-" style="width:5em">${articulo.DESCRIPCIO}</td>
                <td class="col-" style="width:3em" align="center">${cantidad}</td>
                <td class="col-"><img src="eliminar.png" width="17rem" height="17rem" align="left" onClick="eliminarArticulo(this)"></img></td>
            </tr>
            `
        )

    }
    sumFilas();

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
    sumFilas();
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

    Swal.fire({
        title: 'Seguro de que quieres procesar el remito ?',
        showDenyButton: true,
        confirmButtonText: 'Procesar',
        denyButtonText: `Cancelar`,
    }).then((result) => {

        if (result.isConfirmed) {
         
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

        } else if (result.isDenied) {

          Swal.fire('Remito no procesado', '', 'info')

        }
        
    })

})


const procesarRemito = (articulosControlados, remito, codClient) => {

    let server = (window.location.href.includes("sistemas")) ? window.location.href.split('/sistemas')[0]+'/sistemas' : window.location.origin;

    fetch(server+'/control/controlador/procesar.php', {
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
        });

}
btnBorrador.addEventListener("click", ()=>{


    let table = document.querySelector("#table");
    let tr = table.querySelectorAll("tr");
    let numRem = document.querySelector("#numRem").innerHTML;
    
    let newArray= [];
    newArray[0] = []
    
    tr.forEach((element,x) => {

            let allTd = element.querySelectorAll("td")
            let codigo  = allTd[0].innerHTML;
            let descripcion = allTd[1].innerHTML;
            let cantidad = allTd[2].innerHTML;
            newArray[0][x] = [codigo,descripcion,cantidad];
    
    })
    newArray.unshift(numRem);


    let borrador = (sessionStorage.getItem("borrador")) ? sessionStorage.getItem("borrador") : null;

    if(borrador != null && borrador){

        borrador = JSON.parse(borrador);
        
        if(existeEnSession(borrador, numRem)){

            let borrador2 = [];

            borrador2 = borrador.filter( element => {

                return element[0] != numRem;
            });

            borrador = borrador2;
        }

        borrador.push(newArray);

    }else{   
        borrador = [];
        borrador [0]  = newArray;
    }

    let  borradorString = JSON.stringify(borrador);

    sessionStorage.setItem("borrador",borradorString);
    
    
})

btnTraerBorrador.addEventListener("click", ()=>{

    let borrador = sessionStorage.getItem("borrador");
    let numRem = document.querySelector("#numRem").innerHTML;
    let table = document.querySelector("#table");
    borrador = JSON.parse(borrador);

    table.innerHTML = '';
    
    borrador.forEach(x => {
        if(numRem == x[0]){
            
            checkVisibility();

            x[1].forEach(element => {

                let articulo = {
                    COD_ARTICU: element[0],
                    DESCRIPCIO: element[1],
                    CANTIDAD: element[2],
                }
                
                addArticulo(articulo);
            });

    

        }
        
    });

})


const sumFilas = () => {
    let count = document.querySelector("#table").childElementCount;
    if(count > 0){
        btnBorrador.hidden = false;
    }else{
        btnBorrador.hidden = true;
    }


}

const existeBorrador = () => {
    let numRem = document.querySelector("#numRem").innerHTML;
    let borrador = sessionStorage.getItem("borrador");
    if(borrador){
        borrador = JSON.parse(borrador);
        borrador.forEach(x => {
            if(numRem == x[0]){
                btnTraerBorrador.hidden = false;
            }
        })
    }else{
        btnTraerBorrador.hidden = true;
    }
}


const existeEnSession = (borrador,numRem) => {
    existe = false;
    borrador.forEach(element => {
        if(element[0] == numRem){
           existe = true;
        }  
    });
    return existe;
}

