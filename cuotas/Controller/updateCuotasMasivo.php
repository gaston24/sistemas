<?php
require '../MODEL/conexion.php';
$conexion=new Conexion();

$inputJSON = file_get_contents('php://input');
$datosLocal=json_decode($inputJSON, TRUE);

$tarjetas_stringParaQuerySQL="";
$cuotas_stringParaQuerySQL="";

$estado=$datosLocal['estado'];

$provincias=array_keys($datosLocal['provinciasSet']);
$tarjetas=array_keys($datosLocal['tarjetasSet']);
$cuotas=array_keys($datosLocal['cuotasSet']);;

/* var_dump($provincias);
var_dump($tarjetas);
var_dump($cuotas);
 */

foreach($tarjetas as $tarjeta)
{
    $tarjetas_stringParaQuerySQL.=$tarjeta.",";
}
//remuevo la ultima coma
$tarjetas_stringParaQuerySQL=rtrim($tarjetas_stringParaQuerySQL,",");
/* echo $tarjetas_stringParaQuerySQL;
 */
if($estado)
{
    foreach($cuotas as $cuota)
    {
        //armo el string para completar la query de insert de tarjetas
        $cuotas_stringParaQuerySQL.='SELECT '.$cuota.' CUOTA UNION ALL ';
    }
    /* $cuotas_stringParaQuerySQL=rtrim($cuotas_stringParaQuerySQL,'UNION ALL '); */
    $cuotas_stringParaQuerySQL = substr($cuotas_stringParaQuerySQL, 0, -10);
}else
{
    foreach($cuotas as $cuota)
    {
        //armo el string para completar la query de insert de tarjetas
        $cuotas_stringParaQuerySQL.=$cuota.',';
    }
    $cuotas_stringParaQuerySQL=rtrim($cuotas_stringParaQuerySQL,',');
}
/* echo $cuotas_stringParaQuerySQL; */



/* $locales=[]; */

/* var_dump($datosLocal);
print_r($datosLocal['provinciasSet']); */
foreach ($provincias as $provincia) {
    echo 'provincia: '.$provincia;
    $conexion->conexionLocal_porProvincia($provincia,$tarjetas_stringParaQuerySQL,$cuotas_stringParaQuerySQL,$estado);
}
/* print_r($datosLocal['tarjetasSet']);
print_r($datosLocal['cuotasSet']); */