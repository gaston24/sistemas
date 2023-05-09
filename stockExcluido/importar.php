<div class="modal fade" id="altaModalImport" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="max-width: 45%;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Importar artículos</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
            <div class="w3-col w3-half">
            <div class="form-group">
                <input id="fileupload" type=file name="files[]">
            </div>
            </div>
            <div class="w3-responsive table-responsive2">
            <h3 class="w3-center"><i class="fa fa-cloud-upload text-info" aria-hidden="true"></i><strong> Artículos a importar </strong></h3>
            <table id="tblItems" class="table ">
                <thead class="thead-dark" style="font-size: small;">
                <tr>
                    <th scope="col" style="width: 2%">ARTICULO</th>
                    <th scope="col" style="width: 1%">CANTIDAD</th>
                    <th scope="col" style="width: 15%">OBSERVACIONES</th>
                    <th scope="col" style="width: 15%">DESCRIPCION</th>
                </tr>
                </thead>
                <tbody style="font-size: small;"></tbody>
            </table>
            </div>

        </div>
      <div class="modal-footer">
        <button class="btn btn-success" id='save' onclick="">Guardar</button>
        <button type="button" id='close' class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

      </div>
    </div>
  </div>
</div>