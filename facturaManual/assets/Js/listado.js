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
        const tokenResponse = await fetch('http://127.0.0.1:8000/Api/gettoken', {
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

        let headersList = {
            "Authorization": token
            }
        let url = "http://127.0.0.1:8000/Api/getFacturas/"+numSuc;
        let response = await fetch(url, { 
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
                tipoCell.textContent = registro.tipoFactura;
                
                var numeroCell = document.createElement('td');
                numeroCell.textContent = registro.numeroFactura;
                
                var imgCell = document.createElement('td');
                var img = document.createElement('img');
                img.src = registro.imgFactura;
                img.alt = "Imagen cargada";
                imgCell.appendChild(img);
                
                var fechaCell = document.createElement('td');
                fechaCell.textContent = registro.fechaRegistro;
      
                row.appendChild(idCell);
                row.appendChild(sucursalCell);
                row.appendChild(tipoCell);
                row.appendChild(numeroCell);
                row.appendChild(imgCell);
                row.appendChild(fechaCell);
      
                tableBody.appendChild(row);
              });
        } else {
            throw new Error(`Error al obtener los registros: ${response.statusText}`);
        }

    } catch (error) {
        console.error(error);
        alert(error);
        location.reload();
    }
};