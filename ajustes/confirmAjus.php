<?php
session_start();

if(!isset($_SESSION['username'])){
	header("Location:index.php");
}else{

require_once '../class/conexion.php';
require_once '../class/extralarge.php';
require_once 'class/Ajuste.php';

$xl = new Extralarge();
$cid = new Conexion();
$ajuste = new Ajuste();

$fecha = date("Y") . "/" . date("m") . "/" . date("d");
$hora = (date("H")-5).date("i").date("s");

$response = [];

$ajuste->ejecutarSqlNuevos();

$data = json_decode($_POST['data']);


foreach ($data as $key => $value) {


	$tieneFilas = false;
	
	if($value->articulo != ''){
		
		$nuevo = $value->articulo;
		$codigo = $value->codigo;
		$cant = $value->cant;
		$ncomp = $value->ncomp;
		
		if($value->tcomp == ''){
			$tcomp = 'TRANSFERENCIA';
		}else{
			$tcomp = $value->tcomp;
		}

		//BUSCAR ARTICULO NUEVO
		$articuloNuevo = $ajuste->traerArticulo($nuevo); 
		
		if(count($articuloNuevo) > 0){
			$tieneFilas = true;
		

			foreach ($articuloNuevo as $key => $v) {
				
				$codConsulta = $v['COD_ARTICU'];
				
				
				if($codConsulta != '***DESTRUCCION'){
					
					//ACTUALIZAR CODIGO NUEVO
					$ajuste->actualizarCodigoNuevo($nuevo, $codigo, $ncomp);
					
					
					//LLENAR LA VARIABLE DE PROXIMO NUMERO DE REMITO
					$proximo = $ajuste->setearProximoRemito();
					
					//UPDATEAR EL PROXIMO NUMERO DE REMITO EN EL TALONARIO
					$ajuste->updateRemitoEnTalonario();


					//LLENAR VARIABLE DE PROXIMO NUMERO INTERNO
					$proxInterno = $ajuste->traerProximoInterno();
						
					
					//INSERTA ENCABEZADO
					$ajuste->insertarEncabezado($fecha, $proximo, $proxInterno, $hora);
					
					
					//INSERTA DETALLE SALIDA
					$ajuste->insertarDetalleSalida($cant, $codigo, $fecha, $proxInterno);
						
					//RESTA CANTIDAD
					$ajuste->restarCantidad($cant, $codigo);
					
					
					//INSERTA DETALLE ENTRADA
					$ajuste->insertarDetalleEntrada($cant, $nuevo, $fecha, $proxInterno);
					
					
					//SUMA STOCK SI EXISTE EL ARTICULO O AGREGA EL REGISTRO		
					$ajuste->sumarStock($nuevo, $cant);

					
				}
				
				//ACTUALIZA REGISTROS PENDIENTES
				$ajuste->actualizarRegistrosPendientes($ncomp, $codigo);

			}
		}

		if(!$tieneFilas){

			$response[$value->articulo] = 0;

		}else{
			
			$response[$value->articulo] = 1;
		}
		

	}	

}

echo json_encode($response);
// echo "<script> swal.fire({
// 			icon: 'success',
//             title: 'Ajuste realizado exitosamente!',
//             showConfirmButton: true,
//           })
//             .then(function () {
//                 window.location = 'ajusteLocal.php';
//             });
// 		  	</script>";

			  
// header('Location: ajusteLocal.php');

}

?>
