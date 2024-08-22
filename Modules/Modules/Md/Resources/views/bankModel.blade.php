

<div class="modal fade" id="bankModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bank</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="modal-body p-4 bg-white">
                    <form id="" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Bank Code<span class="text-danger">*</span></label>

                                <input type="text" name="bankCode" id="txtBankCode"
                                class="form-control validate" required maxlength="4">
                                <span class="text-danger font-weight-bold category2"></span>


                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Bank Name<span class="text-danger">*</span></label>
                                <input type="search" class="form-control" id="txtbankSearch"  autocomplete="new-search" placeholder="Search Bank" aria-controls="autoComplete_list_1" aria-autocomplete="both" aria-activedescendant="">
                            </div>
                        </div>


                </div>


            </div>
            <div class="modal-footer">
                <input type="hidden" id="id">
                <button type="button" id="btnBankclose" class="btn btn-secondary" >Close</button>
                <button type="button" id="btnSaveBank"

                    class="btn btn-primary btnSaveBank">Save</button>
                <button type="button" id="btnUpdateBank"
                    class="btn btn-primary ">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
<!-- Modal Branch-->
<div class="modal fade" id="bankBranchmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Bank Branch </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="modal-body p-4 bg-white">
                    <form id="" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Code<span class="text-danger">*</span></label>
                                <input type="text" name="branchCode" id="txtbranchCode" class="form-control" maxlength="3"
                                    required>
                                    <span class="text-danger font-weight-bold category1"></span>
                                    <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Bank Branch Name<span class="text-danger">*</span></label>
                                    <input type="search" class="form-control" id="txtbranchSearch"  autocomplete="new-search" placeholder="Search Bank" aria-controls="autoComplete_list_1" aria-autocomplete="both" aria-activedescendant="">

                                    <span class="text-danger font-weight-bold category1"></span>
                            </div>
                        </div>


                </div>


            </div>
            <div class="modal-footer">
                <input type="hidden" id="id">
                <button type="button" id="btnCloseBranch" class="btn btn-secondary" >Close</button>

                <button type="button" id="btnSaveBranch"
                    class="btn btn-primary btnSaveBranch">Save</button>

                <button type="button" id="btnUpdateBranch"
                    class="btn btn-primary ">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->
