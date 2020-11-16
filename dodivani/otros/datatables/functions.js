function condiciones(a){
    var tbl = document.getElementById("table");
    var x = document.querySelectorAll("#row");
    var b = parseInt(a);

//A -> STOCK 4 -> VENTAS 5
//B -> STOCK 6 -> VENTAS 7
//C -> STOCK 8 -> VENTAS 9

//CENTRAL = 3
//CANT  = 10
//LOCAL = 11


    for(i = 0; i<= b; i++){

        //if(tbl.rows[i].cells[4].innerHTML == 0){
            //tbl.rows[i].cells[10].innerHTML = "14";
            //tbl.rows[i].cells[11].innerHTML = `hola mundo -> ${i} -> ${x.length}`;
            //tbl.rows[i].cells[6].innerHTML = "hola";
            var c = parseInt(tbl.rows[i].cells[3].innerHTML);
            var as = parseInt(tbl.rows[i].cells[4].innerHTML);
            var av = parseInt(tbl.rows[i].cells[5].innerHTML);
            var bs = parseInt(tbl.rows[i].cells[6].innerHTML);
            var bv = parseInt(tbl.rows[i].cells[7].innerHTML);
            var cs = parseInt(tbl.rows[i].cells[8].innerHTML);
            var cv = parseInt(tbl.rows[i].cells[9].innerHTML);

           
            if( ( as < av || cs < cv || bs < bv) && ((av - as) == 1) && ((cv - cs) == 1) && ((bv - bs) == 1) && (c > 0)){
                tbl.rows[i].cells[10].innerHTML = '1';
                tbl.rows[i].cells[11].innerHTML = 'CENTRAL';
            }
            
        //}

    }

}