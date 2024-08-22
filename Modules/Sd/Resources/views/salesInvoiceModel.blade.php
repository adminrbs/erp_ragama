<style>
    tr.highlight-row {
    color: red !important; /* or any other color you want */
}

    .table-zebra tr:nth-child(even) {
        background-color: #F6F6F6;
    }

    /* CSS for table container */
.table-container {
    max-height: 350px; /* Set the maximum height for the container */
    overflow: auto; /* Enable vertical and horizontal scrollbar when content exceeds container size */
    position: relative; /* Required for positioning of the header */
}

/* CSS for fixing table headers */
.table-container thead tr th {
    position: sticky; /* Fix the headers */
    top: 0; /* Stick to the top */
    background-color: white; /* Ensure headers are above table body */
}

/* CSS for table body */
.table-body {
    overflow-y: scroll; /* Always show vertical scrollbar for consistency */
    max-height: calc(100% - 30px); /* Adjust according to the height of the table header */
}

    
      
   

</style>

<!-- Modal -->
<div class="modal fade modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" id="">
        <div style="display: flex; align-items: center;">
    <h4 style="margin-left: 20px; margin-top: 10px;" id="branch_name"></h4>
    <h6 style="color: red; margin-left: 200px; margin: top 10px;">Red color records of the below table are blocked records!</h6>
</div>

            <div class="modal-body">
           
            <div class="col-3">
            <input type="search" placeholder="Type to search" id="table_search" class="form-control">
            </div>
            <br>
                <ul class="nav nav-tabs mb-0" id="tabs">
                    <li class="nav-item rbs-nav-item">
                        <a href="#orders" class="nav-link active" aria-selected="true">Orders</a>
                    </li>
                    <li class="nav-item rbs-nav-item">
                        <a href="#items" class="nav-link" aria-selected="true">Item</a>
                    </li>

                </ul>
               
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="orders">
                        <div class="row">
                       
                            <div class="row">
                                <div class="table-container">
                                <table class="table" id="gettable">
                                    <thead>
                                        <tr>
                                            <th style="display: none;">ID</th>
                                            <th>Date</th>
                                            <th>Order No</th>
                                            <th>Customer Name</th>
                                            <th>Rep Name</th>
                                            <th>Amount</th>
                                            <th>Deliver Date</th>
                                            <th style="display: none;">Action</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>

                                </div>
                                
                                <!-- </div> -->


                                <h3 id="lblCount" style="color: red;"></h3>
                            </div>
                        </div>
                    </div>


                    <div class="tab-pane fade show" id="items">
                        <div class="row">
                            <div class="row">
                                <div id="si_pickOrders_itemtable">
                                    <table class="table" id="gettableItems" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Item Code</th>
                                                <th style="width: 30px;">Name</th>
                                                <th>Avl. Qty</th>
                                                <th>QTY</th>
                                                <th>FOC</th>
                                                <th>U.O.M</th>

                                                <th>Price</th>


                                                <th>Value</th>
                                                <th><input type="checkbox" id="selectAll" checked></th>
                                                <th style="display: none;">All Balance</th>
                                                <th style="display: none;">Supply Group Id</th>

                                            </tr>
                                        </thead>
                                        <tbody id="gettableItemsbody">

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>



            </div>
            <div class="modal-footer">
                <div class="col-12">
                    <div class="row">
                        <div class="col-2">
                            <button type="button" class="btn btn-danger" id="btnReject_order">Reject</button>
                        </div>
                        <div class="col-8">

                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="bntLoadData">Get Data</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>