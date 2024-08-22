<!-- Modal -->
<div class="modal fade modal-xl" id="GRNPickOrderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchModelTitle">Purchase Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               
                
            </div>
            <div class="modal-body" >
                <div style="height: 300px;">
                    <table class="table datatable-fixed-both-PO_TableData table-striped"  id="PO_TableData">
                        <thead>
                            <tr>  
                                <th>Order Date</th>
                                <th>Order No</th>
                                <th>Supplier Name</th>
                                <th>Amount</th>
                                <th>Order By</th>
                                <th>Expected Date</th>
                                <th>Action</th>
                               
                            </tr>
                        </thead>
                        <tbody id="PO_TableDataBody">

                        </tbody>
                    </table>
                </div>
                <br>
                <br>
                <br>
                <table class="table" id="gettableItems">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Name</th>
                                <th>QTY</th>
                                <th>Balance QTY</th>
                                <th>FOC</th>
                                <th>Balance FOC</th>
                                <th>U.O.M</th>
                               <!--  <th>Pack Size</th>
                                <th>Package Size</th> -->
                                <th>Price</th>
                                <th>Disc. %</th>
                               <!--  <th>Disc. Amount</th> -->
                                <th>Value</th>
                                <th><input type="checkbox" id="selectAll" checked></th>

                            </tr>
                        </thead>
                        <tbody id="gettableItemsbody">

                        </tbody>
                    </table>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btnGetData">Get Data</button> 
            </div>
        </div>
    </div>
</div>