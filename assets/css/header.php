<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> -->
<link rel="shortcut icon" href="../../../css/icono.jpg" />


<script defer src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">



<?php
function strright($rightstring, $length) {
	return(substr($rightstring, -$length));
}

$dia = date('Y-m').'-'.strright(('0'.(date('d'))),2);
$hora = (date('G')-5).':'.date('i:s');
$fechaHora = $dia.' '.$hora.':000' ;
?>



<style type="text/css">
table.table-fh {
    width: 100%;
}
table.table-fh, table.table-fh > tbody > tr > td {
    border-collapse: collapse;
    border: 1px solid #000;
}
table.table-fh > thead {
    display: table;
    width: calc(100% - 17px);
}
table.table-fh > tbody {
    display: block;
    max-height: 90vh;
    overflow-y: scroll;
}

table.table-fh.table-4c > thead > tr >th, table.table-fh.table-4c > tbody > tr > td {
    width: calc(100% / 4);
}
table.table-fh.table-5c > thead > tr >th, table.table-fh.table-5c > tbody > tr > td {
    width: calc(100% / 5);
}
table.table-fh.table-6c > thead > tr >th, table.table-fh.table-6c > tbody > tr > td {
    width: calc(100% / 6);
}
table.table-fh.table-7c > thead > tr >th, table.table-fh.table-7c > tbody > tr > td {
    width: calc(100% / 7);
}
table.table-fh.table-8c > thead > tr >th, table.table-fh.table-8c > tbody > tr > td {
    width: calc(100% / 8);
}
table.table-fh.table-9c > thead > tr >th, table.table-fh.table-9c > tbody > tr > td {
    width: calc(100% / 9);
}
table.table-fh.table-10c > thead > tr >th, table.table-fh.table-10c > tbody > tr > td {
    width: calc(100% / 10);
}
table.table-fh.table-11c > thead > tr >th, table.table-fh.table-11c > tbody > tr > td {
    width: calc(100% / 11);
}
table.table-fh.table-12c > thead > tr >th, table.table-fh.table-12c > tbody > tr > td {
    width: calc(100% / 12);
}

table.table-fh > thead > tr >th, table.table-fh > tbody > tr > td {
    padding: 5px;
    word-break: break-all;
    text-align: left;
}
table.table-fh > tbody > tr {
    display: table;
    width: 100%;
}
table.table-fh > tbody > tr > td {
    border: none;
}



</style>