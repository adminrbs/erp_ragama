<style>
    .highlight-row {

        color: red !important;


    }

    .table-zebra tr:nth-child(even) {
        background-color: #F6F6F6;
    }
</style>

<!-- Modal -->
<div class="modal fade modal-md" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="">
            <input type="hidden" id="hiddem_lbl">
            <div class="modal-body">
                <!-- <div style="height: 350px;"> -->
                <table class="table datatable-fixed-both-getdata table-zebra" id="gettable">
                    <thead>
                        <tr>
                            
                            <th>Invoice Number</th>
                            <th>Set Off Amount</th>
                            

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                
               
            </div>
            <div class="modal-footer">
                <div class="col-12">
                    <div class="row">
                       <!--  <div class="col-2">
                            <button type="button" class="btn btn-danger" id="btnReject_order">Reject</button>
                        </div> -->
                        <div class="col-10">

                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-primary" id="bntLoadData">Get Data</button> -->
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>