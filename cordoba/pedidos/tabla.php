
<?php 
if(substr($v['DESCRIPCIO'], -11)=='-- SALE! --'){
    ?>
    <tr style="font-size:smaller;font-weight:bold;color:#FE2E2E" >
    <?php
    }else{
    ?>
    <tr style="font-size:smaller" >
    <?php
    }
    ?>
    
    <td style="width: 4%"> 
    <a target="_blank" href="../../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13) ;?>.jpg"><img src="../../../Imagenes/<?= substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="" height="40" width="40"></a>
    </td>
        <td style="width: 8%" class="small"><?= $v['COD_ARTICU'] ;?>  </td>	
        <td style="width: 1%"><input name="codArt[]" value="<?= $v['COD_ARTICU'] ;?>"  hidden>  </td>					
        <td style="width: 15%" class="small"><?= $v['DESCRIPCIO'] ;?>  </td>				
        <td style="width: 15%" class="small" align="left"><?= $v['RUBRO'] ;?>  </td>
        <td style="width: 1%"><input name="rubro[]" value="<?= $v['RUBRO'] ;?>"  hidden>  </td>	
        <td style="width: 3%" id="stock"><?= (int)($v['CANT_STOCK']) ;?>  </td>
        <td style="width: 1% ; border-left: 1px solid black"><input name="stock[]" value="<?= $v['CANT_STOCK'] ;?>"  hidden>  </td>	
        <td style="width: 2%"><?= (int)($v['812_STOCK']) ;?>  </td>
        <td style="width: 4%"><input type="text" name="cantPed_812[]" id="cantPed" value="0" onkeyup="total();precioTotal()" onchange="verifica();" size="1" tabindex="1"  class="form-control form-control-sm">  </td>
        <td style="width: 2% ; border-left: 1px solid black"><?= (int)($v['813_STOCK']) ;?>  </td>				
        <td style="width: 2%"><?= (int)($v['813_VENDIDO']) ;?>  </td>
        <td style="width: 4%"><input type="text" name="cantPed_813[]" id="cantPed" value="0" onkeyup="total();precioTotal()" onchange="verifica();" size="1" tabindex="1"  class="form-control form-control-sm">  </td>
        <td style="width: 2% ; border-left: 1px solid black"><?= (int)($v['814_STOCK']) ;?>  </td>
        <td style="width: 2%"><?= (int)($v['814_VENDIDO']) ;?>  </td>
        <td style="width: 4%"><input type="text" name="cantPed_814[]" id="cantPed" value="0" onkeyup="total();precioTotal()" onchange="verifica();" size="1" tabindex="1"  class="form-control form-control-sm">  </td>
        <td style="width: 2% ; border-left: 1px solid black"><?= (int)($v['815_STOCK']) ;?>  </td>
        <td style="width: 2%"><?= (int)($v['815_VENDIDO']) ;?>  </td>
        <td style="width: 4%"><input type="text" name="cantPed_815[]" id="cantPed" value="0" onkeyup="total();precioTotal()" onchange="verifica();" size="1" tabindex="1"  class="form-control form-control-sm">  </td>
        <td style="width: 2% ; border-left: 1px solid black"><?= (int)($v['816_STOCK']) ;?>  </td>				
        <td style="width: 2%"><?= (int)($v['816_VENDIDO']) ;?>  </td>
        <td style="width: 4%"><input type="text" name="cantPed_816[]" id="cantPed" value="0" onkeyup="total();precioTotal()" onchange="verifica();" size="1" tabindex="1"  class="form-control form-control-sm">  </td>
        <td style="width: 2% ; border-left: 1px solid black"><?= (int)($v['876_STOCK']) ;?>  </td>				
        <td style="width: 2%"><?= (int)($v['876_VENDIDO']) ;?>  </td>
        <td style="width: 4%"><input type="text" name="cantPed_876[]" id="cantPed" value="0" onkeyup="total();precioTotal()" onchange="verifica();" size="1" tabindex="1"  class="form-control form-control-sm">  </td>
        <td style="width: 4%" id="precio"><?=(int)($v['PRECIO']) ;?> </td>			
    </tr>

    <script>

    function verifica(){

        var x = document.querySelectorAll("#id_tabla #cantPed"); 
        var y = document.querySelectorAll("#id_tabla #stock");
        
        var i;
        for (i = 0; i < x.length; i++) {
            if( parseInt(x[i].value) > parseInt(y[i].innerHTML) ){
                Swal.fire({
                        icon: 'error',
                        title: 'Error...',
                        text: 'La cantidad ingresada es mayor al stock!',
                        });
                x[i].value = 0;
            }
        }
    };



    </script>