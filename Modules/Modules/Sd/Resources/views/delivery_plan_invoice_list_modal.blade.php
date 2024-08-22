<div class="modal fade" id="modalDeliveryPlanInvoiceList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-white">
            <div  class="modal-header">
                <h6>Invoice List</h6>
            </div>

            <div class="modal-body">

                <div class="row">
                    <table class="table datatable-fixed-both-delivery-plan-allocated-invoice-list table-striped" id="allocatedInvoiceListTable">
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
                <div class="mt-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>