$(document).ready(function(){
    if($("#rotulo").length){

        var div = document.getElementById("form");
        div.style.visibility = 'hidden';
        window.print();
        setTimeout(
            function(){
                div.style.visibility = 'visible';
            } 
            , 15000);

    }
});