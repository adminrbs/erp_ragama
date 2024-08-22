<div class="modal fade" id="routeModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content bg-white">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Route</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
              <input type="hidden" id="hiddenlbl">
                <div class="modal-body p-4 bg-white">
                    <form id="frmRoute" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-lg">
                            <label for="txtRoute"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Route<span
                                class="text-danger">*</span></label>
                            <input type="text" name="txtRoute" id="txtRoute" class="form-control validate" required>
                            <span class="text-danger font-weight-bold group1"></span>

                            <!-- <label for="txtRoute"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Order</label>
                            <input type="number" name="txtOrderNum" id="txtNOrderNum" class="form-control validate" required> -->
                            <!-- <span class="text-danger font-weight-bold group1"></span> -->
                        </div>
                    </div>
                </div>


        </div>
        <div class="modal-footer">
            <input type="hidden" id="id">
            <button type="button" id="btnCloseGroup" class="btn btn-secondary">Close</button>
          <button type="button" id="btnSaveRoutes" class="btn btn-primary ">Save</button>
          <button type="button" id="btnUpdateRoute" class="btn btn-primary updategroup">Update</button>
        </div>
    </form>
      </div>
    </div>
  </div>