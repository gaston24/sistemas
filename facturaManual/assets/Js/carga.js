const form = document.querySelector('form');
window.onload = async function() {

    // const configResponse = await fetch('midata.php');
    //     if (!configResponse.ok) {
    //         throw new Error(`Error al obtener la configuración: ${configResponse.statusText}`);
    //     }        
    //     const configData = await configResponse.text();
    //     if (!configData) {
    //         throw new Error('La respuesta de config.php está vacía');
    //     }        
    //     const config = JSON.parse(configData);

        const urlCarga = "../facturaManual/listado.php?suc=";
            // const numSuc = 201;
            const nuevoHref = urlCarga + numSuc;
            const enlace = document.getElementById('cerrar');
            if (enlace) {
                enlace.href = nuevoHref;
            }
};
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    try {
        
        const configResponse = await fetch('midata.php');
        if (!configResponse.ok) {
            throw new Error(`Error al obtener la configuración: ${configResponse.statusText}`);
        }        
        const configData = await configResponse.text();
        if (!configData) {
            throw new Error('La respuesta de config.php está vacía');
        }        
        const config = JSON.parse(configData);
        const urlToken = config.urlPath +"/Api/gettoken";
        const tokenResponse = await fetch(urlToken, {
            method: 'GET',
            headers: {
                'USERNAME': config.username,
                'PASSWORD': config.password
            },
        });
        const data = await tokenResponse.text();
        // console.log("dataResponse: " + data);
        // if (!data) {
        //     throw new Error('La respuesta está vacía');
        // }
        // const result = JSON.parse(data);
        // const token = "token " + result["token"];
        // console.log(token);
        // 
        let token = "";
        if (tokenResponse.ok) {
            const result = JSON.parse(data);
            token = "token " + result["token"];
            console.log(token);

        } else {
            throw new Error(`Error al obtener el token: ${tokenResponse.statusText}`);
           
        }

        // Obtener los elementos del formulario
        const numeroSucursal = numSuc;
        const tipoFactura = document.getElementById("tipoFactura").value;
        const numeroFactura = document.getElementById("numeroFactura").value;
        const imgFacturaInput = document.getElementById("imgFactura");
        let files = imgFacturaInput.files;
        nameFile = numeroSucursal + numeroFactura;

        const formData = new FormData();
        if (files.length > 0) {
            formData.append("imgFactura", files[0]);
        }
        formData.append("numeroSucursal", numeroSucursal);
        formData.append("tipoFactura", tipoFactura);
        formData.append("numeroFactura", numeroFactura);
        let headersList = {
        "Authorization": token
        }

        const urlPost = config.urlPath + '/Api/postFactura';
        console.log(urlPost);
        let postResponse = await fetch(urlPost, { 
        method: "POST",
        body: formData,
        headers: headersList
        });

        if (postResponse.ok) {
            // window.location.href = '/sistemas/index.php';
            alert('Factura cargada con exito');
            location.reload();
        } else {
            const resultado = await postResponse.text();
            const result = JSON.parse(resultado);
            for (let clave in result) {
                if (result.hasOwnProperty(clave)) {
                    let valor = result[clave];
                    // Mostrar el valor aquí
                    throw new Error(` ${valor}`);
                }
            }
        }
        

    } catch (error) {
        if (error.message.includes('Failed to fetch')) {
            alert('Error de conexión. Por favor, revise su configuración de red y vuelva a intentarlo.');
            location.reload();
        } else {
            alert(error);
            console.error(error);
            location.reload();
            
        }
    }
});

