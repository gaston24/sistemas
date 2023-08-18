<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css"> -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css" class="rel">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.dataTables.min.css" class="rel">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        
           @media (max-width: 425px) {

            .card-1 {
                width: 78%;
                height: 30%;
                margin-top:55%
            }
            .card-1 h1 {
                margin-top:10%;
                font-size:150%
            }
            .card-1 div {
                margin-top:5%;
                margin-left:8%;
                font-size:70%
            }

         
        }
        #danger {
            background-color: #dc3545;
        }
        .gris {
            background-color: #f8f9fa;
        }
        .card.card-1 {
            background-color: white;
        }

    </style>
    
</head>
<body>
    <!-- <div style="background-color:#E43D3D;height: 300px;"></div>

    <div style="background-color:yellow">asasd</div> -->
    
    <div class="gris" style="height:57rem;padding-left: 0px;padding-right: 0px;font-size:20px"><strong>Asunto : $desc_sucursal - Cambio de estado en Solicitud N ° $numSolicitud </strong>
   
            <div class="page-wrapper p-b-100 pt-2 font-robo" style="height: 25rem;width:100%;display: flex; justify-content: center; align-items: center;" id="danger">
                <div class="wrapper wrapper--w880" style="margin-left:30%">
                    <div class="card card-1" style="width: 50rem;height: 20rem;margin-top:16rem">
                       <div style="text-align:center"><h1 style="margin-top:2rem;font-size:60px">XL Fallas</h1></div>
                       <div style="margin-top:2rem;margin-left:2rem;font-size:22px">Hola,</div>
                       <div style="margin-top:2rem;margin-left:2rem;font-size:22px">Le informamos que la solicitud con el numero N ° $numSolicitud ha sido <br> autorizada por $nombreSup.</div>
                    </div>
                    <div style="text-align: center; font-size:14px">Este correo es informativo, favor no responder a esta direccion de correo, ya que no se <br> encuentra habilitada para recibir mensajes</div>
                    <div style="text-align: center; font-size:15px;margin-top:1rem">Extra Large, ARGENTINA</div>
            
                </div>
            </div>
    </div>

    
</body>
</html>