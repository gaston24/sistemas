import { TableGenerator } from "./tableGenerator.js";



fetch("Controller/InfoLocales.php")
  .then((response) => response.json())
  .then((data) => {
 /************************* */
    let spinner=document.querySelector('.loader');
    let switchbutton=document.querySelector('.switch-button .switch-button__label');//el bton switch por las propiedades que tiene no se oculta. Lo controlo desde acá. 
    spinner.style.display='none';
    switchbutton.style.display='inline-block';
/***************************/
    let tarjetas = ["AME", "MST", "VIS"];
    let div = document.getElementById("div");
    console.log(data);
    //local=>[[{info local}],[{tarjeta y cuotas},{tarjeta y cuotas},{tarjeta y cuotas},{tarjeta y cuotas}...]]
    data.forEach((local) => {
      div.innerHTML += `<h3 id="${local[0][0]}">${local[0][1]}</h3>`;//nombre del local
      let numSucursal = local[0][0];//num de sucursal
      let msj = typeof local[1][1] == "string" ? local[1][1] : "";
      console.log(msj);
      console.log(typeof msj);
      if (!msj.includes("Error")) {
        let encabezadosTabla = [
          "TARJETA",
          "CUOTA 1",
          "CUOTA 2",
          "CUOTA 3",
          "CUOTA 4",
          "CUOTA 5",
          "CUOTA 6",
          "CUOTA 7",
          "CUOTA 12",
          "CUOTA 13",
          "CUOTA 16",
        ];
        let nuevaTabla = new TableGenerator(encabezadosTabla, numSucursal);
        nuevaTabla.generateTableHeader();
        tarjetas.forEach((item) => {
          const infoCuotas = local[1].filter((tarjeta) => {
            if (tarjeta.COD_TARJET === item) {
              return [tarjeta.CUOTA];
            }
          });
          let nombreTarjeta = infoCuotas[0].COD_TARJET;
          let cuotasTarjetaHabilitadas = infoCuotas.map((item) => {
            return parseInt(item.CUOTA);
          });
          let datos = [nombreTarjeta, ...cuotasTarjetaHabilitadas];
          nuevaTabla.setInfo = datos;
          nuevaTabla.inputType = "checkbox";
          nuevaTabla.generateTableBody();
        });
      } else {
        div.innerHTML += `<h5 style="color:red;">Error en la conexión.</h5>`;
      }
    });

    iniciarEscucha(); //una vez cargado los elementos comienza a escuchar eventos en el dom como cambio de estado en los checkbox
  })
  .catch((error) => console.log("Hubo un error con la solicitud AJAX.", error));

function iniciarEscucha() {
  document.querySelectorAll("table").forEach((item) => {
    item.addEventListener("click", (event) => {
      console.log("local: " + item.id);

      if (event.target.tagName === "INPUT") {
        console.log("local: " + event.target.checked); // estado true o false del checkbox
        console.log("id: " + event.target.parentNode.id); //el id indica el n° de cuota
        console.log(
          "tarjeta:" + event.target.parentNode.parentNode.children[0].innerText
        ); //obtengo el nombre de la tarjeta
        let datos = {
          local: item.id,
          tarjeta: event.target.parentNode.parentNode.children[0].innerText,
          cuota: event.target.parentNode.id,
          estado: event.target.checked,
        };
        console.log(datos);
        fetch("Controller/updateCuotas.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(datos),
        })
          .then(function (response) {
            if (response.ok) {
              // La solicitud se completó exitosamente
              /* return response.json(); */
              console.log("La solicitud se completó exitosamente");
            } else {
              alert(
                `Error en la solicitud! Código de estado: ${response.status}`
              );
              throw new Error("Error en la solicitud: " + response.status);
            }
          })
          .then(function (data) {
            // Hacer algo con la respuesta recibida del servidor (data)
          })
          .catch(function (error) {
            // Manejar errores
            alert(error.message);
            console.log("error: ", error);
          });
      }
    });
  });
}
