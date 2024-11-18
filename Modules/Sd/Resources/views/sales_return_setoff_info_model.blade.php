<style>
    .highlight-row {
        color: red !important;
    }

    .table-zebra tr:nth-child(even) {
        background-color: #F6F6F6;
    }
</style>

<!-- Modal -->
<div class="modal fade modal-md" id="infoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <input type="hidden" id="hiddem_lbl">
            <div class="modal-body">
                <div class="row">
                    <!-- First Card -->
                    <div class="col-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Invoice Table</h5>
                            </div>
                            <div class="card-body">
                                <table class="table datatable-fixed-both-getdata table-zebra" id="gettable">
                                    <thead>
                                        <tr>
                                            <th>Invoice Number</th>
                                            <th>Set Off Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Add dynamic rows here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- Second Card -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Transaction Setoff Table</h5>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-hover" id="transaction_set_off_table">
                                    <thead>
                                        <tr>
                                            <th>Reference No</th>
                                            <th>Setoff Reference No</th>
                                            <th>Customer</th>
                                            <th>Amount</th>
                                            <th>Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Add dynamic rows here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
