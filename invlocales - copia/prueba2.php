<?php

function strright($rightstring, $length) {
  return(substr($rightstring, -$length));
}
$ahora = date('Y-m').'-'.strright(('0'.(date('d'))),2);	

echo $ahora;

?>