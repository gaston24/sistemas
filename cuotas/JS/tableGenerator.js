export class TableGenerator {
  valueTD;
  inputType; //si necesito un valor diferente dentro de cada td, lo seteo con el tipo [checkbox,text,mail,etc]
  info;

  constructor(headers, id) {
    //el n° de th determina la longitud de la tabla.
    this.headers = headers;
    /* this.info=info; */
    this.table = document.createElement("table");
    this.thead = document.createElement("thead");
    this.tbody = document.createElement("tbody");
    this.id = id;
  }

  generateTableHeader() {
    let tr = document.createElement("tr");
    for (let header of this.headers) {
      let th = document.createElement("th");
      th.appendChild(document.createTextNode(header));
      tr.appendChild(th);
    }
    this.thead.appendChild(tr);
    this.table.appendChild(this.thead);
    this.table.setAttribute("id", this.id);
  }

  generateTableBody() {
    let tr = document.createElement("tr");
    this.headers.forEach((item, index) => {
      //la cantidad de columnas head me determina el legth que deberia tener la tabla 
      let td = document.createElement("td");

      if (this.inputType != null) {
        //inputType determina si el elemento td solo contendrá un texto o algun otro elemento como un input.En ese caso lo crea dentro del td.
        if (index != 0) {
          // columna 0 va otro valor .En este caso el nombre de la tarjeta. 
          let input = document.createElement("input");
          input.type = this.inputType;

          switch (index) {
            //para que coincida la longitud de la tabla con el n° de cuota y establecer su relación, cambio el valor del index segun valor de la cuota de la tarjeta. 
            case 8:
              index = 12;
              break;
            case 9:
              index = 13;
              break;
            case 10:
              index = 16;
              break;
            default:
          }
          td.setAttribute("id", index);

         /*  input.checked = true;
          console.log(this.info.includes(index)); */
          if (this.info.includes(index)) {
            input.setAttribute("checked", "checked");
          }
          td.appendChild(input);
        } else {
          td.appendChild(document.createTextNode(this.info[0]));
        }
      }
      tr.appendChild(td);
      this.tbody.appendChild(tr);
    });

    // Construir la tabla

    this.table.appendChild(this.tbody);

    div.appendChild(this.table);
  }

  set valueTD(value) {
    this.valueTD = value;
  }

  set valueTH(value) {
    this.valueTH = value;
  }

  set valueAttributeID(value) {
    this.valueAttributeID = value;
  }

  set inputType(value) {
    this.inputType = value;
  }
  /* 
    function  addInput() {
        if()
    } */
  set setInfo(value) {
    this.info = value;
  }
}
