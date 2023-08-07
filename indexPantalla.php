<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga de Cobranza</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
                        <!------------------------------------------------------------>

    <!-- Icons font CSS-->
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <!-- Bootstrap Icons -->

    <!-- Vendor CSS-->
    <!-- <link href="assets/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="assets/datepicker/daterangepicker.css" rel="stylesheet" media="all"> -->

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Main CSS-->
    


    </link>

</head>
<body>
<div class="alert alert-secondary">
        <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
            <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h5>Selecionar</h5></div>
                <div class="card card-1">
                    
                    <div class="row" style="margin-left:50px;">
                        <h2><i class="bi bi-folder2" style="margin-right:20px;font-size:50px"></i>Elija la aplicacion</h2>

                    </div>    

                    <div class="row" style="text-align:center">
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                    </div>
                    <div class="row" style="text-align:center;margin-top:20px">
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                    </div>
                    <div class="row" style="text-align:center;margin-top:20px" >
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-primary" style="width:200px;height:100px"></button></div>
                        <div class="col"><button class="btn btn-secondary" style="width:200px;height:100px" id="btnEquis">599 <i class="bi bi-incognito"></i></button></div>
                    </div>

                </div>    
            </div>
        </div>
</div>    





</body>

<script>

const btnEquis = document.querySelector("#btnEquis");
btnEquis.addEventListener("click", () => {
    window.location.href = "599/composicionDeRemitos.php";


}); 

</script>