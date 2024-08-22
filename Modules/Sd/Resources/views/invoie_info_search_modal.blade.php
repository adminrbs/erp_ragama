<div class="modal fade modal-xl" id="inv_info_search_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inv_info_search_modal_label">Sales Invoices</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <label id="dt"></label>
            <div class="modal-body" id="invoiceTableModelBody">
                <div style="height: 300px;">
                    <div class="row">
                        <div class="col-md-3">
                            <label>From</label>
                            <input type="text" name="from_date" id="from_date" class="form-control daterange-single" >
                        </div>
                        <div class="col-md-3">
                            <label>To</label>
                            <input type="text" name="to_date" id="to_date" class="form-control daterange-single">
                        </div>

                        <div class="col-md-3">
                            <label>Customer</label>
                            <select name="cmbCustomer" id="cmbCustomer" class="select2 form-control">
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Sales Rep</label>
                            <select name="cmbSalesRep" id="cmbSalesRep" class="select2 form-control">
                            </select>
                        </div>

                    </div>



                    <div class="row" style="overflow-y: scroll; height: 280px;">
                        <table class="table  table-striped" id="getInvoicetable">
                            <thead>
                                <tr>
                                    <th>Invoice Date</th>
                                    <th>Invoice Number</th>
                                    <th>Customer Name</th>
                                    <th>Sales Rep</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <br>
                <br>
               <!--  <div class="row">
                    <table class="table" id="gettableItems">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Name</th>
                                <th>QTY</th>
                                <th>FOC</th>
                                <th>U.O.M</th>
                                <th>Pack Size</th>
                                <th>Package Size</th>
                                <th>Price</th>
                                <th>Disc. %</th>
                                <th>Disc. Amount</th>
                                <th>Value</th>
                                <th><input type="checkbox" id="selectAll" checked></th>

                            </tr>
                        </thead>
                        <tbody id="gettableItemsbody">

                        </tbody>
                    </table>

                </div> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="bntLoadData">Get Data</button>
            </div>
        </div>
    </div>
</div>