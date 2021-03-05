function enviar(){


      

    var table = document.getElementById("tabla");
    var matriz = [];

    for(var i=0;  i<table.rows.length-1 ; i++){
        if(table.rows[i].querySelector('input').value != 0){
            matriz[i] = [];
            // console.log(table.rows[i].querySelector('input').value);
            for(var x=0; x<table.rows[0].cells.length ; x++ ){
                // if(i!=0){
                    if(x==8){
                        var dato =  table.rows[i].cells[x].firstChild.value;    
                    }else if(x==0){
                        var dato = "";    
                    }else if(x==2){
                        var dato = "";
                    }else{
                        var dato =  table.rows[i].cells[x].innerHTML;    
                    }
                    matriz[i][x] = dato;
                // }
            }    
        }    
    }



    suma = document.getElementById('total').value;

    if(suc>100){
        totalPedido = document.getElementById('totalPrecio').value;
        console.log(totalPedido + " " + cupo_credito);
        var diferencia = (parseInt(cupo_credito, 10) - parseInt(totalPedido, 10))*-1;
    }

    if(suma!= 0){

        if(suc > 100 && (parseInt(totalPedido, 10) > parseInt(cupo_credito, 10)) ){
            swal({
                title: "Atencion!",
                text: "El limite de crédito fue excedido en "+ diferencia +" pesos, por favor analice quitar articulos o comuníquese con ines.sica@xl.com.ar para evaluar su situación",
                icon: "warning",
                button: "Aceptar",
              });
        }else{
            $("#aguarde").show();
            $("#pantalla").fadeOut();
            postear(matriz, suc, codClient, t_ped, depo, talon_ped);
            // console.log("pedido enviado");
        }

    }else{
        swal({
            title: "Error!",
            text: "No hay ningun articulo seleccionado!",
            icon: "warning",
            button: "Aceptar",
          });
    }



    

    

}



function enviarEcommerce(){


      

    var table = document.getElementById("tabla");
    var matriz = [];

    for(var i=0;  i<table.rows.length-1 ; i++){
        if(table.rows[i].querySelector('input').value != 0){
            matriz[i] = [];
            // console.log(table.rows[i].querySelector('input').value);
            for(var x=0; x<table.rows[0].cells.length ; x++ ){
                // if(i!=0){
                    if(x==8){
                        var dato =  table.rows[i].cells[x].firstChild.value;    
                    }else if(x==0){
                        var dato = "";    
                    }else if(x==2){
                        var dato = "";
                    }else{
                        var dato =  table.rows[i].cells[x].innerHTML;    
                    }
                    matriz[i][x] = dato;
                // }
            }    
        }    
    }



    suma = document.getElementById('total').value;

    if(suma!= 0){

        postear(matriz, suc, codClient, t_ped, depo, talon_ped);
        // console.log("pedido enviado");


    }else{
        swal({
            title: "Error!",
            text: "No hay ningun articulo seleccionado!",
            icon: "warning",
            button: "Aceptar",
          });
    }


}



function enviarMayorista(){
    var table = document.getElementById("tabla");
    var matriz = [];

    for(var i=0;  i<table.rows.length-1 ; i++){
        if(table.rows[i].querySelector('input').value != 0){
            matriz[i] = [];
            // console.log(table.rows[i].querySelector('input').value);
            for(var x=0; x<table.rows[0].cells.length ; x++ ){
                // if(i!=0){
                    if(x==6){
                        var dato =  table.rows[i].cells[x].firstChild.value;    
                    }else if(x==0){
                        var dato = "";    
                    }else if(x==2){
                        var dato = "";
                    }else{
                        var dato =  table.rows[i].cells[x].innerHTML;    
                    }
                    matriz[i][x] = dato;
                // }
            }    
        }    
    }



    
    suma = document.getElementById('total').value;

    


    if(suma!= 0){
        $("#aguarde").show();
        $("#pantalla").fadeOut();
        postear(matriz, suc, codClient, t_ped, depo, talon_ped);

    }else{
        swal({
            title: "Error!",
            text: "No hay ningun articulo seleccionado!",
            icon: "warning",
            button: "Aceptar",
          });
    }

}








function postear(matriz, suc, codClient, t_ped, depo, talon_ped) {
	
	$.ajax({
		url: 'Controlador/cargarPedidoNuevo.php',
		method: 'POST',
		data: {
            matriz: matriz,
            numsuc: suc,
            codClient: codClient,
            tipo_pedido: t_ped,
            depo: depo,
            talon_ped: talon_ped
        },

		success: function(data) {
			swal({
                title: "Pedido cargado exitosamente!",
                text: "Numero de pedido: "+data,
                icon: "success",
                button: "Aceptar",
              })
              .then(function() {
                window.location = "../index.php";
            })
            ;
            
        } 
 
	});
	
}