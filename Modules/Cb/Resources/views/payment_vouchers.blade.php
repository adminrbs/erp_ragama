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
                    <h5 class="mb-0">Payment Vouchers</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div class="card-body border-top">
                        <div class="alert alert-warning alert-icon-start alert-dismissible fade  col-md-4" style="margin:auto;" id="warning_alert">
                            <span class="alert-icon bg-warning text-white">
                                <i class="ph-warning-circle"></i>
                            </span>
                            <span class="fw-semibold"></span><a href="#" class="alert-link" style="display: block; margin: auto;text-align:center;">Customer Blocked</a>
                            <button type="button" class="btn-close" data-bs-dismiss="" id="warningClose"></button>
                        </div>


                        <div class="col-12">
                            <div class="row" style="margin-left: 50px;">
                                <!--  <div class="alert alert-primary alert-icon-start alert-dismissible text-truncate rounded-pill fade show col-10">
                                    <span class="alert-icon bg-primary text-white rounded-pill" style="font-size: 20px;">
                                        <i class="fa fa-info-circle"></i>
                                    </span>
                                    Please select <span class="fw-semibold">branch and location</span> before pick a sales order!
                                     <button type="button" class="btn-close rounded-pill" data-bs-dismiss="alert"></button>
                                </div> -->

                                <div class="col-2 ">
                                    <!-- <div class="row">

                                        <div class="col-8">
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" id="si_model_btn" onclick="openModalWithDelay()">
                                                Pick Orders
                                            </button>
                                        </div>


                                    </div> -->

                                </div>
                            </div>
                        </div>


                        <div class="mb-4">


                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>

                                    <input class="form-control" type="text" id="LblexternalNumber" value="New Invoice" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>

                                    <input type="date" class="form-control" id="invoice_date_time" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-select " id="cmbBranch"></select>
                                </div>

                            </div>

                            <div class="row mb-1">




                                <!-- 
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;">
                                        <span>Supplier Code</span>
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" id="txtSupplier" class="form-control mr-2"> &nbsp;
                                        
                                    </div>
                                </div> -->

                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Precentage</span></label>
                                    <input type="number" id="txtDiscountPrecentage" class="form-control" style="text-align:right;" disabled>
                                </div>
                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Amount</span></label>
                                    <input type="number" id="txtDiscountAmount" class="form-control" style="text-align:right;">
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;color:white">
                                        <span>Options</span>
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <input type="radio" id="rdoPayee" name="option" class="rdo"> &nbsp; Option 1
                                        <input type="radio" id="rdoSup" name="option" class="ms-3 rdo"> &nbsp; Option 2
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Payment Method</span></label>
                                    <select class="form-select" id="cmbPaymentMethod"></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>GL Account</span></label>
                                    <select class="select2 form-control validate" data-live-search="true" id="cmbGlAccount"></select>
                                </div>

                            </div>
                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Payee</span></label>
                                    <select class="select2 form-control validate" data-live-search="true" id="cmbPayee">
                                        
                                    </select>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;">
                                        <span>Supplier Code</span>
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" id="txtSupplier" class="form-control mr-2"> &nbsp;

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;">
                                        <span>Description</span>
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" id="txtDescription" class="form-control mr-2"> &nbsp;

                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>


                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#Item" class="nav-link active" aria-selected="true">Accounts</a>
                            </li>
                            <!-- <li class="nav-item rbs-nav-item">
                                <a href="#rtn" class="nav-link" aria-selected="true">Return Request</a>
                            </li> -->

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rowIndex">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="tblData">
                                                <thead>
                                                    <tr>

                                                        <th>GL Accounts</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th>Analysis</th>
                                                        <th>Remove</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <!-- <div class="tab-pane fade show " id="rtn">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rowIndex">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="rtn_item">
                                                <thead>
                                                    <tr>

                                                        <th>Request Date</th>
                                                        <th>Sales Rep</th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Pack Size</th>
                                                        <th>QTY</th>
                                                        <th><input type="checkbox" class="form-check-input" onchange="check_all(this)" id="chk_selectAll" checked></th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div> -->



                        </div>
                        <div class="row">
                            <div class="col-md-8 mt-2">
                                <div class="col-md-12">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Remarks</span></label>
                                    <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off"></textarea>
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
                                    <!--    <div class="col-md-6" style="display: none;">
                                        <button class="btn btn-primary" type="button" id="btnSaveDraft" style="width: 100%;">Save Draft</button>
                                    </div> -->
                                    <div class="col-md-6">
                                        <button class="btn btn-info" type="button" style="width: 100%;" id="btnSave">Save and Send</button>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <button class="btn btn-danger" type="button" style="width: 100%;" id="btnReject">Reject</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-success" type="button" id="btnApprove" style="width: 100%;">Approve</button>
                                    </div> -->

                                </div>

                                <br>

                                <div class="row">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="chkPrintReport">
                                        <label class="form-check-label" for="chkPrintReport">
                                            Print
                                        </label>
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



@include('datachooser.data-chooser')
@include('sd::salesInvoiceModel')
@include('sd::salesInvoiceBatchModel')
@include('sd::salesInvoiceCustomerOutstandingModal')

</div>



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
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>info
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>

<script src="{{URL::asset('assets/HashMap/HashMap.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/Item.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/ItemSetoff.js')}}?random=<?php echo uniqid(); ?>"></script>



@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{Module::asset('cb:js/payment_vouchers.js')}}?random=<?php echo uniqid(); ?>"></script>










@endsection