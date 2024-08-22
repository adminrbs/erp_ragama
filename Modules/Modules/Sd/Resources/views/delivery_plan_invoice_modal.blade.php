<div class="modal fade" id="modalDeliveryPlanInvoice" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-white">
            <div class="modal-body">
                <input type="hidden" class="form-control" name="hidden_delivery_plan_invoice_id" id="hidden_delivery_plan_invoice_id">
                <div class="row">

                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a id="tabAllocate" href="#allocateInvoice" onclick="showActionInvoice()" class="nav-link active" aria-selected="true">Non Allocate Invoice</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#noneAllocate" onclick="hideActionInvoice()" class="nav-link" aria-selected="false">Allocated Invoice</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <!-- Delivery plan tab -->
                        <div class="tab-pane fade show active" id="allocateInvoice">
                            <div class="row">

                                <div class="row">
                                    <table class="table datatable-fixed-both-delivery-plan-invoice table-striped" id="invoiceTable">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice No</th>
                                                <th>Customer</th>
                                                <th>Info</th>
                                                <th>Town</th>
                                                <th>Amount</th>
                                                <th>Order Date</th>
                                                <th>OrderNo</th>
                                                <th><input type="checkbox" id="mainInvoiceCheck"></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- End of Delivery plan tab -->

                        <!-- Route tab -->
                        <div class="tab-pane fade" id="noneAllocate">


                            <div class="row">
                                <table class="table datatable-fixed-both-delivery-plan-allocated-invoice table-striped" id="allocatedInvoiceTable">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Invoice No</th>
                                            <th>Customer</th>
                                            <th>Town</th>
                                            <th>Amount</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tblAllocatedRemark"></tbody>
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
                    <button type="button" id="btnActionInvoice" class="btn btn-primary">Save</button>
                    &nbsp;&nbsp;
                    <button type="button" id="btnUpdateAllocatedRemark" class="btn btn-primary">Update</button>
                </div>

            </div>
        </div>
    </div>

</div>