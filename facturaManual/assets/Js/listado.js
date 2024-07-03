const numeroSucursal = numSuc;
window.onload = async function() {
    try {
        /* obtener Token */
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
            // console.log(token);

        } else {
            throw new Error(`Error al obtener el token: ${tokenResponse.statusText}`);
            
        }

        let headersList = {
            "Authorization": token
            }
        console.log(token);
        const url = config.urlPath +"/Api/getFacturas/"+numSuc;
        console.log(url);
        const response = await fetch(url, { 
            method: "GET",
            headers: headersList
            });

        if (response.ok) {
            var registros = await response.json();
            var tableBody = document.getElementById('registros-tbody');
            tableBody.innerHTML = '';

            registros.forEach(registro => {
                var row = document.createElement('tr');
                
                var idCell = document.createElement('td');
                idCell.textContent = registro.id;
            
                var sucursalCell = document.createElement('td');
                sucursalCell.textContent = registro.numeroSucursal;
            
                var tipoCell = document.createElement('td');
                // tipoCell.textContent = registro.tipoFactura;
                // console.log(registro.tipoFactura);
            
                var numeroCell = document.createElement('td');
                numeroCell.textContent = registro.numeroFactura;
            
                var imgCell = document.createElement('td');
                var a = document.createElement('a');
                a.href = config.urlPath + registro.imgFactura;
                a.target = "_blank";
                var img = document.createElement('img');
                if(registro.tipoFactura == 0){
                    img.src = "../facturaManual/assets/img/Factura-A.png";
                    tipoCell.textContent = "Factura A";
                }else{
                    img.src = "../facturaManual/assets/img/Factura-B.png";
                    tipoCell.textContent = "Factura B";
                }
                img.alt = registro.imgFactura.substring(registro.imgFactura.lastIndexOf('/') + 1);
                // img.width = 100;
                // img.height = 'auto';
                a.appendChild(img);
                imgCell.appendChild(a);
            
                var fechaCell = document.createElement('td');
                const fecha = new Date(registro.fechaRegistro);
                const dia = String(fecha.getDate()).padStart(2, '0');
                const mes = String(fecha.getMonth() + 1).padStart(2, '0');
                const año = fecha.getFullYear();
                const hora = String(fecha.getHours()).padStart(2, '0');
                const minuto = String(fecha.getMinutes()).padStart(2, '0');
                const fechaFormateada = `${dia}/${mes}/${año} ${hora}:${minuto}`;
                
                fechaCell.textContent = fechaFormateada;
            
                // row.appendChild(idCell);
                // row.appendChild(sucursalCell);
                row.appendChild(imgCell);
                row.appendChild(tipoCell);
                row.appendChild(numeroCell);
                row.appendChild(fechaCell);
            
                tableBody.appendChild(row);
            });            
            
            const urlCarga = "../facturaManual/carga.php?suc=";
            // const numSuc = 201;
            const nuevoHref = urlCarga + numSuc;
            const enlace = document.getElementById('carga');
            if (enlace) {
                enlace.href = nuevoHref;
            }
            document.getElementById("spiner").style.display = "none";

        } else {
            throw new Error(`Error al obtener los registros: ${response.statusText}`);
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
};
