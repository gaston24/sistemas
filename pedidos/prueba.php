<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title> Mejorar la página del carrito de compras de Dangdang </title>
    
    <style type="text/css">
    

    	body,ul,li,div,p,h1,h2,ol{margin: 0;padding: 0;}
ul,li,ol{list-style: none;}
 .content {width: 810px; margin: 0 auto; font-family: "Microsoft Yahei";}
.logo{margin: 10px 0;}
.logo span{
    display: inline-block;
    width: 60px;
    height: 30px;
    line-height: 30px;
    font-size: 14px;
    background: #ff0000;
    color: #ffffff;
    text-align: center;
    border-radius: 10px;
    margin-top: 5px;
    margin-right: 10px;
    cursor: pointer;
    font-weight: bold;
}
.cartList{
    /*background: url("../image/02.jpg") no-repeat;*/
    /*height: 414px;*/
    overflow: hidden;
}
.cartList ul{
    display: flex;
    justify-content: space-between;
    /*float: right;*/
    /*width: 450px;*/
}
.cartList ul:nth-of-type(1){
    display: flex;
    margin-top: 125px;
}
.cartList ul:nth-of-type(2){
    margin: 20px 0;
}
.cartList ul li{
    font-family: "Microsoft Yahei";
    font-size: 12px;
    color: #666666;
    text-align: center;
    line-height: 25px;
    /*float: left;*/
}
.cartList ul li input[name="price"]{
    border: none;
    background: transparent;
    width: 45px;
    text-align: center;
}
.cartList ul li input[name="amount"]{
    width: 45px;
    text-align: center;
    border: 1px solid #999999;
    border-left: none;
    border-right: none;
    height: 21px;
}
.cartList ul li input[name="minus"],.cartList ul li input[name="plus"]{
    height: 25px;
    border: 1px #999999 solid;
    width: 25px;
    text-align: center;
}
.cartList ul li:nth-of-type(1){width: 130px;}
.cartList ul li:nth-of-type(2){width: 100px;}
.cartList ul li:nth-of-type(3){width: 130px;}
.cartList ul li p{cursor: pointer;}
.cartList ol{
    float: right;
    clear: both;
    margin-top: 40px;
}
.cartList ol li{
    float: left;
}
.cartList ol li:nth-of-type(1){
    color: #ff0000;
    width: 80px;
    height: 35px;
    line-height: 35px;
    text-align: center;
}
.cartList ol li span{display: inline-block;
    width: 80px;
    height: 35px;
    line-height: 35px;
    font-size: 14px;
    font-family: "Microsoft Yahei";
    background: #ff0000;
    color: #ffffff;
    text-align: center;
    /*margin-top: 5px;*/
    /*margin-right: 15px;*/
    cursor: pointer;
    font-weight: bold;}
 
    </style>
</head>
 
 <! - onload, calcula la cantidad original al cargar ->
<body onload="total()">
 
<div class="content">
    <div class="logo">
                 <span onclick = "javascript: if (confirm ('¿Está seguro de que desea cerrar?')) window.close ()"> Cerrar </span>
    </div>
    <div class="cartList">
        <ul>
                         <li> Información del producto </li>
                         <li> Imagen del producto </li>
                         <li> Precio unitario (yuanes) </li>
                         <li> Cantidad </li>
                         <li> Cantidad (yuanes) </li>
                         <li> Operación </li>
        </ul>
        <ul style="display: flex;justify-content: space-between;align-items: center" id="first">
                         <li> "El mundo ordinario" </li>
            <li><img src="./img/1.png" alt="" width="50" height="50"></li>
            <li>¥<input type="text" name="price" value="21.90"></li>
            <li><input type="button" name="minus" value="-" onclick="minus(0)"><input type="text" name="amount" value="1"><input type="button" name="plus" value="+" onclick="plus(0)" ></li>
            <li id="price0">$21.90</li>
                         <li> <p onclick = "save ()"> Mover a favoritos </p> <p onclick = "delete1 ()"> Eliminar </p> </li>
        </ul>
        <ul style="display: flex;justify-content: space-between;align-items: center; margin: 20px 0;">
                         <li> "Insectos" </li>
            <li><img src="./img/2.png" alt="" width="50" height="50"></li>
            <li>$<input type="text" name="price" value="24.00"></li>
            <li><input type="button" name="minus" value="-" onclick="minus(1)"><input type="text" name="amount" value="1"><input type="button" name="plus" value="+" onclick="plus(1)"></li>
            <li id="price1">$24.00</li>
                         <li> <p onclick = "save ()"> Mover a favoritos </p> <p onclick = "delete1 ()"> Eliminar </p> </li>
        </ul>
        <ol>
            <li id="totalPrice">&nbsp;</li>
                         <li><span>Checkout</span> </li>
        </ol>
    </div>
</div>
</body>
</html>
 
<script>
         //Sustracción
    function minus(index) {
                 // Obtiene el valor de la cantidad actual
        var amounts=document.getElementsByName("amount");
 
                 // Obtiene el valor del atributo value del elemento de la primera cantidad
                 var count = parseInt (amounts [índice] .valor); // Monto más 1
 
        if (count<=1){
                         alert ("¡No se puede reducir más, casi se ha ido!");
        } else {
                         // Obtiene el valor del atributo value del elemento de la primera cantidad
                         var count = parseInt (amounts [índice] .valor) -1; // Monto más 1
 
                         // Vuelva a vincular el valor de count en el cuadro de texto de cantidad
            amounts[index].value=count;
            var prices=document.getElementsByName("price");
            var price=parseFloat(prices[index].value);
                         // La razón de multiplicar por Math.pow (10,2) es evitar la distorsión
            var totalMoney=((price*Math.pow(10,2))*count)/Math.pow(10,2);
 
            document.getElementById("price"+index).innerHTML="¥："+totalMoney;
        }
 
        total();
 
    }
 
         //Adición
    function plus(index) {
 
                 // Obtiene el valor de la cantidad actual
        var amounts=document.getElementsByName("amount");
 
                 // Obtiene el valor del atributo value del elemento de la primera cantidad
                 var count = parseInt (amounts [index] .valor) +1; // Monto más 1
 
                 // Vuelva a vincular el valor de count en el cuadro de texto de cantidad
        amounts[index].value=count;
 
                 // El precio del puerto operativo actual también se volverá a calcular
                 // Obtener el precio unitario del puerto actual
        var prices=document.getElementsByName("price");
        var price=parseFloat(prices[index].value);
                 // La razón de multiplicar por Math.pow (10,2) es evitar la distorsión
        var totalMoney=((price*Math.pow(10,2))*count)/Math.pow(10,2);
 
                 // Muestra el precio actual en el texto
        document.getElementById("price"+index).innerHTML="¥："+totalMoney;
 
        total();
    }
 
 
         // Encuentra la cantidad total
 
    function total() {
 
                 // Obtener todas las cantidades
        var counts=document.getElementsByName("amount");
 
                 // Obtener todos los precios unitarios
        var prices=document.getElementsByName("price");
 
        var sumMoney=0;
 
        for (var i=0;i<counts.length;i++){
 
                         // La razón de multiplicar por Math.pow (10,2) es evitar la distorsión
            sumMoney+=(parseFloat(prices[i].value)*Math.pow(10,2)*parseInt(counts[i].value)/Math.pow(10,2));
        }
 
                 // Muestra la cantidad total en el elemento especificado
        document.getElementById("totalPrice").innerHTML="¥："+sumMoney;
 
    }
 
 
         //Agregar a los favoritos
    function save() {
                 if (confirm ("¿Está seguro de que desea guardar?")) {
                         alert ("¡Colección exitosa!");
        }
 
    }
         //Borrar
    function delete1() {
                 if (confirm ("¿Está seguro de que desea eliminar?")) {
            var del=document.getElementById("first");
            del.parentNode.removeChild(del);
                         alert ("¡¡Eliminación exitosa !!");
        }
    }
</script>