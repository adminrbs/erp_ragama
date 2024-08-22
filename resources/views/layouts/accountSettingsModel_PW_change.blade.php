<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Modal -->
<div class="modal fade modal-lg" id="pw_changeModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="false">
  <div class="modal-dialog">
    <div class="modal-content" id="">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Account Settings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="col-md-7">
          <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Current Password <span class="text-danger">*</span></span></label>
          <div class="input-group">
            <input class="form-control p_field" type="password" id="txtCurrentPW" value="" autocomplete="off">
            <span class="input-group-text">
              <!-- <i class="fa fa-eye" id="txtcurPW_eye" style="cursor: pointer"></i> -->
            </span>

            <!-- <div class="input-group-text">
              <input type="checkbox" id="checkCurrentPassword">
            </div> -->

          </div>
        </div>
        <div class="col-md-7">
          <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>New Password <span class="text-danger">*</span></span></label>
          <div class="input-group">
            <input class="form-control p_field" type="password" id="txtNewPW" value="">
            <span class="input-group-text">
              <i class="fa fa-eye" id="txtNewPW_eye" style="cursor: pointer"></i>
            </span>

            <!-- <div class="input-group-text">
              <input type="checkbox" id="checkNewPassword">
            </div> -->

          </div>
        </div>
        <div class="col-md-7">
          <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Confirm New Password <span class="text-danger">*</span></span></label>
          <div class="input-group">
            <input class="form-control p_field" type="password" id="txtConfirmPW" value="">
            <span class="input-group-text">
              <i class="fa fa-eye" id="txtconfirmPW_eye" style="cursor: pointer"></i>
            </span>
            <!-- <div class="input-group-text">
              <input type="checkbox" id="checkConfirmNewPassword">

            </div> -->
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="model_btnUpdatePW_">Update</button>
      </div>
    </div>
  </div>
</div>

<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>