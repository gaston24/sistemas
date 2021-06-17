<?php
session_start(); 
if(!isset($_SESSION['username'])){
	
	header("Location:../login.php");
}else{
?>

<!DOCTYPE html>

<style>
    #parrafo {
        margin-top: 1rem
    }

    #parrafo2 {
        margin-top: -0.8rem
    }

    #parrafo3 {
        margin-top: -2rem
    }

    #codigoBarra {
        font-family: 'Libre Barcode 39';
        font-size: 50px;
        padding: 0;
    }

    @media print {
        @page {
            size: landscape;
        }

        * {
            margin: 0;
            padding: 0;
        }
    }
</style>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir Talonario</title>
    <link rel="shortcut icon" href="../assets/css/icono.jpg" />
    <link rel="stylesheet" href="../assets/css/bootstrap/bootstrap.min.css">
    <script src="../assets/css/bootstrap/jquery-3.5.1.slim.min.js"></script>
    <script src="../assets/css/bootstrap/popper.min.js"></script>
    <script src="../assets/css/bootstrap/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <?php include_once __DIR__ . '../fontawesome/css.php'; ?>
    <link href='https://fonts.googleapis.com/css?family=Libre Barcode 39' rel='stylesheet'>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.3/css/all.css" integrity="sha384-SZXxX4whJ79/gErwcOYf+zWLeJdY/qpuqC4cAa9rOGUstPomtqpuNWT9wdPEn2fk" crossorigin="anonymous">
    <script>
        /* document.getElementById('load',window.print()); */
    </script>
    <script>
        /*  window.print();
    window.close(); */
    </script>

</head>



<body>

    <?php

    $hoy = date("d-m-Y");

    ?>
    <div class="row-modal horizontal_print">
        <div class="container col-5 mt-4">
            <div class="row col-9">
                <div class="col border border-secondary rounded-left">
                    <div class="mt-3 mb-2 text-center"><img src="images/LOGO XL 2018.jpg" height="50px" alt=""></div>
                    <p class="small text-center" id="parrafo"><strong>Sucursal</strong></p>
                    <h4 class="text-center">29 - Paseo del Siglo</h4>
                </div>

                <div class="col border border-secondary rounded-right" style="background-color:#e2e3e5;">
                    <div class="mt-2 text-center">
                        <h6>DEVOLUCION POR FALLA</h6>
                    </div></br>
                    <div class="mt-4" id="parrafo2">
                        <p class="small"><strong>Número: </strong>921-00001</p>
                    </div>
                    <div class="mt-0">
                        <p class="small" id="parrafo2"><strong>Fecha: </strong>
                            <?= $hoy ?>
                        </p>
                        <div class="text-center display-4"><strong>F</strong></div>
                    </div>

                </div>
            </div>
            <div class="row col-9">
                <div class="col border border-secondary rounded">
                    <div>
                        <p class="small ml-3 mt-3"><strong>Destinatario: </strong>Casa Central</p>
                    </div>
                    <div>
                        <p class="small ml-3 mt-3"><strong>Localidad: </strong>Victoria</p>
                    </div>
                </div>
            </div>
            <div class="row col-9">
                <div class="col border border-secondary rounded text-center">
                    <div style="background-color:#e2e3e5;">
                        <p class="medium mt-3"><strong>Artículo: </strong></p>
                    </div>
                    <div id="parrafo2">
                        <p class="medium border"><strong><?php if (isset($_GET['codigo'])) {
                                                                echo $_GET['codigo'];
                                                            } ?></strong></p>
                    </div>

                    <div style="background-color:#e2e3e5;">
                        <p class="medium mt-3"><strong>Descripción: </strong></p>
                    </div>
                    <div id="parrafo2">
                        <p class="medium border"><strong><?php if (isset($_GET['descripcion'])) {
                                                                echo $_GET['descripcion'];
                                                            } ?></strong></p>
                    </div>

                    <div class="m-1 row">
                        <div class="border border-dark col text-center" id="codigoBarra"><?php if (isset($_GET['codigo'])) {
                                                                                                echo '*' . $_GET['codigo'] . '*';
                                                                                            } ?></div>
                    </div>
                </div>
            </div>
            <div class="row col-9">
                <div class="col border border-secondary rounded">
                    <div>
                        <p class="medium ml-3 mt-3"><strong>Descripción de falla: </strong></p></br>
                    </div>
                    <div>
                        <p class="medium text-center"><strong><?php if (isset($_GET['descripcion_falla'])) {
                                                                    echo $_GET['descripcion_falla'];
                                                                } ?></strong></p>
                    </div>
                </div>
            </div>

        </div>




        <div class="container col-5 mt-4">
            <div class="row col-9">
                <div class="col border border-secondary rounded-left">
                    <div class="mt-3 mb-2 text-center"><img src="images/LOGO XL 2018.jpg" height="50px" alt=""></div>
                    <p class="small text-center" id="parrafo"><strong>Sucursal</strong></p>
                    <h4 class="text-center"><?php echo $_SESSION['numsuc'].' - '.$_SESSION['descLocal']; ?></h4>
                </div>

                <div class="col border border-secondary rounded-right" style="background-color:#e2e3e5;">
                    <div class="mt-2 text-center">
                        <h6>DEVOLUCION POR FALLA</h6>
                    </div></br>
                    <div class="mt-4" id="parrafo2">
                        <p class="small"><strong>Número: </strong>921-00001</p>
                    </div>
                    <div class="mt-0">
                        <p class="small" id="parrafo2"><strong>Fecha: </strong>
                            <?= $hoy ?>
                        </p>
                        <div class="text-center display-4"><strong>F</strong></div>
                    </div>

                </div>
            </div>
            <div class="row col-9">
                <div class="col border border-secondary rounded">
                    <div>
                        <p class="small ml-3 mt-3"><strong>Destinatario: </strong>Casa Central</p>
                    </div>
                    <div>
                        <p class="small ml-3 mt-3"><strong>Localidad: </strong>Victoria</p>
                    </div>
                </div>
            </div>
            <div class="row col-9">
                <div class="col border border-secondary rounded text-center">
                    <div style="background-color:#e2e3e5;">
                        <p class="medium mt-3"><strong>Artículo: </strong></p>
                    </div>
                    <div id="parrafo2">
                        <p class="medium border"><strong><?php if (isset($_GET['codigo'])) {
                                                                echo $_GET['codigo'];
                                                            } ?></strong></p>
                    </div>

                    <div style="background-color:#e2e3e5;">
                        <p class="medium mt-3"><strong>Descripción: </strong></p>
                    </div>
                    <div id="parrafo2">
                        <p class="medium border"><strong><?php if (isset($_GET['descripcion'])) {
                                                                echo $_GET['descripcion'];
                                                            } ?></strong></p>
                    </div>

                    <div class="m-1 row">
                        <div class="border border-dark col text-center" id="codigoBarra"><?php if (isset($_GET['codigo'])) {
                                                                                                echo '*' . $_GET['codigo'] . '*';
                                                                                            } ?></div>
                    </div>
                </div>
            </div>
            <div class="row col-9">
                <div class="col border border-secondary rounded">
                    <div>
                        <p class="medium ml-3 mt-3"><strong>Descripción de falla: </strong></p></br>
                    </div>
                    <div>
                        <p class="medium text-center"><strong><?php if (isset($_GET['descripcion_falla'])) {
                                                                    echo $_GET['descripcion_falla'];
                                                                } ?></p>
                    </div>
                </div>
            </div>

        </div>


       
    </div>
    <div class="btn-toolbar pt-4 btn_talonario">
        <div class="width-btn"><button type="button" id="btn_imprimir" class="btn btn-success "><i class="fas fa-print"></i><!-- <a href="javascript:openPage()"> --> Imprimir
                <!-- </a> -->
            </button></div>
        <div class="width-btn"><button type="button" id="btn_cerrar" class="btn btn-primary cerrar"><span class="fas fa-window-close"></span> Cerrar</button></div>
    </div>

</body>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
<script>
    /*  window.close();  */
</script>
<script>
    document.getElementById('btn_imprimir').addEventListener('click', ()=>{window.print()});
    document.getElementById('btn_cerrar').addEventListener('click', () => {
        window.close()
    });

/*     function imprimirTalonario() {

        window.print();
    } */
</script>

</HTMl>
<?php

}

?>
