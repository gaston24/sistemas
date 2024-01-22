<?php  
$accion = $_GET['accion'];

switch ($accion) {
    case 'filtrar':
        filtrar();
        break;
    
    default:
        # code...
        break;
}


function filtrar () {
    require_once "../Class/Articulo.php";

    $maestroArticulos = new Articulo();

    $rubro = $_POST['rubro'];
    $temporada = $_POST['temporada'];

    $todosLosArticulos = $maestroArticulos->traerArticulos($rubro, $temporada);

    echo json_encode($todosLosArticulos);


}

?>