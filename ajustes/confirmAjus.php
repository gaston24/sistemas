<?php
session_start();
//include 'conex.php';
include 'consultas.php';

if(!isset($_SESSION['username'])){
	header("Location:index.php");
}else{
	
	
?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Confirma</title>
		<script src="sweetalert2.min.js"></script>
		<link rel="stylesheet" href="sweetalert2.min.css">
</head>
<body>

<?php

// var_dump($_POST);
// die();


//include 'conex.php';
include 'consultas.php';
$dsn = $_SESSION['dsn'];
$user = 'sa';
$pass = 'Axoft1988';

$cid = odbc_connect($dsn, $user, $pass);

$fecha = date("Y") . "/" . date("m") . "/" . date("d");
$hora = (date("H")-5).date("i").date("s");

// var_dump($_POST);
// die();

odbc_exec($cid, $sqlNuevos);

for($i=0;$i<count($_POST['ncomp']);$i++){
	
	if($_POST['articulo'][$i] != ''){
	
		//echo 'Actualiza '.$_POST['codigo'][$i].' a '.$_POST['nuevo'][$i].' del comprobante '.$_POST['ncomp'][$i].'</br>';
		$nuevo = $_POST['articulo'][$i];
		$codigo = $_POST['codigo'][$i];
		$cant = $_POST['cant'][$i];
		$ncomp = $_POST['ncomp'][$i];
		
		if($_POST['tcomp'][$i] == ''){
			$tcomp = 'TRANSFERENCIA';
		}else{
			$tcomp = $_POST['tcomp'][$i];
		}
		
		$sqlArt = "
		SELECT * FROM STA11 WHERE COD_ARTICU = '$nuevo'
		";

		$resultArt = odbc_exec($cid, $sqlArt);
		
		while($v=odbc_fetch_array($resultArt)){
					$codConsulta = $v['COD_ARTICU'];


					//var_dump('$codConsulta', $codConsulta);

					
					
			//echo $codConsulta;
			if(odbc_num_rows($resultArt)==1){
				//echo $codConsulta;
			
				if($codConsulta != '***DESTRUCCION'){


					//var_dump('if');
				
					//ACTUALIZAR CODIGO NUEVO
					$sqlUpdate = "UPDATE SOF_CONFIRMA SET COD_NUEVO = '$nuevo' WHERE COD_ARTICU = '$codigo' AND N_COMP = '$ncomp';";
					//var_dump('$sqlUpdate', $sqlUpdate);

					odbc_exec($cid, $sqlUpdate);
					
					
					
					//LLENAR LA VARIABLE DE PROXIMO NUMERO DE REMITO
					$sqlProx = "SELECT ' 0'+CAST((SELECT SUCURSAL FROM STA17 WHERE TALONARIO = 850)AS VARCHAR)+RIGHT(('00000'+ CAST((SELECT PROXIMO FROM STA17 WHERE TALONARIO = 850)AS VARCHAR)),8) PROXIMO";
					$resultProx = odbc_exec($cid, $sqlProx);
					while($v=odbc_fetch_array($resultProx)){
					$proximo = $v['PROXIMO'];
					}
					
					//UPDATEAR EL PROXIMO NUMERO DE REMITO EN EL TALONARIO
					$sqlUpdateProx = "UPDATE STA17 SET PROXIMO = PROXIMO+1 WHERE TALONARIO = 850;";
					$resultUpdateProx = odbc_exec($cid, $sqlUpdateProx);


					//LLENAR VARIABLE DE PROXIMO NUMERO INTERNO
					$sqlProxInterno = "SELECT RIGHT(('00100'+CAST((SELECT MAX(NCOMP_IN_S)+1 NCOMP_IN_S FROM STA14 WHERE TALONARIO = 850)AS VARCHAR)),8) PROXINTERNO;";
					$resultProxInterno = odbc_exec($cid, $sqlProxInterno);
					while($v=odbc_fetch_array($resultProxInterno)){
					$proxInterno = $v['PROXINTERNO'];
					}
						
					
					//ENCABEZADO
					$sqlEncabezado = "
					INSERT INTO STA14 
					(
					COD_PRO_CL, COTIZ, EXPORTADO, EXP_STOCK, FECHA_ANU, FECHA_MOV, HORA, 
					LISTA_REM, LOTE, LOTE_ANU, MON_CTE, N_COMP, NCOMP_IN_S, 
					NRO_SUCURS, T_COMP, TALONARIO, TCOMP_IN_S, USUARIO, HORA_COMP,
					ID_A_RENTA, DOC_ELECTR, IMP_IVA, IMP_OTIMP, IMPORTE_BO, IMPORTE_TO, 
					DIFERENCIA, SUC_DESTIN, DCTO_CLIEN, FECHA_INGRESO, HORA_INGRESO, 
					USUARIO_INGRESO, TERMINAL_INGRESO, IMPORTE_TOTAL_CON_IMPUESTOS, 
					CANTIDAD_KILOS
					)
					VALUES
					(
					'GTCENT', 4.5, 0, 0, '1800/01/01', '$fecha', '0000', 0, 0, 0, 1, '$proximo', '$proxInterno', 0, 'AJU', 850, 'AJ', 'AJUSTES', 
					'$hora', 0, 0, 0, 0, 0, 0, 'N', 0, 0, '$fecha', '$hora', 'AJUSTES', (SELECT host_name()), 0, 0
					)
					;";
					//var_dump('$sqlEncabezado', $sqlEncabezado);

					$resultEncabezado = odbc_exec($cid, $sqlEncabezado);
					
					
					
					//DETALLE SALIDA
					$sqlDetSalida = "
					INSERT INTO STA20
					(
					CAN_EQUI_V, CANT_DEV, CANT_OC, CANT_PEND, CANT_SCRAP, CANTIDAD, COD_ARTICU, COD_DEPOSI, DEPOSI_DDE, EQUIVALENC, 
					FECHA_MOV, N_RENGL_OC, N_RENGL_S, NCOMP_IN_S, PLISTA_REM, PPP_EX, PPP_LO, PRECIO, PRECIO_REM, TCOMP_IN_S, TIPO_MOV,
					CANT_FACTU, DCTO_FACTU, CANT_DEV_2, CANT_PEND_2, CANTIDAD_2, CANT_FACTU_2, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, 
					PRECIO_REMITO_VENTAS, CANT_OC_2, RENGL_PADR, PROMOCION, PRECIO_ADICIONAL_KIT, TALONARIO_OC
					)
					VALUES
					(
					1, 0, 0, 0, 0, '$cant', '$codigo', 'OU','', 1, '$fecha', 0, 1, '$proxInterno', 0, 0, 0, 0, 0, 'AJ', 
					'S', 0, 0, 0, 0, 0, 0, 6, 'P', 0, 0, 0, 0, 0, 0
					);";
					// var_dump('$sqlDetSalida', $sqlDetSalida);
					$resultDetSalida = odbc_exec($cid, $sqlDetSalida);
						
					//RESTA CANTIDAD
					$sqlResta = "UPDATE STA19 SET CANT_STOCK = (CANT_STOCK - $cant) WHERE COD_ARTICU = '$codigo' AND COD_DEPOSI = 'OU'";
					$resultResta = odbc_exec($cid, $sqlResta);
					
					
					//DETALLE ENTRADA
					$sqlDetEntrada = "
					INSERT INTO STA20
					(
					CAN_EQUI_V, CANT_DEV, CANT_OC, CANT_PEND, CANT_SCRAP, CANTIDAD, COD_ARTICU, COD_DEPOSI, DEPOSI_DDE, EQUIVALENC, 
					FECHA_MOV, N_RENGL_OC, N_RENGL_S, NCOMP_IN_S, PLISTA_REM, PPP_EX, PPP_LO, PRECIO, PRECIO_REM, TCOMP_IN_S, TIPO_MOV,
					CANT_FACTU, DCTO_FACTU, CANT_DEV_2, CANT_PEND_2, CANTIDAD_2, CANT_FACTU_2, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, 
					PRECIO_REMITO_VENTAS, CANT_OC_2, RENGL_PADR, PROMOCION, PRECIO_ADICIONAL_KIT, TALONARIO_OC
					)
					VALUES
					(
					1, 0, 0, 0, 0, '$cant', '$nuevo', (SELECT COD_SUCURS FROM STA22 WHERE INHABILITA = 0 AND DIR_SUCURS != '' AND COD_SUCURS NOT IN ('OU','RV')),'', 1, 
					'$fecha', 0, 2, '$proxInterno', 0, 0, 0, 0, 0, 'AJ', 'E', 0, 0, 0, 0, 0, 0, 6, 'P', 0, 0, 0, 0, 0, 0
					);";
					//var_dump('$sqlDetEntrada', $sqlDetEntrada);
					$resultDetEntrada = odbc_exec($cid, $sqlDetEntrada);
					
					
					//SUMA STOCK SI EXISTE EL ARTICULO O AGREGA EL REGISTRO		
					$sqlConsulta19 = "EXEC SP_SJ_ARTICULO_OUTLET '$nuevo', $cant";
					odbc_exec($cid, $sqlConsulta19);
					
				}
				
				//ACTUALIZA REGISTROS PENDIENTES
				$sqlActuaPend = "UPDATE SOF_CONFIRMA SET N_ORDEN_CO = '1' WHERE N_ORDEN_CO = '' AND N_COMP = '$ncomp' AND COD_ARTICU = '$codigo'";
				$resultActuaPend = odbc_exec($cid, $sqlActuaPend);
			
			}
		}
	
	}	

}

echo "<script> swal.fire({
			icon: 'success',
            title: 'Ajuste realizado exitosamente!',
            showConfirmButton: true,
          })
            .then(function () {
                window.location = 'ajusteLocal.php';
            });
		  	</script>";

			  
header('Location: ajusteLocal.php');

}

?>
   
</body>
</html>