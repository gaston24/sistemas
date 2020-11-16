
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
<a target="_blank" href="../../imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg"><img src="../../imagenes/<?php echo substr($v['COD_ARTICU'], 0, 13) ;?>.jpg" alt="" height="40" width="40"></a>
</td>
<td style="width: 8%" class="small"><?php echo $v['COD_ARTICU'] ;?>  </td>	
<td style="width: 1%"><input name="codArt[]" value="<?php echo $v['COD_ARTICU'] ;?>"  hidden>  </td>					
<td style="width: 15%" class="small"><?php echo $v['DESCRIPCIO'] ;?>  </td>				
<td style="width: 15%" class="small" align="left"><?php echo $v['RUBRO'] ;?>  </td>
<td style="width: 1%"><input name="rubro[]" value="<?php echo $v['RUBRO'] ;?>"  hidden>  </td>	
<td style="width: 3%"><?php echo (int)($v['CANT_STOCK']) ;?>  </td>
<td style="width: 1%"><input name="stock[]" value="<?php echo $v['CANT_STOCK'] ;?>"  hidden>  </td>	

<td style="width: 2% ; border-left: 1px solid black"><?php echo (int)($v['851_STOCK']) ;?>  </td>
<td style="width: 2%"><?php echo (int)($v['851_VENDIDO']) ;?>  </td>
<td style="width: 4%"><input type="text" name="cantPed_851[]" value="0" size="1" tabindex="1" id="articulo" class="form-control form-control-sm">  </td>

<td style="width: 2% ; border-left: 1px solid black"><?php echo (int)($v['833_STOCK']) ;?>  </td>				
<td style="width: 2%"><?php echo (int)($v['833_VENDIDO']) ;?>  </td>
<td style="width: 4%"><input type="text" name="cantPed_833[]" value="0" size="1" tabindex="1" id="articulo" class="form-control form-control-sm">  </td>

<td style="width: 2% ; border-left: 1px solid black"><?php echo (int)($v['902_STOCK']) ;?>  </td>				
<td style="width: 2%"><?php echo (int)($v['902_VENDIDO']) ;?>  </td>
<td style="width: 4%"><input type="text" name="cantPed_902[]" value="0" size="1" tabindex="1" id="articulo" class="form-control form-control-sm">  </td>
<td style="width: 4%"></td>			

</tr>