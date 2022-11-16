<?php
session_start();
if (!isset($_SESSION['username'])) {
	header("Location:login.php");
} else {

	$vendedor = $_SESSION['vendedor'];

?>
	<!doctype html>

	<head>
		<title>Carga Inicial</title>
		<?php include '../../css/header.php'; ?>


	</head>

	<body>

		</br>
		<div class="container-fluid">

			<?php

			$dsn = "1 - CENTRAL";
			$user = "sa";
			$pass = "Axoft1988";

			$cid = odbc_connect($dsn, $user, $pass);

			if (!$cid) {
				echo "</br>Imposible conectarse a la base de datos!</br>";
			}

			$sql = "


SELECT N_IMPUESTO, A.COD_CLIENT, NOM_COM, B.DSN
FROM GVA14 A
INNER JOIN SOF_USUARIOS B
ON CAST(A.N_IMPUESTO AS INT) = B.NRO_SUCURS
WHERE A.COD_CLIENT IN
(
'FRBAUD', 
'FRORCE',
'FRORIG',
'FRORNC',
'FRORSJ',
'FRPASJ'
)


";

			$result = odbc_exec($cid, $sql) or die(exit("Error en odbc_exec"));

			?>


			<h2 align="center">Seleccionar Sucursales</h2></br>

			<nav style="margin-left:20%; margin-right:20%">

				<form action="cargaPedidoCordoba.php" method="post">
					<table class="table table-striped" id="id_tabla">

						<tr>

							<td><strong>NUM SUC</strong></td>

							<td></td>

							<td><strong>CODIGO</strong></td>

							<td></td>

							<td><strong>NOMBRE</strong></td>

							<td><strong>SELEC</strong></td>

						</tr>


						<?php

						$total = 0;

						while ($v = odbc_fetch_array($result)) {

						?>


							<tr>
								<!--style="font-size:smaller">-->

								<td><?php echo $v['N_IMPUESTO']; ?></td>

								<td><input type="text" name="suc[]" value="<?php echo $v['N_IMPUESTO']; ?>" hidden></td>

								<td><?php echo $v['COD_CLIENT']; ?></td>

								<td><input type="text" name="dsn[]" value="<?php echo $v['DSN']; ?>" hidden></td>

								<td><?php echo $v['NOM_COM']; ?></td>

								<td>
									<select type="option" name="selec[]" class="form-control form-control-sm col-md-6">
										<option value="si">SI</option>
										<option value="no">NO</option>
									</select>
								</td>

								<!--<td><input type="checkbox" name="selec[]" checked></td>-->

							</tr>



						<?php

							$total = $total + 1;
						}

						?>




					</table>

					<input type="submit" value="Pedidos" class="btn btn-primary btn-sm">

				</form>





			</nav>

		</div>
		<script>
			//*******TRAER CANTIDAD DE PEDIDOS DE LOS LOCALES DE S.LOPEZ */
			window.addEventListener('DOMContentLoaded', traerCantidadPedidos);
			let estado;

			function traerCantidadPedidos() {

				conexion1 = new XMLHttpRequest();
				conexion1.onreadystatechange = () => {
					if (conexion1.readyState == 4 && conexion1.status == 200) {
						estado = JSON.parse(conexion1.responseText);
						console.log(estado);
						localStorage.setItem("infoCordoba", JSON.stringify(estado));
					}
				};
				conexion1.open("GET", "pedidos/limitePedidosCordoba.php", true);
				conexion1.send();
			}
		</script>

	</body>

	</html>

<?php
}
?>