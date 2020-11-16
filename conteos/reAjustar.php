<?php 
session_start(); 
if(!isset($_SESSION['username'])){
	header("Location:login.php");
}else{
	
$permiso = $_SESSION['permisos'];
$user = $_SESSION['username'];


$dsn = $_SESSION['sucursal'];
$usuario = "sa";
$clave="Axoft1988";
$cid=odbc_connect($dsn, $usuario, $clave);





$fecha = date("Y") . "/" . date("m") . "/" . date("d");
$hora = (date("H")-5).date("i").date("s");



//LLENAR LA VARIABLE DE PROXIMO NUMERO DE REMITO
$sqlProx = "
SELECT ' '+CAST((SELECT SUCURSAL FROM STA17 WHERE TALONARIO = 850)AS VARCHAR)+RIGHT(('00000'+ CAST((SELECT PROXIMO FROM STA17 WHERE TALONARIO = 850)AS VARCHAR)),8) PROXIMO
";
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


//LLENAR VARIABLE DE PROXIMO NUMERO INTERNO
$sqlProxInterno = "SELECT RIGHT(('00100'+CAST((SELECT MAX(NCOMP_IN_S)+1 NCOMP_IN_S FROM STA20 WHERE TCOMP_IN_S = 'AJ')AS VARCHAR)),8) PROXINTERNO;";
$resultProxInterno = odbc_exec($cid, $sqlProxInterno);
while($v=odbc_fetch_array($resultProxInterno)){
$proxInterno = $v['PROXINTERNO'];
}	

//ENCABEZADO
$sqlEncabezado = "
INSERT INTO STA14 
(
COTIZ, EXPORTADO, EXP_STOCK, FECHA_ANU, FECHA_MOV, HORA, 
LISTA_REM, LOTE, LOTE_ANU, MON_CTE, N_COMP, NCOMP_IN_S, 
NRO_SUCURS, T_COMP, TALONARIO, TCOMP_IN_S, USUARIO, HORA_COMP,
ID_A_RENTA, DOC_ELECTR, IMP_IVA, IMP_OTIMP, IMPORTE_BO, IMPORTE_TO, 
DIFERENCIA, SUC_DESTIN, DCTO_CLIEN, FECHA_INGRESO, HORA_INGRESO, 
USUARIO_INGRESO, TERMINAL_INGRESO, IMPORTE_TOTAL_CON_IMPUESTOS, 
CANTIDAD_KILOS, COD_PRO_CL
)
VALUES
(
4.5, 0, 0, '1800/01/01', '$fecha', '0000', 
0, 0, 0, 1, '$proximo', '$proxInterno', 
0, 'AJU', 850, 'AJ', 'AJUSTES', '$hora', 
0, 0, 0, 0, 0, 0, 
'N', 0, 0, '$fecha', '$hora', 
'AJUSTES', (SELECT host_name()), 0, 
0, 'GTCENT'
)
;";
odbc_exec($cid, $sqlEncabezado);


for($i=0;$i<count($_POST['cantAjuste']);$i++){
	
	$ajustar = $_POST['ajustar'][$i];
	
	
	if($ajustar==1){
		
		$codArticu = $_POST['codArticu'][$i];
		$audita = $_POST['audita'][$i];
		$cantAjuste = $_POST['cantAjuste'][$i];
		$id = $_POST['id'][$i];
		$renglon = $i+1;

		$sqlBusca = "
		SELECT * FROM STA19 WHERE COD_ARTICU = '$codArticu' AND COD_DEPOSI = (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS != 'OU' AND INHABILITA = 0)
		";

		$resultBusca = odbc_exec($cid, $sqlBusca);

		while($v=odbc_fetch_array($resultBusca)){
				$codConsulta = $v['COD_ARTICU'];
				
				
			//echo $codConsulta;
			if(odbc_num_rows($resultBusca)==1){
				
			//SUMA CANTIDAD
				$sqlSuma = "UPDATE STA19 SET CANT_STOCK = (CANT_STOCK + $cantAjuste) WHERE COD_ARTICU = '$codArticu' AND COD_DEPOSI = (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS != 'OU' AND INHABILITA = 0)";
				$resultSuma = odbc_exec($cid, $sqlSuma);

			}else{
				
				$sqlAgrega = "
				INSERT INTO STA19 
				(CANT_COMP, CANT_PEND, CANT_STOCK, COD_ARTICU, COD_DEPOSI, FECHA_ANT, LOTE, SALDO_ANT, EXP_SALDO, CANT_COMP_2, CANT_PEND_2, CANT_STOCK_2, SALDO_ANT_STOCK_2)
				VALUES
				(0, 0, $cantAjuste, '$codArticu', (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS != 'OU' AND INHABILITA = 0), GETDATE(), 0, 0, 0, 0, 0, 0, 0)";
				$resultAgrega = odbc_exec($cid, $sqlAgrega);
				
			}

		}

	
		
		
		if($cantAjuste < 0){
			$cantAjuste = $cantAjuste *-1;
			$tcomp = 'S';
		}else{
			$tcomp = 'E';
		}
		
		//echo $codArticu.' - '.$audita.' - '.$ajustar.' - '.$cantAjuste.'</br>';
		
		//DETALLE SALIDA
		$sqlDetSalida = "
		INSERT INTO STA20
		(
		CAN_EQUI_V, CANT_DEV, CANT_OC, CANT_PEND, CANT_SCRAP, CANTIDAD, COD_ARTICU, COD_DEPOSI, 
		DEPOSI_DDE, EQUIVALENC, FECHA_MOV, N_RENGL_OC, N_RENGL_S, NCOMP_IN_S, PLISTA_REM, PPP_EX, PPP_LO, PRECIO, PRECIO_REM, TCOMP_IN_S, 
		TIPO_MOV, CANT_FACTU, DCTO_FACTU, CANT_DEV_2, CANT_PEND_2, CANTIDAD_2, CANT_FACTU_2, ID_MEDIDA_STOCK, UNIDAD_MEDIDA_SELECCIONADA, 
		PRECIO_REMITO_VENTAS, CANT_OC_2, RENGL_PADR, PROMOCION, PRECIO_ADICIONAL_KIT, TALONARIO_OC
		)
		VALUES
		(
		1, 0, 0, 0, 0, '$cantAjuste', '$codArticu', (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS != 'OU' AND INHABILITA = 0),
		'', 1, '$fecha', 0, $renglon, '$proxInterno', 0, 0, 0, 0, 0, 'AJ', 
		'$tcomp', 0, 0, 0, 0, 0, 0, 6, 'P', 
		0, 0, 0, 0, 0, 0
		);";
		odbc_exec($cid, $sqlDetSalida);
		
		//MARCAR AUDITORIA
		$sqlMarca = "
		UPDATE SOF_INVENTARIO_AUDITA SET AUDITORIA = 'N Comp - $proximo - $audita'
		WHERE ID = '$id'
		;";
		odbc_exec($cid, $sqlMarca);
		
		
	}
	
	
	
}


}

echo "<script>setTimeout(function () {window.location.href= 'index.php';},1);</script>";

?>