let data2;
fetch('Controller/InfoLocales.php')
.then(response => response.json())  // parsear la respuesta como JSON
.then(data => {
    data2=data;
    console.log(data);  // imprimir el objeto de respuesta
    /* console.log(data.nombre);  // imprimir la propiedad "nombre" */
})
.catch(error => console.log('Hubo un error con la solicitud AJAX.', error));
