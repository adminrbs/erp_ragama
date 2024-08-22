<div class="modal fade" id="block_customer_model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Release note</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12 mb-4">
                    <label id="hiddnLBLforID" style="display: hidden;"></label>
                    <label id="hiddnLBLforblockStatus" style="display: hidden;"></label>
                    <label id="customer_remark"></label>
                    <br>
                    <br>
                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Remark <span class="text-danger"></span> </label>
                    
                    <div>

                        <input class="form-control form-control-sm validate" type="text" id="txtRemark" name="" autocomplete="new-search" required>

                    </div>
                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Number of Orders <span class="text-danger"></span> </label>

                    <div>

                        <input class="form-control form-control-sm validate" type="number" id="txtnumOfOrders" name="" autocomplete="new-search">

                    </div>
                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Value <span class="text-danger"></span> </label>

                    <div>

                        <input class="form-control form-control-sm validate" type="number" id="txtValue" name="" autocomplete="new-search">

                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="btnRelease">Release</button>
                <button type="button" class="btn btn-danger" id="btnBlock">Block</button>
            </div>
        </div>
    </div>
</div>