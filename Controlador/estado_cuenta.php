<?php
// session_start();
?>

<div class="modal fade" id="dataFranquiciaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Estado de cuenta</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <ul class="list-group">

        <li class="list-group-item list-group-item-secondary">
            Detalle de CC
            
        </li>
        <li class="list-group-item">
            Cupo credito 
            <span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['cupoCrediCliente'], 0, ".",".");  ?></span>
        </li>
        <li class="list-group-item">
            Total deuda
            <span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['totalDeuda'], 0, ".",".");  ?></span>
        </li>
        <li class="list-group-item">
            Pedidos abiertos
            <span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['pedidos'], 0, ".",".");  ?></span>
        </li>
        <li class="list-group-item">
            Disponible para pedidos
            <?php 
              var_dump("here");
            if(isset($_SESSION)){
              // var_dump("session ".print_r($_SESSION));
              // if(((int)$_SESSION['cupoCredi'] / (int)$_SESSION['cupoCrediCliente']) < 0.10){ ?>
              
              
            
            <?php 
              // } 				
            }

            ?>
        </li>
        </ul>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>


	
