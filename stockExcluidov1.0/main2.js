document.getElementById("save").addEventListener("click", guardar);

//Importar excel//
let descripcion;
let b = 0;
let articulosAImportar = [];
var ExcelToJSON = function () {
  this.parseExcel = function (file) {
    var reader = new FileReader();
    reader.onload = function (e) {
      var data = e.target.result;
      var workbook = XLSX.read(data, {
        type: "binary",
      });
      workbook.SheetNames.forEach(function (sheetName) {
        var XL_row_object = XLSX.utils.sheet_to_row_object_array(
          workbook.Sheets[sheetName]
        );
        var productList = JSON.parse(JSON.stringify(XL_row_object));

        var rows = $("#tblItems tbody");

        for (i = 0; i < productList.length; i++) {
          let columns = Object.values(productList[i]);
          columns[0] =
            columns[0] != undefined && columns != "" ? columns[0] : " ";
          columns[1] =
            columns[1] != undefined && columns != "" ? columns[1] : " ";
          columns[2] =
            columns[2] != undefined && columns != "" ? columns[2] : " ";
          buscarCodigo(columns[0]);
          columns.push(descripcion); //agregar descripción al objeto
          console.log(columns);
          articulosAImportar.push(columns); //arreglo con objetos para guardar en la db
          console.log("art a imp: " + articulosAImportar);
          rows.append(`
                              <tr>
                                  <td>${columns[0]}</td>
                                  <td>${columns[1]}</td>
                                  <td>${columns[2]}</td>
                                  <td id=${columns[0]}>${descripcion}</td>
                              </tr>
                          `);
          if (descripcion.includes("CODIGO INEXISTENTE")) {
            console.log("entraste");
            document.getElementById(columns[0]).style.color = "red";
          }
        }

        //  console.log(articulosAImportar);
      });
    };
    reader.onerror = function (ex) {
      // console.log(ex);
    };

    reader.readAsBinaryString(file);
  };
};

function handleFileSelect(evt) {
  var files = evt.target.files; // FileList object
  var xl2json = new ExcelToJSON();
  xl2json.parseExcel(files[0]);
}

document
  .getElementById("fileupload")
  .addEventListener("change", handleFileSelect, false);

//Recargar modal importar en botón Cerrar//

document.getElementById("close").addEventListener("click", () => {
  location.reload();
});

//Funciones botón Guardar//

document.getElementById("save").addEventListener("click", () => {
  if (b == 1) {
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "No se puede guardar si existen codigos inválidos.",
    });
  }

  /*  location.reload(); */
});

//buscar codigo
let resultado;
function buscarCodigo(art) {
  conexion = new XMLHttpRequest();
  conexion.onreadystatechange = () => {
    if (conexion.readyState == 4 && conexion.status == 200) {
      resultado = conexion.responseText;
      valores(resultado);
    }
  };

  conexion.open("GET", "articulos.php?codigo=" + art, false);
  conexion.send();
}

function valores(valor) {
  if (valor == "error") {
    /*  console.log("no existe"); */
    descripcion = "CODIGO INEXISTENTE";
    b = 1;
    Swal.fire({
      icon: "error",
      title: "Oops...",
      text: "Existen codigos incorrectos",
    });
  } else {
    /* console.log('descripcion: '+valor); */
    descripcion = valor;
  }
}

function modificarTabla() {
  let tabla = document.getElementsByTagName("table");
  let rows = tabla[1].rows;
}

function guardar() {
  if (b == 0) {
    //traer el json con las filas del excel
    console.log(articulosAImportar);
    conexion = new XMLHttpRequest();
    conexion.onreadystatechange = () => {
      if (conexion.readyState == 4 && conexion.status == 200) {
        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Cambios guardados",
          showConfirmButton: false,
          timer: 2000,
          x: setTimeout(function () {
            location.reload();
          }, 2500),
        });
      } else {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "Ocurrio un error: " + conexion.responseText,
          showConfirmButton: false,
          timer: 1500,
        });
      }
    };
    conexion.open("POST", "./Controller/insertArticulo.php", false);
    conexion.setRequestHeader(
      "Content-Type",
      "application/x-www-form-urlencoded"
    );
    let articulos =
      "articulos=" + encodeURIComponent(JSON.stringify(articulosAImportar));
    conexion.send(articulos);
  }
}
