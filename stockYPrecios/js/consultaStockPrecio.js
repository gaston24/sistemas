const traerArticulos = () => {

  $.ajax({
    url: "Controller/StockPrecioController.php?accion=traerArticulos",
    type: "GET",
    success: function (response) {
  
      localStorage.setItem("articulos", response);
    }

  });
}

document.addEventListener("DOMContentLoaded", traerArticulos);

const traerArticulo = (div) => {

  codArticulo =  div.value.split("-")[0] ;
  

  
  $.ajax({
      url: "Controller/StockPrecioController.php?accion=traerArticulos",
      type: "POST",
      data: {codArticulo: codArticulo},
      success: function (response) {
          data = JSON.parse(response);
  
          
          if(data.length > 0){

              document.querySelector("#articulo"). value = data[0]['COD_ARTICU'] ;
              document.querySelector("#descripcion").value = data[0]['DESCRIPCIO'];
              document.querySelector("#stock").value =  parseInt(data[0]['CANT_STOCK']) ;
              document.querySelector("#precio").value = "$" + parseNumber(data[0]['PRECIO']);

          }else{

              document.querySelector("#articulo"). value = "" ;
              document.querySelector("#descripcion").value = "";
              document.querySelector("#stock").value =  "" ;
              document.querySelector("#precio").value = "";

          }

      }
  
    });


}

const parseNumber = (number) => {
  number = parseInt(number);

  newNumber = number.toLocaleString('de-De', {
      style: 'decimal',
      maximumFractionDigits: 0,
      minimumFractionDigits: 0
  });

  return newNumber;
}

const borrar = () => {


  
  document.querySelector("#select2-selectArticulo-container").textContent = "";
  document.querySelector("#articulo"). value = "" ;
  document.querySelector("#descripcion").value = "";
  document.querySelector("#stock").value =  "" ;
  document.querySelector("#precio").value = "";


}