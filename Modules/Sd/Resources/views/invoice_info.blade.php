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
                    <h5 class="mb-0">Invoice Inquiry</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div style="margin-bottom: 0px !important;">
                        <div class="card-body border-top">

                            <div class="mb-4">

                                <div class="row mb-1">

                                    <div class="col-12">

                                        <div class="col-4">
                                            <div class="row">
                                                <div class="col-3">
                                                    <label class="transaction-lbl" style="width: 100%;text-align: left;"><span>Number</span></label>
                                                </div>
                                                <div class="col-6"><input type="text" id="txtInv" class="form-control" style="text-align:left;" placeholder="Invoice Number"></div>
                                                <div class="col-2">
                                                    <input type="button" id="btnSearch" class="btn btn-primary" value="Get">
                                                </div>

                                                <div class="col-1">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inv_info_search_modal" id="modelOpenBTN" style="height: 40px;">
                                                        <i class="fa fa-search" aria-hidden="true"></i>
                                                    </button>
                                                </div>

                                            </div>

                                        </div>


                                        <br>
                                        <div class="col-md-12">
                                            <div class="card border-secondary mb-3" style="max-width: 100%">

                                                <div class="card-body text-secondary">
                                                    <div class="row mb-1">
                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Reference No</span></label>
                                                                        </div>
                                                                        <div class="col-7">
                                                                            <label id="LblexternalNumber" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Sales Order</span></label>
                                                                        </div>
                                                                        <div class="col-7">
                                                                            <label id="lblSalesOrder" class="val_lbl"></label>
                                                                        </div>

                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Branch</span></label>


                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label name="" id="txtBranch" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>

                                                                </div>


                                                                <div class="col-3">
                                                                    <dic class="row">
                                                                        <div class="col-4">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Location</span></label>


                                                                        </div>
                                                                        <div class="col-8">
                                                                            <label name="" id="txtlocation" class="val_lbl"></label>
                                                                        </div>
                                                                    </dic>
                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Sales Rep</span></label>
                                                                        </div>
                                                                        <div class="col-7">


                                                                            <label id="txtEmp" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">
                                                                    <dic class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Customer</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtCustomerID" class="val_lbl"></label>
                                                                        </div>
                                                                </div>

                                                                <div class="col-3">

                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Invoice Date</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="invoice_date_time" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-3">

                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Order Date</span></label>
                                                                        </div>
                                                                        <div class="col-7">
                                                                            <label id="dt_order" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>

                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Total Amount</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtTotal" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-3">

                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Paid Amount</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtPaid" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                                <div class="col-3">

                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Balance</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtBalance" class="val_lbl"></label>
                                                                        </div>
                                                                    </div>



                                                                </div>

                                                                <div class="col-3">
                                                                    <div class="row">
                                                                        <div class="col-5">
                                                                            <label class="transaction-lbl mb-0 compulsory-fields" style="width: 100%;text-align: left;"><span>Gap</span></label>
                                                                        </div>
                                                                        <div class="col-7">

                                                                            <label id="txtGap" class="val_lbl"></label>
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
                    </div>




                    <div style="margin-top: -3% !important;">
                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#transaction" class="nav-link active" aria-selected="true">Transaction</a>
                            </li>
                            <li class="nav-item rbs-nav-item">
                                <a href="#Item" class="nav-link " aria-selected="true">Invoice Items</a>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show" id="Item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rowIndex">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped val_table" id="item_table">
                                                <thead>
                                                    <tr>

                                                        <th>Item Code</th>
                                                        <th>Name</th>
                                                        <th style="text-align: right;">QTY</th>
                                                        <th style="text-align: right;">FOC</th>
                                                        <th>U.O.M</th>
                                                        <th>Pacs</th>
                                                        <th style="text-align: right;">Price</th>
                                                        <th>Disc. %</th>
                                                        <th style="text-align: right;">Value</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show active" id="transaction">
                                <div class="row">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card border-secondary mb-3" style="max-width: 100%">
                                                <div class="card-header custom-header-style">Sales Returns</div>
                                                <div class="card-body text-secondary">
                                                    <input type="hidden" id="rowIndex">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped val_table" id="return_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Reference#</th>
                                                                    <th>Date</th>
                                                                    <th>Amount</th>
                                                                    <th>User</th>
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

                                        <div class="col-md-8">
                                            <div class="card border-secondary mb-3" style="max-width: 100%">
                                                <div class="card-header custom-header-style">Sales Return Items</div>
                                                <div class="card-body text-secondary">
                                                    <input type="hidden" id="rowIndex">
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-striped val_table" id="return__item_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Code</th>
                                                                    <th>Name</th>
                                                                    <th>Qty</th>
                                                                    <th>FOC</th>
                                                                    <th>U.O.M</th>
                                                                    <th>Pacs</th>
                                                                    <th>Price</th>
                                                                    <th>Disc. %</th>
                                                                    <th>Value</th>
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
                                <br>


                                <div class="row">
                                    <div class="col-6">
                                        <div class="card border-secondary mb-3" style="max-width: 100%">
                                            <div class="card-header custom-header-style">Receipts</div>
                                            <div class="card-body text-secondary">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped val_table" id="receipts_table">
                                                        <thead>
                                                            <tr>

                                                                <th>Date</th>
                                                                <th>Reference#</th>
                                                                <th>Collector</th>
                                                                <th>Amount</th>
                                                                <th>Cheque</th>
                                                                <th>Gap</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="card border-secondary mb-3" style="max-width: 100%">
                                            <div class="card-header custom-header-style">SFA Receipts</div>
                                            <div class="card-body text-secondary">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped val_table" id="sfa_receipts_table">
                                                        <thead>
                                                            <tr>

                                                                <th>Date</th>
                                                                <th>Reference#</th>
                                                                <th>Collector</th>
                                                                <th>Amount</th>
                                                                <th>Cheque</th>
                                                                <th>Gap</th>


                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br>


                                <div class="row">

                                <div class="col-6">
                                        <div class="card border-secondary mb-3" style="max-width: 100%">
                                            <div class="card-header custom-header-style">Customer Transaction Allocation</div>
                                            <div class="card-body text-secondary">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped val_table" id="allocation_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Reference#</th>
                                                                <th>Date</th>
                                                                <th>Set Off From</th>
                                                                <th>Amount</th>
                                                               


                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>






                                    <div class="col-6">
                                        <div class="card border-secondary mb-3" style="max-width: 100%">
                                            <div class="card-header custom-header-style">Delivery Plan</div>
                                            <div class="card-body text-secondary">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped val_table" id="delivery_plan_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Reference#</th>
                                                                <th>Vehicle</th>
                                                                <th>Driver</th>
                                                                <th>Helper</th>
                                                                <th>User</th>
                                                                <th style="display: none;">Report</th>


                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>


                                <div class="row">

                                <div class="col-6">
                                        <div class="card border-secondary mb-3" style="max-width: 100%">
                                            <div class="card-header custom-header-style">Picking List</div>
                                            <div class="card-body text-secondary">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped val_table" id="picking_list_table">
                                                        <thead>
                                                            <tr>

                                                                <th>Date</th>
                                                                <th>Picking List No</th>
                                                                <th style="display: none;">Report</th>



                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="card border-secondary mb-3" style="max-width: 100%">
                                            <div class="card-header custom-header-style">Delivery Confirmation</div>
                                            <div class="card-body text-secondary">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-striped val_table" id="delivery_confirmation_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Delivered</th>
                                                                <th>Seal</th>
                                                                <th>Sign</th>
                                                                <!--  <th>No Seal</th> -->
                                                                <th>Cash</th>
                                                                <th>CHQ</th>
                                                                <th>No Seal</th>
                                                                <th>Cancel</th>
                                                                <th>User</th>


                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>
                                                    </table>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!--  <div class="col-6">
                                    <div class="card border-secondary mb-3" style="max-width: 100%">
                                        <div class="card-header custom-header-style"></div>
                                        <div class="card-body text-secondary">
                                            <div class="table-responsive">
                                                
                                            </div>

                                        </div>
                                    </div>
                                </div> -->
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

@include('sd::invoie_info_search_modal')

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



<script src="{{Module::asset('sd:js/invoice_info.js')}}?random=<?php echo uniqid(); ?>"></script>







@endsection