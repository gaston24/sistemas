<?php

echo 'testing - todo OK';

?>


<!DOCTYPE html>
<html lang="es">
<head>
  <title>Exportar tablas HTML a EXCEL utilizando el plugin jQuery TableExport</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <script src="bower_components\jquery\dist\jquery.min.js"></script>
  <script src="bower_components\jquery-table2excel\dist\jquery.table2excel.min.js"></script>
 
</head>
<body>
 
 
<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="#">Exportar datos</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
 
</nav>
 
<div class="container" style="margin-top:30px">
  <div class="row">
    <div class="col-sm-12">
      <h3>Exportar tablas HTML a EXCEL utilizando el plugin jQuery TableExport</h3>
		<H4>Población por paises</H4>
      <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="table2excel">
							
							
                                <thead>
                                <tr>
                                    <th>Ranking</th>
                                    <th>País</th>
                                    <th>Población</th>
                                    <th>% de la población mundial</th>
                                    <th>Fecha</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>中华人民共和国 (People's Republic of China)</td>
                                    <td>1,370,570,000</td>
                                    <td>18.9%</td>
                                    <td>June 24, 2015</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>India</td>
                                    <td>1,273,140,000</td>
                                    <td>17.6%</td>
                                    <td>June 24, 2015</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>United States "USA"</td>
                                    <td>321,268,000</td>
                                    <td>4.43%</td>
                                    <td>June 24, 2015</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Indonesia</td>
                                    <td>255,461,700</td>
                                    <td>3.52%</td>
                                    <td>July 1, 2015</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Brazil</td>
                                    <td>204,503,000</td>
                                    <td>2.82%</td>
                                    <td>June 24, 2015</td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Pakistan</td>
                                    <td>190,156,000</td>
                                    <td>2.62%</td>
                                    <td>June 24, 2015</td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Nigeria</td>
                                    <td>183,523,000</td>
                                    <td>2.53%</td>
                                    <td>July 1, 2015</td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Bangladesh</td>
                                    <td>126,880,000</td>
                                    <td>2.19%</td>
                                    <td>June 24, 2015</td>
                                </tr>
                                
                            </tbody></table>
                        </div>
      
      <hr class="d-sm-none">
    </div>
    
  </div>
</div>
 
	<button id="exportar">exportar</button>
</body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>


<script>

    $(document).ready(() => {
                $("#exportar").click(function(){
        $("#table2excel").table2excel({
            // exclude CSS class
            exclude: ".noExl",
            name: "Worksheet Name",
            filename: "SomeFile", //do not include extension
            fileext: ".xls" // file extension
        }); 
        });

    });

</script>

</html>
