<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:../login.php");
}else{

$codClient = $_SESSION['codClient'];

$_SESSION['codArt'] = $_GET['codArt'];
$_SESSION['contenedor'] = $_GET['contenedor'];

$codArt = $_SESSION['codArt'] ;

?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Carga Inicial</title>
    <link rel="shortcut icon" href="icono.jpg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css"
        integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"
        integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous">
    </script>

    <script>
    function pulsar(e) {
        tecla = (document.all) ? e.keyCode : e.which;
        return (tecla != 13);
    }


    function total() {
        var suma = 0;
        var x = document.querySelectorAll(
        "#id_tabla input[name='cantPed[]']"); //tomo todos los input con name='cantProd[]'

        var i;
        for (i = 0; i < x.length; i++) {
            suma += parseInt(0 + x[i]
            .value); //acá hago 0+x[i].value para evitar problemas cuando el input está vacío, si no tira NaN
        }

        // ni idea dónde lo vas a mostrar ese dato, yo puse un input, pero puede ser cualquier otro elemento
        document.getElementById('total').value = suma;
    };
    </script>



</head>

<body>

    </br>
    <div class="container-fluid">

        <?php

$dsn = "1 - CENTRAL";
$user = "sa";
$pass = "Axoft1988";

$cid = odbc_connect($dsn, $user, $pass);

if(!$cid){echo "</br>Imposible conectarse a la base de datos!</br>";}

$sql="

SELECT 799 NRO_SUCURS, 'MAARTE' COD_CLIENT, 'OFICINA & ARTE S.R.L' DESC_SUCURSAL
UNION ALL
SELECT NRO_SUCURS, COD_CLIENT, DESCRIPCION DESC_SUCURSAL 
FROM SOF_USUARIOS A
WHERE 
(
	(
		NRO_SUCURS IN 
		(
			SELECT NRO_SUCURS FROM SOF_USUARIOS A
			INNER JOIN SUCURSAL B ON A.NRO_SUCURS = B.NRO_SUCURSAL
			WHERE A.NRO_SUCURS > 104 AND B.CA_423_HABILITADO = 1
		)
	) OR COD_CLIENT IN ('GTWEB', 'GTDAF')
)
AND COD_CLIENT NOT IN (SELECT COD_CLIENT FROM SOF_DISTRIBUCION_INICIAL_RELACION WHERE COD_ARTICU = '$codArt')
AND NRO_SUCURS NOT IN (806, 829)
ORDER BY 1

";

$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));

?>


        <h2 align="center">Cantidad a Enviar para Franquicias</br>Codigo: <?php echo $_SESSION['codArt']; ?></h2></br>

        <nav style="margin-left:20%; margin-right:20%">

            <form method="POST" action="procesarLocales.php" onkeypress="return pulsar(event)">

                <table class="table table-striped" id="id_tabla">

                    <tr>

                        <td>NRO SUCURSAL</td>

                        <td></td>

                        <td>DESCRIPCION</td>

                        <td>CANT</td>

                        <td></td>

                    </tr>


                    <?php
	
		$total = 0;
       
		while($v=odbc_fetch_array($result)){

        ?>


                    <tr>
                        <!--style="font-size:smaller">-->

                        <td align="center"><?php echo $v['NRO_SUCURS'] ;?></td>

                        <td><input name="codClient[]" value="<?php echo $v['COD_CLIENT'] ;?>" hidden></td>

                        <td><?php echo $v['DESC_SUCURSAL'] ;?></td>

                        <td><input type="text" value="1" name="cantPed[]" size="5" id="articulo"
                                onChange="total();verifica()"></td>

                        <td><input name="numsuc[]" value="<?php echo $v['NRO_SUCURSAL'] ;?>" hidden></td>

                    </tr>



                    <?php

		$total = $total+1;
		
        }

        ?>




                </table>



                <input type="submit" value="Enviar Pedidos" class="btn btn-primary btn-sm" style="margin-left:80%">

                </br>

            </form>

            <div>
                Total de los pedidos: <input name="total_todo" size="4" id="total" value="<?php echo $total ?> "
                    type="text">
                </br></br>
            </div>

        </nav>

    </div>


</body>

</html>

<?php
}
?>