<?php  
$accion = $_GET['accion'];

switch ($accion) {
    case 'filtrar':
        filtrar();
        break;
    
    case 'filtrarDestinos':
        filtrarDestinos();
        break;
    
    case 'liquidar':
        liquidar();
        break;
    
    case 'cambiarDestino':
        cambiarDestino();
        break;
    
    case 'cambiarLiquidacion':
        cambiarLiquidacion();
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


    function filtrarDestinos () {

        require_once "../Class/Destino.php";

        $maestroArticulos = new Destino();

        $rubro = $_POST['rubro'];
        $temporada = $_POST['temporada'];

    
        $todosLosArticulos = $maestroArticulos->traerArticulos($rubro, $temporada);

        echo json_encode($todosLosArticulos);


    }

    function liquidar () {
        
        require_once "../Class/Destino.php";

        $maestroArticulos = new Destino();

        
        $liquidacionSiString = $_POST['liquidacionSiString'];
        $liquidacionNoString = $_POST['liquidacionNoString'];


        $maestroArticulos->liquidar($liquidacionSiString, 'SI');

        $maestroArticulos->liquidar($liquidacionNoString, 'NO');

        echo true;
    }

    function cambiarDestino () {
        
            require_once "../Class/Destino.php";
        
            $maestroArticulos = new Destino();
        
        
            $codArticu = $_POST['codArticu'];
            $destino = $_POST['destino'];
            var_dump($codArticu, $destino);


            $maestroArticulos->cambiarDestino($codArticu, $destino);
        
            echo true;
    }

    function cambiarLiquidacion () {
            
        require_once "../Class/Destino.php";

        $maestroArticulos = new Destino();


        $codArticu = $_POST['codArticu'];
        $liquidacion = $_POST['liquidacion'];

        $maestroArticulos->cambiarLiquidacion($codArticu, $destino);

        echo true;

    }
?>