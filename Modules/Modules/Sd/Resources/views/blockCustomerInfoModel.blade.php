<style>
    .zebra-row:nth-child(odd) {
        background-color: #F0F8FF;
        /* Light gray background for odd rows */
    }

    /* You can customize the background color for even rows if needed */
    .zebra-row:nth-child(even) {
        background-color: #F0F8FC;
        /* White background for even rows */
    }

    .right {
        text-align: right;
    }
</style>


<div class="modal fade modal-lg" id="block_customer_model_info" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Info - <label class="form values" id="lbl_cus_name"></label></h5>
                <input type="hidden" id="block_id_hidden_lbl">
                <input type="hidden" id="hidden_cus_lbl">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="margin-top: 3px;">

                <div class="card card-body">
                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a href="#general" class="nav-link active" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#outstanding" class="nav-link" aria-selected="false">Outstanding</a>
                        </li>


                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="general">
                            <div class="row">

                            </div>
                            <div class="col-md-12 mb-4">
                                <div class="row">
                                    <div class="col-4">

                                    </div>
                                    <div class="col-4">
                                        <label class="form text-center" style="font-weight: bold;">Customer</label>

                                        <label class="form values" id="lbl_cus_code" style="color: white;"></label>
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form">Setting</label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form">Value</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">

                                        <label class="form text-center" style="font-weight: bold;">Sales Rep: </label>
                                        <label class="form values" id="lbl_sales_rep_name"></label>
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form">Setting</label>

                                            </div>
                                            <div class="col-6">
                                                <label class="form">Value</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="row zebra-row" style="">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4"><label class="form">Credit limit ( Alert )</label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cl_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cl_vl"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cl_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cl_vl"></label></div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row zebra-row" style="">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4"><label class="form">Credit preiod ( Altert )</label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cp_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cp_vl"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cp_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cp_vl"></label></div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row zebra-row" style="">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4"><label class="form">Credit limit ( Hold )</label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cl_hold_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cl_hold_vl"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cl_hold_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cl_hold_vl"></label></div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row zebra-row" style="">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4"><label class="form">Credit preiod ( Hold )</label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cp_hold_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_cp_hold_vl"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cp_hold_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_cp_hold_vl"></label></div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row zebra-row" style="">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4"><label class="form">PD cheque period</label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_pd_pr_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_pd_pr_vl"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_pd_pr_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_pd_pr_vl"></label></div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row zebra-row" style="">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-4"><label class="form">PD cheque amount</label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_pd_am_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_cus_pd_am_vl"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_pd_am_st"></label></div>
                                                <div class="col-2"><label class="form values right" id="lbl_sr_pd_am_vl"></label></div>
                                            </div>
                                        </div>



                                    </div>
                                </div>

                                <div class="row" style="">
                                    <div class="col-4">
                                        <h5>Credit Score</h5>
                                    </div>
                                </div>

                                <div class="row " style="margin-top: 2px;">
                                    <div class="col-7">
                                        <div class="row" style="margin-top: 5px;">
                                            <div class="col-6">
                                                <label class="form">No of blocked </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form values right" id="lbl_no_of_blocks"></label>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-6">
                                                <label class="form">No of cheque dishonoured </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form values right" id="lbl_no_of_chq_rtn"></label>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-6">
                                                <label class="form">Dishonoured cheque (nonpaid) </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form values right" id="lbl_no_of_rtn_chq_non_paid"></label>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-6">
                                                <label class="form">Average sales for last 3 months</label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form values right" id="lbl_avgsales_last_three_months"></label>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-6">
                                                <label class="form">Last invoice date </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form values" id="lbl_last_invoice_date"></label>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-6">
                                                <label class="form">Last reciept date </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form values" id="lbl_last_rcpt_date"></label>
                                            </div>
                                        </div>

                                        <div class="row" style="margin-top: 10px;">
                                            <div class="col-6">
                                                <label class="form">Last reciept </label>
                                            </div>
                                            <div class="col-6">
                                                <label class="form values" id="lbl_last_rcpt"></label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-5">

                                        <div class="col-12" style="display: none;">
                                            <div class="chart-square" style="width: 250px; height: 250px; border: 1px solid black; display: flex; align-items: center; justify-content: center;">
                                                <label>Chart</label>
                                            </div>
                                        </div>

                                    </div>
                                </div>




                            </div>
                        </div>
                        <!-- End of general tab -->

                        <!-- Outstanding tab -->
                        <div class="tab-pane fade show " id="outstanding">
                            <div class="col-md-3 mb-4">
                                <select name="cmbBranch" id="cmbBranch" class="form-select">

                                </select>
                            </div>
                            <div class="col-md-12 mb-4">
                                <table id="outstandingTable" class="table">
                                    <thead>
                                        <tr>
                                            <thead>
                                                <th>Date</th>
                                                <th>Invoice Number</th>
                                                <th>Amount</th>
                                                <th>Age</th>
                                            </thead>
                                        </tr>
                                    </thead>
                                    <tbody id="outstandingTableBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- End of Outstanding tab -->
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btnRelease" style="display: none;">Save</button>
                <!-- <button type="button" class="btn btn-danger" id="btnBlock">Cancel</button> -->
            </div>
        </div>
    </div>
</div>