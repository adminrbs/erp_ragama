@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>

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
                    <h5 class="mb-0">Sales Return</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div class="card-body border-top">
                        <div class="alert alert-warning alert-icon-start alert-dismissible fade hide  col-md-4" style="margin:auto;" id="warning_alert">
                            <span class="alert-icon bg-warning text-white">
                                <i class="ph-warning-circle"></i>
                            </span>
                            <span class="fw-semibold"></span><a href="#" class="alert-link" style="display: block; margin: auto;text-align:center;">No return location for the selected branch</a>
                            <button type="button" class="btn-close" data-bs-dismiss="" id="warningClose"></button>
                        </div>
                        <div class="col-md-12 " style="background-color:#EBFFFF;height: 50px; text-align:right;">
                            <button type="button" class="btn btn-primary" id="btnBack">
                                <i class="fa fa-arrow-left" aria-hidden="true"> Back to list</i>
                            </button>
                        </div>
                        <div class="mb-4">

                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>
                                    <!-- <input type="text" name="customer_id" id="customer_id" class="form-control form-control-sm" required placeholder="Referance No" autocomplete="off"> -->
                                    <input type="text" class="form-control" id="LblexternalNumber" value="New Document" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>
                                    <!-- <input type="text" name="date" id="date" class="form-control form-control-sm" required placeholder="Date" autocomplete="off"> -->
                                    <input type="date" class="form-control" id="invoice_date_time" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 Branch compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-select" id="cmbBranch"></select>
                                </div>
                            </div>

                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Location</span></label>
                                    <select class="form-select" id="cmbLocation"></select>
                                </div>

                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="transaction-lbl mb-0" style="width: 500%;text-align: left;"><span>Sales Invoice Number</span></label>
                                            <input type="text" class="form-control" id="txtInvoiceID">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="transaction-lbl mb-0" style="width: 500%;text-align: left;color:white"><span>Search</span></label>
                                            <button type="button" class="btn btn-primary " id="btnReturnGetData" style="height: 30px !important;">
                                                Get Data
                                            </button>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#SalesReturnInvoiceModal" id="modelOpenBTN">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Sales Rep</span></label>
                                    <select class="form-select" id="cmbEmp"></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Customer Code</span></label>
                                    <input type="text" id="txtCustomerID" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Customer Name</span></label>
                                    <input type="text" class="form-control" id="lblCustomerName" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Customer Address</span></label>
                                    <input type="text" class="form-control" id="lblCustomerAddress" disabled>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Reason</span></label>
                                    <select class="form-select" id="cmbReason"></select>
                                </div>


                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Precentage</span></label>
                                    <input type="number" id="txtDiscountPrecentage" class="form-control" style="text-align:right;" disabled>
                                </div>
                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Amount</span></label>
                                    <input type="number" id="txtDiscountAmount" class="form-control" style="text-align:right;">
                                </div>

                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Book Number</span></label>
                                            <select class="form-select" id="cmbBookNumber"></select>
                                        </div>
                                        <div class="col-6">
                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Page Number</span></label>
                                            <input type="number" id="txtPageNumber" class="form-control" style="text-align:right;">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Your reference number</span></label>
                                    <input type="text" id="txtyourreferencenumber" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Sales Analyst</span></label>
                                    <select class="form-select" id="cmbSalesAnalysist"></select>
                                </div>
                                <!--  <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Payment Term</span></label>
                                    <select class="form-control" id="cmbPaymentTerm"></select>
                                </div>
                    
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Delivery Instruction</span></label>
                                    <input type="text" id="txtDeliveryInst" class="form-control">
                                </div> -->



                            </div>

                        </div>

                        <hr>


                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#Item" class="nav-link active" aria-selected="true">Product</a>
                            </li>
                            <li class="nav-item rbs-nav-item">
                                <a href="#set_off" class="nav-link" aria-selected="true">Invoice Set Off</a>
                            </li>
                            <li class="nav-item rbs-nav-item" id="return_item_tab">
                                <a href="#returned_item" class="nav-link" aria-selected="true">Returned Items</a>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="tblData">
                                                <thead>
                                                    <tr>

                                                        <th>Item Code</th>
                                                        <th>Name</th>
                                                        <th>QTY</th>
                                                        <th>FOC</th>
                                                        <th>U.O.M</th>
                                                        <th>Package Size</th>
                                                        <th>Pack Size</th>
                                                        <th>Price</th>
                                                        <th>Retail Price</th>
                                                        <th>Disc. %</th>
                                                        <th>Disc. Amount</th>
                                                        <th>Value</th>
                                                        <th style="width: 120px;">Button</th>
                                                        <th>Sales Invoice Foc</th>
                                                        <th>Cost Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="set_off">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table" id="set_off_table">
                                                <thead>
                                                    <tr>

                                                        <th style="width: 50px;">Date</th>
                                                        <th style="width: 350px;">Invoice</th>
                                                        <th>Age</th>
                                                        <th>Balance</th>
                                                        <th>Select</th>
                                                        <th>Set off</th>
                                                        <th>Remaining Balance</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>



                            <div class="tab-pane fade show" id="returned_item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table" id="returned_items_table">
                                                <thead>
                                                    <tr>

                                                        <th>Item Code</th>
                                                        <th>Name</th>
                                                        <th>QTY</th>
                                                        <th>FOC</th>
                                                        <th>U.O.M</th>
                                                       <!--  <th>Package Size</th> -->
                                                        <th>Pack Size</th>
                                                        <th>Price</th>
                                                      <!--   <th>Retail Price</th> -->
                                                       <!--  <th>Disc. %</th>
                                                        <th>Value</th>
                                                        <th>Cost Price</th> -->
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
                            <div class="col-md-8 mt-2">
                                <div class="col-md-12">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Remarks</span></label>
                                    <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off" required></textarea>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Gross Total :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblGrossTotal">0.00</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Total Discount :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblTotalDiscount">0.00</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Total Tax :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblTotaltax">0.00</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong class="font-weight-bold">Net Total :</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblNetTotal">0.00</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6" style="display: none;">
                                        <button class="btn btn-primary" type="button" id="btnSaveDraft" style="width: 100%;">Save Draft</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-info" type="button" style="width: 100%;" id="btnSave">Save and Send</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-danger" type="button" style="width: 100%;" id="btnReject">Reject</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-success" type="button" id="btnApprove" style="width: 100%;">Approve</button>
                                    </div>

                                </div>
                                <br>

                                <div class="row">
                                    <!--    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="chkPrintReport">
                                        <label class="form-check-label" for="chkPrintReport">
                                            Print
                                        </label>
                                    </div> -->

                                </div>


                            </div>
                        </div>

                </form>
                <hr>

            </div>
        </div>
    </div>
</div>
<!-- /dashboard content -->




@include('datachooser.data-chooser')
@include('sd::salesReturnInvoiceModel')


</div>
<!-- /content area -->


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
<script src="{{URL::asset('assets/js/purblicProgress.js')}}?random=<?php echo uniqid(); ?>"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesReturn.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesReturnModel.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/salesReturnReport.js')}}?random=<?php echo uniqid(); ?>"></script>
@endsection