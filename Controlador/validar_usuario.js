$("#usuarioRegistrado").change(function(){
    var usuario = $("#usuarioRegistrado").val();
    var datos = new FormData();
    datos.append("validarUsuario", usuario);

    //console.log(usuario);

    $.ajax({
        url: "Controlador/ajax.php",
        method:"POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        success: function(respuesta){
            if(respuesta==0 && $("#label").css("display", "none") ){
                //$("#label").append(respuesta);
                $("#label").css("display", "block");
            }
            if(respuesta==1 && $("#label").css("display", "block")){
                $("#label").css("display", "none");
            }

        }
    });
});

