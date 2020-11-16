<?php

$fecha = date("Y") . "/" . date("m") . "/" . date("d");
$hora = (date("H")-5).date("i").date("s");



//LLENAR LA VARIABLE DE PROXIMO NUMERO DE REMITO
$sqlProx = "SELECT ' '+CAST((SELECT SUCURSAL FROM STA17 WHERE TALONARIO = 850)AS VARCHAR)+RIGHT(('00000'+ CAST((SELECT PROXIMO FROM STA17 WHERE TALONARIO = 850)AS VARCHAR)),8) PROXIMO";
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
1, 0, 0, 0, 0, '$cant', '$nuevo', (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS != 'OU' AND INHABILITA = 0),'', 1, 
'$fecha', 0, 2, '$proxInterno', 0, 0, 0, 0, 0, 'AJ', 'E', 0, 0, 0, 0, 0, 0, 6, 'P', 0, 0, 0, 0, 0, 0
);";
$resultDetEntrada = odbc_exec($cid, $sqlDetEntrada);


$sqlBusca = "
SELECT * FROM STA19 WHERE COD_ARTICU = '$nuevo' AND COD_DEPOSI = (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS != 'OU' AND INHABILITA = 0)
";

$resultBusca = odbc_exec($cid, $sqlArt);

while($v=odbc_fetch_array($resultBusca)){
			$codConsulta = $v['COD_ARTICU'];
			
			
	//echo $codConsulta;
	if(odbc_num_rows($resultBusca)==1){
		
	//SUMA CANTIDAD
		$sqlSuma = "UPDATE STA19 SET CANT_STOCK = (CANT_STOCK + $cant) WHERE COD_ARTICU = '$nuevo' AND COD_DEPOSI = CAST((SELECT NRO_SUCURSAL FROM SUCURSAL WHERE ID_SUCURSAL IN (SELECT ID_SUCURSAL FROM EMPRESA))AS VARCHAR)";
		$resultSuma = odbc_exec($cid, $sqlSuma);

	}else{
		
		$sqlAgrega = "
		INSERT INTO STA19 
		(CANT_COMP, CANT_PEND, CANT_STOCK, COD_ARTICU, COD_DEPOSI, FECHA_ANT, LOTE, SALDO_ANT, EXP_SALDO, CANT_COMP_2, CANT_PEND_2, CANT_STOCK_2, SALDO_ANT_STOCK_2)
		VALUES
		(0, 0, $cant, '$nuevo', (SELECT COD_SUCURS FROM STA22 WHERE COD_SUCURS != 'OU' AND INHABILITA = 0), GETDATE(), 0, 0, 0, 0, 0, 0, 0)";
		$resultAgrega = odbc_exec($cid, $sqlAgrega);
		
	}
	
}


?>
