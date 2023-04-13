<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valores A Rendir</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" type="text/css" href="select2/select2.min.css">

    <script src="select2/select2.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    </link>

</head>

<body>

    <div class="alert alert-secondary">
        <div class="page-wrapper bg-secondary p-b-100 pt-2 font-robo">
            <div class="wrapper wrapper--w680"><div style="color:white; text-align:center"><h5>Valores A Rendir</h5></div>
                <div class="card card-1">
                    
                    <div class="row" style="margin-left:50px">

                        <h2><i class="bi bi-cash" style="margin-right:20px;font-size:50px"></i>Valores A Rendir</h2>

                    </div>

                    <div class="row" style="margin-left:50px;margin-top">
                        <div class="col-3">    <h4>Total efectivo : <input type="text" style="width:150px; height:45px"></h4></div>

                        <div class="col">    <h4>Total Cheques : <input type="text" style="width:150px; height:45px" id="efectivo"></h4></div>
                      
                        <div class="col">    <h4><button class="btn btn-success btn_exportar" id="btnExport" style=" height:45px"><i class="fa fa-file-excel-o"></i> Rendir<i class="bi bi-file-earmark-excel"></i></button></h4></div>
                    </div>

                    <table class="table table-striped table-bordered" id="myTable" style="width: 99%;" cellspacing="0" data-page-length="100">
                        <thead class="thead-dark">
                            <tr>
                                <th style="position: sticky; top: 0; z-index: 10; width: 200px;" class="col-1">CLIENTE</th>
                                <th style="position: sticky; top: 0; z-index: 10;">EFECTIVO</th>
                                <th style="position: sticky; top: 0; z-index: 10;">CHEQUES</th>
                                <th style="position: sticky; top: 0; z-index: 10;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            
                      
                        </tbody>
                
            </div>
        </div>



        </table>

    <script src="js/functions.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>

</html>
<script>

        $(document).ready(function() {
            $('#myTable').DataTable({
                responsive: true,
                buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
            });
            
        });
</script>
