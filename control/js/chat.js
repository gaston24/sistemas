
function getChat(remito){

    $.ajax({
        url: 'controlador/chatControlador.php',
        method: 'POST',
        dateType: 'json',
        data: {
            ncomp : remito
        },
        success: function(data) {
            console.log(typeof(data));
            clearChat();
            updateChat(data);
        }
    });
        
}

function clearChat(){
    console.log("borrando chat");
    $('#chatShow').html("");
}

function updateChat(data){
    console.log("actualizando chat");

    var_add_chat = '';
    data.forEach(element => {
        console.log(element);
            var remito = element.remito;
            var user = element.user;
            var message = element.message;
            var datetime = element.datetime;

            if(user == 'RAMIRO'){
                var_add_chat += '<div class="row mr-2">';
                    var_add_chat += '<div class="col-2" ></div>';

                    var_add_chat += '<div class="col-10 alert alert-primary" >';
                        var_add_chat += '<div class="row-fluid" ><p class="text-right">'+message+'</p></div>';
                        var_add_chat += '<div class="row-fluid font-italic" ><p class="text-right"><small>'+datetime+'</small></p></div>';
                    var_add_chat += '</div>';
                var_add_chat += '</div>';
                
            }else{
                var_add_chat += '<div class="row ml-2">';
                    var_add_chat += '<div class="col-10 alert alert-secondary" >';
                    var_add_chat += '<div class="row-fluid" ><p class="text-left">'+message+'</p></div>';
                    var_add_chat += '<div class="row-fluid font-italic" ><p class="text-left"><small>'+datetime+'</small></p></div>';
                    var_add_chat += '</div>';

                    var_add_chat += '<div class="col-2" ></div>';
                var_add_chat += '</div>';
            }
    });
    
    $('#chatShow').append(var_add_chat);
}

function sendChat(){

    var msg = $('chatNew').val();

    $.ajax({
        url: 'controlador/sendChatControlador.php',
        method: 'POST',
        dateType: 'json',
        data: {
            ncomp : remito
        },
        success: function(data) {
            console.log(typeof(data));
            clearChat();
            updateChat(data);
        }
    });

}

