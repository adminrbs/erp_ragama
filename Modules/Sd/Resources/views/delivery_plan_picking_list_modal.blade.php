<div class="modal fade" id="modalDeliveryPlanPackingList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-white">
            <div class="modal-body">
                <input type="hidden" id="hid_delivery_plan_external_no">
                <div class="row">

                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a id="tabPackingList" onclick="hideActionPickingListInvoice()" href="#packingList" class="nav-link active" aria-selected="true">Picking List</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#tabList" onclick="showActionPickingListInvoice()" class="nav-link" aria-selected="false">Not created</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Packing List tab -->
                        <div class="tab-pane fade show active" id="packingList">
                            <div class="row">

                                <div class="row">
                                    <table class="table datatable-fixed-both-delivery-plan-picking-list table-striped" id="pickingListTable">
                                        <thead>
                                            <tr>
                                                <th>Preview</th>
                                                <th>Packing List ID</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- End of Packing List tab -->

                        <!-- Route tab -->
                        <div class="tab-pane fade" id="tabList">


                            <div class="row">
                                <table class="table datatable-fixed-both-delivery-plan-non-picking-list table-striped" id="nonPickingListTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice No</th>
                                            <th>Customer</th>
                                            <th>Town</th>
                                            <th>Amount</th>
                                            <th>Order Date</th>
                                            <th>OrderNo</th>
                                            <th><input type="checkbox" id="mainNonPickingCheckCheck"></th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>


                        </div>
                        <!-- End of Town tab -->



                    </div>
                    <!--enf of tabs -->

                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    &nbsp;&nbsp;
                    <button type="button" id="btnActionPickingListInvoice" class="btn btn-primary">Create</button>
                </div>

            </div>
        </div>
    </div>
</div>