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
        background-color: #ffff00;
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
                    <h5 class="mb-0">Sales Invoice Copy Received</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div style="margin-bottom: 0px !important;">
                        <div class="card-body border-top">

                            <div class="mb-4">

                                <div class="row mb-1">

                                    <div class="col-12">
                                        <div class="row">
                                        <div class="col-4">
                                                <div class="row">
                                                    <div class="col-3">
                                                        <label class="transaction-lbl" style="width: 100%;text-align: left;"><span>Employe</span></label>
                                                    </div>
                                                    <div class="col-6">
                                                        <select id="cmbEmp" class="select2 form-control validate" data-live-search="true">

                                                        </select>
                                                    </div>




                                                </div>

                                            </div>
                                            <div class="col-4">
                                                <div class="row" style="display:none;">
                                                    <div class="col-3">
                                                        <label class="transaction-lbl" style="width: 100%;text-align: left;"><span>Number</span></label>
                                                    </div>
                                                    <div class="col-6"><input type="text" id="txtInv" class="form-control" style="text-align:left;" placeholder="Invoice Number"></div>
                                                    <div class="col-2">
                                                        <input type="button" id="btnSearch" class="btn btn-primary" value="Get">
                                                    </div>

                                                    <div class="col-1">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inv_info_search_modal" id="modelOpenBTN" style="height: 40px;display:none;">
                                                            <i class="fa fa-search" aria-hidden="true"></i>
                                                        </button>
                                                    </div>

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

                                                                <table id="invoiceDataTable" class="table">

                                                                    <thead>
                                                                        <tr>
                                                                            <th>Reference</th>
                                                                            <th>Sales Rep</th>
                                                                            <th>Customer</th>
                                                                            <th>Invoice Date</th>
                                                                            <th>Total Amount</th>
                                                                            <th>Paid Amount</th>
                                                                            <th>Balance</th>
                                                                            <th><input type="checkbox" id="ChkSelectAll" class="form-check-input" onchange="SelectAll()"></th>
                                                                        </tr>
                                                                    </thead>

                                                                    <tbody id="invoiceDataTableBody">

                                                                    </tbody>
                                                                </table>

                                                            </div>



                                                        </div>

                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-4" style="display:none;">
                                        <input type="text" name="" id="txtRemark" class="form-control" placeholder="Remark">
                                    </div>
                                    <div class="col-2">
                                        <input type="button" id="btnSave" class="btn btn-primary" value="Save">
                                    </div>
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
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>





@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->



<script src="{{Module::asset('sd:js/sales_invoice_copy_received.js')}}?random=<?php echo uniqid(); ?>"></script>







@endsection