document.addEventListener("DOMContentLoaded", ()=>{
    datosVarios();
    llenarTabla(JSON.parse(articulosRemito), JSON.parse(articulosControlados));
    let datosTotal = actualizarDatos();
    totales();
    procesarRemitoControlado(datosTotal);
})


const llenarTabla = (articulosRemito, articulosControlados)=>{

    let cantArticuCompara = 0;

    articulosRemito.forEach(x => {

        articulosControlados.forEach(y=>{
            if(x.COD_ARTICU == y[0]){
                cantArticuCompara = (x.COD_ARTICU == y[0]) ? y[1] : 0;
                row(x.COD_ARTICU, x.CANTIDAD, cantArticuCompara);
            }
        })
        
    });

    let artSobran = [];

    articulosRemito.forEach(y=>{

        let flag = false;

        articulosControlados.forEach(x=>{
            if(y.COD_ARTICU == x[0]){
                flag = true;
            }
        })

        if(!flag){
            artSobran.push(y)
        }
        
    })

    artSobran.forEach(x=>{
        row(x.COD_ARTICU, x.CANTIDAD, 0);
    })


}


const row = (codArticu, cantRem, cantControl) =>{

    let table = document.querySelector("tbody");

    table.insertAdjacentHTML('beforeend', 
        `<tr id="rowData" style="font-size:smaller" >
            <td id="codArticu">${codArticu}</td>
            <td id="descripcio"></td>
            <td id="cantRem">${cantRem}</td>
            <td id="cantControl">${cantControl}</td>
            <td id="dif"></td>
        </tr>`
    );
}

const actualizarDatos = () =>{
    let table = document.querySelector("tbody");

    let maestroArt = localStorage.getItem('maestroArt')
    maestroArt = maestroArt.replaceAll(/(\r\n|\n|\r)/gm, '');
    maestroArt = JSON.parse(maestroArt);
    
    let rows = table.querySelectorAll("#rowData");
    
    rows.forEach(x=>{
        maestroArt.forEach(y=>{
    
            if(x.querySelector("#codArticu").innerHTML == y.COD_ARTICU){
                x.querySelector("#descripcio").innerHTML = y.DESCRIPCIO
            }
            
        })
    
        x.querySelector("#dif").innerHTML = parseInt(x.querySelector("#cantRem").innerHTML) - parseInt(x.querySelector("#cantControl").innerHTML)
        
    })

    
    let datosTotal = [];
    let tablaActual = document.querySelectorAll("#rowData");

    tablaActual.forEach((x, y)=>{
        datosTotal.push([x.querySelectorAll("td")[0].innerHTML, x.querySelectorAll("td")[2].innerHTML, x.querySelectorAll("td")[3].innerHTML, x.querySelectorAll("td")[4].innerHTML])
    })

    return datosTotal;

}

const totales = () =>{
    let table = document.querySelector("tbody");

    let rows = table.querySelectorAll("#rowData");

    let cantRem = 0;
    let cantControl = 0;
    let dif = 0;
    
    rows.forEach(x=>{

        cantRem += parseInt(x.querySelector("#cantRem").innerHTML);
        cantControl += parseInt(x.querySelector("#cantControl").innerHTML);
        dif += parseInt(x.querySelector("#dif").innerHTML);

        if(dif != 0){
            x.style.color = '#FE2E2E'
            x.style.fontWeight = 'bold'
        }

    })

    table.insertAdjacentHTML('beforeend', 
        `<tr style="font-weight: bold;">
            <td ></td>
            <td>TOTALES</td>
            <td >${cantRem}</td>
            <td >${cantControl}</td>
            <td >${dif}</td>
        </tr>`
    );


    
}

const datosVarios = ()=> { 
    const timeElapsed = Date.now();
    const today = new Date(timeElapsed);
    const date = today.toLocaleString(); 

    document.querySelector("#vendNom").innerHTML = nombreVen.replaceAll('_', ' ') + ' - ' + date;
}


const procesarRemitoControlado = (datosTotal) => {

    let server = window.location.href.split('/sistemas')[0];

    fetch(server+'/sistemas/control/controlador/procesarRemito.php', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({data: datosTotal}) 
      })

}