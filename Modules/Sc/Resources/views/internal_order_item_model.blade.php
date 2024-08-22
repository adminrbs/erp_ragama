

<!-- Modal -->
<div class="modal fade modal-xl" id="item_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <div class="col-3">
                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Supply Group</span></label>
                    <select class="select2 form-control validate" id="cmbSupplyGroup" data-live-search="true"></select>
                </div>
                <div class="col-1"></div>
                <div class="col-2">
                    <div class="row">
                        <div class="col-12">
                        <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkMultiple">
                                                        <span class="form-check-label"></span>
                                                    </label>

                        </div>
                        

                    </div>
                </div>


                <h5 class="modal-title" id="batchModelTitle" style="margin-left: 100px!important;"></h5>
                <h5 class="modal-title" id="lblBalance" style="margin-left: 5px!important;"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <input type="hidden" id="hiddenItem">

            </div>
            <div class="modal-body" id="batchModalBody">

                <div style="height: 300px;">
                    <table class="table table-striped" id="model_item_table">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Item Name</th>
                                <th>Pack Size</th>
                                <th>From B.Stock</th>
                                <th>To B.Stock</th>
                                <th>AVG Sales</th>
                                <th>Supply Group</th>
                                <th><input type="checkbox" class="form-check-input" id="selectAll" onchange="selectAll(this)"></th>




                            </tr>
                        </thead>
                        <tbody id="model_item_tablebody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnSetOff" onclick="loadSelectedItems()">Get Data</button>
            </div>
        </div>
    </div>
</div>