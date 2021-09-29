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
            <?php if(((int)$_SESSION['cupoCredi'] / (int)$_SESSION['cupoCrediCliente']) < 0.10){ ?>
                <span class="badge badge-warning badge-pill" id="icon"><?= "$".number_format((int)$_SESSION['cupoCredi'], 0, ".",".");  ?></span>
                <p id="info" class="text-danger"><small>El importe disponible en $ es inferior al 10% del cupo de crédito!</small></p>
                <?php }else if(((int)$_SESSION['cupoCredi'] / (int)$_SESSION['cupoCrediCliente']) >= 0.10){ ?>
                <span class="badge badge-primary badge-pill"><?= "$".number_format((int)$_SESSION['cupoCredi'], 0, ".",".");  ?></span>
            <?php } ?>				
        </li>
        </ul>	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>


	
