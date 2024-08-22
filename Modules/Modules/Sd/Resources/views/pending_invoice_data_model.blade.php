<div class="modal fade modal-xl" id="pending_inv_data_list_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="myForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="batchModelTitle">Pending Inquiries Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <input type="hidden" id="hiddenItem">
                </div>
                <div class="modal-body" id="batchModalBody">
                    <div class="row">
                        <div class="col-4">
                            <label for="selectEmployee" class="form-label">Employee</label>
                            <select class="form-select" id="cmbEmp"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="txtStatement" class="form-label">Statement</label>
                            <textarea rows="4" name="remarks" id="txtStatement" class="form-control form-control-sm" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" id="btn_save">Save</button>
                        </div>
                    </div>
                    <div class="row">
                        <div style="height: 300px;">
                            <table class="table table-striped" id="pending_inv_data_list_table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Employee</th>
                                        <th>Statement</th>
                                    </tr>
                                </thead>
                                <tbody id="pending_inv_data_list_table_body">
                                    <!-- Table body content goes here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
