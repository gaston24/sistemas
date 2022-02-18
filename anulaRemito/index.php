
<?php


include 'class/remito.php';

$remitos = new Remito();
$list = $remitos->traerRemitos();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anula remito</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.2/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    
    <H3 class="">SOLICITUD DE ANULACION DE REMITO</H3>

    

    <div class="container mt-4">
       
        <div>
            <label for="" ><a style="vertical-align: middle;">Nro Remito</a></label>
        </div>
            
            <div class="row">
                <div class="col-5">
                    <select name="nroRemito" id="nroRemito" class="form-control">
                        <?php
                        foreach ($list as $key => $value) {
                            ?>
                            <option value="<?=$value['NCOMP_IN_S']?>" ><?=$value['N_COMP']?> - <?=$value['COD_PRO_CL']?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="col">
                    <button class="btn btn-success" id="addRow">AGREGAR</button>
                </div>        
            </div>

            <table id="table" class="display">
                <thead>
                    <tr>
                        <th>Fecha remito</th>
                        <th>Remito</th>
                        <th>Cliente</th>
                        <th>Observaciones</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>

            </table>

            <div class="col">
                    <button class="btn btn-primary" id="send">ENVIAR</button>
            </div>  
    </div>

</body>

<script>

$(document).ready(function() {
    var t = $('#table').DataTable();
    
    function showDate(datePass) {
        var todayTime = new Date(datePass);
        var month = String(todayTime.getMonth()+1);
        month = '0'+month;
        month = month.substr(month.length-2);
        var day = String(todayTime.getDate());
        day = '0'+day;
        day = day.substr(day.length-2);
        var year = todayTime.getFullYear();
        var hour = todayTime.getHours();
        var min = String(todayTime.getMinutes());
        min = '0'+min;
        min = min.substr(min.length-2);
        var sec = String(todayTime.getSeconds());
        sec = '0'+sec;
        sec = sec.substr(sec.length-2);
        let time = day + "-" + month + "-" + year;
        return time;
    }

    const nroRemitoSelec = document.querySelector("#nroRemito")

    var remitos = '<?=json_encode($list)?>'
    remitos = JSON.parse(remitos)

    $('#addRow').on( 'click', function () {
        let remitoSelect = remitos.filter(x=>{
            if(x.NCOMP_IN_S == nroRemitoSelec.options[nroRemitoSelec.selectedIndex].value){
                return x
            }
        })
        
        t.row.add( [
            showDate(remitoSelect[0].FECHA.date),
            remitoSelect[0].N_COMP,
            remitoSelect[0].COD_PRO_CL,
            '<input name="motivo" type="text">'
        ] ).draw( false );

        // ACA CREO QUE ES ASI
        // filtra el array de los remitos originales, le resta el seleccionado mas arriba

        remitos = remitos.filter(x=>{
            if(x.NCOMP_IN_S != remitoSelect[0].NCOMP_IN_S){
                return x
            }            
        })


    } );

    const buttonSend = document.querySelector("#send")
    const tablaDatos = document.querySelector("#tableBody")

    buttonSend.addEventListener("click", function(){

        let datos = tablaDatos.querySelectorAll("tr")
        let datosEnviar = [];
        let temp = [];

        datos.forEach((x, i)=>{
            let datitos = x.querySelectorAll("td")
            temp = [];
            datitos.forEach(y=>{
                if(y.firstChild.value){
                    temp.push(y.firstChild.value)
                }else{
                    temp.push(y.innerHTML)
                }
            })
            datosEnviar.push(temp)
        })

        procesar(datosEnviar)

    })


    const procesar = (datosEnviar) => {
        console.log("estoy en la function procesar y recibi:", datosEnviar)
    }
 

} );

// crear function ajax para enviar el array con los datos de cada row
// crear el php controller que recorra con un foreach cada campo recibido por POST 
    // tiene que llamar a una function de una class que inserte en la DB
// ponerle los SESSION y vincular el dato del session[sucursal] con el metodo de inicio


</script>

</html>