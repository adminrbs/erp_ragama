@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .highlight {
        background-color: #a0d3f1;
    }

    .custom-header-style {
        font-weight: bold;
        font-size: 18px;
        text-align: center;
        padding: 5px;
        /* You can set the text color as needed */
    }
</style>
@endsection

@section('content')


<!-- Content area -->
<div class="content">

    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Supplier Transaction Allocation</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div style="margin-bottom: 0px !important;padding:1px!important;">
                        <div class="card-body border-top">

                            <div class="mb-4">

                                <div class="row mb-1">

                                    <div class="col-12">

                        
                                        <div class="col-md-12">
                                            <div class="card border-secondary mb-3" style="max-width: 100%;padding:1px;">

                                                <div class="card-body text-secondary">
                                                    <div class="row mb-1">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <label class="transaction-lbl" style="width: 100%;text-align: left;"><span>Supplier</span></label>
                                                                        </div>
                                                                        <div class="col-7"><input type="text" id="txt_supplier" class="form-control" style="text-align:left;" placeholder="Search Supplier"></div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Name</span></label>
                                                                        </div>
                                                                        <div class="col-7">
                                                                            <label id="lblName" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Address</span></label>
                                                                        </div>
                                                                        <div class="col-7">
                                                                            <label id="lblAddress" class="val_lbl"></label>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Town</span></label>


                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label name="" id="lblTown" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>

                                                                </div>


                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Route</span></label>


                                                                        </div>
                                                                        <div class="col-8">
                                                                            <label name="" id="lblRoute" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Outstanding</span></label>
                                                                        </div>
                                                                        <div class="col-7">


                                                                            <label id="lblOustanding" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <dic class="row">
                                                                          <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                                                        </div>
                                                                        <div class="col-7">
                                                                            <select class="form-select" id="cmbBranch"></select> 
                                                                            
                                                                        </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <!--  <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Total Amount</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtTotal" class="val_lbl"></label>
                                                                        </div> -->
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <!--  <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Paid Amount</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtPaid" class="val_lbl"></label>
                                                                        </div> -->
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <!--     <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Balance</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtBalance" class="val_lbl"></label>
                                                                        </div> -->
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <!--  <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Invoice Date</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="invoice_date_time" class="val_lbl"></label>
                                                                        </div> -->
                                                                    </div>

                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <!--     <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Order Date</span></label>
                                                                        </div>
                                                                        <div class="col-7">
                                                                            <label id="dt_order" class="val_lbl"></label>
                                                                        </div> -->
                                                                    </div>


                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <!--   <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Gap</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtGap" class="val_lbl"></label>
                                                                        </div> -->
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>




                    <div style="margin-top: -3% !important;">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border-secondary mb-3" style="max-width: 100%">
                                                <div class="card-header custom-header-style">Transaction</div>
                                                <div class="card-body text-secondary">
                                                    <input type="hidden" id="rowIndex">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped val_table" id="transaction_table">
                                                            <thead>
                                                                <tr>

                                                                    <th>Date</th>
                                                                    <th>Reference#</th>
                                                                    <th>Description</th>
                                                                    <th style="display: none;">Amount</th>
                                                                    <th style="display: none;">P. Amount</th>
                                                                    <th>Balance</th>
                                                                    <th>Set Off</th>
                                                                    <th style="display:none">dl id</th>
                                                                    <th style="display:none">dl setoff id</th>
                                                                    <th style="display:none">Branch ID</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Your table 1 content goes here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="card border-secondary mb-3" style="max-width: 100%">
                                                <div class="card-header custom-header-style">Set off from</div>
                                                <div class="card-body text-secondary">
                                                    <input type="hidden" id="rowIndex">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped val_table" id="set_off_data_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <th>Reference#</th>
                                                                    <th>Description</th>
                                                                    <th style="display: none;">Amount</th>
                                                                    <th>Balance</th>
                                                                    <th>Remain</th>
                                                                    <th style="display:none">DL ID</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <!-- Your table 2 content goes here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            

                            <div class="col-4"  style="margin-left: 10px;">
                               
                                <div class="row">
                                    <div class="col-2">
                                        <input type="button" class="btn btn-primary" id="btnSave" value="Save">
                                    </div>
                                    <div class="col-2">
                                        <input type="button" class="btn btn-danger" id="btnReset" value="Reset">
                                    </div>
                                </div>
                            </div>

                        

                    </div>


                </form>
                <hr>

            </div>
        </div>
    </div>
</div>

<!-- model -->



</div>

@include('datachooser.data-chooser')


@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>





@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>



<script src="{{Module::asset('sl:js/transaction_allocation.js')}}?random=<?php echo uniqid(); ?>"></script>







@endsection