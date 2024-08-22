<div class="modal fade" id="townModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-white">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Town</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">

        <div class="modal-body p-4 bg-white">
          <form id="frmTown" class="needs-validation" novalidate>
            <input type="hidden" id="hiddenlbl">
            <div class="row">
              <div class="col-lg">
                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>District</label>
                <select class="form-select" aria-label="Default select example" id="cmbDistrict">
                  
                </select>
              </div>
              <div class="col-lg">
                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Town<span class="text-danger">*</span></label>
                <input type="text" name="Town" id="txtTown" class="form-control validate" required>
                <span class="text-danger font-weight-bold town1"></span>

              </div>

            </div>
        </div>


      </div>
      <div class="modal-footer">
        <input type="hidden" id="id">
        <button type="button" id="btnClose" class="btn btn-secondary">Close</button>
        <button type="button" id="btnSaveTown" class="btn btn-primary ">Save</button>
        <button type="button" id="btnUpdateTown" class="btn btn-primary updategroup">Update</button>
      </div>
      </form>
    </div>
  </div>
</div>