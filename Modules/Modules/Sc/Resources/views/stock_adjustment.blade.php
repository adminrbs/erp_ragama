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


<!-- Content area -->
<div class="content">

    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Stock Adjustment</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">


                




                    <div class="card-body border-top">

                    <div class="col-12">
                            <div class="row" style="margin-left: 50px;">
                                <div class="alert alert-primary alert-icon-start alert-dismissible text-truncate rounded-pill fade show col-10">
                                    <span class="alert-icon bg-primary text-white rounded-pill" style="font-size: 20px;">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                    Please select <span class="fw-semibold"> a location</span> before to proceed and please note that kindly enter <span class="fw-semibold">minus QTY </span> to stock out or <span class="fw-semibold">plus QTY </span> to stock in.
                                   <!--  <button type="button" class="btn-close rounded-pill" data-bs-dismiss="alert"></button> -->
                                </div>

                               
                            </div>
                        </div>
                        <!-- <div class="col-md-12 " style="background-color:#EBFFFF;height: 50px; text-align:right;">
                        <button type="button" class="btn btn-primary" id="btnBack">
                            <i class="fa fa-arrow-left" aria-hidden="true"> Back to list</i> 
                            </button>
                            
                        </div> -->
                        <div class="mb-4">
                            <div class="row mb-1">

                                <div class="col-md-2">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>
                                    <!-- <input type="text" name="customer_id" id="customer_id" class="form-control form-control-sm" required placeholder="Referance No" autocomplete="off"> -->
                                    <input type="text" class="form-control" id="LblexternalNumber" value="New Document" disabled>
                                </div>


                                <div class="col-md-2">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>
                                    <!-- <input type="text" name="date" id="date" class="form-control form-control-sm" required placeholder="Date" autocomplete="off"> -->
                                    <input type="date" class="form-control" id="stock_adjustment_date_time" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-select" id="cmbBranch"></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Location</span></label>
                                    <select class="form-select" id="cmbLocation"></select>
                                </div>
                            </div>

                            <div class="row mb-1">

                                

                                {{-- <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Supplier</span></label>
                                    <input type="text" id="txtSupplier" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Name</span></label>
                                    <label class="form-control" id="lblSupplierName">Supplier Name</label>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Address</span></label>
                                    <input type="text" class="form-control" id="lblSupplierAddress"></label>
                                </div> --}}
                                <!-- <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Purchase Order</span></label>
                                    <input type="text" id="txtPurchaseORder" class="form-control">
                                </div> -->
                                {{-- <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Supplier's Invoice #</span></label>
                                    <input type="text" id="txtSupplierInvoiceNumber" class="form-control">
                                </div> --}}
                               <!--  <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Invoice Amount</span></label>
                                    <input type="number" id="txtSupplierInvoiceAmount" class="form-control" style="text-align:right;">
                                </div> -->
                               <!--  <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Payment Due Date</span></label>
                                    <input type="date" id="dtPaymentDueDate" class="form-control">
                                </div> -->
                             <!--    <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Payment Type</span></label>
                                    <select class="form-select" id="cmbPaymentType"></select>
                                </div> -->
                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Precentage</span></label>
                                    <input type="number" id="txtDiscountPrecentage" class="form-control" style="text-align:right;" disabled>
                                </div>
                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Amount</span></label>
                                    <input type="number" id="txtDiscountAmount" class="form-control" style="text-align:right;">
                                </div>
                                <!-- <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>+/- Adjustment</span></label>
                                    <input type="number" id="txtAdjustmentAmount" class="form-control" style="text-align:right;">
                                </div> -->
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Your Reference</span></label>
                                    <input type="text" id="txtYourReference" class="form-control">
                                </div>


                            </div>

                        </div>

                        <hr>
                </form>


                <ul class="nav nav-tabs mb-0" id="tabs">
                    <li class="nav-item rbs-nav-item">
                        <a href="#Item" class="nav-link active" aria-selected="true">Product</a>
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
                                                <!--  <th>FOC</th>
                                                <th>U.O.M</th> 
                                                <th>Package Size</th>-->
                                                <th>Pack Size</th> 
                                                <th>cost price</th>
                                                <th>wh. Price</th>
                                                <th>retail price</th>
                                                <th>Value</th>
                                                <th>Batch#</th>
                                                <th>Avl. Qty</th>
                                                <th>Set Off Qty</th>
                                                <th>Set off</th>
                                                <th style="width: 120px;">Button</th>
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
                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Remarks</span></label>
                            <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off" required></textarea>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="row" style="display: none;">
                            <div class="col-md-6">
                                <label>Gross Total :</label>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblGrossTotal">0.00</label>
                            </div>
                        </div>

                        <div class="row" style="display: none;">
                            <div class="col-md-6">
                                <label>Total Discount :</label>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblTotalDiscount">0.00</label>
                            </div>
                        </div>

                        <div class="row" style="display: none;">
                            <div class="col-md-6">
                                <label>Total Tax :</label>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblTotaltax">0.00</label>
                            </div>
                        </div>

                        <div class="row" style="display: none;">
                            <div class="col-md-6">
                                <strong class="font-weight-bold">Net Total :</strong>
                            </div>
                            <div class="col-md-6">
                                <label style="text-align: right;width: 100%;" id="lblNetTotal">0.00</label>
                            </div>
                        </div>

                        <br>
                        <br>
                        <div class="row">
                             {{-- <div class="col-md-6" style="display: none;">
                                <button class="btn btn-primary" type="button" id="btnSaveDraft" style="width: 100%;">Save Draft</button>
                            </div> --}}
                            <div class="col-md-6">
                                <button class="btn btn-info" type="button" style="width: 100%;" id="btnSave">Save and Send</button>
                            </div>
                            {{-- <div class="col-md-6">
                                <button class="btn btn-danger" type="button" style="width: 100%;" id="btnReject">Reject</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-success" type="button" id="btnApprove" style="width: 100%;">Approve</button>
                            </div> --}}
                            <br>
                            
                        </div>
                        <br>
                        <div>
                        <!-- <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="chkPrintReport">
                                <label class="form-check-label" for="chkPrintReport">
                                    Print
                                </label>
                            </div> -->
                        </div>

                    </div>
                </div>


                <hr>

            </div>
        </div>
    </div>
</div>
<!-- /dashboard content -->




@include('datachooser.data-chooser')
@include('sc::stock _adjustment_model')
@include('sc::stock_adjustment_set_off_model')


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

@endsection
@section('scripts')

<script src="{{URL::asset('assets/HashMap/HashMap.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/Item.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/ItemSetoff.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{Module::asset('sc:js/stock_adjustment.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sc:js/stock_adjustment_model.js')}}?random=<?php echo uniqid(); ?>"></script>
@endsection