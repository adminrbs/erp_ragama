<!-- Modal -->
<div class="modal fade modal-xl" id="batchModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchModelTitle">Item Set Off</h5>
                
                <h5 class="modal-title" id="batchModelTitle" style="margin-left: 100px!important;"></h5>
                <h5 class="modal-title" id="lblBalance" style="margin-left: 5px!important;"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <input type="hidden" id="hiddenItem">
                
            </div>
            <div class="modal-body" id="batchModalBody" >
                <div style="height: 300px;">
                    <table class="table table-striped"  id="batchTableData">
                        <thead>
                            <tr>
                                <!-- <th>Set off Id</th> -->
                                <th>Batch Number</th>
                                <th>Cost Price</th>
                                <th>Whole Sale</th>
                                <th>Retail Price</th>
                                <th>Avl Qty</th>
                                <th>Set Off</th>
                               
                            </tr>
                        </thead>
                        <tbody id="batchTableDataBody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <!--  <button type="button" class="btn btn-primary" id="btnSetOff">Set Off</button>  -->
            </div>
        </div>
    </div>
</div>