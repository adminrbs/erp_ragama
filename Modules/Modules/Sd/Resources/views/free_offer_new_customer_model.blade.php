<div class="modal fade" id="offer_customer_model" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lblOfferApplyModel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="col-md-6">
                        <h5>Filter by</h5>
                        <select class="form-select" id="cmbFilterBy">
                            <option value="0" selected>Select</option>
                            <option value="2">Customer</option>
                            <option value="3">Customer Group</option>
                        </select>



                        <br>

                    </div>

                    <select multiple class="form-control  listbox-buttons" id="cmbFilterData">

                    </select>

                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_add_cus">Add</button>
            </div>
        </div>
    </div>
</div>