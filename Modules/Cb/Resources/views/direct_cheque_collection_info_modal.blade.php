<!-- Modal -->
<div class="modal fade modal-xl" id="receipt_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchModelTitle">Direct Receipts</h5>
                
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <input type="hidden" id="hiddenItem">
                
            </div>
            <div class="modal-body" id="batchModalBody" >
                <div style="height: 300px;">
                    <table class="table table-striped"  id="receipts_table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Reference</th>
                                
                                <th style="text-align:right;">Amount</th>
                               
                                
                               
                            </tr>
                        </thead>
                        <tbody id="receipts_table">

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