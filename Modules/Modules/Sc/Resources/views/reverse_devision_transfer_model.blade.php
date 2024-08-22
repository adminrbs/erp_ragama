<style>
    .highlight-row {
   
    color: red !important;

   
}

.table-zebra tr:nth-child(even) {
    background-color: #F6F6F6;
}
</style>

<!-- Modal -->
<div class="modal fade modal-xl" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="">
        
            <div class="modal-body" >
                <!-- <div style="height: 350px;"> -->
                    <table class="table datatable-fixed-both-getdata table-zebra"  id="gettable" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Reference</th>
                                <th>From Branch</th>
                                <th>From Location</th>
                                <th>Amount</th>
                                <th>Action</th>
                                
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                <!-- </div> -->
                <br>
             
                <div id="si_pickOrders_itemtable">
                <table class="table" id="gettableItems">
                        <thead>
                            <tr>
                                <th>Item Code</th>
                                <th>Name</th>
                                <th>Reversable Qty</th>
                                <th>QTY</th>
                                <th style="display: none;">Pacs</th>
                                <th>Price</th>
                                <th>Value</th>
                                <th><input type="checkbox" id="selectAll" checked></th>
                                <th style="display: none;">All Balance</th>

                            </tr>
                        </thead>
                        <tbody id="gettableItemsbody">

                        </tbody>
                    </table>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary" id="bntLoadData">Get Data</button> 
            </div>
        </div>
    </div>
</div>