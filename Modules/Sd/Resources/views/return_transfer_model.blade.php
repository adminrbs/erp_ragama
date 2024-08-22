<!-- Modal -->
<div class="modal fade modal-xl" id="rtn_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Return Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                <div style="height: 300px;">
                    <table class="table datatable-fixed-both-getdata table-striped"  id="get_table" >
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference Number</th>
                                <th>Customer Name</th>
                                <th>Code</th>
                                <th>Item Name</th>
                                <th>Pack Size</th>
                                <th>Reason</th>
                                <th>Total Qty</th>
                                <th>Select</th>

                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
             
               
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <button type="button" class="btn btn-primary" id="bntLoadData">Get Item</button> 
               <button type="button" class="btn btn-primary" id="btnGetAll">Get All</button>
            </div>
        </div>
    </div>
</div>