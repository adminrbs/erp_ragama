@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<style>
    .text-right {
    text-align: right;
    
}


.table-container {
    max-height: 450px; /* Set your desired max height */
    overflow-y: auto; /* Enable vertical scrolling */
    border: 1px solid #ccc; /* Optional: Add border for clarity */
}

/* Optional: Style the table header */



.table-container table {
    width: 100%;
}


</style>

<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Cheques In Hand</h5>
            </div>
            <div class="row" id="top_border">
          <!--   <div class="col-md-3" style="margin-left: 10px;margin-top: 10px">
                    <select class="form-select" id="cmbAccountNumber">
                        <option>Select Account</option>
                    </select>

            </div> -->
            <!-- <div class="col-md-3" style="margin-left: 10px;margin-top: 10px">
                   <input type="date" class="form-control" id="dtBankingDate">

            </div> -->
            <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <!-- <a href="/cb/cheque_return" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a> -->
            </div>
               
                
               <!--  <div class="col-md-2" style="margin-left: 50px; margin-top: 10px">
                    <h4>Number Of Selected:</h4>

                </div>
                <div class="col-md-2" style=" margin-top: 10px" id="">
                    <h4 id="row_count">0</h4>

                </div> -->

                <div class="col-md-2" style="margin-top: 10px;margin-left: 10px;display:none;">
                    <input type="date" name="cashDate" id="cashDate" class="form-control">
                </div>
            </div>

            <div class="row">

                <div class="col-md-12">
                    <div class="table-container">
                    <table class="table datatable-fixed-both table-striped" id="cheque_with_sales_rep_table">
                        <thead>
                            <tr>
                                <th>Sales Rep</th>
                                <th style="text-align: right;">Cheques In Hand</th>
                                <th style="text-align: right;">Late (3 Days ago)</th>
                                <th>Info</th>

                            </tr>
                        </thead>
                        <tbody>


                        </tbody>
                    </table>
                    </div>
                    
                </div>
              <!--   <div class="col-md-4" style="margin-left: 10px;margin-bottom:5px;float:right;">
                    <input type="button" id="btn_dishonur_cheque" class="btn btn-primary" value="Save">
                </div> -->
            </div>
        </div>
    </div>
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->



<!--Model -->
<div class="modal fade modal-xl" id="cheque_with_Sales_rep_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="empNamelbl"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <label id="dt"></label>
            <div class="modal-body" id="invoiceTableModelBody">

            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: black;background-color:white!important;">
                    
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a href="#total_cash" class="nav-link active" aria-selected="true">Cheque In Hand</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#late" class="nav-link" aria-selected="true">Late Cash(3 Days Ago)</a>
                        </li>

                       

                    </ul>
                    <!--enf of tabs -->
                    <!-- staring of form -->
                    <form id="frmCash" class="needs-validation" novalidate>
                        <input type="hidden" name="" id="txtHiddenId">
                        <div class="tab-content">
                            <!-- General tab -->
                            <div class="tab-pane fade show active" id="total_cash">
                                <div class="row">

                                    <div class="row">
                                        <h1>Total Cheques In Hand</h1>

                                        <div class="mb-4">
                                            <div class="table-responsive">
                                                <div class="table-container">
                                                <table id="total_cheque_table" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Reference</th>
                                                            <th>Receipt Date</th>
                                                            <th>Customer</th>
                                                            <th>Bank Code</th>
                                                            <th>Branch Code</th>
                                                            <th>Cheque No</th>
                                                            <th style="text-align: right;">Amount</th>
                                                           
                                                        </tr>
                                                    </thead>
                                                    <tbody class="cash">

                                                    </tbody>

                                                </table>
                                                </div>
                                            </div>

                                        </div>
                                     

                                    </div>

                                </div>
                            </div>
                            <!-- End of general tab -->
                          
                            <div class="tab-pane fade show" id="late">
                                <div class="row">

                                    <div class="row">
                                        <h1>Late Cash</h1>

                                        <div class="mb-4">
                                            <div class="table-responsive">
                                                <table id="late_cheque_table" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Reference</th>
                                                            <th>Receipt Date</th>
                                                            <th>Customer</th>
                                                            <th>Bank Code</th>
                                                            <th>Branch Code</th>
                                                            <th>Cheque No</th>
                                                            <th>Amount</th>
                                                            <th>Age</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="cash">

                                                    </tbody>

                                                </table>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            

                            
                        </div>
                        <div class="row mb-1">
                            <!-- <div class="col-md-4 mb-2">
                                <button type="submit" id="btnSave" class="btn btn-primary form-btn btn-sm">Save</button>
                                <button type="button" id="btnReset" class="btn btn-warning form-btn btn-sm">Reset</button>
                            </div> -->
                        </div>

                    </form>
                    <!-- end of form -->

                </div>
            </div>




                
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               <!--  <button type="button" class="btn btn-primary" id="bntLoadData">Get Data</button> -->
            </div>
        </div>
    </div>
</div>



<!-- End of Model -->

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{Module::asset('cb:js/cheque_with_sales_rep_list.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection