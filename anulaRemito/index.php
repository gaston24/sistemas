
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
                    <select name="nroRemito" id="" class="form-control">
                        <?php
                        foreach ($list as $key => $value) {
                            ?>
                            <option value="<?=$value['NCOMP_IN_S']?>"><?=$value['N_COMP']?> - <?=$value['COD_PRO_CL']?></option>
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
            </table>

            <div class="col">
                    <button class="btn btn-primary" id="send">ENVIAR</button>
            </div>  
    </div>

</body>

<script>

$(document).ready(function() {
    var t = $('#table').DataTable();
    var counter = 1;
 
    $('#addRow').on( 'click', function () {
        t.row.add( [
            '2021-09-21',
            '16700002030',
            'GTCENT',
            'Error en el cliente'
        ] ).draw( false );
 
        counter++;
    } );
 
    // Automatically add a first row of data
    $('#addRow').click();
} );

</script>

</html>