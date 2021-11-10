let button = document.getElementById('buttonHistorial');

var lastCode = '';

const getHistorial = (user)=>{

    return axios({
        method: 'post',
        url: 'controlador/verHistorial.php',
        data: {
            user: user
        },
    })
    .then(res=>{
        let resultado = res.data;
        var historial = '';

        resultado.map( (x) => {
            if(lastCode == ''){lastCode = x[0].COD_ARTICU};
            historial += x[0].COD_ARTICU+'\n';
        })
        document.getElementById('lastCodigoControlado').innerHTML = lastCode;
        localStorage.setItem('historial', historial);
    })

}

getHistorial(user);


button.addEventListener('click', ()=>{
    alert(localStorage.getItem('historial'))
})