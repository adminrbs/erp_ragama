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

    <!-- Multiple fixed columns -->
    <div class="card mt-2">

        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Sales Invoice Tracking Inquery</h5>
            </div>
            <br>
            <br>
            <div class="row" style="margin-top: 15px;">
                <div class="col-4" style="margin-left: 10px;">
                    <div class="row">
                    <div class="col-6">
                        <label class="form-label">From Date</label>
                        <div class="input-group">
                            <input type="text" id="from_date" class="form-control daterange-single">
                        </div>
                    </div>

                    <div class="col-6">
                        <label class="form-label">To Date</label>
                        <div class="input-group">
                            <input type="text" id="to_date" class="form-control daterange-single">
                        </div>
                    </div>
                    </div>
                </div>
                <div class="col-2">
                    <label class="form-label">Filter by</label>
                    <select id="cmbAny" name="cmbAny" class="form-select validate">
                        <option value="0" selected>Any</option>
                        <option value="1">Office In</option>
                        <option value="1">Office Not In</option>
                    </select>
                     
                </div>
                <div class="col-1">
                    <!-- <label class="form-label">Supply Group</label>
                    <select id="cmbSupplyGroup" name="cmbSupplyGroup" class="select2 form-control validate">

                    </select> -->
                </div>
                <div class="col-3">
                    <!-- <label class="form-label">Status</label>
                    <select id="cmbStatus" name="cmbStatus" class="form-select validate">
                        <option value="0">Any</option>
                        <option value="1">Active</option>
                        <option value="2">Non-active</option>

                    </select> -->
                </div>
                <div class="col-1">
                    <!-- <label class="form-label" style="color: white;">Add new offer here</label>
               
               
                    <a href="/sd/freeOfferCreateNewView" class="btn btn-primary" target="_blank">
                        <i class="fa fa-plus">&nbsp;Create New</i>
                    </a> -->
                    <div>
                    <button type="button" class="btn btn-success" id="btn_print">Print</button>
                </div>
                </div>




            </div>

            <br>
            <br>
            <div class="row" style="margin-top: 15px;">
               

                <div class="col-md-12">
                    <table class="table datatable-fixed-both table-striped" id="invoice_tracking_inquery_list">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="form-check-input" id="chkAll" onchange="selectAll(this)"></th>
                                <th>Reference</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Customer</th>
                                <th>Sales Rep</th>
                                <th>Route</th>
                                <th>Town</th>
                                <th>Balance</th>
                                <th>Action</th>
                                <th>Remark</th>


                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->
@include('sd::pending_invoice_data_model')
@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<!-- <script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script> -->
<!-- <script src="{{URL::asset('assets/demo/pages/datatables_extension_buttons_excel.js')}}"></script> -->
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('sd:js/invoice_tracking_inquery.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection