<div class="modal fade" id="modalDeliveryPostponeList" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h6>Invoice List</h6>
                <h6 id="postpone_delivery_header">Postpone 0</h6>
            </div>

            <div class="modal-body">

                <div class="row">
                    <table class="table datatable-fixed-both-delivery-plan-postpone table-striped" id="deliveryPostponeTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Customer</th>
                                <th>Town</th>
                                <th>Amount</th>
                                <th>Postpone</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody id="tblPostponeDelivery"></tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="btnUpdateDeliveryPostpone">Update</button>&nbsp;
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">Close</button>

                </div>
            </div>

        </div>
    </div>
</div>