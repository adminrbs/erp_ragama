<!-- Modal -->
<div class="modal fade modal-xl" id="poModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchModelTitle">Info</h5>

                <h5 class="modal-title" id="batchModelTitle" style="margin-left: 100px!important;"></h5>
                <h5 class="modal-title" id="lblBalance" style="margin-left: 5px!important;"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <input type="hidden" id="hiddenItem">

            </div>
            <div class="modal-body" id="batchModalBody">
                <div style="height: 300px;">
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a href="#History" class="nav-link active" aria-selected="true">History</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#Pending" class="nav-link" aria-selected="true">Pending</a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="History">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table  table-striped" id="history_table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Supplier Name</th>
                                                <th>Supplier Inv</th>
                                                <th>Qty</th>
                                                <th>Foc</th>
                                                <th>Add. Bonus</th>
                                                <th>Price</th>
                                                <th>Discount</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody id="history_table_body">

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="Pending">
                            <div class="row">
                                <table class="table  table-striped" id="pending_table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Supplier Name</th>
                                            <th>Supplier Inv</th>
                                            <th>Qty</th>
                                            <th>Foc</th>
                                            <th>Add. Bonus</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Value</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pending_table_body">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>







                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>