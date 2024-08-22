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
                    <h5 class="mb-0">Invoice Re-print</h5>
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
                                                <div class="col-8"><input type="text" id="txtInv" class="form-control" style="text-align:left;" placeholder="Invoice Number"></div>
                                                <div class="col-1">
                                                    <input type="button" id="btnSearch" class="btn btn-primary" value="Get data">
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

                                            <div class="row">
                                                <div class="col-3">
                                                    <!-- use for get space -->
                                                </div>
                                                <div class="col-3">
                                                    <!-- use for get space -->
                                                </div>
                                                <div class="col-3">
                                                    <!-- use for get space -->
                                                </div>
                                                <div class="col-3">
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <!-- use for get space -->
                                                        </div>
                                                        <div class="col-4">
                                                            <input type="button" id="btn_reprint" class="btn btn-success" value="Request Re-Print">
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



                </form>


            </div>
        </div>
    </div>
</div>

<!-- model -->



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
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>





@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->



<script src="{{Module::asset('sd:js/sales_invoice_reprint_request.js')}}?random=<?php echo uniqid(); ?>"></script>







@endsection