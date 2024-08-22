<div class="modal fade" id="modal_non_allocated_list" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-white">
            <div class="modal-body">
                <input type="hidden" class="form-control" name="hidden_delivery_plan_invoice_id" id="hidden_delivery_plan_invoice_id">
                <div class="row">

                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a id="tabAllocate" href="#non_allocateInvoice" onclick="showActionInvoice()" class="nav-link active" aria-selected="true">Non Allocate Invoice</a>
                        </li>
                        
                    </ul>
                    <div class="tab-content">
                        <!-- Delivery plan tab -->
                        <div class="tab-pane fade show active" id="non_allocateInvoice">
                            <div class="row">

                                <div class="row">
                                    <table class="table datatable-fixed-both-delivery-plan-invoice_non table-striped" id="invoiceTable_non">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Invoice No</th>
                                                <th>Customer</th>
                                                <th>Town</th>
                                                <th>Amount</th>
                                                <th>Order Date</th>
                                                <th>OrderNo</th>
                                               
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                        <!-- End of Delivery plan tab -->

                       
                        <!-- End of Town tab -->



                    </div>
                    <!--enf of tabs -->

                </div>
               

            </div>
        </div>
    </div>

</div>