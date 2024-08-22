<!-- Modal -->
<div class="modal fade modal-xl" id="receipt_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="batchModelTitle">SFA Receipts</h5>
                
                
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <input type="hidden" id="hiddenItem">
                
            </div>
            <div class="modal-body" id="batchModalBody" >
            <div style="height: 150px;">
    <table class="table table-striped" id="receipts_table">
        <thead>
            <tr>
                <th>Date</th>
                <th>Reference</th>
                <th style="text-align:right;">Amount</th>
                <th>Current Type</th>
                <th>Change To</th>
                <th>Bank</th>
                <th>Bank Branch</th>
                <th>Cheque No</th>
                
            </tr>
        </thead>
        <tbody id="receipts_table_body" style="height: 100px;">
            <!-- Table rows will be dynamically added here -->
        </tbody>
    </table>
</div>

<!-- Textboxes with labels under the table -->
<div class="form-row" style="display: flex; align-items: center; margin-top: 10px;">
    <div class="form-group" style="margin-right: 20px;">
        <label for="txtField1">Banking Date</label>
        <input type="date" class="form-control" id="dtBankingDate">
    </div>
    <div class="form-group">
        <label for="txtField2">Remark</label>
        <input type="text" id="txtRemark" class="form-control">
    </div>
</div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="btnChange" onclick="changeType()">Change</button> 
                <button type="button" class="btn btn-danger" id="btnReject" onclick="cancelReceipt()">Reject</button> 
            </div>
        </div>
    </div>
</div>